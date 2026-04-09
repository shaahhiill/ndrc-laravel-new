@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Nestlé Command Center</h2>
            <p class="text-sm text-gray-400 font-black uppercase tracking-widest mt-1">National Distribution & Retail Channel Analytics</p>
        </div>
        <div class="flex gap-4">
            <button class="px-6 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 hover:shadow-md transition-all">Download Report 📊</button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-xl shadow-gray-200/50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Transaction Value</p>
            <p class="text-3xl font-black text-gray-900 mt-2">Rs {{ number_format($stats['total_revenue'] / 1000, 1) }}k</p>
            <p class="text-xs text-green-500 font-bold mt-2">↑ 12% from last month</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-xl shadow-gray-200/50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Orders</p>
            <p class="text-3xl font-black text-nestle-blue mt-2">{{ $stats['total_orders'] }}</p>
            <p class="text-xs text-gray-400 font-bold mt-2">System wide</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-xl shadow-gray-200/50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Retail Network</p>
            <p class="text-3xl font-black text-nestle-brown mt-2">{{ $stats['total_users'] }}</p>
            <p class="text-xs text-gray-400 font-bold mt-2">Wholesalers & Retailers</p>
        </div>
        <div class="p-8 rounded-[2rem] border {{ $stats['low_stock'] > 0 ? 'bg-red-50 border-red-100' : 'bg-green-50 border-green-100' }} shadow-xl">
            <p class="text-[10px] font-black {{ $stats['low_stock'] > 0 ? 'text-red-400' : 'text-green-400' }} uppercase tracking-widest">Inventory Alerts</p>
            <p class="text-3xl font-black {{ $stats['low_stock'] > 0 ? 'text-red-600' : 'text-green-600' }} mt-2">{{ $stats['low_stock'] }}</p>
            <p class="text-xs font-bold mt-2">{{ $stats['low_stock'] > 0 ? 'Items below reorder point' : 'All items well stocked' }}</p>
        </div>
    </div>

    <!-- Performance Table -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-lg font-black text-gray-900 uppercase">Recent System Activity</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Order</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Retailer</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Distributor</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($recent_orders as $o)
                        @php
                            $badgeClass = match($o->status) {
                                'delivered' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'dispatched' => 'bg-blue-100 text-blue-700',
                                default => 'bg-yellow-100 text-yellow-700'
                            };
                        @endphp
                        <tr>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-900">#{{ $o->order_number }}</p>
                                <p class="text-[10px] text-gray-400 font-bold">{{ $o->created_at->format('M d, H:i') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-bold text-gray-700">{{ $o->retailer->name }}</p>
                            </td>
                            <td class="px-8 py-6 font-medium text-gray-600">{{ $o->distributor->name }}</td>
                            <td class="px-8 py-6 font-black text-gray-900">Rs {{ number_format($o->total_amount, 2) }}</td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                    {{ str_replace('_', ' ', $o->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
