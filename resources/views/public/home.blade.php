@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[500px] md:min-h-[600px] flex items-center justify-center py-20">
    <div class="absolute inset-0 z-0">
        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCuR1gsqHmbw75dRDn3DqpT4j01I6Pv8JR_4Y7yFWvWfa9-Suri6idFE1QlXDVoVu2Pr6txujET1kfI21XN6jjSxGUnW6EO5YCrhI_vHNmU9y09-H-fupzN77Ta3FCzWyBKqS1rnMTRgoF97pdUxkKx2lZl-ZLsVIl-9coK5NIFMYNo30MrrYewadFrXIApPOVBv4Y4k9G6HhHpkeXlX2iO2muyeRG3_bIljPenm5H7Z3WONGRiMnRHcWDLN9KvbOreTfE-QbvigxAH" alt="Hero background" />
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]"></div>
    </div>
    <div class="relative z-10 w-full max-w-4xl px-6 text-center">
        <h1 class="text-white text-4xl sm:text-5xl md:text-6xl mb-6 drop-shadow-lg font-black leading-tight">Experience more than <br class="hidden md:block"> just a seat.</h1>
        <p class="text-white/90 mb-10 max-w-2xl mx-auto drop-shadow-md text-base md:text-lg font-medium">Unlock access to the world's most sought-after concerts, sporting events, and theater performances with guaranteed security.</p>

        <!-- Search Form (Realtime Livewire) -->
        <livewire:public.global-search />
    </div>
</section>

<!-- Categories Bento Grid -->
<section class="py-12 md:py-20 px-4 md:px-6 max-w-screen-2xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 md:mb-10 gap-4">
        <div>
            <span class="text-[#3525cd] text-xs md:text-sm font-black tracking-[0.2em] uppercase">Categories</span>
            <h2 class="text-[#191c1e] text-2xl md:text-3xl font-black mt-2">Explore Your Passions</h2>
        </div>
        <a href="{{ route('public.events.index') }}" class="text-[#3525cd] text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all">
            View All Events <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 md:grid-rows-2 gap-4 h-auto md:h-[600px]">
        <a href="{{ route('public.events.index', ['category' => 'music']) }}" class="md:col-span-2 md:row-span-2 relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30 min-h-[300px]">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCktb3_b-lkBwOA69AfR0Hq9UFOy0W8fyDrZajblcCPceegpnXHX4B5nT5Ql5gd8Dzb3BOeWoDUaCwnXGwR-apojf4qzVQ9FioyaHzcBUVCwAajjtkKS6AAIeitLxqdIDOsZfL_u9LwElHRnXJhnKuIPRsnfZUx9nQ3B9BXvu-ULDnpdP5RY3G1AVzoYrXyOC9MgMVujM32obCJnhWEy2YcTIjAoUMXGQtREoQdh82Yk8P8hWldKg_DJ1PVVvVfhT-rJJdxgHOjq6O8" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6 md:bottom-8 md:left-8">
                <h3 class="text-white text-2xl md:text-3xl font-black mb-1 md:mb-2">Concerts & Live Music</h3>
                <p class="text-white/80 text-xs md:text-sm font-medium">From stadiums to intimate clubs.</p>
            </div>
        </a>
        <a href="{{ route('public.events.index', ['category' => 'sports']) }}" class="md:col-span-2 relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30 min-h-[200px]">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7jKwMzxKLdIfO29ptdOXB1nLMa9bL-V0KkvANzpWzfLQupIAsDpTfMdmp5KV8tLCWD87n5QdCjLiMT_J39SvxRUDsezT_izUTImsFgCSutTprSTh-mgPEWQTefW7KQUEYTDXFDIUYyvBq5YKqXobZN9G7kPA7uCFyDsDSZPaZiijR2Tvwo0YTez_qgWHRxbksCXCvxF9lC-lWDyl3Dnr_Zfq_cjykZZ20xMqpR_OL-HVXq2J1GIW8bdjPlJ00YGkc77U05XJ5jgeU" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6">
                <h3 class="text-white text-xl md:text-2xl font-black">Sports & Athletics</h3>
            </div>
        </a>
        <a href="{{ route('public.events.index', ['category' => 'theater']) }}" class="relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30 min-h-[200px]">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCsVIij4Kz8cLw9rYfmulKBaOvoGnkfYpPdI9coVQclBgEYbuC6IF7mMqtPfbbumAibKrBhANstuq3omYDZPwWoTkOTYMcIFvQCwT0OzqLIumyag0T4bYq2vP3rcNm5NXLRzsrg4NPk7JyEo4yQ6a0okUJDeC7jT-5f740aCsiUm_E3gFj3RXJtN4GKn_f7YlU3K7Nx1lh7cGkCaMzPZ9XtC2Ct5Bap9Lks4fGng4DQuE853fEharsMVM28eiKwFTqYCMsY7GDYoNII" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6">
                <h3 class="text-white text-xl md:text-2xl font-black">Theater</h3>
            </div>
        </a>
        <a href="{{ route('public.events.index', ['category' => 'festival']) }}" class="relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30 min-h-[200px]">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDT6gQUdeiKktjUlA73E13q6-Q048baorFE_vjVJe-AxUkzoan7Rngk__y9vDaj90KhdvbZde7GbY5ABZvGbFUKudUzwOkNmlYSEK1rJMArPJO3YCxY2GureACBI0222BDt-LwPP91iUyfghPN2am3weBJXFerO8IMhx5iVY9mPMkIkmMx8s-RccMT5YScjglIopASgO8989lriwMwEPcWALlkUTPp7OSlcTl3S32OAyK6o7xpPHKBhaXPrURqjxnp3HQQAnYZ-9HvZ" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6">
                <h3 class="text-white text-xl md:text-2xl font-black">Festivals</h3>
            </div>
        </a>
    </div>
</section>

<!-- Trending Now - Real Events from DB -->
<section class="bg-[#f2f4f6] py-20">
    <div class="max-w-screen-2xl mx-auto px-6">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-[#191c1e] text-3xl font-semibold">Trending Now</h2>
            <a href="{{ route('public.events.index') }}" class="text-[#3525cd] text-sm font-medium flex items-center gap-2 hover:underline">
                View all <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        @if($trendingEvents->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($trendingEvents as $event)
            <a href="{{ route('public.events.show', $event->slug) }}"
               class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 group hover:shadow-lg transition-all flex flex-col">
                <div class="relative h-48 overflow-hidden">
                    @if($event->image)
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" />
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-5xl">event</span>
                        </div>
                    @endif
                    @if($event->ticketTypes->min('price') == 0)
                        <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold uppercase">Free</div>
                    @endif
                </div>
                <div class="p-5 flex-grow border-l-4 border-indigo-600">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-semibold text-[#3525cd] uppercase">{{ $event->category }}</span>
                        <span class="flex items-center gap-1 text-xs text-[#464555]">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            {{ $event->start_date->format('d M') }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 line-clamp-1">{{ $event->title }}</h3>
                    <div class="flex items-center gap-2 text-[#464555] mb-4 text-sm">
                        <span class="material-symbols-outlined text-sm">location_on</span>
                        {{ $event->venue?->name ?? $event->city }}
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-[#777587] block">Starting from</span>
                            @php $minPrice = $event->ticketTypes->min('price'); @endphp
                            <span class="text-[#3525cd] font-semibold text-xl">
                                {{ $minPrice > 0 ? '€' . number_format($minPrice, 2) : 'Free' }}
                            </span>
                        </div>
                        <span class="bg-[#d5e3fd] text-[#57657b] px-4 py-2 rounded-lg text-sm font-medium group-hover:bg-[#3525cd] group-hover:text-white transition-colors">
                            Book
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 text-[#464555]">
            <span class="material-symbols-outlined text-5xl text-[#c7c4d8] block mb-4">event_busy</span>
            <p class="text-lg font-medium">No upcoming events yet. Check back soon!</p>
        </div>
        @endif
    </div>
</section>

<!-- Trust Features -->
<section class="py-24 max-w-screen-2xl mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-[#4f46e5]/10 rounded-2xl flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[#3525cd] text-3xl">verified_user</span>
            </div>
            <h3 class="text-2xl font-semibold mb-4">100% Buyer Guarantee</h3>
            <p class="text-[#464555] text-sm">Every ticket is valid and will be delivered on time. We guarantee your entry or your money back.</p>
        </div>
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-[#d5e3fd]/30 rounded-2xl flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[#515f74] text-3xl">bolt</span>
            </div>
            <h3 class="text-2xl font-semibold mb-4">Instant Digital Delivery</h3>
            <p class="text-[#464555] text-sm">Skip the line and the mail. Most tickets are delivered instantly to your phone for seamless entry.</p>
        </div>
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-[#dae2fd] rounded-2xl flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-[#41485e] text-3xl">support_agent</span>
            </div>
            <h3 class="text-2xl font-semibold mb-4">Dedicated Support</h3>
            <p class="text-[#464555] text-sm">Our expert team is available 24/7 to ensure your event experience is nothing short of perfect.</p>
        </div>
    </div>
</section>

<!-- Newsletter / CTA -->
<section class="max-w-screen-2xl mx-auto px-6 mb-20">
    <div class="bg-[#3525cd] rounded-[2.5rem] p-12 md:p-20 relative overflow-hidden flex flex-col items-center text-center">
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white rounded-full blur-3xl"></div>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 relative z-10 max-w-2xl">Don't miss the next big moment.</h2>
        <p class="text-[#dad7ff] text-lg mb-10 relative z-10 max-w-lg">Get first access to presales, exclusive discounts, and personalized event recommendations delivered to your inbox.</p>
        <div class="flex flex-col sm:flex-row gap-3 w-full max-w-md relative z-10">
            <input class="flex-grow px-6 py-4 rounded-xl border-none focus:ring-2 focus:ring-[#0f0069] bg-white/10 text-white placeholder:text-white/60 backdrop-blur-md" placeholder="Enter your email" type="email"/>
            <button class="bg-white text-[#3525cd] px-8 py-4 rounded-xl font-semibold hover:bg-white/90 transition-all active:scale-95">Subscribe</button>
        </div>
        <p class="text-white/50 text-[10px] mt-6 relative z-10">By subscribing, you agree to our Terms of Service and Privacy Policy.</p>
    </div>
</section>
@endsection
