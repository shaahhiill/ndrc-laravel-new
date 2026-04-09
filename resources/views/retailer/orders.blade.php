@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="/retailer/dashboard" class="hover:text-nestle-blue font-medium transition-colors">Dashboard</a></li>
                <li><svg class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                <li class="text-nestle-brown font-bold">Order History</li>
            </ol>
        </nav>
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">All Orders</h2>
    </div>

    <div class="bg-white shadow-2xl rounded-[2.5rem] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Order Ref</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Partner</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
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
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6 text-sm font-black text-gray-900">#{{ $o->order_number }}</td>
                            <td class="px-8 py-6 text-sm text-gray-500">{{ $o->order_date->format('d M, Y') }}</td>
                            <td class="px-8 py-6 text-sm text-gray-700">{{ $o->distributor->name }} @if($o->wholesaler) <span class="text-[10px] text-gray-400 italic">via {{ $o->wholesaler->name }}</span> @endif</td>
                            <td class="px-8 py-6 text-lg font-black text-gray-900">Rs {{ number_format($o->total_amount, 2) }}</td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                    {{ str_replace('_', ' ', $o->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center">
                                <p class="text-xl font-black text-gray-300 uppercase tracking-widest">No orders found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
