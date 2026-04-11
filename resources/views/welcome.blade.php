<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nestlé NDRC - National Distribution & Retail Channel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nestle-blue': '#0085C3',
                        'nestle-brown': '#63513D',
                        'nestle-gold': '#C49500',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .hero-overlay { background: linear-gradient(90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 100%); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 overflow-x-hidden">
    <!-- Top Navigation -->
    <nav class="absolute top-0 left-0 right-0 z-50 px-6 py-6" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Nestl%C3%A9_Logo.svg" alt="Nestle" class="h-8 brightness-0 invert">
                <div class="h-6 w-px bg-white/20 hidden sm:block"></div>
                <span class="text-white font-bold tracking-tight text-lg hidden sm:block uppercase">NDRC Platform</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-white/80 hover:text-white transition-colors text-sm font-semibold uppercase tracking-wider">Features</a>
                <a href="#network" class="text-white/80 hover:text-white transition-colors text-sm font-semibold uppercase tracking-wider">Network</a>
                <div class="h-6 w-px bg-white/10 mx-2"></div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-white text-nestle-blue px-6 py-2.5 rounded-full font-bold shadow-lg hover:scale-105 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-white font-bold text-sm hover:underline">Sign In</a>
                    <a href="{{ route('register') }}" class="bg-nestle-blue text-white px-8 py-3 rounded-full font-bold shadow-xl shadow-nestle-blue/20 hover:bg-blue-600 transition-all uppercase text-xs tracking-widest">Join Network</a>
                @endauth
            </div>

            <button @click="mobileMenu = !mobileMenu" class="md:hidden text-white"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2"></path></svg></button>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenu" class="md:hidden absolute top-20 left-4 right-4 bg-white rounded-3xl p-8 shadow-2xl flex flex-col gap-6" x-cloak>
            <a href="{{ route('login') }}" class="text-gray-900 font-bold text-lg border-b pb-4">Sign In</a>
            <a href="{{ route('register') }}" class="bg-nestle-blue text-white text-center py-4 rounded-2xl font-bold">Join Network</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative h-[90vh] flex items-center overflow-hidden">
        <img src="{{ asset('images/home-hero.jpg') }}" alt="Nestle Logistics" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 hero-overlay"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 w-full">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-nestle-blue/20 border border-nestle-blue/30 rounded-lg text-nestle-blue mb-6">
                    <span class="w-2 h-2 rounded-full bg-nestle-blue animate-ping"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-white">Official B2B Channel</span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-extrabold text-white tracking-tighter leading-none mb-6">
                    Powering <br/>
                    <span class="text-nestle-blue">Distribution</span> <br/>
                    Intelligence.
                </h1>
                <p class="text-xl text-white/70 font-medium mb-10 leading-relaxed max-w-lg">
                    Unlocking end-to-end visibility for the Nestlé Direct Retail Channel. Streamlined ordering, predictive stock management, and unified logistics.
                </p>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-nestle-blue text-white rounded-2xl font-bold hover:bg-blue-600 transition-all shadow-2xl shadow-nestle-blue/40 text-center uppercase tracking-widest text-sm">Get Started Now</a>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 bg-white/10 backdrop-blur-md text-white border border-white/20 rounded-2xl font-bold hover:bg-white hover:text-gray-900 transition-all text-center uppercase tracking-widest text-sm">Learn More</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Feature Section -->
    <section id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-end justify-between gap-8 mb-16">
                <div class="max-w-2xl">
                    <h2 class="text-nestle-blue font-black uppercase tracking-widest text-xs mb-4">Core Capabilities</h2>
                    <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight">The Digital Backbone of <br/>Nestlé Distribution.</h3>
                </div>
                <div class="lg:mb-2 text-gray-500 font-medium max-w-sm">Every order placed through NDRC contributes to a national data layer for optimized inventory flow.</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="p-10 rounded-[2.5rem] bg-gray-50 border border-gray-100 hover:shadow-2xl transition-all group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm mb-8 group-hover:scale-110 transition-transform">📦</div>
                    <h4 class="text-xl font-bold mb-4 text-gray-900">Seamless Bulk Ordering</h4>
                    <p class="text-gray-500 leading-relaxed">Direct integration with distributor inventory. Order by Case, Carton, or Crate with real-time stock verification.</p>
                </div>
                <div class="p-10 rounded-[2.5rem] bg-nestle-blue text-white shadow-2xl shadow-nestle-blue/20 hover:-translate-y-2 transition-transform">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mb-8">✨</div>
                    <h4 class="text-xl font-bold mb-4">Smart Recommendations</h4>
                    <p class="text-white/70 leading-relaxed text-sm">AI-driven insights that suggest upcoming stock needs based on your unique purchase cycles and seasonal trends.</p>
                    <div class="mt-8 pt-6 border-t border-white/10 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest">
                        <span>Predictive</span>
                        <div class="h-1 w-12 bg-white/20 rounded-full overflow-hidden"><div class="h-full bg-white w-2/3"></div></div>
                        <span>Accuracy 88%</span>
                    </div>
                </div>
                <div class="p-10 rounded-[2.5rem] bg-gray-50 border border-gray-100 hover:shadow-2xl transition-all group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl shadow-sm mb-8 group-hover:scale-110 transition-transform">💳</div>
                    <h4 class="text-xl font-bold mb-4 text-gray-900">Secure Settlement</h4>
                    <p class="text-gray-500 leading-relaxed font-normal">Choose between integrated Stripe card payments for instant processing or flexible Cash on Delivery workflows.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Network Section -->
    <section id="network" class="py-24 bg-gray-900 text-white overflow-hidden relative">
        <div class="absolute right-0 top-0 w-1/2 h-full opacity-10 pointer-events-none">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Nestl%C3%A9_Logo.svg" alt="" class="w-full h-full object-contain">
        </div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div>
                    <h3 class="text-4xl font-extrabold tracking-tighter mb-8 leading-tight italic">"Ensuring Nestlé products are always available at the point of need."</h3>
                    <p class="text-gray-400 text-lg mb-12 font-medium">The NDRC network connects thousands of retailers and wholesalers across Sri Lanka to regional distribution hubs, creating a unified supply chain.</p>
                    <div class="flex gap-12">
                        <div>
                            <p class="text-4xl font-black mb-1">99%</p>
                            <p class="text-[10px] font-black text-nestle-blue uppercase tracking-widest">Uptime Record</p>
                        </div>
                        <div class="h-12 w-px bg-white/10"></div>
                        <div>
                            <p class="text-4xl font-black mb-1">2k+</p>
                            <p class="text-[10px] font-black text-nestle-blue uppercase tracking-widest">Daily Transactions</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 p-2 rounded-[3.5rem] shadow-3xl">
                    <div class="bg-gray-800 rounded-[3rem] overflow-hidden p-12">
                        <h4 class="text-xl font-bold mb-6">Ready to scale?</h4>
                        <p class="text-gray-400 mb-8 text-sm">Join the digital revolution in Nestlé's route-to-market operations today.</p>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-white text-gray-900 py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-nestle-blue hover:text-white transition-all">Become a Partner</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Simple Footer -->
    <footer class="py-12 bg-white border-t">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/Nestl%C3%A9_Logo.svg" alt="Nestle" class="h-6 mx-auto mb-8 opacity-30 grayscale">
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em]">Nestlé Lanka PLC — Distribution Technology Division</p>
            <p class="text-gray-400 text-[10px] mt-4">© {{ date('Y') }} Official Direct Retail Channel Portal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
