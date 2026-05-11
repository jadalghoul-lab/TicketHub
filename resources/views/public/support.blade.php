@extends('layouts.public')

@section('content')
<div class="bg-indigo-600 text-white pt-40 md:pt-56 pb-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
    <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-black mb-6 tracking-tight ">How can we help?</h1>
        <p class="text-indigo-100 text-lg md:text-xl max-w-2xl mx-auto font-medium">
            Our support team is here to assist you with any questions about tickets, events, or your account.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Contact Info -->
        <div class="lg:col-span-1 flex flex-col gap-8">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                    <span class="material-symbols-outlined text-2xl">mail</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Email Us</h3>
                <p class="text-slate-500 mb-4">We'll get back to you within 24 hours.</p>
                <a href="mailto:support@tickethub.com" class="text-indigo-600 font-bold hover:text-indigo-700">support@tickethub.com</a>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                    <span class="material-symbols-outlined text-2xl">call</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Call Us</h3>
                <p class="text-slate-500 mb-4">Mon-Fri from 9am to 6pm.</p>
                <a href="tel:+32021234567" class="text-indigo-600 font-bold hover:text-indigo-700">+32 02 123 45 67</a>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                    <span class="material-symbols-outlined text-2xl">location_on</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Visit Us</h3>
                <p class="text-slate-500">
                    TicketHub HQ<br>
                    123 Event Street<br>
                    1000 Brussels, BE
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <livewire:public.support-form />
        </div>
    </div>
</div>
@endsection
