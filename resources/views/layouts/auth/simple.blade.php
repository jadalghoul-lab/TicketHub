<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" style="color-scheme: light;">
    <head>
        @include('partials.head')
        <style>
            body {
                background-color: #f7f9fb;
                color: #0f172a; /* slate-900 */
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="min-h-screen bg-[#f7f9fb] text-slate-900 antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-bold group" wire:navigate>
                    <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-indigo-600 text-3xl">confirmation_number</span>
                    </div>
                    <span class="text-2xl font-black tracking-tighter text-slate-900">{{ config('app.name', 'TicketHub') }}</span>
                </a>

                <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col gap-6">
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
