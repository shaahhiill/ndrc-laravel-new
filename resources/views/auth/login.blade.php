@extends('layouts.app')

@section('content')
<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-nestle-brown underline decoration-nestle-blue decoration-4 underline-offset-8">Sign in to NDRC</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="{{ route('register') }}" class="font-semibold leading-6 text-nestle-blue hover:text-nestle-blue/80">create a new account</a>
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        <div class="bg-white px-6 py-12 shadow sm:rounded-xl sm:px-12 border border-gray-100">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-nestle-blue sm:text-sm sm:leading-6 px-4">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    </div>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full rounded-lg border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-nestle-blue sm:text-sm sm:leading-6 px-4">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-lg bg-nestle-brown px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-nestle-brown/90 transition-all">Sign in</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
