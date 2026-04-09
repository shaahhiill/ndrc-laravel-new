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
            </div>
            
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="h-12 w-12 bg-nestle-blue/10 rounded-xl flex items-center justify-center text-nestle-blue">🏢</div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Pricing Mode</p>
                    <p class="text-nestle-brown font-bold text-sm">Distributor Rates</p>
                </div>
            </div>
        </div>

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
                                <h2 class="text-lg font-black text-nestle-brown uppercase tracking-tighter">{{ $currentCat }}</h2>
                                <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-transparent"></div>
                            </div>
                        @endif

                        <div class="bg-white rounded-[2rem] border border-gray-100 p-5 sm:p-6 flex flex-col sm:flex-row items-center gap-6 shadow-sm hover:shadow-xl hover:border-nestle-blue/20 transition-all group overflow-hidden relative">
                            <!-- Product Icon -->
                            <div class="h-20 w-20 sm:h-24 sm:w-24 bg-[#F5F3F0] rounded-3xl flex-shrink-0 flex items-center justify-center text-4xl shadow-inner group-hover:scale-105 transition-transform">
                                @switch($p->category)
                                    @case('Dairy') 🥛 @break
                                    @case('Beverages') ☕ @break
                                    @case('Noodles') 🍜 @break
                                    @case('Confectionery') 🍫 @break
                                    @case('Culinary') 🥣 @break
                                    @default 📦
                                @endswitch
                            </div>

                            <!-- Details -->
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-nestle-blue transition-colors">{{ $p->name }}</h3>
                                <div class="mt-2 flex flex-wrap justify-center sm:justify-start items-center gap-2">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ $p->sku }}</span>
                                    <span class="px-2 py-1 bg-nestle-blue/5 text-nestle-blue rounded-lg text-[10px] font-bold uppercase tracking-widest">{{ $p->unit }}</span>
                                </div>
                            </div>

                            <!-- Pricing & Qty -->
                            <div class="flex flex-col items-center sm:items-end gap-3 min-w-[150px]">
                                <p class="text-xl font-black text-nestle-brown">Rs {{ number_format($p->price, 2) }}</p>
                                
                                <div class="flex items-center bg-gray-50 rounded-2xl p-1 border border-gray-100 shadow-inner">
                                    <button type="button" @click="updateQty({{ $p->id }}, -1)" class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 hover:text-nestle-brown hover:bg-white transition-all font-black text-xl">-</button>
                                    <input type="number" 
                                           name="items[{{ $p->id }}][quantity]" 
                                           id="qty_{{ $p->id }}" 
                                           x-model="cart['{{ $p->id }}']"
                                           class="w-12 text-center bg-transparent border-0 p-0 text-sm font-black text-gray-900 focus:ring-0">
                                    <button type="button" @click="updateQty({{ $p->id }}, 1)" class="w-10 h-10 rounded-xl flex items-center justify-center text-nestle-blue hover:bg-white transition-all font-black text-xl">+</button>
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
                            <p class="text-white/60 text-sm mt-1">Direct fulfillment chain.</p>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="space-y-4 max-h-[40vh] overflow-y-auto no-scrollbar">
                                <template x-for="(qty, id) in cart" :key="id">
                                    <div x-show="qty > 0" class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                        <div class="flex-1">
                                            <p class="text-sm font-black text-gray-900 leading-tight" x-text="getProduct(id).name"></p>
                                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">
                                                <span x-text="qty"></span> x Rs <span x-text="getProduct(id).price.toFixed(2)"></span>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black text-nestle-brown">Rs <span x-text="(qty * getProduct(id).price).toLocaleString()"></span></p>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="Object.values(cart).reduce((a, b) => a + b, 0) === 0" class="text-center py-10 opacity-30 select-none text-sm font-bold uppercase tracking-widest">
                                    Cart is Empty
                                </div>
                            </div>
                            <div class="pt-6 border-t border-gray-100 flex justify-between items-center bg-nestle-blue/5 p-4 rounded-2xl">
                                <span class="text-nestle-blue font-bold">Total Amount</span>
                                <span class="text-2xl font-black text-gray-900">Rs <span x-text="calculateTotal().toLocaleString()"></span></span>
                            </div>
                            <button type="submit" :disabled="calculateTotal() === 0" :class="calculateTotal() > 0 ? 'bg-nestle-blue' : 'bg-gray-200 cursor-not-allowed'" class="w-full h-16 text-white font-black text-lg rounded-2xl transition-all shadow-lg flex items-center justify-center gap-3">
                                <span>Send Order Request</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
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
            let val = parseInt(this.cart[id]) + delta;
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
