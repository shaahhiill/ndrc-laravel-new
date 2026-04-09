<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NetworkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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
    Route::post('/retailer/join-network', [NetworkController::class, 'joinRequest'])->name('network.join');
    Route::post('/orders', [OrderController::class, 'placeOrder'])->name('orders');
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
            ->limit(10)
            ->get();

        return view('wholesaler.dashboard', compact('user', 'stats', 'orders'));
    })->name('wholesaler.dashboard');

    Route::get('/distributor/dashboard', function () {
        $user = auth()->user();
        
        $orders = \App\Models\Order::with(['retailer', 'wholesaler'])
            ->where('distributor_id', $user->id)
            ->whereIn('status', ['distributor_pending', 'distributor_confirmed'])
            ->orderBy('status')
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

    Route::post('/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'statusUpdate'])->name('orders.update-status');

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
});
