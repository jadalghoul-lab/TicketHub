@extends('layouts.public')

@section('content')
<div class="bg-indigo-600 dark:bg-slate-950 text-white pt-20 md:pt-28 pb-20 relative overflow-hidden border-b border-transparent dark:border-slate-800">
    <!-- Ambient Glow behind columns -->
    <div class="absolute right-1/4 top-1/4 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 blur-3xl rounded-full -z-10"></div>
    <div class="absolute left-1/4 bottom-1/4 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-500/5 blur-3xl rounded-full -z-10"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
    <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-serif mb-6 tracking-tight text-white">How can we help?</h1>
        <p class="text-indigo-100 dark:text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">
            Our support team is here to assist you with any questions about tickets, events, or your account.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Contact Info (Left Column) -->
        <div class="lg:col-span-5 flex flex-col gap-6">
            <!-- Email Us Card -->
            <div class="bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-6 flex items-start gap-4 shadow-sm">
                <div class="w-12 h-12 flex items-center justify-center shrink-0 bg-indigo-50 dark:bg-slate-800/40 border border-transparent dark:border-slate-700/30 rounded-xl text-indigo-600 dark:text-slate-200">
                    <span class="material-symbols-outlined text-2xl">mail</span>
                </div>
                <div class="flex flex-col gap-1">
                    <h3 class="font-semibold text-slate-900 dark:text-white text-base">Email Us</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">We'll get back to you within 24 hours.</p>
                    <a href="mailto:support@tickethub.com" class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold hover:underline">support@tickethub.com</a>
                </div>
            </div>

            <!-- Call Us Card -->
            <div class="bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-6 flex items-start gap-4 shadow-sm">
                <div class="w-12 h-12 flex items-center justify-center shrink-0 bg-indigo-50 dark:bg-slate-800/40 border border-transparent dark:border-slate-700/30 rounded-xl text-indigo-600 dark:text-slate-200">
                    <span class="material-symbols-outlined text-2xl">call</span>
                </div>
                <div class="flex flex-col gap-1">
                    <h3 class="font-semibold text-slate-900 dark:text-white text-base">Call Us</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">Mon-Fri from 9am to 6pm.</p>
                    <a href="tel:+32021234567" class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold hover:underline">+32 02 123 45 67</a>
                </div>
            </div>

            <!-- Visit Us Card -->
            <div class="bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-6 flex items-start gap-4 shadow-sm">
                <div class="w-12 h-12 flex items-center justify-center shrink-0 bg-indigo-50 dark:bg-slate-800/40 border border-transparent dark:border-slate-700/30 rounded-xl text-indigo-600 dark:text-slate-200">
                    <span class="material-symbols-outlined text-2xl">location_on</span>
                </div>
                <div class="flex flex-col gap-1">
                    <h3 class="font-semibold text-slate-900 dark:text-white text-base">Visit Us</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">TicketHub HQ</p>
                    <span class="text-slate-700 dark:text-slate-200 text-sm font-medium">123 Event Street, 1000 Brussels, BE</span>
                </div>
            </div>

            <!-- Stylized Map Card -->
            <div class="bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm h-48 relative group">
                <!-- Abstract Map Graphic SVG -->
                <svg class="absolute inset-0 w-full h-full bg-slate-50 dark:bg-[#050814]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <radialGradient id="mapGlow" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" stop-color="#4f46e5" stop-opacity="0.15"/>
                            <stop offset="100%" stop-color="#4f46e5" stop-opacity="0"/>
                        </radialGradient>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#mapGlow)"/>
                    <g stroke="currentColor" class="text-slate-200 dark:text-slate-800/60" stroke-width="1" fill="none" opacity="0.6">
                        <!-- Brussels concentric circles -->
                        <circle cx="50%" cy="50%" r="30" />
                        <circle cx="50%" cy="50%" r="60" />
                        <circle cx="50%" cy="50%" r="90" />
                        <circle cx="50%" cy="50%" r="120" />
                        
                        <!-- Intersecting roads -->
                        <line x1="0" y1="0" x2="100%" y2="100%" />
                        <line x1="100%" y1="0" x2="0" y2="100%" />
                        <line x1="50%" y1="0" x2="50%" y2="100%" />
                        <line x1="0" y1="50%" x2="100%" y2="50%" />
                    </g>
                    <!-- Marker Pulse -->
                    <circle cx="50%" cy="50%" r="8" fill="#4f46e5" class="animate-ping" opacity="0.4"/>
                    <circle cx="50%" cy="50%" r="4" fill="#4f46e5"/>
                </svg>
                
                <div class="absolute bottom-4 left-4 z-10">
                    <span class="bg-indigo-600 text-white text-[10px] font-semibold px-3 py-1.5 rounded-lg shadow-md">TicketHub HQ</span>
                </div>
            </div>
        </div>

        <!-- Contact Form (Right Column) -->
        <div class="lg:col-span-7">
            <livewire:public.support-form />
        </div>
    </div>

    <!-- Bottom Informational Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20">
        <div class="bg-white dark:bg-slate-900/20 border border-slate-100 dark:border-slate-800/40 rounded-2xl p-8 text-center shadow-sm hover:scale-[1.02] transition-transform duration-300">
            <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-3xl mb-4">speed</span>
            <h4 class="font-bold text-slate-900 dark:text-slate-200 text-sm mb-2">Fast Track</h4>
            <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Need to cancel a ticket? Use our automated portal for instant refunds.</p>
        </div>
        <div class="bg-white dark:bg-slate-900/20 border border-slate-100 dark:border-slate-800/40 rounded-2xl p-8 text-center shadow-sm hover:scale-[1.02] transition-transform duration-300">
            <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-3xl mb-4">menu_book</span>
            <h4 class="font-bold text-slate-900 dark:text-slate-200 text-sm mb-2">Documentation</h4>
            <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Browse our extensive API guides and developer resources.</p>
        </div>
        <div class="bg-white dark:bg-slate-900/20 border border-slate-100 dark:border-slate-800/40 rounded-2xl p-8 text-center shadow-sm hover:scale-[1.02] transition-transform duration-300">
            <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-3xl mb-4">security</span>
            <h4 class="font-bold text-slate-900 dark:text-slate-200 text-sm mb-2">Security Center</h4>
            <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Learn about how we protect your transactions and personal data.</p>
        </div>
    </div>
</div>
@endsection
