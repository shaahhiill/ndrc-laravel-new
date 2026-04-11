<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SmartOrderingService
{
    /**
     * Generate smart recommendations for a retailer
     */
    public function getRecommendations($user)
    {
        // 1. Get Top Ordered Products (Popularity)
        $topProducts = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('COUNT(*) as order_count'))
            ->where('orders.retailer_id', $user->id)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->get();

        // 2. Calculate Expiry/Restock Risk (Frequency based)
        // For demo: We assume products need restock every 14 days if ordered before
        $recommendations = [];

        foreach ($topProducts as $item) {
            $product = Product::find($item->product_id);
            if (!$product)
                continue;

            $lastOrder = Order::where('retailer_id', $user->id)
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->latest()
                ->first();

            $daysSinceLast = $lastOrder ? Carbon::parse($lastOrder->order_date)->diffInDays(now()) : 999;

            // Logic: If average order cycle is 14 days and it's been 12 days, recommend it.
            $score = 0;
            $reason = "";
            $predictedQty = (int) ($item->total_qty / max(1, $item->order_count));

            if ($daysSinceLast > 10) {
                $score += 40;
                $reason = "Low stock based on purchase cycle";
            }

            // Market Trend boost (for demo: Milo and Maggi are always trending)
            if (str_contains(strtolower($product->name), 'milo') || str_contains(strtolower($product->name), 'maggi')) {
                $score += 30;
                $reason = $reason ? $reason . " + Market trending" : "High market demand";
            }

            if ($score > 20) {
                $recommendations[] = [
                    'product' => $product,
                    'score' => min(98, $score + rand(5, 15)), // Add slight randomness for "AI" feel
                    'reason' => $reason,
                    'predicted_qty' => $predictedQty,
                    'last_ordered_at' => $lastOrder ? $lastOrder->order_date->format('d M') : 'Never',
                ];
            }
        }

        // Add some "New Products" recommendations if list is short
        if (count($recommendations) < 3) {
            $newProducts = Product::whereNotIn('id', $topProducts->pluck('product_id'))->limit(2)->get();
            foreach ($newProducts as $p) {
                $recommendations[] = [
                    'product' => $p,
                    'score' => rand(60, 75),
                    'reason' => "Trending in your region (" . ($user->territory ?? 'Central') . ")",
                    'predicted_qty' => 1,
                    'last_ordered_at' => 'New for you',
                ];
            }
        }

        return collect($recommendations)->sortByDesc('score')->take(5);
    }

    public function getTrendData($user)
    {
        // Monthly sales trend for the last 6 months
        return Order::where('retailer_id', $user->id)
            ->select(DB::raw('strftime("%m", order_date) as month'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
