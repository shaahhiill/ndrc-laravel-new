@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-12" x-data="{ activeTab: 'picks' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- AI Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li><a href="/retailer/dashboard" class="hover:text-nestle-blue font-medium transition-colors">Dashboard</a></li>
                        <li><svg class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></li>
                        <li class="text-nestle-brown font-bold">Smart Recommendations</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-xl shadow-indigo-200">✨</div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">AI Insights <span class="text-indigo-600">Beta</span></h1>
                </div>
                <p class="text-gray-500 mt-2 font-medium">Predictive ordering based on your historical patterns and real-time market trends.</p>
            </div>

            <div class="flex bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100">
                <button @click="activeTab = 'picks'" :class="activeTab === 'picks' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">Smart Picks</button>
                <button @click="activeTab = 'trends'" :class="activeTab === 'trends' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">Trend Analytics</button>
            </div>
        </div>

        <div x-show="activeTab === 'picks'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recommendation Feed -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                    Suggested for your next order
                </h3>
                
                @forelse($recommendations as $rec)
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 shadow-xl shadow-gray-200/50 hover:border-indigo-200 transition-all group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-5">
                            <div class="h-16 w-16 bg-gray-50 rounded-2xl flex items-center justify-center text-3xl shadow-inner group-hover:scale-110 transition-transform">
                                @switch($rec['product']->category)
                                    @case('Dairy') 🥛 @break
                                    @case('Beverages') ☕ @break
                                    @case('Noodles') 🍜 @break
                                    @case('Cereals') 🥣 @break
                                    @default 📦
                                @endswitch
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-1">AI Confidence: {{ $rec['score'] }}%</p>
                                <h4 class="text-xl font-black text-gray-900 leading-tight">{{ $rec['product']->name }}</h4>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Predicted Bulk</p>
                            <p class="text-xl font-black text-nestle-brown">{{ $rec['predicted_qty'] }} <span class="text-xs">{{ $rec['product']->unit }}</span></p>
                        </div>
                    </div>

                    <div class="p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/30 mb-6 font-medium text-sm text-indigo-900 flex items-center gap-3">
                        <span class="text-lg">🤖</span>
                        {{ $rec['reason'] }}
                    </div>

                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-50">
                        <div class="flex items-center gap-8">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Last Ordered</p>
                                <p class="text-sm font-black text-gray-900">{{ $rec['last_ordered_at'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Suggested Qty</p>
                                <p class="text-sm font-black text-gray-900">{{ $rec['predicted_qty'] }} Units</p>
                            </div>
                        </div>
                        <a href="/retailer/place-order" class="h-12 w-12 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-indigo-600 transition-all shadow-lg hover:shadow-indigo-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-[2.5rem] p-16 text-center border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 font-bold">We need more historical data to generate smart recommendations.</p>
                </div>
                @endforelse
            </div>

            <!-- Stats & Insights Sidebar -->
            <div class="space-y-8">
                <!-- Predictive Analytics Summary -->
                <div class="bg-indigo-900 rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-indigo-200 text-[10px] font-black uppercase tracking-[0.3em] mb-4">Market Anticipation</h4>
                        <p class="text-4xl font-black mb-1">+24%</p>
                        <p class="text-sm font-bold text-indigo-100/70 mb-8">Estimated increase in Beverage demand next month in your region.</p>
                        
                        <div class="space-y-4">
                            <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-400 w-3/4 rounded-full"></div>
                            </div>
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
                                <span>Current Supply</span>
                                <span>High Risk Zone</span>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-800 rounded-full blur-3xl opacity-50"></div>
                </div>

                <!-- Chart: Purchase Trends -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl shadow-gray-200/50">
                    <h5 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-8">Order Volume Trend (6M)</h5>
                    <div id="trendChart"></div>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'trends'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
             <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-lg">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Top Selling Category</p>
                    <p class="text-2xl font-black text-gray-900">Beverages</p>
                    <p class="text-xs text-green-600 font-bold mt-2">↑ 12% vs last month</p>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-lg">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Avg Order Value</p>
                    <p class="text-2xl font-black text-gray-900">Rs 42,500</p>
                    <p class="text-xs text-gray-400 font-bold mt-2">Steady performance</p>
                </div>
                <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-lg">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Restock Frequency</p>
                    <p class="text-2xl font-black text-gray-900">Every 9 Days</p>
                    <p class="text-xs text-indigo-600 font-bold mt-2">Highly Optimized</p>
                </div>
             </div>

             <div class="bg-white rounded-[3rem] p-10 border border-gray-100 shadow-2xl">
                <h3 class="text-xl font-black text-gray-900 mb-10">Historical Expenditure Analytics</h3>
                <div id="mainTrendChart"></div>
             </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Trend Data from Backend
        const trends = @json($trends);
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const labels = trends.map(t => months[parseInt(t.month)-1]);
        const data = trends.map(t => t.total);

        // Sidebar Mini Chart
        var options1 = {
            series: [{
                name: "Order Value",
                data: data.length ? data : [10000, 15000, 12000, 22000, 18000, 25000]
            }],
            chart: {
                type: 'area',
                height: 250,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#4F46E5'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100]
                }
            },
            xaxis: {
                categories: labels.length ? labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: { show: false },
            grid: { show: false }
        };

        var chart1 = new ApexCharts(document.querySelector("#trendChart"), options1);
        chart1.render();

        // Main Analytics Chart
        var options2 = {
            series: [{
                name: 'Expenditure',
                type: 'column',
                data: data.length ? data : [23000, 31000, 40000, 51000, 30000, 41000]
            }, {
                name: 'Forecast',
                type: 'line',
                data: (data.length ? data : [23000, 31000, 40000, 51000, 30000, 41000]).map(v => v * 1.1)
            }],
            chart: {
                height: 450,
                type: 'line',
                toolbar: { show: false }
            },
            stroke: {
                width: [0, 4],
                curve: 'smooth'
            },
            colors: ['#E0E7FF', '#4F46E5'],
            title: { show: false },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: labels.length ? labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            xaxis: { type: 'category' },
            yaxis: [{
                title: { text: 'Monthly Expenditure (Rs)' },
            }],
            grid: {
                borderColor: '#f1f1f1',
                xaxis: { lines: { show: true } }
            }
        };

        var chart2 = new ApexCharts(document.querySelector("#mainTrendChart"), options2);
        chart2.render();
    });
</script>
@endpush
@endsection
