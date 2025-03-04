<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @stack('styles')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    @vite(['resources/css/tailwind.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div id="app">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="64" height="64">
                                    <circle cx="100" cy="100" r="90" fill="#4F46E5" />
                                    
                                    <path d="M60 50 L140 50 C150 50 155 55 155 65 L155 135 C155 145 150 150 140 150 L60 150 C50 150 45 145 45 135 L45 65 C45 55 50 50 60 50 Z" 
                                          fill="white" 
                                          stroke="#4F46E5" 
                                          stroke-width="5"/>
                                    
                                    <polyline points="75 90 90 105 120 75" 
                                              fill="none" 
                                              stroke="#4F46E5" 
                                              stroke-width="8" 
                                              stroke-linecap="round" 
                                              stroke-linejoin="round"/>
                                    
                                    <polyline points="75 120 90 135 120 105" 
                                              fill="none" 
                                              stroke="#4F46E5" 
                                              stroke-width="8" 
                                              stroke-linecap="round" 
                                              stroke-linejoin="round"/>
                                    
                                    <path d="M140 45 C160 65 160 95 140 115 L100 155 C80 175 50 175 30 155 C10 135 10 105 30 85 L80 35" 
                                          fill="none" 
                                          stroke="white" 
                                          stroke-width="10" 
                                          stroke-linecap="round"/>
                                </svg>
                            </a>
                        </div>

                        @auth
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('tasks.index') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('tasks.index') ? 'border-indigo-500' : 'border-transparent hover:border-gray-300 hover:text-gray-700' }}">
                                Tasks
                            </a>
                            <a href="{{ route('categories.index') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('categories.*') ? 'border-indigo-500' : 'border-transparent hover:border-gray-300 hover:text-gray-700' }}">
                                Categories
                            </a>
                            <a href="{{ route('tasks.calendar') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('tasks.calendar') ? 'border-indigo-500' : 'border-transparent hover:border-gray-300 hover:text-gray-700' }}">
                                Calendar
                            </a>
                            <a href="{{ route('dashboard') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'border-indigo-500' : 'border-transparent hover:border-gray-300 hover:text-gray-700' }}">
                                Dashboard
                            </a>
                        </div>

                        <div class="relative ml-3 flex items-center">
                            <a href="{{ route('notifications.index') }}" class="text-gray-500 hover:text-gray-700">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        

                        
                        @endauth
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-500 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <a href="{{ route('register') }}" class="text-gray-500 px-3 py-2 rounded-md text-sm font-medium">Register</a>
                        @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700" role="menuitem">Sign out</button>
                        </form>
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <div>
                                    <button @click="open = !open" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                            <span class="text-xs font-medium leading-none text-white">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </span>
                                        </span>
                                    </button>
                                </div>
                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-100" 
                                    x-transition:enter-start="transform opacity-0 scale-95" 
                                    x-transition:enter-end="transform opacity-100 scale-100" 
                                    x-transition:leave="transition ease-in duration-75" 
                                    x-transition:leave-start="transform opacity-100 scale-100" 
                                    x-transition:leave-end="transform opacity-0 scale-95" 
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                    role="menu" 
                                    aria-orientation="vertical" 
                                    aria-labelledby="user-menu-button" 
                                    tabindex="-1"
                                    style="display: none;"
                                >
                                  
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
@stack('scripts')

</body>
</html>