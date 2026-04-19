<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DeliveryRoute;
use App\Models\DeliveryStop;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    protected $googleMaps;

    public function __construct(GoogleMapsService $googleMaps)
    {
        $this->googleMaps = $googleMaps;
    }

    public function index()
    {
        $distributor = Auth::user();
        
        // Orders ready for route planning
        $orders = Order::where('distributor_id', $distributor->id)
            ->whereIn('status', ['distributor_confirmed', 'dispatched'])
            ->with('retailer')
            ->get();

        $activeRoutes = DeliveryRoute::where('distributor_id', $distributor->id)
            ->whereIn('status', ['pending', 'in_transit'])
            ->with('stops.order.retailer')
            ->get();

        return view('distributor.route-optimization', compact('orders', 'activeRoutes'));
    }

    public function optimize(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        if (empty($orderIds)) {
            return response()->json(['error' => 'No orders selected'], 422);
        }

        $distributor = Auth::user();
        $orders = Order::whereIn('id', $orderIds)
            ->with(['retailer' => function($query) {
                $query->select('id', 'name', 'address', 'latitude', 'longitude');
            }])
            ->get();

        // Group orders by location (lat,lng or address)
        $groupedOrders = [];
        foreach ($orders as $order) {
            $key = $order->retailer->latitude && $order->retailer->longitude 
                ? $order->retailer->latitude . ',' . $order->retailer->longitude
                : $order->retailer->address;
            
            $groupedOrders[$key][] = $order;
        }

        $uniqueKeys = array_keys($groupedOrders);
        
        // Origin is the distributor's location
        $origin = $distributor->latitude && $distributor->longitude 
            ? "{$distributor->latitude},{$distributor->longitude}" 
            : $distributor->address;

        // If all orders are at the same location, no need for API
        if (count($uniqueKeys) <= 1) {
            return response()->json([
                'optimized_orders' => $orders,
                'total_distance' => 0,
                'total_duration' => 0,
                'polyline' => '',
                'legs' => [],
            ]);
        }

        $optimizedData = $this->googleMaps->getOptimizedRoute($origin, $uniqueKeys);

        if (!$optimizedData) {
            return response()->json(['error' => 'Route optimization failed. Please check if addresses are correct.'], 500);
        }

        // Map the optimized order back to our orders
        $optimizedOrders = [];
        foreach ($optimizedData['waypoint_order'] as $index) {
            $key = $uniqueKeys[$index];
            $ordersAtLocation = $groupedOrders[$key];
            foreach ($ordersAtLocation as $order) {
                $optimizedOrders[] = $order;
            }
        }

        return response()->json([
            'optimized_orders' => $optimizedOrders,
            'total_distance' => $optimizedData['total_distance'],
            'total_duration' => $optimizedData['total_duration'],
            'polyline' => $optimizedData['polyline'],
            'legs' => $optimizedData['legs'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'order_ids' => 'required|array',
            'total_distance' => 'nullable|numeric',
            'total_duration' => 'nullable|integer',
        ]);

        $distributor = Auth::user();

        try {
            DB::beginTransaction();

            $route = DeliveryRoute::create([
                'distributor_id' => $distributor->id,
                'name' => $validated['name'],
                'status' => 'pending',
                'total_distance' => $validated['total_distance'],
                'total_duration' => $validated['total_duration'],
                'optimized_at' => now(),
            ]);

            foreach ($validated['order_ids'] as $index => $orderId) {
                DeliveryStop::create([
                    'route_id' => $route->id,
                    'order_id' => $orderId,
                    'sequence_number' => $index + 1,
                    'status' => 'pending',
                ]);

                // Update order status if needed
                Order::where('id', $orderId)->update(['status' => 'distributor_confirmed']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Route saved successfully',
                'route_id' => $route->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to save route: ' . $e->getMessage()], 500);
        }
    }
}
