<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <style>
            body {
                font-family: 'Inter', sans-serif;
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
            updateTheme();
            document.addEventListener('livewire:navigated', updateTheme);
            window.addEventListener('storage', (event) => {
                if (event.key === 'flux.appearance') updateTheme();
            });
        </script>
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white antialiased transition-colors duration-200">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-bold group" wire:navigate>
                    <div class="flex items-center gap-2 group-hover:scale-110 transition-transform">
                        <span class="w-2 h-6 bg-indigo-600 dark:bg-indigo-500 rounded-full"></span>
                        <span class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white">{{ config('app.name', 'TicketHub') }}</span>
                    </div>
                </a>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 flex flex-col gap-6">
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
