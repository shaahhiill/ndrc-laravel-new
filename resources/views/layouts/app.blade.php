<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NDRC Platform - Nestlé') }}</title>
    
    <!-- PWA & Mobile Optimization -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#002B5C">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nestle-brown': '#63513D',
                        'nestle-blue': '#0085C3',
                        'nestle-success': '#4CAF50',
                        'nestle-warning': '#FF9800',
                        'nestle-danger': '#F44336',
                        'nestle-bg': '#F5F3F0',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-nestle-bg min-h-screen" x-data="{ mobileMenu: false }">

    @auth
    <!-- Premium App Navigation -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center gap-3">
                        <img src="{{ asset('images/nestle-white.png') }}" alt="Nestlé NDRC" class="h-8 brightness-0">
                        <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>
                        <span class="text-xs font-black tracking-[0.2em] text-gray-900 hidden sm:block">NDRC LANKA</span>
                    </a>
                    
                    <!-- Desktop Nav -->
                    <div class="hidden md:ml-10 md:flex md:space-x-4">
                        @php
                        $roleLinks = [
                            'nestle' => [
                                ['label' => 'Command Center', 'url' => '/nestle/dashboard'],
                                ['label' => 'Warehouse', 'url' => '/nestle/warehouse'],
                                ['label' => 'Analytics', 'url' => '/nestle/analytics']
                            ],
                            'retailer' => [
                                ['label' => 'Dashboard', 'url' => '/retailer/dashboard'],
                                ['label' => 'New Order', 'url' => '/retailer/place-order'],
                                ['label' => 'Smart Picks ✨', 'url' => '/retailer/smart-orders'],
                                ['label' => 'History', 'url' => '/retailer/orders']
                            ],
                            'wholesaler' => [
                                ['label' => 'Incoming Orders', 'url' => '/wholesaler/dashboard'],
                                ['label' => 'Retailer Network', 'url' => '/wholesaler/retailers']
                            ],
                            'distributor' => [
                                ['label' => 'Confirmations', 'url' => '/distributor/dashboard'],
                                ['label' => 'Route Planning 🚚', 'url' => '/distributor/route-optimization'],
                                ['label' => 'Demand Map 🔥', 'url' => '/distributor/demand-analytics']
                            ]
                        ];
                        $links = $roleLinks[auth()->user()->role] ?? [];
                        @endphp
                        
                        @foreach ($links as $link)
                            <a href="{{ $link['url'] }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-nestle-blue hover:bg-nestle-blue/5 rounded-xl transition-all">
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <button class="p-2.5 rounded-2xl bg-gray-50 text-gray-400 hover:bg-nestle-blue/5 hover:text-nestle-blue transition-all hidden sm:block">
                        <span class="text-xl">🔔</span>
                    </button>

                    <!-- User Meta -->
                    <div class="flex items-center gap-3 pl-3 border-l border-gray-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-black text-gray-900 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-bold text-nestle-blue uppercase tracking-widest mt-1 opacity-70">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="h-10 w-10 bg-nestle-blue rounded-2xl flex items-center justify-center text-white font-black shadow-lg shadow-nestle-blue/20">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>

                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2.5 rounded-2xl bg-gray-100 text-gray-800 transition-all">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit" class="bg-gray-900 text-white px-5 py-2.5 rounded-2xl text-xs font-black hover:bg-nestle-brown transition-all">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenu" class="md:hidden absolute top-20 left-0 w-full bg-white border-b border-gray-100 shadow-2xl z-40 p-6 space-y-4">
            @foreach ($links as $link)
                <a href="{{ $link['url'] }}" class="block px-6 py-4 bg-gray-50 rounded-2xl text-sm font-black text-gray-700 hover:bg-nestle-blue/10 hover:text-nestle-blue transition-all">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <div class="pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 text-red-600 px-6 py-4 rounded-2xl text-sm font-black text-center">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    @if (session('success') || session('error') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed bottom-10 right-10 z-[100] max-w-sm w-full">
        @if (session('success'))
        <div class="bg-green-600 text-white p-6 rounded-[2rem] shadow-2xl flex items-center gap-4 border-2 border-white/20 backdrop-blur-md">
            <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">✅</div>
            <div class="flex-1">
                <p class="font-black text-sm uppercase tracking-widest">Success</p>
                <p class="text-xs font-bold text-white/80 mt-1">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if (session('error') || $errors->any())
        <div class="bg-red-600 text-white p-6 rounded-[2rem] shadow-2xl flex items-center gap-4 border-2 border-white/20 backdrop-blur-md">
            <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">⚠️</div>
            <div class="flex-1">
                <p class="font-black text-sm uppercase tracking-widest">Attention Needed</p>
                <p class="text-xs font-bold text-white/80 mt-1">
                    @if(session('error')) {{ session('error') }} @else Please check the form for mistakes. @endif
                </p>
            </div>
        </div>
        @endif
    </div>
    @endif

    <main class="relative">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm">© {{ date('Y') }} Nestlé Lanka PLC. NDRC Platform v1.0 (Laravel Port)</p>
        </div>
    </footer>

    @stack('scripts')
    
    <!-- Service Worker Registration for PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('SW registered: ', registration);
                }).catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }
    </script>
</body>
</html>
