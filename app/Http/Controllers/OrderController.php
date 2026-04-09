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
            'distributor_id' => 'required|exists:users,id',
            'wholesaler_id' => 'nullable|exists:users,id',
            'items' => 'required|array',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $user = auth()->user();
                $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                $totalAmount = 0;
                $orderItemsData = [];

                foreach ($request->items as $productId => $data) {
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

                $status = $request->wholesaler_id ? 'wholesaler_pending' : 'distributor_pending';

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'retailer_id' => $user->id,
                    'wholesaler_id' => $request->wholesaler_id,
                    'distributor_id' => $request->distributor_id,
                    'status' => $status,
                    'order_date' => now(),
                    'scheduled_dispatch_date' => now()->addDays(2),
                    'total_amount' => $totalAmount,
                ]);

                foreach ($orderItemsData as $itemData) {
                    $order->items()->create($itemData);
                }

                // Notify next in chain
                $notifyTarget = $request->wholesaler_id ?: $request->distributor_id;
                Notification::create([
                    'user_id' => $notifyTarget,
                    'type' => 'order_status',
                    'title' => 'New Order Received',
                    'message' => "New order {$orderNumber} has been placed by {$user->name}",
                ]);

                if ($request->wantsJson() || $request->ajax()) {
                    return (new OrderResource($order->load(['items.product', 'retailer', 'distributor', 'wholesaler'])))
                        ->additional([
                            'status' => 'success',
                            'message' => 'Order placed successfully'
                        ]);
                }

                return redirect()->route('retailer.dashboard')->with('success', "Order {$orderNumber} placed successfully!");
            });
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
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
