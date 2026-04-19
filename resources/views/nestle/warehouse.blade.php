@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Warehouse monitoring</h2>
            <p class="text-sm text-gray-400 font-black uppercase tracking-widest mt-1">Real-time Stock Levels & Replenishment Hub</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="h-10 w-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600 font-black">✓</div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Stock Status</p>
                <p class="text-green-600 font-extrabold text-sm uppercase">Nominal</p>
            </div>
        </div>
    </div>

    <!-- Inventory Alerts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-xl shadow-gray-200/50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Global SKUs</p>
            <p class="text-3xl font-black text-gray-900 mt-2">{{ $stocks->count() }}</p>
            <p class="text-xs text-gray-400 font-bold mt-2 tracking-tight">Total Unique Products</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-xl shadow-gray-200/50">
            <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Low Stock Alerts</p>
            <p class="text-3xl font-black text-red-600 mt-2">{{ $stocks->filter(fn($s) => $s->total_stock <= $s->reorder_point)->count() }}</p>
            <p class="text-xs text-gray-400 font-bold mt-2 tracking-tight">Need Immediate Action</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-xl shadow-gray-200/50">
            <p class="text-[10px] font-black text-nestle-blue uppercase tracking-widest">Avg Availability</p>
            <p class="text-3xl font-black text-nestle-blue mt-2">94.2%</p>
            <p class="text-xs text-gray-400 font-bold mt-2 tracking-tight">Standard Distribution Node</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Product SKU</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Current Stock</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Reserved</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Reorder Point</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Capacity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-bold text-xs">
                    @foreach ($stocks as $s)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6">
                                <p class="text-gray-900 font-black">{{ $s->product->name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $s->product->sku }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-xl {{ $s->total_stock <= $s->reorder_point ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ number_format($s->total_stock) }} {{ $s->product->unit }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-gray-500">{{ number_format($s->reserved_stock) }}</td>
                            <td class="px-8 py-6 text-gray-500">{{ number_format($s->reorder_point) }}</td>
                            <td class="px-8 py-6">
                                @php $percent = min(100, ($s->total_stock / 2000) * 100); @endphp
                                <div class="w-32 bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="h-full {{ $percent < 20 ? 'bg-red-500' : 'bg-nestle-blue' }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
