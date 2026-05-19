<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
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
    <body class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-200">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <flux:sidebar.header class="border-b border-slate-100 dark:border-slate-800 pb-4">
                @php
                    $logoName = 'TicketHub';
                    if (auth()->user()->isAdmin()) $logoName = 'Admin Panel';
                    if (auth()->user()->isOrganizer()) $logoName = 'Organizer Hub';
                @endphp
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-1 py-2 group">
                    <span class="w-2 h-6 bg-indigo-600 dark:bg-indigo-500 rounded-full group-hover:h-7 transition-all duration-200"></span>
                    <span class="font-black text-base tracking-tight text-slate-900 dark:text-white">{{ $logoName }}</span>
                </a>
                <flux:sidebar.collapse class="lg:hidden ml-auto text-slate-500 dark:text-slate-400" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="py-4">
                <flux:sidebar.group :heading="__('Management')" class="grid gap-1">
                    @if(auth()->user()->isAdmin())
                        <flux:sidebar.item icon="home" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                            Admin Dashboard
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="banknotes" :href="route('admin.payouts')" :current="request()->routeIs('admin.payouts')" wire:navigate>
                            Payout Requests
                        </flux:sidebar.item>
                    @endif

                    @if(auth()->user()->isOrganizer())
                        <flux:sidebar.item icon="home" :href="route('organizer.dashboard')" :current="request()->routeIs('organizer.dashboard')" wire:navigate>
                            Organizer Hub
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="calendar-days" :href="route('organizer.events.index')" :current="request()->routeIs('organizer.events.*')" wire:navigate>
                            Events
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="viewfinder-circle" :href="route('organizer.global-scanner')" :current="request()->routeIs('organizer.global-scanner')" wire:navigate>
                            Global Scanner
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="arrow-path" :href="route('organizer.refunds')" :current="request()->routeIs('organizer.refunds')" wire:navigate>
                            Refund Requests
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="ticket" :href="route('organizer.coupons')" :current="request()->routeIs('organizer.coupons')" wire:navigate>
                            Coupons
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="banknotes" :href="route('organizer.payouts')" :current="request()->routeIs('organizer.payouts')" wire:navigate>
                            Payouts
                        </flux:sidebar.item>
                    @endif

                    @if(auth()->user()->isCustomer())
                        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                            Dashboard
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="ticket" :href="route('public.tickets.index')" :current="request()->routeIs('public.tickets.index')" wire:navigate>
                            My Tickets
                        </flux:sidebar.item>
                    @endif
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav class="border-t border-slate-100 dark:border-slate-800 pt-4 pb-2">
                <flux:sidebar.item icon="globe-alt" :href="route('home')" wire:navigate class="text-slate-600 dark:text-slate-400">
                    {{ __('Back to Website') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile Header -->
        <flux:header class="lg:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
            <flux:sidebar.toggle class="lg:hidden text-slate-600 dark:text-slate-400" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 mx-auto">
                <span class="w-1.5 h-5 bg-indigo-600 dark:bg-indigo-500 rounded-full"></span>
                <span class="font-black text-sm tracking-tight text-slate-900 dark:text-white">
                    @if(auth()->user()->isAdmin()) Admin Panel
                    @elseif(auth()->user()->isOrganizer()) Organizer Hub
                    @else TicketHub
                    @endif
                </span>
            </a>

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
