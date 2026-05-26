<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TicketHub') }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #f7f9fb;
            font-family: 'Inter', sans-serif;
        }
        .dark body {
            background-color: #0f172a;
        }
    </style>

    <script>
        function updateTheme() {
            const appearance = localStorage.getItem('flux.appearance');
            if (appearance === 'dark' || (appearance !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        
        // Initial setup
        updateTheme();
        
        // Re-apply theme on Livewire SPA navigation
        document.addEventListener('livewire:navigated', updateTheme);
        
        // Sync across tabs (e.g. if user changes theme in dashboard settings)
        window.addEventListener('storage', (event) => {
            if (event.key === 'flux.appearance') updateTheme();
        });
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#f7f9fb] dark:bg-[#0f172a] text-[#191c1e] dark:text-[#f1f5f9] antialiased">
    <!-- TopNavBar -->
    <header class="bg-slate-50/90 dark:bg-slate-900/90 backdrop-blur-md font-['Inter'] antialiased text-sm font-medium docked full-width top-0 sticky border-b border-slate-200 dark:border-slate-800 shadow-sm z-50 transition-colors duration-200">
        <div class="flex justify-between items-center w-full px-4 md:px-6 py-3 max-w-screen-2xl mx-auto">
            <div class="flex items-center gap-4 md:gap-8">
                <!-- Mobile Menu Toggle (Left on mobile) -->
                <button x-data @click="$dispatch('open-mobile-menu')" class="flex lg:hidden p-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors">
                    <span class="material-symbols-outlined">menu_open</span>
                </button>

                <a href="{{ route('home') }}" class="text-xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-6 bg-indigo-600 dark:bg-indigo-500 rounded-full hidden sm:block"></span>
                    TicketHub
                </a>
                
                <nav class="hidden lg:flex items-center gap-6">
                    <a class="{{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 pb-1' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-200' }}" href="{{ route('home') }}">Marketplace</a>
                    <a class="{{ request()->routeIs('public.events.*') ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 pb-1' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-200' }}" href="{{ route('public.events.index') }}">Events</a>
                    <a class="{{ request()->routeIs('public.tickets.*') ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 pb-1' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-200' }}" href="{{ route('public.tickets.index') }}">My Tickets</a>
                    <a class="{{ request()->routeIs('public.support') ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 pb-1' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-200' }}" href="{{ route('public.support') }}">Support</a>
                </nav>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <livewire:public.cart-icon />
                <livewire:public.notification-bell />
                
                <!-- Dark Mode Toggle Button (Visible on all screens) -->
                <button x-data="{ 
                            isDark: localStorage.getItem('flux.appearance') === 'dark' || (localStorage.getItem('flux.appearance') !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches),
                            toggle() {
                                this.isDark = !this.isDark;
                                localStorage.setItem('flux.appearance', this.isDark ? 'dark' : 'light');
                                if (this.isDark) {
                                    document.documentElement.classList.add('dark');
                                } else {
                                    document.documentElement.classList.remove('dark');
                                }
                            }
                        }" 
                        @click="toggle"
                        class="p-2 transition-colors duration-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md active:scale-95 text-slate-600 dark:text-slate-400" 
                        title="Toggle Dark Mode">
                    <span class="material-symbols-outlined" x-text="isDark ? 'light_mode' : 'dark_mode'"></span>
                </button>

                <div class="hidden lg:flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="p-2 transition-colors duration-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md active:scale-95 text-slate-600 dark:text-slate-400" title="Dashboard">
                            <span class="material-symbols-outlined">account_circle</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 transition-colors duration-200 hover:bg-red-50 dark:hover:bg-red-900/30 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 rounded-md active:scale-95" title="Logout">
                                <span class="material-symbols-outlined">logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-indigo-600 dark:bg-indigo-500 text-white px-5 py-2 rounded-xl font-bold hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-indigo-100 dark:shadow-none">
                            Login
                        </a>
                    @endauth
                </div>

                <!-- Mobile Account Toggle (Visible only on mobile) -->
                @auth
                <a href="{{ route('dashboard') }}" class="lg:hidden w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs border-2 border-white shadow-sm">
                    {{ auth()->user()->initials() }}
                </a>
                @else
                <a href="{{ route('login') }}" class="lg:hidden p-2 text-slate-600">
                    <span class="material-symbols-outlined">login</span>
                </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Mobile Drawer Menu (Slide out) -->
    <div x-data="{ open: false }" 
         x-on:open-mobile-menu.window="open = true"
         x-show="open" 
         class="fixed inset-0 z-[60] lg:hidden"
         style="display: none;">
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="absolute inset-y-0 left-0 w-80 bg-white dark:bg-slate-900 shadow-2xl p-6">
            <div class="flex items-center justify-between mb-8">
                <span class="text-xl font-black text-slate-900 dark:text-white">TicketHub</span>
                <button @click="open = false" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <nav class="flex flex-col gap-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 p-4 rounded-2xl {{ request()->routeIs('home') ? 'bg-indigo-50 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">storefront</span> Marketplace
                </a>
                <a href="{{ route('public.events.index') }}" class="flex items-center gap-3 p-4 rounded-2xl {{ request()->routeIs('public.events.*') ? 'bg-indigo-50 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">confirmation_number</span> Browse Events
                </a>
                <a href="{{ route('public.tickets.index') }}" class="flex items-center gap-3 p-4 rounded-2xl {{ request()->routeIs('public.tickets.*') ? 'bg-indigo-50 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">local_activity</span> My Tickets
                </a>
                <a href="{{ route('public.support') }}" class="flex items-center gap-3 p-4 rounded-2xl {{ request()->routeIs('public.support') ? 'bg-indigo-50 dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <span class="material-symbols-outlined">help</span> Help & Support
                </a>
            </nav>
            <div class="absolute bottom-8 left-6 right-6">
                @auth
                <div class="bg-slate-50 rounded-3xl p-4 flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->role->value }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 p-4 rounded-2xl font-bold hover:bg-red-100 transition-colors">
                        <span class="material-symbols-outlined">logout</span> Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center bg-indigo-600 text-white p-4 rounded-2xl font-bold shadow-lg shadow-indigo-100">
                    Login / Register
                </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Bottom Navigation (Mobile Only) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-t border-slate-100 dark:border-slate-800 px-6 py-3">
        <div class="flex justify-between items-center max-w-md mx-auto">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('home') ? 'font-fill' : '' }}">home</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Home</span>
            </a>
            <a href="{{ route('public.events.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('public.events.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('public.events.*') ? 'font-fill' : '' }}">explore</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Events</span>
            </a>
            <div class="relative -top-6">
                <a href="{{ route('public.events.index') }}" class="w-14 h-14 bg-indigo-600 dark:bg-indigo-500 rounded-2xl rotate-45 flex items-center justify-center text-white shadow-xl shadow-indigo-200 dark:shadow-none border-4 border-white dark:border-slate-900 active:scale-90 transition-transform">
                    <span class="material-symbols-outlined -rotate-45 text-2xl">search</span>
                </a>
            </div>
            <a href="{{ route('public.tickets.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('public.tickets.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('public.tickets.*') ? 'font-fill' : '' }}">local_activity</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Tickets</span>
            </a>
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'font-fill' : '' }}">person</span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Profile</span>
            </a>
        </div>
    </div>

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 font-['Inter'] text-xs leading-relaxed full-width bottom-0 pb-20 lg:pb-0">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 w-full max-w-screen-2xl mx-auto gap-4">
            <div class="flex flex-col items-center md:items-start gap-4">
                <span class="text-lg font-bold text-slate-900 dark:text-white">TicketHub</span>
                <p class="text-slate-500 dark:text-slate-400 max-w-xs text-center md:text-left">
                    © {{ date('Y') }} TicketHub SaaS. Professional Ticketing Infrastructure.
                </p>
            </div>
            <nav class="flex flex-wrap justify-center gap-8">
                <a class="text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 hover:underline transition-all" href="#">About</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 hover:underline transition-all" href="#">Privacy</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 hover:underline transition-all" href="#">Terms</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 hover:underline transition-all" href="{{ route('public.support') }}">Contact</a>
            </nav>
            <div class="flex gap-4">
                <button class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">public</span>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">alternate_email</span>
                </button>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
