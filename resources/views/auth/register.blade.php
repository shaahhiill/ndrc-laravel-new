@extends('layouts.app')

@section('content')
<style>
.role-card.active {
    border-color: #0082c3 !important;
    background-color: #f0f9ff !important;
    box-shadow: 0 0 0 2px #0082c3 !important;
}
</style>

<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-nestle-brown underline decoration-nestle-blue decoration-4 underline-offset-8">Create NDRC Account</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold leading-6 text-nestle-blue hover:text-nestle-blue/80">Sign in</a>
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[550px]" x-data="{ step: 1, selectedRole: '' }">
        <div class="bg-white px-6 py-12 shadow sm:rounded-xl sm:px-12 border border-gray-100">
            <form id="registerForm" class="space-y-6" action="{{ route('register.post') }}" method="POST">
                @csrf
                <!-- Step 1: Basic Info -->
                <div x-show="step === 1" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900">Full Name / Business Name</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" required class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-900">Email address</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" required class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-900">Phone Number</label>
                            <div class="mt-2">
                                <input id="phone" name="phone" type="tel" required class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-900">Physical Store / Business Address</label>
                        <div class="mt-2">
                            <textarea id="address" name="address" rows="2" required placeholder="e.g. 123, Galle Road, Colombo 03" class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4"></textarea>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-4">Select Your Role</label>
                        <div class="grid grid-cols-1 gap-3">
                            <label class="role-card relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-nestle-blue group transition-all" :class="selectedRole === 'retailer' ? 'active' : ''">
                                <input type="radio" name="role" value="retailer" x-model="selectedRole" required class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Retailer</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">I own a shop and want to place orders.</span>
                                    </span>
                                </span>
                                <span class="text-2xl group-hover:scale-110 transition-transform">🏪</span>
                            </label>

                            <label class="role-card relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-nestle-blue group transition-all" :class="selectedRole === 'wholesaler' ? 'active' : ''">
                                <input type="radio" name="role" value="wholesaler" x-model="selectedRole" class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Wholesaler</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">I supply to retailers.</span>
                                    </span>
                                </span>
                                <span class="text-2xl group-hover:scale-110 transition-transform">🏭</span>
                            </label>
                            
                            <label class="role-card relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-nestle-blue group transition-all" :class="selectedRole === 'distributor' ? 'active' : ''">
                                <input type="radio" name="role" value="distributor" x-model="selectedRole" class="sr-only">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">Distributor</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500">I supply directly to wholesalers.</span>
                                    </span>
                                </span>
                                <span class="text-2xl group-hover:scale-110 transition-transform">🚚</span>
                            </label>
                        </div>
                    </div>

                    <button type="button" @click="if(selectedRole) step = 2" class="flex w-full justify-center rounded-lg bg-nestle-brown px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-nestle-brown/90 transition-all">Continue to Details →</button>
                </div>

                <!-- Step 2: Role-Specific Fields -->
                <div x-show="step === 2" class="space-y-6">
                    <button type="button" @click="step = 1" class="text-sm font-semibold text-nestle-blue hover:text-nestle-blue/80 flex items-center">
                        <span class="mr-1">←</span> Back to Basic Info
                    </button>

                    <div x-show="selectedRole === 'retailer'" class="space-y-6">
                        <h3 class="text-lg font-bold text-nestle-brown">Retailer Context</h3>
                        <div x-data="{ orderType: 'direct' }">
                            <label class="block text-sm font-medium text-gray-900">How do you place orders?</label>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="via_wholesaler" name="order_type" type="radio" value="wholesaler" x-model="orderType" class="h-4 w-4 border-gray-300 text-nestle-blue focus:ring-nestle-blue">
                                    <label for="via_wholesaler" class="ml-3 block text-sm font-medium text-gray-700">Via a Wholesaler</label>
                                </div>
                                <div x-show="orderType === 'wholesaler'" class="ml-7 mt-2">
                                    <select name="wholesaler_id" class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                                        <option value="">Select your Wholesaler</option>
                                        @foreach($wholesalers as $w)
                                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center">
                                    <input id="direct_dist" name="order_type" type="radio" value="direct" x-model="orderType" class="h-4 w-4 border-gray-300 text-nestle-blue focus:ring-nestle-blue">
                                    <label for="direct_dist" class="ml-3 block text-sm font-medium text-gray-700">Directly from Distributor</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900">Choose Your Primary Distributor</label>
                            <select name="distributor_id" required class="mt-2 block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                                <option value="">Select a Distributor</option>
                                @foreach($distributors as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->territory }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div x-show="selectedRole === 'wholesaler'" class="space-y-6">
                        <h3 class="text-lg font-bold text-nestle-brown">Wholesaler Details</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-900">Affiliated Nestle Distributor</label>
                            <select name="distributor_id" class="mt-2 block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                                <option value="">Select your Distributor</option>
                                @foreach($distributors as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->territory }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div x-show="selectedRole === 'distributor'" class="space-y-6">
                        <h3 class="text-lg font-bold text-nestle-brown">Distributor Assignment</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-900">Assigned Territory</label>
                            <select name="territory" class="mt-2 block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-nestle-blue px-4">
                                <option>Western Province</option>
                                <option>Central Province</option>
                                <option>Southern Province</option>
                                <option>Northern Province</option>
                                <option>Eastern Province</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="flex w-full justify-center rounded-lg bg-nestle-brown px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-nestle-brown/90 transition-all">Complete Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
