@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showModal: false }">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Catalogue Management</h2>
            <p class="text-sm text-gray-400 font-black uppercase tracking-widest mt-1">Add or Update National Product Listing</p>
        </div>
        <button @click="showModal = true" class="px-8 py-4 bg-nestle-blue text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-nestle-blue/20 hover:scale-105 transition-all">Add New Product ➕</button>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">SKU</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Product Name</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 uppercase font-bold text-xs">
                    @foreach ($products as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-8 py-6 text-gray-400">{{ $p->sku }}</td>
                            <td class="px-8 py-6 text-gray-900">{{ $p->name }}</td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-1 bg-blue-50 text-nestle-blue rounded-lg">{{ $p->category }}</span>
                            </td>
                            <td class="px-8 py-6 text-gray-600">{{ $p->unit }}</td>
                            <td class="px-8 py-6 text-gray-900 font-black">Rs {{ number_format($p->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="showModal = false">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-white p-8 sm:p-12">
                    <h3 class="text-3xl font-black text-gray-900 leading-none mb-2">New Product</h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">Enter details for the unified catalogue.</p>

                    <form action="{{ route('nestle.products.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Product Name</label>
                                <input type="text" name="name" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-4 px-6 text-sm font-bold focus:ring-nestle-blue focus:border-nestle-blue">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">SKU</label>
                                <input type="text" name="sku" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-4 px-6 text-sm font-bold focus:ring-nestle-blue focus:border-nestle-blue">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Category</label>
                                <select name="category" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-4 px-6 text-sm font-bold focus:ring-nestle-blue focus:border-nestle-blue">
                                    <option value="Dairy">Dairy</option>
                                    <option value="Beverages">Beverages</option>
                                    <option value="Noodles">Noodles</option>
                                    <option value="Confectionery">Confectionery</option>
                                    <option value="Culinary">Culinary</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Unit (e.g. 1kg, 400g)</label>
                                <input type="text" name="unit" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-4 px-6 text-sm font-bold focus:ring-nestle-blue focus:border-nestle-blue">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Price (Rs)</label>
                                <input type="number" step="0.01" name="price" required class="w-full rounded-2xl border-gray-100 bg-gray-50 py-4 px-6 text-sm font-bold focus:ring-nestle-blue focus:border-nestle-blue">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex gap-4">
                            <button type="button" @click="showModal = false" class="flex-1 py-4 bg-gray-100 text-gray-900 rounded-2xl text-xs font-black uppercase tracking-widest">Cancel</button>
                            <button type="submit" class="flex-1 py-4 bg-nestle-blue text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-nestle-blue/20">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
