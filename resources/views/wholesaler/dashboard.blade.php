@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Wholesaler Hub</h2>
            <p class="text-sm text-gray-400 font-black uppercase tracking-widest mt-1">Manage incoming orders from your retailers</p>
        </div>
        <div class="flex gap-4">
            <a href="/retailer/place-order" class="px-8 py-4 bg-nestle-brown text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-nestle-brown/20 hover:scale-[1.02] transition-all">Restock Inventory 🛒</a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-xl border border-gray-100">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                        <span class="text-2xl">📥</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Orders</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_count'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 text-xs">
                <span class="font-bold text-nestle-blue">Review All Orders →</span>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-xl border border-gray-100">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-nestle-blue/10 rounded-lg p-3">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Retailer Network</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['retailer_count'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 text-xs">
                <span class="font-bold text-nestle-blue">Manage Network →</span>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-xl border border-gray-100">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <span class="text-2xl">📈</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Aggregated Confirmed</dt>
                            <dd class="text-2xl font-semibold text-gray-900">Rs {{ number_format($stats['confirmed_total'], 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3 text-xs">
                <span class="text-gray-400">Lifetime value</span>
            </div>
        </div>
    </div>

    <!-- Incoming Orders -->
    <div class="bg-white shadow rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Incoming Retailer Orders</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Order</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Retailer</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Total</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($orders as $o)
                        @php
                            $badgeClass = match($o->status) {
                                'delivered' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'dispatched' => 'bg-blue-100 text-blue-700',
                                'payment_pending' => 'bg-purple-100 text-purple-700',
                                'wholesaler_pending' => 'bg-yellow-100 text-yellow-700',
                                'wholesaler_accepted', 'distributor_confirmed' => 'bg-indigo-100 text-indigo-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-black text-gray-900">#{{ $o->order_number }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $o->retailer->name }}</td>
                            <td class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $o->order_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm font-black text-gray-900">Rs {{ number_format($o->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                    {{ $o->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($o->status === 'wholesaler_pending')
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('orders.update-status', $o->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="wholesaler_accepted">
                                            <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-600/20">Accept</button>
                                        </form>
                                        <form action="{{ route('orders.update-status', $o->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="bg-red-50 text-red-600 border border-red-100 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Archived</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center opacity-30 select-none">
                            <div class="text-4xl mb-4">📂</div>
                            <p class="uppercase font-black tracking-widest text-xs">No order records found</p>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
