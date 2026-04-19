@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-12">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Market Intelligence</h2>
            <p class="text-sm text-gray-400 font-black uppercase tracking-widest mt-1">Unified Analytics for last-mile
                visibility gap</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Revenue Card -->
            <div
                class="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-gray-100 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8">
                    <span
                        class="px-4 py-2 bg-green-100 text-green-700 rounded-2xl text-[10px] font-black uppercase tracking-widest">Live
                        Flow</span>
                </div>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-4">Cumulative Revenue</h3>
                <p class="text-5xl font-black text-gray-900 ring-offset-inherit">Rs {{ number_format($revenue, 2) }}</p>
                <div class="mt-8 h-48 w-full bg-gray-50 rounded-3xl flex items-end justify-between p-6 gap-2">
                    @foreach([40, 60, 45, 90, 65, 80, 100] as $h)
                        <div class="flex-1 bg-nestle-blue/20 rounded-t-xl hover:bg-nestle-blue transition-all"
                            style="height: {{ $h }}%"></div>
                    @endforeach
                </div>
                <div class="mt-4 flex justify-between text-[10px] font-black text-gray-300 uppercase tracking-widest px-2">
                    <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                </div>
            </div>

            <!-- Network Stats -->
            <div
                class="bg-nestle-brown p-10 rounded-[3rem] text-white shadow-2xl shadow-nestle-brown/20 flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-black text-white/40 uppercase tracking-[0.3em] mb-8">Supply Chain Nodes</h3>
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl">🏬
                            </div>
                            <div>
                                <p class="text-2xl font-black leading-none">
                                    {{ \App\Models\User::where('role', 'distributor')->count() }}</p>
                                <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest">Distributors</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl">🏭
                            </div>
                            <div>
                                <p class="text-2xl font-black leading-none">
                                    {{ \App\Models\User::where('role', 'wholesaler')->count() }}</p>
                                <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest">Wholesalers</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center text-2xl">🏪
                            </div>
                            <div>
                                <p class="text-2xl font-black leading-none">
                                    {{ \App\Models\User::where('role', 'retailer')->count() }}</p>
                                <p class="text-[10px] font-bold opacity-60 uppercase tracking-widest">Retailers</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-8 border-t border-white/10">
                    <p class="text-[10px] font-black uppercase tracking-widest italic opacity-40">Data refreshed 1 min ago
                    </p>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl">
                <h4 class="text-sm font-black text-gray-900 uppercase mb-8">Order Volume by Category</h4>
                <div class="space-y-6">
                    @foreach(['Dairy' => 85, 'Beverages' => 65, 'Noodles' => 45, 'Confectionery' => 30] as $cat => $val)
                        <div>
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-2">
                                <span>{{ $cat }}</span>
                                <span class="text-gray-400">{{ $val }}%</span>
                            </div>
                            <div class="w-full bg-gray-50 h-3 rounded-full overflow-hidden">
                                <div class="bg-nestle-blue h-full" style="width: {{ $val }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-xl flex flex-col justify-center items-center text-center">
                <div
                    class="h-32 w-32 border-[12px] border-nestle-blue border-r-gray-100 rounded-full flex items-center justify-center mb-6">
                    <span class="text-2xl font-black text-gray-900">72%</span>
                </div>
                <h4 class="text-sm font-black text-gray-900 uppercase">Fulfillment Rate</h4>
                <p class="text-xs font-bold text-gray-400 mt-2 uppercase tracking-tight">On-time national deliveries</p>
            </div>
        </div>
    </div>
@endsection