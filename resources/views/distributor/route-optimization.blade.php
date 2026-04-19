@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="routeOptimizer()">
    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-10 left-1/2 transform -translate-x-1/2 z-[100] bg-gray-900 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-4 border border-white/10"
         style="display: none;">
        <div class="h-8 w-8 bg-nestle-success/20 rounded-full flex items-center justify-center text-nestle-success">✓</div>
        <span x-text="toast.message" class="font-black text-sm tracking-tight"></span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Route <span class="text-nestle-blue">Optimization</span></h1>
            <p class="text-gray-500 font-medium mt-1">Select orders to generate the most efficient delivery sequence.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <template x-if="optimizedData">
                <button @click="saveRoute()" 
                    class="bg-nestle-success text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-nestle-success/20 hover:scale-105 transition-all flex items-center gap-2">
                    <span>💾</span> Save & Assign Route
                </button>
            </template>
            <button @click="optimizeRoute()" :disabled="selectedOrders.length === 0 || loading"
                class="bg-nestle-blue text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-nestle-blue/20 hover:scale-105 transition-all disabled:opacity-50 disabled:hover:scale-100 flex items-center gap-2">
                <template x-if="!loading">
                    <span class="flex items-center gap-2"><span>🚀</span> Generate Optimal Route</span>
                </template>
                <template x-if="loading">
                    <span class="flex items-center gap-2 font-bold animate-pulse">Calculating...</span>
                </template>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-250px)] min-h-[600px]">
        
        <!-- Sidebar: Selection & Sequence -->
        <div class="lg:col-span-4 flex flex-col gap-6 h-full overflow-hidden">
            
            <!-- Stats Bar -->
            <div x-show="optimizedData" class="grid grid-cols-2 gap-4" x-transition:enter="transition ease-out duration-300">
                <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Distance</p>
                    <p class="text-xl font-black text-gray-900" x-text="optimizedData.total_distance + ' km'"></p>
                </div>
                <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Est. Duration</p>
                    <p class="text-xl font-black text-gray-900" x-text="optimizedData.total_duration + ' mins'"></p>
                </div>
            </div>

            <!-- Tabs/Navigation for Sidebar -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl flex flex-col h-full overflow-hidden">
                <div class="flex border-b border-gray-50 p-2">
                    <button @click="tab = 'pending'" :class="tab === 'pending' ? 'bg-nestle-blue text-white shadow-lg' : 'text-gray-500'" class="flex-1 py-3 px-4 rounded-2xl text-xs font-black transition-all">
                        PENDING ORDERS
                    </button>
                    <button @click="tab = 'sequence'" :class="tab === 'sequence' ? 'bg-nestle-blue text-white shadow-lg' : 'text-gray-500'" class="flex-1 py-3 px-4 rounded-2xl text-xs font-black transition-all relative">
                        OPTIMIZED SEQUENCE
                        <span x-show="optimizedData" class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-nestle-success opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-nestle-success border-2 border-white"></span>
                        </span>
                    </button>
                </div>

                <!-- Pending Orders List -->
                <div x-show="tab === 'pending'" class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar">
                    @forelse($orders as $order)
                    <div data-testid="order-card" 
                         class="group relative bg-gray-50 hover:bg-white border border-transparent hover:border-nestle-blue/20 p-4 rounded-2xl transition-all cursor-pointer"
                         @click="toggleOrder('{{ $order->id }}', '{{ $order->retailer->address }}', '{{ $order->retailer->latitude }}', '{{ $order->retailer->longitude }}', '{{ $order->retailer->name }}')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div data-testid="order-badge"
                                     class="h-10 w-10 flex items-center justify-center rounded-xl font-black text-xs transition-colors"
                                     :class="isSelected('{{ $order->id }}') ? 'bg-nestle-blue text-white' : 'bg-white text-gray-400 border border-gray-200'">
                                    #{{ $loop->iteration }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900">{{ $order->retailer->name }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 truncate max-w-[150px]">{{ $order->retailer->address }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-gray-900">LKR {{ number_format($order->total_amount, 2) }}</p>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-tighter mt-1"
                                      style="background: {{ $order->status === 'dispatched' ? '#E8F5E9' : '#FFF3E0' }}; color: {{ $order->status === 'dispatched' ? '#2E7D32' : '#E65100' }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 opacity-50">
                        <span class="text-4xl block mb-2">📦</span>
                        <p class="text-xs font-black">No pending orders found.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Sequence List (Post-Optimization) -->
                <div x-show="tab === 'sequence'" class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
                    <template x-if="!optimizedData">
                        <div class="text-center py-20 opacity-50">
                            <span class="text-4xl block mb-2">🛤️</span>
                            <p class="text-xs font-black">Generate a route to see sequence.</p>
                        </div>
                    </template>
                    <template x-if="optimizedData">
                        <div class="space-y-4">
                            <!-- Helper Info -->
                            <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                                <p class="text-[10px] font-black text-blue-800 uppercase tracking-widest flex items-center gap-2">
                                    <span class="animate-pulse">✨</span> AI Optimized Route
                                </p>
                                <p class="text-xs font-medium text-blue-600 mt-1">Stops are ordered to minimize travel time and distance.</p>
                            </div>

                            <div class="space-y-2 relative">
                                <!-- Vertical Line -->
                                <div class="absolute left-[20px] top-6 bottom-6 w-0.5 bg-gray-100"></div>

                                <template x-for="(order, index) in optimizedData.optimized_orders" :key="order.id">
                                    <div>
                                        <!-- Only show the stop number for the first order at a location -->
                                        <div class="relative flex items-center gap-4 bg-white p-4 rounded-2xl border border-gray-100 group hover:border-nestle-blue/30 transition-all"
                                             :class="index > 0 && order.retailer.id === optimizedData.optimized_orders[index-1].retailer.id ? 'mt-[-1rem] border-t-0 rounded-t-none pt-2 z-0' : 'z-10'">
                                             
                                             <div class="h-10 w-10 flex-shrink-0 bg-nestle-blue text-white rounded-xl shadow-lg shadow-nestle-blue/20 flex items-center justify-center font-black text-sm z-10 transition-transform group-hover:scale-110" 
                                                  x-show="index === 0 || order.retailer.id !== optimizedData.optimized_orders[index-1].retailer.id"
                                                  x-text="(optimizedData.optimized_orders.slice(0, index + 1).filter((o, i) => i === 0 || o.retailer.id !== optimizedData.optimized_orders[i-1].retailer.id).length)">
                                             </div>
                                             <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center" 
                                                  x-show="index > 0 && order.retailer.id === optimizedData.optimized_orders[index-1].retailer.id">
                                                 <div class="w-1 h-4 bg-gray-200 rounded-full"></div>
                                             </div>

                                             <div class="flex-1 min-w-0">
                                                 <p class="text-sm font-black text-gray-900 truncate" x-text="order.retailer.name"></p>
                                                 <p class="text-[10px] font-bold text-gray-400 truncate" x-text="order.retailer.address"></p>
                                                 <p class="text-[9px] font-black text-nestle-blue uppercase mt-1" x-text="'Order #' + order.order_number"></p>
                                             </div>
                                             <div class="text-right">
                                                 <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter" 
                                                    x-text="index === 0 || order.retailer.id !== optimizedData.optimized_orders[index-1].retailer.id ? 'STOP ' + (optimizedData.optimized_orders.slice(0, index + 1).filter((o, i) => i === 0 || o.retailer.id !== optimizedData.optimized_orders[i-1].retailer.id).length) : 'SAME STOP'"></p>
                                             </div>
                                         </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Main Content: Map -->
        <div class="lg:col-span-8 bg-white rounded-[2rem] border border-gray-100 shadow-2xl relative overflow-hidden flex flex-col h-full">
            <div id="map" class="flex-1 w-full bg-gray-50 z-0"></div>
            
            <div class="absolute top-6 left-6 z-10 flex flex-col gap-2">
                <div class="bg-white/90 backdrop-blur-md px-5 py-3 rounded-2xl shadow-xl border border-white/50 flex items-center gap-3">
                    <div class="h-3 w-3 bg-nestle-blue rounded-full animate-ping"></div>
                    <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Live Optimization Agent</span>
                </div>
            </div>
            
            <!-- Map Controls Overlay -->
            <div class="absolute bottom-6 right-6 z-10 flex flex-col gap-2">
                <button @click="resetMap()" class="bg-white p-3 rounded-2xl shadow-xl hover:bg-gray-50 transition-all text-gray-700">
                    🔄
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function routeOptimizer() {
        return {
            tab: 'pending',
            loading: false,
            selectedOrders: [],
            optimizedData: null,
            map: null,
            markers: [],
            routeLine: null,
            toast: { show: false, message: '' },

            init() {
                this.initMap();
            },

            initMap() {
                // Default to Colombo center if nothing else
                this.map = L.map('map', {
                    zoomControl: false,
                    attributionControl: false
                }).setView([6.9271, 79.8612], 12);

                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19
                }).addTo(this.map);

                L.control.zoom({ position: 'bottomleft' }).addTo(this.map);
            },

            toggleOrder(id, address, lat, lng, name) {
                console.log('ALPINE: Toggling Order ID:', id);
                const orderId = String(id);
                const index = this.selectedOrders.findIndex(o => String(o.id) === orderId);
                if (index > -1) {
                    this.selectedOrders.splice(index, 1);
                    this.removeMarker(id);
                } else {
                    this.selectedOrders.push({ id, address, lat, lng, name });
                    this.addMarker(id, lat, lng, name);
                }
                this.fitMap();
            },

            isSelected(id) {
                const orderId = String(id);
                return this.selectedOrders.some(o => String(o.id) === orderId);
            },

            addMarker(id, lat, lng, name) {
                if (!lat || !lng) return;
                
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="w-8 h-8 bg-nestle-blue rounded-xl flex items-center justify-center text-white font-black shadow-lg border-2 border-white transform -translate-x-1/2 -translate-y-1/2">🛒</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                const marker = L.marker([lat, lng], { icon: customIcon }).addTo(this.map);
                marker.bindPopup(`<p class="font-black text-xs text-gray-900">${name}</p><p class="text-[10px] text-gray-500">${id}</p>`);
                this.markers.push({ id, marker });
            },

            removeMarker(id) {
                const markerObj = this.markers.find(m => m.id == id);
                if (markerObj) {
                    this.map.removeLayer(markerObj.marker);
                    this.markers = this.markers.filter(m => m.id != id);
                }
            },

            fitMap() {
                if (this.markers.length > 0) {
                    const group = new L.featureGroup(this.markers.map(m => m.marker));
                    this.map.fitBounds(group.getBounds().pad(0.1));
                }
            },

            async optimizeRoute() {
                if (this.selectedOrders.length === 0) return;

                this.loading = true;
                this.tab = 'sequence';

                try {
                    const response = await fetch('{{ route("distributor.route-optimize") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_ids: this.selectedOrders.map(o => o.id)
                        })
                    });

                    const data = await response.json();

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    this.optimizedData = data;
                    this.drawRoute(data.polyline);
                } catch (error) {
                    console.error('Optimization failed:', error);
                    alert('An error occurred during optimization.');
                } finally {
                    this.loading = false;
                }
            },

            decodePolyline(str, precision) {
                var index = 0,
                    lat = 0,
                    lng = 0,
                    coordinates = [],
                    shift = 0,
                    result = 0,
                    byte = null,
                    latitude_change,
                    longitude_change,
                    factor = Math.pow(10, precision || 5);

                while (index < str.length) {
                    byte = null;
                    shift = 0;
                    result = 0;

                    do {
                        byte = str.charCodeAt(index++) - 63;
                        result |= (byte & 0x1f) << shift;
                        shift += 5;
                    } while (byte >= 0x20);

                    latitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));

                    shift = 0;
                    result = 0;

                    do {
                        byte = str.charCodeAt(index++) - 63;
                        result |= (byte & 0x1f) << shift;
                        shift += 5;
                    } while (byte >= 0x20);

                    longitude_change = ((result & 1) ? ~(result >> 1) : (result >> 1));

                    lat += latitude_change;
                    lng += longitude_change;

                    coordinates.push([lat / factor, lng / factor]);
                }

                return coordinates;
            },

            drawRoute(polyline) {
                if (this.routeLine) {
                    this.map.removeLayer(this.routeLine);
                }

                let coordinates = [];
                if (polyline) {
                    coordinates = this.decodePolyline(polyline);
                } else {
                    // Fallback: draw straight lines between optimized stops
                    coordinates = this.optimizedData.optimized_orders
                        .filter(o => o.retailer.latitude && o.retailer.longitude)
                        .map(o => [o.retailer.latitude, o.retailer.longitude]);
                }

                if (coordinates.length > 0) {
                    this.routeLine = L.polyline(coordinates, {
                        color: '#0085C3',
                        weight: 6,
                        opacity: 0.8,
                        smoothFactor: 1,
                        lineJoin: 'round'
                    }).addTo(this.map);

                    this.map.fitBounds(this.routeLine.getBounds().pad(0.1));
                }

                // Update markers to show sequence
                this.markers.forEach(m => this.map.removeLayer(m.marker));
                this.markers = [];
                
                this.optimizedData.optimized_orders.forEach((order, index) => {
                    const numIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div class="w-10 h-10 bg-nestle-blue rounded-xl flex items-center justify-center text-white font-black shadow-lg border-2 border-white transform -translate-x-1/2 -translate-y-1/2 scale-110 active:scale-95 transition-all text-sm">${index + 1}</div>`,
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });

                    const marker = L.marker([order.retailer.latitude, order.retailer.longitude], { icon: numIcon }).addTo(this.map);
                    marker.bindPopup(`<p class="font-black text-xs text-gray-900">Stop ${index + 1}: ${order.retailer.name}</p>`);
                    this.markers.push({ id: order.id, marker });
                });
            },

            async saveRoute() {
                const name = prompt('Enter a name for this route:', 'Route ' + new Date().toLocaleDateString());
                if (!name) return;

                try {
                    const response = await fetch('{{ route("distributor.route-save") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: name,
                            order_ids: this.optimizedData.optimized_orders.map(o => o.id),
                            total_distance: this.optimizedData.total_distance,
                            total_duration: this.optimizedData.total_duration
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showToast('Route saved successfully!');
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        alert(data.error);
                    }
                } catch (error) {
                    console.error('Save failed:', error);
                    alert('An error occurred while saving the route.');
                }
            },

            showToast(message) {
                this.toast.message = message;
                this.toast.show = true;
                setTimeout(() => this.toast.show = false, 3000);
            },

            resetMap() {
                if (this.routeLine) this.map.removeLayer(this.routeLine);
                this.markers.forEach(m => this.map.removeLayer(m.marker));
                this.markers = [];
                this.selectedOrders = [];
                this.optimizedData = null;
                this.tab = 'pending';
                this.map.setView([6.9271, 79.8612], 12);
            }
        }
    }
</script>

<style>
    .custom-div-icon {
        background: transparent;
        border: none;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 1.5rem;
        padding: 0.5rem;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        border: 1px solid #f1f1f1;
    }
    .leaflet-popup-tip {
        display: none;
    }
</style>
@endpush

@endsection
