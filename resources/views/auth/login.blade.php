@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-nestle-bg relative overflow-hidden px-4">
    <!-- Background Decorative Elements -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-nestle-blue/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-nestle-brown/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl shadow-xl shadow-nestle-blue/10 mb-6 p-4">
                <!-- Authentic Nestlé Bird's Nest SVG -->
                <svg viewBox="0 0 100 80" class="w-full text-nestle-blue fill-current">
                    <path d="M75,55 C78,55 80,53 80,50 L80,30 C80,27 78,25 75,25 L25,25 C22,25 20,27 20,30 L20,50 C20,53 22,55 25,55 L35,55 L32,65 L68,65 L65,55 L75,55 Z M50,15 C55,15 58,18 58,22 C58,26 55,29 50,29 C45,29 42,26 42,22 C42,18 45,15 50,15 Z M30,40 C33,40 35,38 35,35 C35,32 33,30 30,30 C27,30 25,32 25,35 C25,38 27,40 30,40 Z M70,40 C73,40 75,38 75,35 C75,32 73,30 70,30 C67,30 65,32 65,35 C65,38 67,40 70,40 Z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Nestlé <span class="text-nestle-blue">NDRC</span></h1>
            <p class="text-gray-700 font-bold mt-2">Digital Distribution & Reporting Center</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/90 backdrop-blur-xl p-8 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-300/50 border border-white">
            <div class="mb-8">
                <h2 class="text-3xl font-black text-gray-900 leading-none">Welcome Back</h2>
                <p class="text-sm text-gray-700 mt-2 font-bold">Please sign in to your NDRC account.</p>
            </div>

            @if ($errors->any())
                <!-- Error Alert ... -->
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-black text-gray-900 uppercase tracking-widest ml-1 mb-2">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" 
                        placeholder="john.doe@nestle.com"
                        class="block w-full bg-gray-50 border-2 border-gray-100 rounded-2xl py-4 px-6 text-gray-900 placeholder:text-gray-400 focus:border-nestle-blue focus:ring-0 transition-all font-bold">
                </div>

                <div>
                    <div class="flex items-center justify-between ml-1 mb-2">
                        <label for="password" class="block text-xs font-black text-gray-900 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-[10px] font-black text-nestle-blue uppercase tracking-widest hover:opacity-70 transition-opacity">Forgot Password?</a>
                    </div>
                    <input id="password" name="password" type="password" required 
                        placeholder="••••••••"
                        class="block w-full bg-gray-50 border-2 border-gray-100 rounded-2xl py-4 px-6 text-gray-900 placeholder:text-gray-400 focus:border-nestle-blue focus:ring-0 transition-all font-bold">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="group relative w-full flex justify-center py-5 px-6 border border-transparent rounded-2xl text-sm font-black text-white bg-gray-900 hover:bg-nestle-blue focus:outline-none transition-all shadow-xl shadow-gray-900/20 hover:shadow-nestle-blue/40 overflow-hidden">
                        <span class="relative z-10">Sign In</span>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center pt-8 border-t border-gray-100">
                <p class="text-sm font-bold text-gray-700">
                    Don't have an NDRC account?
                    <a href="{{ route('register') }}" class="font-black text-nestle-blue hover:underline underline-offset-4 decoration-2">Request Access</a>
                </p>
            </div>
        </div>
        
        <!-- Trust Badge -->
        <div class="mt-8 text-center flex items-center justify-center gap-2">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            <span class="text-[10px] font-black text-gray-800 uppercase tracking-[0.2em]">Secure Node 01 | Encrypted Connection</span>
        </div>
    </div>
</div>
@endsection
