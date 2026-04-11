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

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm font-bold text-center animate-pulse">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-2xl">
                    <ul class="list-disc list-inside text-sm font-bold text-red-600 pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-black text-gray-900 uppercase tracking-widest ml-1 mb-2">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" 
                        placeholder="john.doe@nestle.com"
                        class="block w-full bg-gray-50 border-2 border-gray-100 rounded-2xl py-4 px-6 text-gray-900 placeholder:text-gray-400 focus:border-nestle-blue focus:ring-0 transition-all font-bold">
                </div>

                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between ml-1 mb-2">
                        <label for="password" class="block text-xs font-black text-gray-900 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-[10px] font-black text-nestle-blue uppercase tracking-widest hover:opacity-70 transition-opacity">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required 
                            placeholder="••••••••"
                            class="block w-full bg-gray-50 border-2 border-gray-100 rounded-2xl py-4 px-6 pr-14 text-gray-900 placeholder:text-gray-400 focus:border-nestle-blue focus:ring-0 transition-all font-bold">
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-nestle-blue transition-colors">
                            <template x-if="!show">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.263 0 .385C20.601 15.951 16.79 19 12 19c-4.79 0-8.601-3.049-9.964-6.678Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </template>
                            <template x-if="show">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </template>
                        </button>
                    </div>
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
