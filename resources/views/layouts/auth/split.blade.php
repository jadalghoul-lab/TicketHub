<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 overflow-hidden">
            <!-- Brand Logo for mobile (absolute top) -->
            <div class="lg:hidden absolute top-8 left-0 right-0 z-50 flex justify-center">
                 <a href="{{ route('home') }}" class="flex items-center gap-2 group" wire:navigate>
                    <div class="w-10 h-10 bg-white rounded-xl shadow-lg border border-slate-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-indigo-600 text-2xl">confirmation_number</span>
                    </div>
                    <span class="text-xl font-black tracking-tighter text-slate-900">{{ config('app.name', 'TicketHub') }}</span>
                </a>
            </div>

            <!-- Left Side: Visual -->
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex border-r border-slate-100">
                <div class="absolute inset-0 bg-slate-900">
                    <img src="{{ asset('assets/img/login_bg.png') }}" class="w-full h-full object-cover opacity-60 mix-blend-overlay" alt="Login Background">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-950/80 via-slate-900/40 to-transparent"></div>
                </div>
                
                <a href="{{ route('home') }}" class="relative z-20 flex items-center gap-3 group" wire:navigate>
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                        <span class="material-symbols-outlined text-white text-3xl">confirmation_number</span>
                    </div>
                    <span class="text-2xl font-black tracking-tighter text-white">{{ config('app.name', 'TicketHub') }}</span>
                </a>

                <div class="relative z-20 mt-auto bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-[2.5rem] max-w-lg">
                    <div class="flex gap-1 mb-4 text-amber-400">
                        @for($i=0; $i<5; $i++)
                            <span class="material-symbols-outlined text-sm font-fill">star</span>
                        @endfor
                    </div>
                    <blockquote class="space-y-4">
                        <p class="text-xl font-medium leading-relaxed italic text-indigo-50">
                            &ldquo;The best ticketing platform I've ever used. The interface is intuitive, and the checkout process is incredibly fast. Highly recommended for any event goer!&rdquo;
                        </p>
                        <footer class="flex items-center gap-3 pt-4 border-t border-white/10">
                            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-sm">JS</div>
                            <div>
                                <p class="font-bold text-white">James Sterling</p>
                                <p class="text-xs text-indigo-200">Platinum Member</p>
                            </div>
                        </footer>
                    </blockquote>
                </div>
            </div>

            <!-- Right Side: Content -->
            <div class="w-full flex items-center justify-center h-full relative">
                <!-- Subtle background patterns for mobile -->
                <div class="lg:hidden absolute inset-0 -z-10 overflow-hidden pointer-events-none">
                    <div class="absolute -top-20 -right-20 w-80 h-80 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
                    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-purple-50 rounded-full blur-3xl opacity-50"></div>
                </div>

                <div class="w-full max-w-sm px-6 py-12">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
