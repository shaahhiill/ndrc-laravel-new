<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\Distributor\AnalyticsController as DistributorAnalytics;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::get('/register', function () {
    $wholesalers = \App\Models\User::where('role', 'wholesaler')->where('status', 'active')->get();
    $distributors = \App\Models\User::where('role', 'distributor')->where('status', 'active')->get();
    return view('auth.register', compact('wholesalers', 'distributors'));
})->name('register')->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    
    // RETAILER ONLY ROUTES
    Route::middleware(['role:retailer'])->group(function () {
        Route::get('/retailer/dashboard', function () {
            $user = auth()->user()->load('wholesaler');
            $orders = \App\Models\Order::with('distributor')
                ->where('retailer_id', $user->id)
                ->latest()
                ->limit(10)
                ->get();
            
            $pending_req = \App\Models\NetworkRequest::with('wholesaler')
                ->where('retailer_id', $user->id)
                ->where('status', 'pending')
                ->first();

            $wholesalers = [];
            if (!$user->wholesaler_id && !$pending_req) {
                $wholesalers = \App\Models\User::where('role', 'wholesaler')
                    ->where(function($q) use ($user) {
                        $q->where('region', $user->region)
                          ->orWhere('territory', $user->territory);
                    })
                    ->get();
            }

            return view('retailer.dashboard', compact('user', 'orders', 'pending_req', 'wholesalers'));
        })->name('retailer.dashboard');

        Route::get('/retailer/place-order', function () {
            $products = \App\Models\Product::orderBy('category')->orderBy('name')->get();
            return view('retailer.place-order', compact('products'));
        })->name('retailer.place-order');

        Route::get('/retailer/orders', function () {
            $orders = \App\Models\Order::with(['distributor', 'wholesaler'])
                ->where('retailer_id', auth()->id())
                ->latest()
                ->get();
            return view('retailer.orders', compact('orders'));
        })->name('retailer.orders');

        Route::get('/retailer/smart-orders', [\App\Http\Controllers\RecommendationController::class, 'index'])->name('retailer.smart-orders');
        Route::post('/retailer/join-network', [NetworkController::class, 'joinRequest'])->name('network.join');
        Route::post('/orders', [OrderController::class, 'placeOrder'])->name('orders');

        // Payment & Checkout Flow (Retailer Only)
        Route::get('/checkout/{order}', [\App\Http\Controllers\StripeController::class, 'checkout'])->name('order.checkout');
        Route::post('/payment/process/{order}', [\App\Http\Controllers\StripeController::class, 'processPayment'])->name('stripe.process');
        Route::post('/payment/cash/{order}', [\App\Http\Controllers\StripeController::class, 'confirmCash'])->name('payment.confirm-cash');
        Route::get('/payment/success', [\App\Http\Controllers\StripeController::class, 'success'])->name('stripe.success');
        Route::get('/payment/cancel', [\App\Http\Controllers\StripeController::class, 'cancel'])->name('stripe.cancel');
    });

    // WHOLESALER ONLY ROUTES
    Route::middleware(['role:wholesaler'])->group(function () {
        Route::get('/wholesaler/dashboard', function () {
            $user = auth()->user();
            $stats = [
                'pending_count' => \App\Models\Order::where('wholesaler_id', $user->id)->where('status', 'wholesaler_pending')->count(),
                'retailer_count' => \App\Models\User::where('wholesaler_id', $user->id)->count(),
                'confirmed_total' => \App\Models\Order::where('wholesaler_id', $user->id)->where('status', 'distributor_confirmed')->sum('total_amount'),
            ];
            
            $orders = \App\Models\Order::with('retailer')
                ->where('wholesaler_id', $user->id)
                ->latest()
                ->get();

            return view('wholesaler.dashboard', compact('user', 'stats', 'orders'));
        })->name('wholesaler.dashboard');
    });

    // DISTRIBUTOR ONLY ROUTES
    Route::middleware(['role:distributor'])->group(function () {
        Route::get('/distributor/dashboard', function () {
            $user = auth()->user();
            
            // Distributors only care about orders that are READY for fulfillment.
            // Items in 'payment_pending' are hidden from them to avoid confusion.
            $orders = \App\Models\Order::with(['retailer', 'wholesaler'])
                ->where('distributor_id', $user->id)
                ->whereNotIn('status', ['payment_pending'])
                ->latest()
                ->get();

            $wholesalers = \App\Models\User::where('distributor_id', $user->id)
                ->where('role', 'wholesaler')
                ->withCount(['retailers' => function($q) {
                    $q->where('role', 'retailer');
                }])
                ->get();

            $direct_retailers = \App\Models\User::where('distributor_id', $user->id)
                ->where('role', 'retailer')
                ->where('order_direct', true)
                ->get();

            return view('distributor.dashboard', compact('user', 'orders', 'wholesalers', 'direct_retailers'));
        })->name('distributor.dashboard');

        Route::get('/distributor/route-optimization', [RouteController::class, 'index'])->name('distributor.route-optimization');
        Route::post('/distributor/route-optimization', [RouteController::class, 'optimize'])->name('distributor.route-optimize');
        Route::post('/distributor/route-save', [RouteController::class, 'store'])->name('distributor.route-save');
        
        // Demand & Priority Analytics
        Route::get('/distributor/demand-analytics', [DistributorAnalytics::class, 'index'])->name('distributor.demand-analytics');
        Route::get('/api/distributor/demand-trends', [DistributorAnalytics::class, 'getDemandTrends'])->name('api.distributor.demand-trends');
    });

    // NESTLE ADMIN ROUTES
    Route::middleware(['role:nestle'])->group(function () {
        Route::get('/nestle/dashboard', function () {
            $stats = [
                'total_orders' => \App\Models\Order::count(),
                'total_revenue' => \App\Models\Order::where('status', 'delivered')->sum('total_amount'),
                'total_users' => \App\Models\User::count(),
                'low_stock' => \App\Models\WarehouseStock::whereColumn('total_stock', '<=', 'reorder_point')->count(),
            ];
            
            $recent_orders = \App\Models\Order::with(['retailer', 'distributor'])->latest()->limit(10)->get();
            
            return view('nestle.dashboard', compact('stats', 'recent_orders'));
        })->name('nestle.dashboard');

        Route::get('/nestle/products', function () {
            $products = \App\Models\Product::orderBy('category')->orderBy('name')->get();
            return view('nestle.products', compact('products'));
        })->name('nestle.products');
        
        Route::get('/nestle/warehouse', function () {
            $stocks = \App\Models\WarehouseStock::with('product')->get();
            return view('nestle.warehouse', compact('stocks'));
        })->name('nestle.warehouse');

        Route::get('/nestle/analytics', function () {
            // High level metrics for the admin
            $total_orders = \App\Models\Order::count();
            $revenue = \App\Models\Order::where('status', 'delivered')->sum('total_amount');
            return view('nestle.analytics', compact('total_orders', 'revenue'));
        })->name('nestle.analytics');
        
        Route::post('/nestle/products', [App\Http\Controllers\ProductController::class, 'store'])->name('nestle.products.store');
    });

    // Shared Actions (e.g. status updates)
    Route::post('/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'statusUpdate'])->name('orders.update-status');
});
