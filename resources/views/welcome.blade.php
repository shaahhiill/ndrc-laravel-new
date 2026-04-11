<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nestlé NDRC | National Distribution Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nestle-blue': '#0085C3',
                        'nestle-dark': '#002B5C',
                        'nestle-accent': '#63513D',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 43, 92, 0.95) 0%, rgba(0, 133, 195, 0.8) 100%);
        }
        .nav-scrolled {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .floating { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-[#F8F9FA] text-[#1E1E1E] antialiased" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    
    <!-- Premium Navigation -->
    <nav :class="{'nav-scrolled py-4': scrolled, 'py-8': !scrolled}" class="fixed w-full z-50 transition-all duration-500 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <!-- Stable Nestle Logo (Text + Icon fallback) -->
                <div class="flex items-center gap-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4c/Nestl%C3%A9_Logo_2015.png" alt="Nestlé" class="h-8 lg:h-10">
                    <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                </div>
            </div>

            <!-- Balanced Links -->
            <div class="hidden md:flex items-center gap-10">
                <a href="#features" class="text-sm font-bold tracking-tight hover:text-nestle-blue transition-colors">Digital Capabilities</a>
                <a href="#network" class="text-sm font-bold tracking-tight hover:text-nestle-blue transition-colors">Our Network</a>
                <div class="h-4 w-px bg-gray-200"></div>
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
                    <a href="{{ $dashUrl }}" class="px-8 py-3 bg-nestle-blue text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-nestle-blue/20 hover:scale-105 transition-all">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold hover:text-nestle-blue transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-nestle-blue text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-nestle-blue/20 hover:bg-nestle-dark transition-all">Register</a>
                @endauth
            </div>

            <!-- Mobile Toggle -->
            <button class="md:hidden p-2"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2"></path></svg></button>
        </div>
    </nav>

    <!-- Immersive Hero -->
    <section class="relative min-h-screen flex items-center overflow-hidden">
        <img src="{{ asset('images/premium-hero.jpg') }}" alt="Command Center" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 hero-gradient"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-12 w-full pt-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white mb-10 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-ping"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">National Direct Retail Channel</span>
                    </div>
                    <h1 class="text-6xl lg:text-8xl font-black text-white leading-[0.9] tracking-tighter mb-8">
                        The Final Link <br/>
                        <span class="text-blue-300">In Visibility.</span>
                    </h1>
                    <p class="text-xl text-white/70 font-medium leading-relaxed mb-12 max-w-lg mx-auto lg:mx-0">
                        Bridging the supply chain gap from central distribution to the last mile retail outlet with real-time intelligence.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-12 py-6 bg-white text-nestle-dark rounded-[2rem] font-black uppercase tracking-widest text-xs shadow-3xl hover:bg-nestle-blue hover:text-white transition-all">Get Started</a>
                        <a href="#features" class="w-full sm:w-auto px-12 py-6 bg-white/10 border border-white/20 text-white rounded-[2rem] font-black uppercase tracking-widest text-xs hover:bg-white/20 transition-all backdrop-blur-md">Explore Tech</a>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <div class="glass-panel p-1 rounded-[4rem] shadow-3xl floating">
                        <div class="rounded-[3.8rem] overflow-hidden bg-nestle-dark h-[500px] relative">
                            <div class="absolute inset-0 bg-gradient-to-t from-nestle-dark via-transparent to-transparent z-10"></div>
                            <div class="p-12 relative z-20 space-y-8">
                                <div class="flex gap-4">
                                    <div class="h-12 w-12 bg-nestle-blue rounded-2xl flex items-center justify-center text-white text-xl">📊</div>
                                    <div class="space-y-1">
                                        <div class="h-2 w-24 bg-white/20 rounded"></div>
                                        <div class="h-4 w-48 bg-white/10 rounded"></div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="h-1 w-full bg-white/5 rounded"></div>
                                    <div class="h-1 w-2/3 bg-white/5 rounded"></div>
                                    <div class="h-1 w-full bg-white/5 rounded"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="h-24 bg-white/5 rounded-3xl border border-white/10"></div>
                                    <div class="h-24 bg-white/5 rounded-3xl border border-white/10"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Structural Feature Set -->
    <section id="features" class="py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-end justify-between gap-12 mb-24">
                <div class="max-w-2xl">
                    <p class="text-nestle-blue font-black uppercase tracking-[0.3em] text-xs mb-6">NDRC Digital Layers</p>
                    <h2 class="text-5xl font-black tracking-tighter leading-tight">Advanced Platform <br/>Capabilities</h2>
                </div>
                <div class="max-w-sm text-gray-500 font-medium">Connecting Nestlé with localized commerce through predictive data and seamless orchestration.</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                <!-- Card 1 -->
                <div class="p-12 rounded-[3.5rem] bg-[#F8F9FA] hover:bg-white hover:shadow-3xl border border-transparent hover:border-blue-100 transition-all group">
                    <div class="h-20 w-20 bg-white rounded-3xl shadow-sm flex items-center justify-center text-3xl mb-10 group-hover:scale-110 transition-transform">🏢</div>
                    <h3 class="text-2xl font-black mb-6">Wholesaler Visibility</h3>
                    <p class="text-gray-500 leading-relaxed">Real-time mapping of structural wholesaler stock levels across all prioritized territories.</p>
                </div>
                <!-- Card 2 -->
                <div class="p-12 rounded-[3.5rem] bg-nestle-blue text-white shadow-2xl shadow-nestle-blue/30 transform lg:-translate-y-8">
                    <span class="px-4 py-2 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-10 inline-block">Core Tech</span>
                    <h3 class="text-2xl font-black mb-6">Order Aggregation</h3>
                    <p class="text-white/70 leading-relaxed font-medium">Automated sync of national retail orders into a centralized command center for fulfillment.</p>
                </div>
                <!-- Card 3 -->
                <div class="p-12 rounded-[3.5rem] bg-[#F8F9FA] hover:bg-white hover:shadow-3xl border border-transparent hover:border-blue-100 transition-all group">
                    <div class="h-20 w-20 bg-white rounded-3xl shadow-sm flex items-center justify-center text-3xl mb-10 group-hover:scale-110 transition-transform">🧠</div>
                    <h3 class="text-2xl font-black mb-6">Smart Recommendations</h3>
                    <p class="text-gray-500 leading-relaxed">AI-driven reorder suggestions based on retailer velocity and regional demand spikes.</p>
                </div>
                <!-- Card 4 -->
                <div class="p-12 rounded-[3.5rem] bg-[#F8F9FA] hover:bg-white hover:shadow-3xl border border-transparent hover:border-blue-100 transition-all group">
                    <div class="h-20 w-20 bg-white rounded-3xl shadow-sm flex items-center justify-center text-3xl mb-10 group-hover:scale-110 transition-transform">📍</div>
                    <h3 class="text-2xl font-black mb-6">Market Demand Visibility</h3>
                    <p class="text-gray-500 leading-relaxed">Tracking true consumer pull-through directly at the point of sale in every district.</p>
                </div>
                <!-- Card 5 -->
                <div class="p-12 rounded-[3.5rem] bg-nestle-dark text-white shadow-2xl">
                    <div class="h-20 w-20 bg-white/10 rounded-3xl flex items-center justify-center text-3xl mb-10">📈</div>
                    <h3 class="text-2xl font-black mb-6">Sales Intelligence</h3>
                    <p class="text-gray-400 leading-relaxed">Empowering retailers with historical performance analytics to grow their Nestlé footprint.</p>
                </div>
                <!-- Card 6 -->
                <div class="p-12 rounded-[3.5rem] bg-[#F8F9FA] hover:bg-white hover:shadow-3xl border border-transparent hover:border-blue-100 transition-all group">
                    <div class="h-20 w-20 bg-white rounded-3xl shadow-sm flex items-center justify-center text-3xl mb-10 group-hover:scale-110 transition-transform">🚀</div>
                    <h3 class="text-2xl font-black mb-6">Last Mile Sync</h3>
                    <p class="text-gray-500 leading-relaxed">The NDRC ensures Nestlé Lanka maintains an aggressive and visible presence in localized markets.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Unified Footer -->
    <footer class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/4c/Nestl%C3%A9_Logo_2015.png" alt="Nestlé" class="h-10 mx-auto mb-10 opacity-30 grayscale hover:grayscale-0 transition-all">
            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-nestle-blue mb-4">Nestlé Lanka PLC | Information Layer</p>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">© {{ date('Y') }} NDRC National Portal. All Systems Operational.</p>
        </div>
    </footer>

</body>
</html>
