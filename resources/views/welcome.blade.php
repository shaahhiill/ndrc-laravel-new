<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nestlé NDRC | Digital Information Layer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nestle-blue': '#0085C3',
                        'nestle-dark': '#010409',
                        'nestle-surface': '#0D1117',
                        'accent-blue': '#38BDF8',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #010409; color: #FFFFFF; scroll-behavior: smooth; }
        .glow { box-shadow: 0 0 50px -10px rgba(0, 133, 195, 0.3); }
        .glass { background: rgba(13, 17, 23, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .text-gradient { background: linear-gradient(135deg, #FFFFFF 0%, #38BDF8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sub-header { color: #8B949E; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.75rem; }
    </style>
</head>
<body class="selection:bg-nestle-blue selection:text-white">

    <!-- Premium Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-[100] border-b border-white/10 bg-nestle-dark/80 backdrop-blur-xl" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <!-- Direct Asset Access for Logo -->
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/4c/Nestl%C3%A9_Logo_2015.png" alt="Nestlé" class="h-6 brightness-0 invert">
                <div class="h-6 w-px bg-white/10 hidden sm:block"></div>
                <span class="text-xs font-bold tracking-[0.2em] text-white hidden sm:block">NDRC PORTAL</span>
            </div>

            <div class="hidden md:flex items-center gap-12">
                <a href="#about" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-all">The Layer</a>
                <a href="#analytics" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-all">Intelligence</a>
                @auth
                    @php
                        $dashUrl = match(auth()->user()->role) {
                            'retailer' => '/retailer/dashboard',
                            'wholesaler' => '/wholesaler/dashboard',
                            'distributor' => '/distributor/dashboard',
                            'nestle' => '/nestle/dashboard',
                            default => '/dashboard'
                        };
                    @endphp
                    <a href="{{ $dashUrl }}" class="px-8 py-3 bg-nestle-blue text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-xl shadow-nestle-blue/20 hover:scale-105 transition-all">Go To Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-white hover:text-accent-blue transition-all">Login</a>
                    <a href="{{ route('register') }}" class="px-8 py-3 border border-white/20 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-black transition-all">Register Account</a>
                @endauth
            </div>
            
            <button @click="open = !open" class="md:hidden text-white"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2"></path></svg></button>
        </div>
    </nav>

    <!-- Ultra Premium Hero -->
    <header class="relative min-h-screen flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/ultra-hero.jpg') }}" alt="NDRC Corporate" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-nestle-dark via-nestle-dark/70 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-nestle-dark to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 w-full pt-20">
            <div class="max-w-4xl">
                <p class="sub-header mb-8 text-accent-blue">Digital Information Layer — Nestlé Lanka PLC</p>
                <h1 class="text-6xl lg:text-9xl font-black tracking-tighter leading-[0.9] text-white mb-10">
                    Bridges the <br/>
                    <span class="text-gradient">Last Mile Gap.</span>
                </h1>
                <p class="text-xl lg:text-2xl text-gray-300 font-medium leading-relaxed mb-14 max-w-2xl">
                    NDRC is the national infrastructure for granular supply chain visibility. We don't just move products—we move the intelligence that powers the flow.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-12 py-6 bg-nestle-blue text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-2xl shadow-nestle-blue/30 hover:bg-accent-blue transition-all text-center">Join the Network</a>
                    <a href="#about" class="w-full sm:w-auto px-12 py-6 border border-white/10 text-white font-black uppercase tracking-widest text-xs rounded-xl hover:bg-white/5 transition-all text-center">Core Purpose</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Detailed Purpose Section -->
    <section id="about" class="py-32 bg-nestle-dark relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div>
                    <p class="sub-header mb-4">The Solution</p>
                    <h2 class="text-4xl lg:text-5xl font-extrabold mb-10 tracking-tight text-white leading-tight">Eliminating the <span class="text-accent-blue">"Black Box"</span> in localized retail.</h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-12">
                        For decades, the final mile of distribution—from wholesaler to small retailer—has been invisible. NDRC provides the missing digital layer that aggregates data across territories, creating a unified view of actual market demand.
                    </p>
                    
                    <div class="space-y-8">
                        <div class="flex gap-6">
                            <div class="h-12 w-12 rounded-xl bg-nestle-blue/10 flex items-center justify-center shrink-0 border border-nestle-blue/20">
                                <span class="text-nestle-blue text-xl">01</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white mb-2 tracking-tight">Real-Time Order Aggregation</h4>
                                <p class="text-gray-500 text-sm">Every localized order is instantly captured and synced with the national distribution hub for predictive planning.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="h-12 w-12 rounded-xl bg-nestle-blue/10 flex items-center justify-center shrink-0 border border-nestle-blue/20">
                                <span class="text-nestle-blue text-xl">02</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white mb-2 tracking-tight">Wholesaler Visibility</h4>
                                <p class="text-gray-500 text-sm">Full transparency into wholesaler stock levels and regional fulfillment capacity, identifying bottlenecks before they impact sales.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative group">
                    <div class="absolute -inset-4 bg-nestle-blue/20 rounded-[3rem] blur-3xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <img src="{{ asset('images/analytics-detail.jpg') }}" alt="Analytics Layer" class="relative rounded-[2.5rem] border border-white/10 shadow-3xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Deep Analytics Section -->
    <section id="analytics" class="py-32 bg-nestle-surface border-y border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24 uppercase">
                <p class="sub-header mb-4 text-accent-blue">Corporate Intelligence Layer</p>
                <h3 class="text-5xl font-black tracking-tighter text-white">National Analytics Hub</h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Detailed Tech Card 1 -->
                <div class="glass p-12 rounded-[2.5rem] hover:border-nestle-blue/30 transition-all">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h4 class="text-xl font-bold text-white mb-1">Smart Order Recommendations</h4>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Predictive Restocking</p>
                        </div>
                        <span class="text-2xl">🤖</span>
                    </div>
                    <div id="reco-chart" class="mb-8"></div>
                    <p class="text-gray-400 text-xs leading-relaxed">Leverages historical sales velocity and regional trends to suggest optimal SKU volume—minimizing out-of-stock events at the retail level.</p>
                </div>

                <!-- Detailed Tech Card 2 -->
                <div class="glass p-12 rounded-[2.5rem] bg-nestle-blue/5 border-nestle-blue/20">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h4 class="text-xl font-bold text-white mb-1">Market Demand Visibility</h4>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Regional Depletion Data</p>
                        </div>
                        <span class="text-2xl">🔥</span>
                    </div>
                    <div id="demand-chart" class="mb-8"></div>
                    <p class="text-gray-400 text-xs leading-relaxed font-medium">Aggregating thousands of daily retail transactions to map actual consumer pull across 24 regional districts.</p>
                </div>

                <!-- Detailed Tech Card 3 -->
                <div class="glass p-12 rounded-[2.5rem] hover:border-nestle-blue/30 transition-all">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h4 class="text-xl font-bold text-white mb-1">Retailer Sales Intelligence</h4>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Point of Sale Growth</p>
                        </div>
                        <span class="text-2xl">💹</span>
                    </div>
                    <div id="growth-chart" class="mb-8"></div>
                    <p class="text-gray-400 text-xs leading-relaxed">Empowers individual retailers with personal performance dashboards—driving loyalty and aggressive business scaling.</p>
                </div>
            </div>

            <!-- Value Callouts -->
            <div class="mt-24 grid grid-cols-1 md:grid-cols-4 gap-12 border-t border-white/5 pt-16">
                <div class="text-center md:text-left">
                    <p class="text-4xl font-black text-white mb-2">99.9%</p>
                    <p class="sub-header text-accent-blue">System Uptime</p>
                </div>
                <div class="text-center md:text-left">
                    <p class="text-4xl font-black text-white mb-2">~14%</p>
                    <p class="sub-header text-accent-blue">Inventory Savings</p>
                </div>
                <div class="text-center md:text-left">
                    <p class="text-4xl font-black text-white mb-2">24/7</p>
                    <p class="sub-header text-accent-blue">Cloud Monitoring</p>
                </div>
                <div class="text-center md:text-left">
                    <p class="text-4xl font-black text-white mb-2">8k+</p>
                    <p class="sub-header text-accent-blue">Active Hubs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Institutional Footer -->
    <footer class="py-24 bg-nestle-dark border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-12">
                <div class="flex items-center gap-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4c/Nestl%C3%A9_Logo_2015.png" alt="Nestlé" class="h-6 brightness-0 invert opacity-30">
                    <div class="h-6 w-px bg-white/10"></div>
                    <p class="text-[10px] font-black uppercase text-white/40 tracking-[0.3em]">NDRC National Distribution Support</p>
                </div>
                <div class="flex gap-10">
                    <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-widest text-white/30 hover:text-white transition-all">Login</a>
                    <a href="{{ route('register') }}" class="text-[10px] font-bold uppercase tracking-widest text-white/30 hover:text-white transition-all">Register</a>
                </div>
            </div>
            <div class="mt-16 text-center text-gray-600 text-[10px] font-medium uppercase tracking-widest">
                © {{ date('Y') }} Nestlé Lanka PLC. Restricted Access Monitoring System.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Recommendations
            var options1 = {
                series: [{ name: 'SKU Re-Order Prediction', data: [30, 40, 45, 50, 49, 60, 70, 91] }],
                chart: { type: 'area', height: 160, sparkline: { enabled: true }, toolbar: { show: false } },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#0085C3'],
                fill: { type: 'gradient', gradient: { opacityFrom: 0.6, opacityTo: 0.1 } },
                tooltip: { theme: 'dark', x: { show: false } }
            };
            new ApexCharts(document.querySelector("#reco-chart"), options1).render();

            // Chart 2: Demand
            var options2 = {
                series: [{ name: 'National Depletion', data: [12, 19, 3, 5, 2, 3, 15, 20, 25, 22, 18, 30] }],
                chart: { type: 'bar', height: 160, sparkline: { enabled: true }, toolbar: { show: false } },
                colors: ['#38BDF8'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
                tooltip: { theme: 'dark', x: { show: false } }
            };
            new ApexCharts(document.querySelector("#demand-chart"), options2).render();

            // Chart 3: Growth
            var options3 = {
                series: [{ name: 'Retailer Sales Growth', data: [10, 25, 15, 30, 45, 40, 55, 60] }],
                chart: { type: 'line', height: 160, sparkline: { enabled: true }, toolbar: { show: false } },
                stroke: { curve: 'stepline', width: 3 },
                colors: ['#FFFFFF'],
                tooltip: { theme: 'dark', x: { show: false } }
            };
            new ApexCharts(document.querySelector("#growth-chart"), options3).render();
        });
    </script>
</body>
</html>
