@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F0F2F5] py-12" x-data="{ paymentMethod: 'card' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="mb-8 p-6 bg-red-600 text-white rounded-[2rem] shadow-xl shadow-red-200 flex items-center gap-4 border-2 border-white/20">
                <div class="h-12 w-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">⚠️</div>
                <div>
                    <p class="font-black text-xs uppercase tracking-widest leading-none mb-1">Attention Required</p>
                    <p class="text-sm font-bold opacity-90">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        <!-- Progress Steps -->
        <div class="flex items-center justify-center gap-4 mb-12 opacity-80 scale-90 sm:scale-100">
            <div class="flex items-center gap-2 group">
                <div class="h-8 w-18 bg-white text-gray-400 rounded-full flex items-center justify-center text-[10px] font-black uppercase border border-gray-200 px-4">Catalogue</div>
            </div>
            <div class="h-px w-8 bg-gray-300"></div>
            <div class="flex items-center gap-2">
                <div class="h-8 w-18 bg-nestle-blue text-white rounded-full flex items-center justify-center text-[10px] font-black uppercase shadow-lg shadow-nestle-blue/20 px-4 whitespace-nowrap">Checkout</div>
            </div>
            <div class="h-px w-8 bg-gray-300"></div>
            <div class="flex items-center gap-2">
                <div class="h-8 w-18 bg-white text-gray-400 rounded-full flex items-center justify-center text-[10px] font-black uppercase border border-gray-200 px-4">Payment</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Column: Details -->
            <div class="lg:col-span-7 space-y-8">
                <!-- Shipping Address Section -->
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 border border-gray-100 shadow-xl shadow-gray-200/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-nestle-blue/5 rounded-full -mr-16 -mt-16"></div>
                    <div class="flex items-center justify-between mb-8 relative">
                        <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
                            <span class="h-10 w-10 bg-nestle-blue/10 rounded-xl flex items-center justify-center text-nestle-blue">📍</span>
                            Delivery Information
                        </h3>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-lg">Verified Account</span>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Business Name</p>
                                <p class="font-bold text-gray-900 leading-tight">{{ $order->retailer->name }}</p>
                            </div>
                            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Phone Reference</p>
                                <p class="font-bold text-gray-900 leading-tight">{{ $order->retailer->phone ?? 'Not Provided' }}</p>
                            </div>
                        </div>
                        <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Delivery Address</p>
                            <p class="font-bold text-gray-900 leading-tight">{{ $order->retailer->address ?: 'Set in profile' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $order->retailer->region }} — {{ $order->retailer->territory }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items Section -->
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 border border-gray-100 shadow-xl shadow-gray-200/50">
                    <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
                        <span class="h-10 w-10 bg-nestle-brown/10 rounded-xl flex items-center justify-center text-nestle-brown">📦</span>
                        Order Inventory
                    </h3>
                    
                    <div class="space-y-5">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-4 border-b border-gray-50 last:border-0 last:pb-0">
                            <div class="h-12 w-12 bg-gray-50 rounded-xl flex items-center justify-center text-2xl">
                                @switch($item->product->category)
                                    @case('Dairy') 🥛 @break
                                    @case('Beverages') ☕ @break
                                    @case('Noodles') 🍜 @break
                                    @default 📦
                                @endswitch
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-black text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $item->quantity }} {{ $item->product->unit }} @ Rs {{ number_format($item->unit_price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-gray-900">Rs {{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Payment Selection & Summary -->
            <div class="lg:col-span-5">
                <div class="sticky top-8 space-y-8">
                    <!-- Payment Choice Card -->
                    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 border border-gray-100 shadow-2xl">
                        <h3 class="text-xl font-black text-gray-900 mb-8">Select Payment Method</h3>
                        
                        <div class="grid grid-cols-1 gap-4 mb-10">
                            <!-- Card Option -->
                            <label class="cursor-pointer group">
                                <input type="radio" value="card" x-model="paymentMethod" class="sr-only peer">
                                <div class="p-6 border-2 border-gray-100 rounded-3xl peer-checked:border-nestle-blue peer-checked:bg-nestle-blue/5 transition-all flex items-center gap-4 group-hover:border-nestle-blue/30 group-hover:bg-gray-50">
                                    <div class="h-12 w-12 rounded-2xl bg-white border border-gray-200 flex items-center justify-center text-2xl shadow-sm transition-transform peer-checked:scale-110">💳</div>
                                    <div class="flex-1">
                                        <p class="font-black text-gray-900 text-sm">Online Payment</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Secure Credit/Debit Card</p>
                                    </div>
                                    <div class="h-6 w-6 rounded-full border-2 border-gray-100 flex items-center justify-center peer-checked:border-nestle-blue">
                                        <div x-show="paymentMethod === 'card'" class="h-3 w-3 rounded-full bg-nestle-blue"></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Cash Option -->
                            <label class="cursor-pointer group">
                                <input type="radio" value="cash" x-model="paymentMethod" class="sr-only peer">
                                <div class="p-6 border-2 border-gray-100 rounded-3xl peer-checked:border-nestle-blue peer-checked:bg-nestle-blue/5 transition-all flex items-center gap-4 group-hover:border-nestle-blue/30 group-hover:bg-gray-50">
                                    <div class="h-12 w-12 rounded-2xl bg-white border border-gray-200 flex items-center justify-center text-2xl shadow-sm transition-transform peer-checked:scale-110">💵</div>
                                    <div class="flex-1">
                                        <p class="font-black text-gray-900 text-sm">Cash on Delivery</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Pay at Doorstep</p>
                                    </div>
                                    <div class="h-6 w-6 rounded-full border-2 border-gray-100 flex items-center justify-center peer-checked:border-nestle-blue">
                                        <div x-show="paymentMethod === 'cash'" class="h-3 w-3 rounded-full bg-nestle-blue"></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Summary Breakdown -->
                        <div class="space-y-4 pt-8 border-t border-gray-100">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400 font-bold uppercase tracking-widest">Inventory Total</span>
                                <span class="font-black text-gray-900">Rs {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400 font-bold uppercase tracking-widest">Processing Fee</span>
                                <span class="font-black text-green-600">ZERO</span>
                            </div>
                            <div class="pt-6 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Payable</p>
                                    <p class="text-3xl font-black text-gray-900">Rs {{ number_format($order->total_amount, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-nestle-blue uppercase tracking-[0.2em] mb-1">Partner Chain</p>
                                    <p class="text-xs font-bold text-gray-600">{{ $order->distributor->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submission Logic using selection -->
                        <div class="mt-10">
                            <!-- Card Submission -->
                            <form x-show="paymentMethod === 'card'" action="{{ route('stripe.process', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full h-20 bg-nestle-blue text-white rounded-[2rem] font-black text-lg shadow-xl shadow-nestle-blue/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                                    <span>Proceed to Secure Pay</span>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </button>
                            </form>

                            <!-- Cash Submission -->
                            <form x-show="paymentMethod === 'cash'" action="{{ route('payment.confirm-cash', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full h-20 bg-nestle-brown text-white rounded-[2rem] font-black text-lg shadow-xl shadow-nestle-brown/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                                    <span>Confirm Cash Order</span>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                        </div>
                        
                        <div class="mt-8 flex flex-col items-center gap-6">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] text-center">Encrypted Transaction Processing</p>
                            <div class="flex justify-center items-center gap-6 opacity-30 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-500">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3" alt="Visa">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5" alt="Mastercard">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" class="h-4" alt="Stripe">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between">
                         <a href="{{ route('retailer.place-order') }}" class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-nestle-blue transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Catalogue
                        </a>
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">Order Ref: #{{ $order->order_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
