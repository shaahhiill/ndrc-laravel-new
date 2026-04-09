@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-bold animate-pulse">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-bold">
            🚨 {{ session('error') }}
        </div>
    @endif

    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Retailer Dashboard</h2>
            <p class="text-sm text-gray-400 font-bold uppercase tracking-widest mt-1">Direct Retail Channel Operations</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="/retailer/place-order" class="inline-flex items-center rounded-2xl bg-nestle-brown px-8 py-4 text-sm font-black text-white shadow-xl shadow-nestle-brown/20 hover:scale-[1.02] active:scale-95 transition-all">
                New Inventory Order 🛒
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Activity -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white shadow-2xl rounded-[2.5rem] border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-900 uppercase">Recent Activity</h3>
                    <a href="/retailer/orders" class="text-xs font-black text-nestle-blue hover:underline">View All</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($orders as $o)
                        @php
                            $badgeClass = match($o->status) {
                                'delivered' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'dispatched' => 'bg-blue-100 text-blue-700',
                                'placed', 'wholesaler_pending', 'distributor_pending' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <div class="p-8 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-3 bg-gray-100 rounded-2xl text-xl">🛒</div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Order Ref</p>
                                        <p class="text-sm font-black text-gray-900">#{{ $o->order_number }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                    {{ str_replace('_', ' ', $o->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-xs font-bold text-gray-500">Scheduled: {{ $o->distributor->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase">{{ $o->order_date->format('d M, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-black text-gray-900">Rs {{ number_format($o->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="text-6xl mb-4 opacity-10">📦</div>
                            <p class="text-xl font-black text-gray-300 uppercase tracking-widest">No orders logged</p>
                            <a href="/retailer/place-order" class="text-nestle-blue font-black mt-4 inline-block hover:underline">Start Restocking Now</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Network Status -->
        <div class="space-y-8">
            <!-- Wholesaler Affiliation -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-2xl relative overflow-hidden group">
                <div class="relative z-10">
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Network Affiliation</h3>
                    
                    @if ($user->wholesaler_id)
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-nestle-blue/10 rounded-2xl flex items-center justify-center text-3xl">🏬</div>
                            <div>
                                <p class="text-[10px] font-black text-nestle-blue uppercase tracking-widest mb-1">Primary Partner</p>
                                <p class="text-lg font-black text-gray-900 leading-tight">{{ $user->wholesaler->name }}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-green-50 rounded-2xl border border-green-100 flex items-center gap-3">
                            <span class="text-lg">✅</span>
                            <span class="text-xs font-bold text-green-700">Verified Hub Partner</span>
                        </div>
                    @elseif ($pending_req)
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center text-3xl animate-pulse">⏳</div>
                            <div>
                                <p class="text-[10px] font-black text-yellow-600 uppercase tracking-widest mb-1">Approval Pending</p>
                                <p class="text-lg font-black text-gray-900 leading-tight">{{ $pending_req->wholesaler->name }}</p>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-loose">Waiting for wholesaler confirmation.</p>
                    @else
                        <div class="space-y-6">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-loose">Send a join request to local partners.</p>
                            
                            <form action="{{ route('network.join') }}" method="POST" class="space-y-4">
                                @csrf
                                <select name="wholesaler_id" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-4 px-6 text-sm font-bold focus:ring-nestle-blue focus:border-nestle-blue transition-all">
                                    <option value="">Select Local Partner...</option>
                                    @foreach ($wholesalers as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->territory }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:scale-95 transition-all">Request to Join</button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-nestle-blue/5 rounded-full blur-3xl group-hover:scale-110 transition-transform"></div>
            </div>

            <div class="bg-nestle-blue rounded-[2.5rem] p-8 text-white shadow-2xl shadow-nestle-blue/20 relative overflow-hidden">
                <h3 class="text-lg font-black mb-4 tracking-tight leading-tight">Spring Sales Boost 🎁</h3>
                <p class="text-white/70 text-sm font-medium leading-relaxed mb-6 italic font-serif">"Stock up on MAGGI products this week!"</p>
                <a href="/retailer/place-order?category=Culinary" class="inline-flex items-center gap-2 bg-white text-nestle-blue px-6 py-3 rounded-2xl text-xs font-black uppercase shadow-lg hover:scale-105 transition-all">Shop Highlights</a>
            </div>
        </div>
    </div>
</div>
@endsection
