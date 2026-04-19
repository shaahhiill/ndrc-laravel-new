<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Notification;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        if (auth()->user()->role !== 'retailer') {
            return back()->with('error', 'Only retailers are authorized to place bulk orders.');
        }

        $request->validate([
            'items' => 'required|array',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $user = auth()->user();
                $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                $totalAmount = 0;
                $orderItemsData = [];

                // Filter out zero quantities and missing data
                $items = array_filter($request->items, fn($i) => isset($i['quantity']) && (int)$i['quantity'] > 0);
                
                if (empty($items)) {
                    throw new \Exception("Your cart is empty. Please select quantities for the products you wish to order.");
                }

                foreach ($items as $productId => $data) {
                    $product = Product::findOrFail($productId);
                    $qty = (int)$data['quantity'];
                    $subtotal = $product->price * $qty;
                    $totalAmount += $subtotal;

                    $orderItemsData[] = [
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'unit_price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }

                // Determine Distributor
                $distributorId = $request->distributor_id ?: ($user->distributor_id ?: \App\Models\User::where('role', 'distributor')
                    ->where(function($q) use ($user) {
                        $q->where('territory', $user->territory)
                          ->orWhere('region', $user->region);
                    })->first()?->id);
                
                if (!$distributorId) {
                    // Final fallback to any distributor if location-based fails
                    $distributorId = \App\Models\User::where('role', 'distributor')->first()?->id;
                }

                if (!$distributorId) {
                    throw new \Exception("The NDRC network is currently unavailable in your region. Please contact Nestlé support.");
                }

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'retailer_id' => $user->id,
                    'wholesaler_id' => $request->wholesaler_id ?: $user->wholesaler_id,
                    'distributor_id' => $distributorId,
                    'status' => 'payment_pending',
                    'order_date' => now(),
                    'scheduled_dispatch_date' => now()->addDays(2),
                    'total_amount' => $totalAmount,
                ]);

                foreach ($orderItemsData as $itemData) {
                    $order->items()->create($itemData);
                }

                return redirect()->route('order.checkout', $order);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function index()
    {
        $user = auth()->user();
        
        $query = Order::with(['retailer', 'wholesaler', 'distributor', 'items.product']);

        if ($user->role === 'retailer') {
            $query->where('retailer_id', $user->id);
        } elseif ($user->role === 'wholesaler') {
            $query->where('wholesaler_id', $user->id);
        } elseif ($user->role === 'distributor') {
            $query->where('distributor_id', $user->id);
        }

        return OrderResource::collection($query->orderBy('created_at', 'desc')->get());
    }

    public function statusUpdate(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:wholesaler_accepted,distributor_confirmed,dispatched,delivered,rejected'
        ]);

        $user = auth()->user();
        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        // Logic for stock deduction on dispatch
        if ($request->status === 'dispatched' && $oldStatus !== 'dispatched') {
            foreach ($order->items as $item) {
                $stock = \App\Models\WarehouseStock::where('product_id', $item->product_id)->first();
                if ($stock) {
                    $stock->total_stock -= $item->quantity;
                    $stock->save();
                }
            }
        }

        // Notify the retailer
        Notification::create([
            'user_id' => $order->retailer_id,
            'type' => 'order_status',
            'title' => 'Order Status Updated',
            'message' => "Your order {$order->order_number} is now " . str_replace('_', ' ', $request->status),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Status updated']);
        }

        return back()->with('success', 'Order status updated successfully.');
    }
}
