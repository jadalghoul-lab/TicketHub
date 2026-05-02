@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-20 px-6 flex flex-col items-center justify-center text-center">
    <div class="max-w-md w-full">
        <!-- Success Icon Animation -->
        <div class="mb-10 relative">
            <div class="absolute inset-0 bg-green-100 rounded-full blur-3xl opacity-50 scale-150"></div>
            <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto shadow-xl shadow-green-200 relative animate-bounce">
                <span class="material-symbols-outlined text-white text-5xl">check_circle</span>
            </div>
        </div>

        <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Order Confirmed!</h1>
        <p class="text-slate-500 text-lg mb-10 leading-relaxed">
            Thank you for your purchase. Your payment was successful and your tickets are now available in your collection.
        </p>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 mb-10 text-left">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Order Number</p>
                    <p class="text-sm font-bold text-slate-900">#{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Paid</p>
                    <p class="text-sm font-bold text-indigo-600">€{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-indigo-600">calendar_today</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-900">{{ $order->event->title }}</p>
                    <p class="text-[10px] text-slate-500 font-medium">{{ $order->event->start_date->format('l, d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <a href="{{ route('public.tickets.index') }}" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 group">
                View My Tickets
                <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">confirmation_number</span>
            </a>
            <a href="{{ route('home') }}" class="text-slate-400 font-bold hover:text-slate-900 transition-colors">
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
