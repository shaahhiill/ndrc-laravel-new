@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F9FAFB] py-12" x-data="cartManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32 lg:pb-0">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li><a href="/retailer/dashboard" class="hover:text-nestle-blue font-medium transition-colors">Dashboard</a></li>
                        <li><svg class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                        <li class="text-nestle-brown font-bold">New Inventory Order</li>
                    </ol>
                </nav>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Product Catalogue</h1>
                <p class="text-gray-500 mt-2 font-medium">B2B Wholesale Portal — Select bulk cases and cartons below.</p>
            </div>
            
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="h-12 w-12 bg-nestle-blue/10 rounded-xl flex items-center justify-center text-nestle-blue">🏢</div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Pricing Mode</p>
                    <p class="text-nestle-brown font-bold text-sm">Distributor Rates (Bulk)</p>
                </div>
            </div>
        </div>

        <!-- Featured Banner -->
        <div class="mb-12 rounded-[3rem] overflow-hidden relative h-64 shadow-2xl bg-nestle-blue">
            <img src="{{ asset('images/catalog-banner.jpg') }}" alt="Nestle Products" class="absolute inset-0 w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-r from-nestle-blue/80 to-transparent flex flex-col justify-center px-12 text-white">
                <h2 class="text-3xl font-black mb-2 uppercase tracking-tighter">Supply Chain replenishment</h2>
                <p class="text-lg font-bold opacity-90 max-w-md">Official B2B fulfillment channel for certified Nestlé Retailers.</p>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-8 p-6 bg-red-600 text-white rounded-[2rem] shadow-xl shadow-red-200 flex items-center gap-4 border-2 border-white/20">
                <div class="h-12 w-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">⚠️</div>
                <div>
                    <p class="font-black text-xs uppercase tracking-widest leading-none mb-1">Attention Required</p>
                    <p class="text-sm font-bold opacity-90">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form id="orderForm" action="{{ route('orders') }}" method="POST">
            @csrf
            <input type="hidden" name="distributor_id" value="{{ auth()->user()->distributor_id }}">
            <input type="hidden" name="wholesaler_id" value="{{ auth()->user()->wholesaler_id }}">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Product List -->
                <div class="lg:col-span-8 space-y-8">
                    @php $currentCat = ''; @endphp
                    @foreach ($products as $p)
                        @if ($currentCat !== $p->category)
                            @php $currentCat = $p->category; @endphp
                            <div class="flex items-center gap-4 border-b border-gray-200 pb-2 mt-8">
                                <h2 class="text-lg font-black text-nestle-brown uppercase tracking-tighter">{{ $currentCat }} Hub</h2>
                                <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-transparent"></div>
                            </div>
                        @endif

                        <div class="bg-white rounded-[2rem] border border-gray-100 p-6 flex flex-col sm:flex-row items-center gap-8 shadow-sm hover:shadow-2xl hover:border-nestle-blue/30 transition-all group relative overflow-hidden">
                            <!-- Stock Indicator Dot -->
                            <div class="absolute top-4 right-4 flex items-center gap-1.5 px-2 py-1 bg-green-50 rounded-lg">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-[9px] font-black text-green-700 uppercase tracking-widest">In Stock</span>
                            </div>

                            <!-- Product Icon -->
                            <div class="h-24 w-24 bg-gray-50 rounded-3xl flex-shrink-0 flex items-center justify-center text-5xl shadow-inner group-hover:scale-110 transition-transform duration-500">
                                @switch($p->category)
                                    @case('Dairy') 🥛 @break
                                    @case('Beverages') ☕ @break
                                    @case('Noodles') 🍜 @break
                                    @case('Confectionery') 🍫 @break
                                    @case('Culinary') 🥣 @break
                                    @case('Cereals') 🥣 @break
                                    @case('Nutrition') 🤱 @break
                                    @default 📦
                                @endswitch
                            </div>

                            <!-- Details -->
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-nestle-blue transition-colors">{{ $p->name }}</h3>
                                <p class="text-xs text-gray-400 mt-2 font-medium">Wholesale SKU: <span class="text-gray-900 font-black">{{ $p->sku }}</span></p>
                                <div class="mt-4 flex flex-wrap justify-center sm:justify-start items-center gap-3">
                                    <span class="px-3 py-1 bg-nestle-blue text-white rounded-lg text-[10px] font-black uppercase tracking-widest">Bulk {{ $p->unit }}</span>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-black uppercase tracking-widest">Nestlé Lanka PLC</span>
                                </div>
                            </div>

                            <!-- Pricing & Qty -->
                            <div class="flex flex-col items-center sm:items-end gap-4 min-w-[200px]">
                                <div class="text-right">
                                    <p class="text-2xl font-black text-nestle-brown leading-none">Rs {{ number_format($p->price, 2) }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Per {{ $p->unit }}</p>
                                </div>
                                
                                <div class="flex items-center bg-gray-900 rounded-2xl p-1 shadow-xl">
                                    <button type="button" @click="updateQty({{ $p->id }}, -1)" class="w-12 h-12 rounded-xl flex items-center justify-center text-white hover:bg-white/10 transition-all font-black text-2xl">−</button>
                                    <input type="number" 
                                           name="items[{{ $p->id }}][quantity]" 
                                           id="qty_{{ $p->id }}" 
                                           x-model="cart['{{ $p->id }}']"
                                           class="w-16 text-center bg-transparent border-0 p-0 text-sm font-black text-white focus:ring-0"
                                           min="0">
                                    <button type="button" @click="updateQty({{ $p->id }}, 1)" class="w-12 h-12 rounded-xl flex items-center justify-center text-nestle-blue hover:bg-white/10 transition-all font-black text-2xl">+</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Sidebar Cart -->
                <div class="hidden lg:block lg:col-span-4">
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl sticky top-28 overflow-hidden">
                        <div class="bg-nestle-brown p-8 text-white">
                            <h3 class="text-2xl font-black tracking-tight">Order Summary</h3>
                            <p class="text-white/60 text-sm mt-1">Bulk distribution pricing applied.</p>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="space-y-4 max-h-[40vh] overflow-y-auto no-scrollbar">
                                <template x-for="(qty, id) in cart" :key="id">
                                    <div x-show="qty > 0" class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                        <div class="flex-1">
                                            <p class="text-sm font-black text-gray-900 leading-tight" x-text="getProduct(id).name"></p>
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">
                                                <span x-text="qty"></span> x <span x-text="getProduct(id).unit"></span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black text-nestle-brown">Rs <span x-text="(qty * getProduct(id).price).toLocaleString()"></span></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="pt-6 border-t border-gray-100 flex justify-between items-center bg-nestle-blue/5 p-4 rounded-2xl">
                                <span class="text-nestle-blue font-bold">Total Amount</span>
                                <span class="text-2xl font-black text-gray-900">Rs <span x-text="calculateTotal().toLocaleString()"></span></span>
                            </div>

                            <button type="submit" :disabled="calculateTotal() === 0" :class="calculateTotal() > 0 ? 'bg-nestle-blue shadow-nestle-blue/30' : 'bg-gray-200 cursor-not-allowed'" class="w-full h-16 text-white font-black text-lg rounded-2xl transition-all shadow-lg flex items-center justify-center gap-3">
                                <span>Review & Checkout</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Sticky Footer -->
            <div class="lg:hidden fixed bottom-12 left-0 right-0 bg-white border-t border-gray-100 p-4 shadow-2xl z-40 transform transition-transform" x-show="calculateTotal() > 0" x-transition:enter="translate-y-full" x-transition:enter-end="translate-y-0">
                <div class="flex items-center justify-between mb-4 px-2">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Bulk Payble</p>
                        <p class="text-xl font-black text-gray-900">Rs <span x-text="calculateTotal().toLocaleString()"></span></p>
                    </div>
                </div>
                <button type="submit" :disabled="calculateTotal() === 0" class="w-full h-14 bg-nestle-blue text-white rounded-xl font-black text-sm uppercase tracking-widest shadow-xl shadow-nestle-blue/30">
                    Review Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function cartManager() {
    return {
        cart: {},
        products: @json($products->keyBy('id')),
        
        init() {
            Object.keys(this.products).forEach(id => {
                this.cart[id] = 0;
            });
        },
        
        getProduct(id) {
            return this.products[id];
        },
        
        updateQty(id, delta) {
            let val = parseInt(this.cart[id] || 0) + delta;
            if (val < 0) val = 0;
            this.cart[id] = val;
        },
        
        calculateTotal() {
            let total = 0;
            Object.entries(this.cart).forEach(([id, qty]) => {
                if (qty > 0) {
                    total += qty * this.products[id].price;
                }
            });
            return total;
        }
    }
}
</script>
@endsection
