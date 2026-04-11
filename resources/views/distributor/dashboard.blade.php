@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] pb-20">
    <!-- Premium Header -->
    <div class="bg-white border-b border-gray-100 pt-12 pb-24 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight">Distributor Portal</h1>
                    <p class="text-lg text-gray-500 mt-2 font-medium">Supply chain management & multi-tier network monitoring.</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $metrics = [
            'pending_orders' => $orders->count(),
            'total_value' => $orders->sum('total_amount')
        ];
    @endphp

    <!-- Metrics Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-gray-50">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Pending Review</p>
                <p class="text-3xl font-black text-nestle-blue mt-2">{{ $metrics['pending_orders'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-gray-50">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Network Reach</p>
                <p class="text-3xl font-black text-nestle-brown mt-2">{{ $wholesalers->count() + $direct_retailers->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-gray-50">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Pipeline Value</p>
                <p class="text-3xl font-black text-gray-900 mt-2">Rs {{ number_format($metrics['total_value'] / 1000, 1) }}k</p>
            </div>
            <div class="bg-nestle-brown p-6 rounded-3xl shadow-xl shadow-nestle-brown/20 text-white">
                <p class="text-[10px] font-black text-white/50 uppercase tracking-widest">Direct Control</p>
                <p class="text-3xl font-black mt-2">{{ $direct_retailers->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12" x-data="{ tab: 'orders' }">
        <div class="flex items-center gap-8 mb-8 border-b border-gray-200">
            <button @click="tab = 'orders'" :class="tab === 'orders' ? 'border-nestle-blue text-nestle-blue' : 'border-transparent text-gray-400 hover:text-gray-600'" class="pb-4 border-b-2 font-black text-sm uppercase tracking-widest transition-all">
                Incoming Orders
            </button>
            <button @click="tab = 'network'" :class="tab === 'network' ? 'border-nestle-blue text-nestle-blue' : 'border-transparent text-gray-400 hover:text-gray-600'" class="pb-4 border-b-2 font-black text-sm uppercase tracking-widest transition-all">
                Partner Network
            </button>
        </div>

        <!-- Orders Tab -->
        <div x-show="tab === 'orders'">
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Origin</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Retailer</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Value</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($orders as $o)
                                @php
                                    $badgeClass = match($o->status) {
                                        'delivered' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'dispatched' => 'bg-blue-100 text-blue-700',
                                        'payment_pending' => 'bg-purple-100 text-purple-700',
                                        'placed', 'wholesaler_pending', 'distributor_pending' => 'bg-yellow-100 text-yellow-700',
                                        default => 'bg-indigo-100 text-indigo-700'
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-all">
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-black text-gray-900">#{{ $o->order_number }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold mt-1">{{ $o->order_date->format('d M, H:i') }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if ($o->wholesaler)
                                            <p class="text-[10px] font-black text-nestle-brown uppercase tracking-widest">{{ $o->wholesaler->name }}</p>
                                        @else
                                            <p class="text-[10px] font-black text-nestle-blue uppercase tracking-widest">Direct</p>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-gray-700">{{ $o->retailer->name }}</td>
                                    <td class="px-8 py-6 text-sm">
                                        <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                            {{ $o->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 font-black text-gray-900">Rs {{ number_format($o->total_amount, 2) }}</td>
                                    <td class="px-8 py-6 text-right">
                                        @if ($o->status === 'distributor_pending' || $o->status === 'wholesaler_accepted')
                                            <form action="{{ route('orders.update-status', $o->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="distributor_confirmed">
                                                <button type="submit" class="px-4 py-2 bg-nestle-blue text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-nestle-blue/20 hover:scale-105 transition-all">Confirm</button>
                                            </form>
                                        @elseif ($o->status === 'distributor_confirmed')
                                            <form action="{{ route('orders.update-status', $o->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="dispatched">
                                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-600/20 hover:scale-105 transition-all">Dispatch 🚚</button>
                                            </form>
                                        @else
                                            <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Logged</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-8 py-20 text-center opacity-30 select-none">
                                    <div class="text-5xl mb-4">📭</div>
                                    <p class="uppercase font-black tracking-[0.2em] text-sm">No transaction history found</p>
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Network Tab -->
        <div x-show="tab === 'network'" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <h3 class="text-xl font-black text-nestle-brown uppercase">Wholesalers</h3>
                @foreach ($wholesalers as $w)
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-lg font-black">{{ $w->name }}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $w->retailers_count }} Retailers</p>
                    </div>
                @endforeach
            </div>
            <div class="space-y-6">
                <h3 class="text-xl font-black text-nestle-blue uppercase">Direct Retailers</h3>
                @foreach ($direct_retailers as $r)
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-lg font-black">{{ $r->name }}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $r->region }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
