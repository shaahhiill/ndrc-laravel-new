<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'Nestlé NDRC - Premium Distribution Platform') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nestle-brown': '#63513D',
                        'nestle-blue': '#0085C3',
                        'nestle-bg': '#F5F3F0',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        
        .hero-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(0, 133, 195, 0.05) 1px, transparent 0);
            background-size: 32px 32px;
        }

        .blob-1 {
            background: linear-gradient(135deg, #0085C3, #63513D);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: blob-bounce 10s infinite alternate ease-in-out;
        }
        
        .blob-2 {
            background: linear-gradient(135deg, #F5F3F0, #0085C3);
            border-radius: 60% 40% 30% 70% / 50% 60% 40% 50%;
            animation: blob-bounce 12s infinite alternate-reverse ease-in-out;
        }

        @keyframes blob-bounce {
            0% { transform: scale(1) translate(0, 0) rotate(0deg); }
            50% { transform: scale(1.1) translate(20px, -20px) rotate(10deg); }
            100% { transform: scale(0.9) translate(-20px, 20px) rotate(-10deg); }
        }

        /* Glassmorphism nav */
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="bg-gray-50 bg-nestle-bg text-gray-900 overflow-x-hidden antialiased">
    
    <!-- Background Animation -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[60vh] h-[60vh] blob-1 opacity-20 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50vh] h-[50vh] blob-2 opacity-20 blur-[80px]"></div>
        <div class="absolute inset-0 hero-pattern"></div>
    </div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav border-b border-white/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-gradient-to-br from-nestle-blue to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-nestle-blue/30">
                        <svg viewBox="0 0 100 80" class="w-6 h-6 text-white fill-current">
                            <path d="M75,55 C78,55 80,53 80,50 L80,30 C80,27 78,25 75,25 L25,25 C22,25 20,27 20,30 L20,50 C20,53 22,55 25,55 L35,55 L32,65 L68,65 L65,55 L75,55 Z M50,15 C55,15 58,18 58,22 C58,26 55,29 50,29 C45,29 42,26 42,22 C42,18 45,15 50,15 Z M30,40 C33,40 35,38 35,35 C35,32 33,30 30,30 C27,30 25,32 25,35 C25,38 27,40 30,40 Z M70,40 C73,40 75,38 75,35 C75,32 73,30 70,30 C67,30 65,32 65,35 C65,38 67,40 70,40 Z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase">Nestlé <span class="text-nestle-blue">NDRC</span></span>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-gray-700 hover:text-nestle-blue transition-colors">Portals</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-bold text-gray-600 hover:text-gray-900 transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-gray-900 text-white px-6 py-2.5 rounded-2xl text-sm font-black shadow-lg shadow-gray-900/20 hover:scale-105 hover:bg-nestle-blue hover:shadow-nestle-blue/30 transition-all uppercase tracking-wider">
                            Join Network
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Hero -->
    <main class="relative z-10 pt-32 pb-16 sm:pt-40 sm:pb-24 lg:pb-32 flex flex-col items-center justify-center min-h-[90vh]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 border border-white/80 shadow-sm backdrop-blur-sm mb-8 animate-bounce" style="animation-duration: 3s;">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-xs font-black text-gray-600 uppercase tracking-widest">Global Supply Chain Live</span>
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-8xl font-black tracking-tighter text-gray-900 mb-6 leading-[1.1]">
                Empowering the<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-nestle-blue to-teal-400">Future of Retail.</span>
            </h1>
            
            <p class="max-w-2xl mx-auto text-lg sm:text-2xl font-medium text-gray-600 mb-10 leading-relaxed">
                Connect directly into the Nestlé digital distribution pipeline. Streamline ordering, track logistics, and grow your local business seamlessly.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-5 rounded-3xl bg-gradient-to-r from-nestle-blue to-blue-600 text-white font-black text-lg shadow-2xl shadow-nestle-blue/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3 group">
                    Become a Partner
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-5 rounded-3xl bg-white text-gray-900 font-black text-lg shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:scale-[1.02] active:scale-95 transition-all border border-gray-100 flex items-center justify-center">
                    Access Portal
                </a>
            </div>
            
            <!-- System Stats -->
            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8 max-w-4xl mx-auto">
                <div class="bg-white/50 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg">
                    <p class="text-4xl font-black text-gray-900">4.9M</p>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Orders Processed</p>
                </div>
                <div class="bg-white/50 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg">
                    <p class="text-4xl font-black text-gray-900">99.9%</p>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Uptime SLA</p>
                </div>
                <div class="bg-white/50 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg">
                    <p class="text-4xl font-black text-gray-900">15k+</p>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Active Retailers</p>
                </div>
                <div class="bg-white/50 backdrop-blur-md p-6 rounded-3xl border border-white/60 shadow-lg">
                    <p class="text-4xl font-black text-gray-900">300+</p>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mt-1">Regional Hubs</p>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-10 border-t border-gray-200/50 bg-white/40 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">
                © {{ date('Y') }} Nestlé NDRC Platform. All Systems Operational.
            </p>
        </div>
    </footer>
</body>
</html>
