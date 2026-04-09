@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-nestle-bg relative overflow-hidden px-4 py-12">
    <!-- Background Decorative Elements -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-nestle-blue/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-nestle-brown/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-xl z-10" x-data="{ step: 1, selectedRole: '' }">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl shadow-xl shadow-nestle-blue/10 mb-6 p-4 mx-auto">
                <!-- Authentic Nestlé Bird's Nest SVG -->
                <svg viewBox="0 0 100 80" class="w-full text-nestle-blue fill-current">
                    <path d="M75,55 C78,55 80,53 80,50 L80,30 C80,27 78,25 75,25 L25,25 C22,25 20,27 20,30 L20,50 C20,53 22,55 25,55 L35,55 L32,65 L68,65 L65,55 L75,55 Z M50,15 C55,15 58,18 58,22 C58,26 55,29 50,29 C45,29 42,26 42,22 C42,18 45,15 50,15 Z M30,40 C33,40 35,38 35,35 C35,32 33,30 30,30 C27,30 25,32 25,35 C25,38 27,40 30,40 Z M70,40 C73,40 75,38 75,35 C75,32 73,30 70,30 C67,30 65,32 65,35 C65,38 67,40 70,40 Z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Create <span class="text-nestle-blue">NDRC</span> Account</h1>
            <p class="text-gray-500 font-medium mt-2">Join the Nestlé Digital Distribution Network</p>
        </div>

        <!-- Multi-step Form -->
        <div class="bg-white/70 backdrop-blur-xl p-8 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white/50">
            
            <!-- Progress Indicator -->
            <div class="flex items-center justify-center mb-10 gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs transition-colors" 
                        :class="step >= 1 ? 'bg-nestle-blue text-white' : 'bg-gray-100 text-gray-400'">1</div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Identity</span>
                </div>
                <div class="w-12 h-px bg-gray-100"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs transition-colors" 
                        :class="step >= 2 ? 'bg-nestle-blue text-white' : 'bg-gray-100 text-gray-400'">2</div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Context</span>
                </div>
            </div>

            <form id="registerForm" action="{{ route('register.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Step 1: Basic Info -->
                <div x-show="step === 1" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Full Name / Business Name</label>
                            <input id="name" name="name" type="text" required class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue transition-all font-medium" placeholder="E.g. Udakara Stores">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Email Address</label>
                                <input id="email" name="email" type="email" required class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue transition-all font-medium" placeholder="contact@business.com">
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Phone Number</label>
                                <input id="phone" name="phone" type="tel" required class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue transition-all font-medium" placeholder="07XXXXXXXX">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Business Address</label>
                            <textarea id="address" name="address" rows="2" required class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue transition-all font-medium" placeholder="Full physical address..."></textarea>
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Security Key (Password)</label>
                            <input id="password" name="password" type="password" required class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue transition-all font-medium" placeholder="••••••••">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-4">Select Your Role</label>
                            <div class="grid grid-cols-1 gap-3">
                                <label class="relative flex cursor-pointer rounded-2xl border p-4 hover:border-nestle-blue transition-all" :class="selectedRole === 'retailer' ? 'border-nestle-blue bg-nestle-blue/5 ring-2 ring-nestle-blue' : 'border-gray-100 bg-gray-50'">
                                    <input type="radio" name="role" value="retailer" x-model="selectedRole" required class="sr-only">
                                    <span class="flex items-center gap-4">
                                        <span class="text-2xl">🏪</span>
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-black text-gray-900">Retailer</span>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Store owner / End seller</span>
                                        </span>
                                    </span>
                                </label>

                                <label class="relative flex cursor-pointer rounded-2xl border p-4 hover:border-nestle-blue transition-all" :class="selectedRole === 'wholesaler' ? 'border-nestle-blue bg-nestle-blue/5 ring-2 ring-nestle-blue' : 'border-gray-100 bg-gray-50'">
                                    <input type="radio" name="role" value="wholesaler" x-model="selectedRole" class="sr-only">
                                    <span class="flex items-center gap-4">
                                        <span class="text-2xl">🏭</span>
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-black text-gray-900">Wholesaler</span>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Network Supplier</span>
                                        </span>
                                    </span>
                                </label>

                                <label class="relative flex cursor-pointer rounded-2xl border p-4 hover:border-nestle-blue transition-all" :class="selectedRole === 'distributor' ? 'border-nestle-blue bg-nestle-blue/5 ring-2 ring-nestle-blue' : 'border-gray-100 bg-gray-50'">
                                    <input type="radio" name="role" value="distributor" x-model="selectedRole" class="sr-only">
                                    <span class="flex items-center gap-4">
                                        <span class="text-2xl">🚚</span>
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-black text-gray-900">Distributor</span>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Main Supply Hub</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="button" 
                            @click="if(document.getElementById('registerForm').checkValidity()) { step = 2 } else { document.getElementById('registerForm').reportValidity() }"
                            class="w-full flex justify-center py-4 px-6 rounded-2xl text-sm font-black text-white bg-gray-900 hover:bg-nestle-blue transition-all shadow-xl shadow-gray-900/10 hover:shadow-nestle-blue/20">
                            Continue to Assignment →
                        </button>
                    </div>
                </div>

                <!-- Step 2: Role-Specific Fields -->
                <div x-show="step === 2" x-transition class="space-y-6">
                    <button type="button" @click="step = 1" class="text-[10px] font-black text-nestle-blue uppercase tracking-widest hover:opacity-70 flex items-center mb-6">
                        <span class="mr-1">←</span> Back to Personal Info
                    </button>

                    <!-- Retailer Details -->
                    <div x-show="selectedRole === 'retailer'" class="space-y-6">
                        <h3 class="text-xl font-black text-gray-900">Retailer Configuration</h3>
                        
                        <div x-data="{ orderType: 'direct' }">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-4">Supply Channel</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="cursor-pointer p-4 rounded-2xl border transition-all" :class="orderType === 'wholesaler' ? 'border-nestle-blue bg-nestle-blue/5 ring-1 ring-nestle-blue' : 'bg-gray-50 border-gray-100'">
                                    <input name="order_type" type="radio" value="wholesaler" x-model="orderType" class="sr-only">
                                    <span class="block text-xs font-black text-gray-800 text-center">Via Wholesaler</span>
                                </label>
                                <label class="cursor-pointer p-4 rounded-2xl border transition-all" :class="orderType === 'direct' ? 'border-nestle-blue bg-nestle-blue/5 ring-1 ring-nestle-blue' : 'bg-gray-50 border-gray-100'">
                                    <input name="order_type" type="radio" value="direct" x-model="orderType" class="sr-only">
                                    <span class="block text-xs font-black text-gray-800 text-center">Direct Distributor</span>
                                </label>
                            </div>

                            <div x-show="orderType === 'wholesaler'" x-transition class="mt-6">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Select Wholesaler</label>
                                <select name="wholesaler_id" class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue font-medium">
                                    <option value="">Select a partner...</option>
                                    @foreach($wholesalers as $w)
                                        <option value="{{ $w->id }}">{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Primary Distributor Hub</label>
                            <select name="distributor_id" required class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue font-medium">
                                <option value="">Select hub...</option>
                                @foreach($distributors as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->territory }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Wholesaler Details -->
                    <div x-show="selectedRole === 'wholesaler'" class="space-y-6">
                        <h3 class="text-xl font-black text-gray-900">Wholesaler Setup</h3>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Affiliated Distributor</label>
                            <select name="distributor_id" class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue font-medium">
                                <option value="">Select your source hub...</option>
                                @foreach($distributors as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->territory }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Distributor Details -->
                    <div x-show="selectedRole === 'distributor'" class="space-y-6">
                        <h3 class="text-xl font-black text-gray-900">Hub Assignment</h3>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Operational Territory</label>
                            <select name="territory" class="block w-full bg-gray-50 border-0 rounded-2xl py-4 px-6 text-gray-900 ring-1 ring-inset ring-gray-100 focus:ring-2 focus:ring-nestle-blue font-medium">
                                <option>Western Province</option>
                                <option>Central Province</option>
                                <option>Southern Province</option>
                                <option>Northern Province</option>
                                <option>Eastern Province</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                            class="w-full flex justify-center py-4 px-6 border border-transparent rounded-2xl text-sm font-black text-white bg-nestle-brown hover:bg-gray-900 transition-all shadow-xl shadow-nestle-brown/10 hover:shadow-gray-900/20">
                            Complete Registration
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-8 text-center pt-8 border-t border-gray-50">
                <p class="text-sm font-medium text-gray-500">
                    Already an NDRC member?
                    <a href="{{ route('login') }}" class="font-black text-nestle-blue hover:underline underline-offset-4 decoration-2">Sign In</a>
                </p>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="mt-8 text-center">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] leading-relaxed">
                By registering, you agree to the Nestlé NDRC <br> Data Processing & Distribution Terms.
            </p>
        </div>
    </div>
</div>
@endsection
