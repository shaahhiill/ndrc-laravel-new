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
        $request->validate([
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $user = auth()->user();
                $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                $totalAmount = 0;
                $orderItemsData = [];

                // Filter out zero quantities
                $items = array_filter($request->items, fn($i) => $i['quantity'] > 0);
                
                if (empty($items)) {
                    throw new \Exception("Cart is empty. Please select products.");
                }

                foreach ($items as $productId => $data) {
                    $product = Product::findOrFail($productId);
                    $qty = $data['quantity'];
                    $subtotal = $product->price * $qty;
                    $totalAmount += $subtotal;

                    $orderItemsData[] = [
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'unit_price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }

                $distributorId = $request->distributor_id ?: ($user->distributor_id ?: \App\Models\User::where('role', 'distributor')->first()?->id);
                
                if (!$distributorId) {
                    throw new \Exception("No distributor assigned to your account. Please contact Nestlé support.");
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

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'success',
                        'redirect' => route('order.checkout', $order)
                    ]);
                }
                
                return redirect()->route('order.checkout', $order);
            });
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 400);
            }

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

        return response()->json(['status' => 'success', 'message' => 'Status updated']);
    }
}
