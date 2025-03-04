@extends('layouts.auth')

@section('content')
<div class="mb-4 text-center">
    <h1 class="text-2xl font-bold">{{ __('Login') }}</h1>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
        <input id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
        <input id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
        @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <div class="flex items-center">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <label for="remember" class="ml-2 text-sm text-gray-700">{{ __('Remember Me') }}</label>
        </div>
    </div>

    <div class="flex items-center justify-between mb-4">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            {{ __('Login') }}
        </button>
        
        @if (Route::has('password.request'))
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </div>

    <div class="text-center mt-4">
        <p class="text-sm text-gray-600">
            {{ __('Don\'t have an account?') }} 
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Register now') }}</a>
        </p>
    </div>
</form>
@endsection