@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="demandAnalytics()">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Demand <span class="text-nestle-blue">Intelligence</span> 🔥</h1>
            <p class="text-gray-500 font-medium mt-1">Real-time geospatial analytics and delivery priority scoring.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="h-10 w-10 bg-red-50 rounded-xl flex items-center justify-center text-red-600 text-xl animate-pulse">⚠️</div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">High Priority</p>
                    <p class="text-lg font-black text-gray-900 mt-1">{{ $priorityOrders->where('priority_level', 'High')->count() }} Deliveries</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Priority Engine & Metrics -->
        <div class="lg:col-span-4 space-y-8 h-[calc(100vh-250px)] overflow-y-auto no-scrollbar pr-2">
            
            <!-- Delivery Priority Engine Card -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-black text-gray-900 tracking-tight">Priority <span class="text-red-600">Engine</span></h2>
                    <span class="bg-gray-100 px-3 py-1 rounded-full text-[10px] font-black text-gray-500 uppercase tracking-widest">Score-Based Ranking</span>
                </div>
                
                <div class="p-4 space-y-3">
                    @forelse($priorityOrders as $order)
                    <div class="group bg-gray-50 hover:bg-white border border-transparent hover:border-nestle-blue/20 p-4 rounded-2xl transition-all flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-2xl flex flex-col items-center justify-center font-black"
                                 style="background: {{ $order->priority_level === 'High' ? '#FEE2E2' : ($order->priority_level === 'Medium' ? '#FEF3C7' : '#E0F2FE') }}; 
                                        color: {{ $order->priority_level === 'High' ? '#991B1B' : ($order->priority_level === 'Medium' ? '#92400E' : '#075985') }}">
                                <span class="text-[10px] uppercase leading-none opacity-70">VAL</span>
                                <span class="text-base">{{ $order->priority_score }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-black text-gray-900 truncate">{{ $order->retailer->name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 truncate">{{ $order->retailer->address }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded-md uppercase tracking-tighter"
                                        style="background: {{ $order->priority_level === 'High' ? '#EF44441A' : ($order->priority_level === 'Medium' ? '#F59E0B1A' : '#3B82F61A') }}; 
                                               color: {{ $order->priority_level === 'High' ? '#B91C1C' : ($order->priority_level === 'Medium' ? '#B45309' : '#1D4ED8') }}">
                                        {{ $order->priority_level }} Priority
                                    </span>
                                    <span class="text-[9px] font-bold text-gray-400">• {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-gray-900">LKR {{ number_format($order->total_amount/1000, 1) }}k</p>
                            <a href="{{ route('distributor.route-optimization') }}" class="inline-block mt-2 h-7 w-7 bg-gray-200 hover:bg-nestle-blue hover:text-white rounded-lg flex items-center justify-center text-gray-500 transition-all">
                                <span class="text-sm">🚚</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 opacity-50">
                        <span class="text-4xl block mb-2">✅</span>
                        <p class="text-xs font-black">All high priority orders managed.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Territory Demand Stats Table -->
            <div class="bg-gray-900 rounded-[2rem] shadow-xl overflow-hidden text-white">
                <div class="p-6 border-b border-white/10">
                    <h2 class="text-lg font-black tracking-tight">Territory <span class="text-nestle-blue">Analytics</span></h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($territoryDemand as $td)
                        <div>
                            <div class="flex justify-between text-[11px] font-black mb-1.5 uppercase tracking-widest text-white/60">
                                <span>{{ $td->territory ?? 'Unmapped Territory' }}</span>
                                <span class="text-white">LKR {{ number_format($td->total_demand/1000, 1) }}k</span>
                            </div>
                            <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                                @php
                                    $maxDemand = $territoryDemand->max('total_demand') ?: 1;
                                    $percent = ($td->total_demand / $maxDemand) * 100;
                                @endphp
                                <div class="h-full bg-nestle-blue rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Demand Trends Chart -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl p-6">
                <h2 class="text-lg font-black text-gray-900 tracking-tight mb-4">30-Day Demand <span class="text-nestle-success">Trend</span></h2>
                <div id="demandChart" class="h-48"></div>
            </div>
        </div>

        <!-- Right: Territory Demand Map (Heatmap) -->
        <div class="lg:col-span-8 bg-white rounded-[2rem] border border-gray-100 shadow-2xl relative overflow-hidden flex flex-col h-[calc(100vh-250px)]">
            <div id="map" class="flex-1 w-full bg-gray-50 z-0"></div>
            
            <div class="absolute top-6 left-6 z-10 flex flex-col gap-2">
                <div class="bg-white/90 backdrop-blur-md px-5 py-3 rounded-2xl shadow-xl border border-white/50 flex items-center gap-3">
                    <div class="h-4 w-4 rounded-full bg-gradient-to-tr from-red-600 to-yellow-400 shadow-lg shadow-red-500/50"></div>
                    <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Territory Demand Heatmap</span>
                </div>
            </div>

            <div class="absolute bottom-6 left-6 z-10">
                <div class="bg-white/90 backdrop-blur-md px-4 py-3 rounded-2xl shadow-xl border border-white/50 text-[10px] font-black text-gray-900 space-y-2">
                    <div class="flex items-center gap-2"><span class="h-2 w-8 bg-red-600 rounded-full"></span> Critical Demand Zone</div>
                    <div class="flex items-center gap-2"><span class="h-2 w-8 bg-yellow-400 rounded-full"></span> High Intensity Area</div>
                    <div class="flex items-center gap-2"><span class="h-2 w-8 bg-blue-500 rounded-full"></span> Standard Activity</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    function demandAnalytics() {
        return {
            map: null,
            heatmapData: @json($heatmapData),

            init() {
                this.initMap();
                this.initChart();
            },

            initMap() {
                this.map = L.map('map', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([6.9271, 79.8612], 12);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(this.map);

                // Initialize Heatmap
                const heatPoints = this.heatmapData.map(p => [p.lat, p.lng, p.count * 10]);
                L.heatLayer(heatPoints, {
                    radius: 25,
                    blur: 15,
                    maxZoom: 10,
                    gradient: {0.4: 'blue', 0.65: 'lime', 1: 'red'}
                }).addTo(this.map);

                L.control.zoom({ position: 'bottomright' }).addTo(this.map);
            },

            async initChart() {
                const response = await fetch('{{ route("api.distributor.demand-trends") }}');
                const trends = await response.json();

                const options = {
                    series: [{
                        name: 'Daily Demand',
                        data: trends.map(t => t.daily_total)
                    }],
                    chart: {
                        type: 'area',
                        height: 190,
                        toolbar: { show: false },
                        sparkline: { enabled: false }
                    },
                    colors: ['#4CAF50'],
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
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: trends.map(t => t.date),
                        labels: { show: false },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: { show: false },
                    grid: { show: false },
                    tooltip: {
                        theme: 'dark',
                        x: { format: 'dd MMM' }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#demandChart"), options);
                chart.render();
            }
        }
    }
</script>

<style>
    .leaflet-container {
        font-family: 'Inter', sans-serif;
    }
</style>
@endpush

@endsection
