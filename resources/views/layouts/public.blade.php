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
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f9fb] text-[#191c1e] antialiased">
    <!-- TopNavBar -->
    <header class="bg-slate-50/90 backdrop-blur-md font-['Inter'] antialiased text-sm font-medium docked full-width top-0 sticky border-b border-slate-200 shadow-sm z-50">
        <div class="flex justify-between items-center w-full px-6 py-3 max-w-screen-2xl mx-auto">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-slate-900">TicketHub</a>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="{{ request()->routeIs('home') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-slate-600 hover:text-slate-900 transition-colors duration-200' }}" href="{{ route('home') }}">Marketplace</a>
                    <a class="{{ request()->routeIs('public.events.*') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-slate-600 hover:text-slate-900 transition-colors duration-200' }}" href="{{ route('public.events.index') }}">Events</a>
                    <a class="{{ request()->routeIs('public.tickets.*') ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-slate-600 hover:text-slate-900 transition-colors duration-200' }}" href="{{ route('public.tickets.index') }}">My Tickets</a>
                    <a class="text-slate-600 hover:text-slate-900 transition-colors duration-200" href="#">Support</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden lg:flex items-center gap-2">
                    <button class="p-2 transition-colors duration-200 hover:bg-slate-100 rounded-md active:scale-95">
                        <span class="material-symbols-outlined">shopping_cart</span>
                    </button>
                    <button class="p-2 transition-colors duration-200 hover:bg-slate-100 rounded-md active:scale-95">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="p-2 transition-colors duration-200 hover:bg-slate-100 rounded-md active:scale-95">
                            <span class="material-symbols-outlined">account_circle</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="p-2 transition-colors duration-200 hover:bg-slate-100 rounded-md active:scale-95">
                            <span class="material-symbols-outlined">login</span>
                        </a>
                    @endauth
                </div>
                <a href="{{ route('register') }}" class="bg-[#4f46e5] text-white px-4 py-2 rounded-lg font-semibold active:scale-95 transition-transform">Get Started</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 font-['Inter'] text-xs leading-relaxed full-width bottom-0">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 w-full max-w-screen-2xl mx-auto gap-4">
            <div class="flex flex-col items-center md:items-start gap-4">
                <span class="text-lg font-bold text-slate-900 dark:text-white">TicketHub</span>
                <p class="text-slate-500 dark:text-slate-400 max-w-xs text-center md:text-left">
                    © {{ date('Y') }} TicketHub SaaS. Professional Ticketing Infrastructure.
                </p>
            </div>
            <nav class="flex flex-wrap justify-center gap-8">
                <a class="text-slate-500 hover:text-indigo-500 hover:underline transition-all" href="#">About</a>
                <a class="text-slate-500 hover:text-indigo-500 hover:underline transition-all" href="#">Privacy</a>
                <a class="text-slate-500 hover:text-indigo-500 hover:underline transition-all" href="#">Terms</a>
                <a class="text-slate-500 hover:text-indigo-500 hover:underline transition-all" href="#">Contact</a>
            </nav>
            <div class="flex gap-4">
                <button class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-sm">public</span>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-sm">alternate_email</span>
                </button>
            </div>
        </div>
    </footer>
</body>
</html>
