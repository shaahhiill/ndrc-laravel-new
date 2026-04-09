@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Wholesaler Hub</h2>
        <p class="mt-1 text-sm text-gray-500">Manage incoming orders from your retailers</p>
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
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Retailer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($orders as $o)
                        @php
                            $badgeClass = match($o->status) {
                                'delivered' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'dispatched' => 'bg-blue-100 text-blue-700',
                                'wholesaler_pending', 'distributor_pending' => 'bg-yellow-100 text-yellow-700',
                                'wholesaler_accepted', 'distributor_confirmed' => 'bg-indigo-100 text-indigo-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $o->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $o->retailer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $o->order_date->format('M d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Rs {{ number_format($o->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $badgeClass }}">
                                    {{ str_replace('_', ' ', $o->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                @if ($o->status === 'wholesaler_pending')
                                    <form action="{{ route('orders.update-status', $o->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="wholesaler_accepted">
                                        <button type="submit" class="text-green-600 font-bold hover:bg-green-50 px-2 py-1 rounded">Accept & Forward</button>
                                    </form>
                                    <form action="{{ route('orders.update-status', $o->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="text-red-600 font-bold hover:bg-red-50 px-2 py-1 rounded ml-2">Reject</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 italic">No action</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
