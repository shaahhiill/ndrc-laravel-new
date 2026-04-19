<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $distributor = Auth::user();

        // 1. Priority Deliveries Calculation
        $priorityOrders = Order::where('distributor_id', $distributor->id)
            ->whereIn('status', ['distributor_confirmed', 'wholesaler_accepted'])
            ->with('retailer')
            ->get()
            ->map(function ($order) {
                $daysInPending = Carbon::parse($order->created_at)->diffInDays(now());
                
                // Simple Priority Score: (Days Pending * 20) + (Order Value / 500)
                $score = ($daysInPending * 20) + ($order->total_amount / 500);
                
                // Boost for "Urgent" orders if we had a flag, or based on retailer stock history
                // For now, let's say orders > 2 days are High Priority
                $priority = 'Low';
                if ($score > 100 || $daysInPending >= 3) $priority = 'High';
                elseif ($score > 50 || $daysInPending >= 1) $priority = 'Medium';

                $order->priority_score = round($score);
                $order->priority_level = $priority;
                return $order;
            })
            ->sortByDesc('priority_score')
            ->take(10);

        // 2. Territory Demand Aggregation
        $territoryDemand = User::where('users.distributor_id', $distributor->id)
            ->where('users.role', 'retailer')
            ->join('orders', 'users.id', '=', 'orders.retailer_id')
            ->select('users.territory', DB::raw('SUM(orders.total_amount) as total_demand'), DB::raw('COUNT(orders.id) as order_count'))
            ->groupBy('users.territory')
            ->get();

        // 3. Heatmap Data (lat, lng, intensity)
        $heatmapData = Order::where('orders.distributor_id', $distributor->id)
            ->join('users', 'orders.retailer_id', '=', 'users.id')
            ->select('users.latitude', 'users.longitude', 'orders.total_amount')
            ->whereNotNull('users.latitude')
            ->whereNotNull('users.longitude')
            ->get()
            ->map(function($o) {
                return [
                    'lat' => (float)$o->latitude,
                    'lng' => (float)$o->longitude,
                    'count' => (float)($o->total_amount / 1000) // normalized intensity
                ];
            });

        return view('distributor.demand-analytics', compact('priorityOrders', 'territoryDemand', 'heatmapData'));
    }

    public function getDemandTrends()
    {
        $distributor = Auth::user();
        
        $trends = Order::where('orders.distributor_id', $distributor->id)
            ->select(DB::raw('DATE(orders.created_at) as date'), DB::raw('SUM(orders.total_amount) as daily_total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($trends);
    }
}
