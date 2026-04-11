<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function checkout(Order $order)
    {
        // Only allow checkout if pending payment
        if ($order->status !== 'payment_pending') {
            return redirect()->route('retailer.dashboard')->with('error', 'This order cannot be paid for or has already been processed.');
        }

        $order->load(['items.product', 'distributor', 'wholesaler']);

        // We will show a premium checkout summary page first
        return view('retailer.checkout', compact('order'));
    }

    public function processPayment(Order $order)
    {
        $secret = config('services.stripe.secret');
        if (!$secret) {
            return back()->with('error', 'Stripe is not configured. Please add STRIPE_SECRET to your .env file.');
        }

        Stripe::setApiKey($secret);

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'lkr',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => (int) ($item->unit_price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('stripe.cancel', [], true) . "?order_id=" . $order->id,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        $order->update([
            'stripe_session_id' => $session->id,
            'payment_method' => 'card'
        ]);

        return redirect()->away($session->url);
    }

    public function confirmCash(Order $order)
    {
        // Update order status based on whether it goes to wholesaler or distributor
        $status = $order->wholesaler_id ? 'wholesaler_pending' : 'distributor_pending';
        
        $order->update([
            'status' => $status,
            'payment_method' => 'cash'
        ]);

        // Notify next in chain
        $notifyTarget = $order->wholesaler_id ?: $order->distributor_id;
        \App\Models\Notification::create([
            'user_id' => $notifyTarget,
            'type' => 'order_status',
            'title' => 'New Order Received (COD)',
            'message' => "Order {$order->order_number} has been placed via Cash on Delivery and is ready for review.",
        ]);

        return redirect()->route('retailer.dashboard')->with('success', "Order {$order->order_number} placed successfully (Cash on Delivery)!");
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('retailer.dashboard')->with('error', 'Invalid payment session.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);
        $orderId = $session->metadata->order_id;
        $order = Order::findOrFail($orderId);

        if ($session->payment_status === 'paid') {
            // Update order status based on whether it goes to wholesaler or distributor
            $status = $order->wholesaler_id ? 'wholesaler_pending' : 'distributor_pending';
            $order->update(['status' => $status]);

            // Notify next in chain
            $notifyTarget = $order->wholesaler_id ?: $order->distributor_id;
            \App\Models\Notification::create([
                'user_id' => $notifyTarget,
                'type' => 'order_status',
                'title' => 'Order Paid & Received',
                'message' => "Order {$order->order_number} has been paid via Stripe and is ready for processing.",
            ]);

            return redirect()->route('retailer.dashboard')->with('success', "Payment successful! Order {$order->order_number} is now being processed.");
        }

        return redirect()->route('retailer.dashboard')->with('error', 'Payment not completed.');
    }

    public function cancel(Request $request)
    {
        $orderId = $request->get('order_id');
        if ($orderId) {
            return redirect()->route('order.checkout', $orderId)->with('error', 'Payment was cancelled. You can try again or choose another method.');
        }
        return redirect()->route('retailer.dashboard')->with('error', 'Payment was cancelled.');
    }
}
