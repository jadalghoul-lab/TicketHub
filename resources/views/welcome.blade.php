@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[600px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCuR1gsqHmbw75dRDn3DqpT4j01I6Pv8JR_4Y7yFWvWfa9-Suri6idFE1QlXDVoVu2Pr6txujET1kfI21XN6jjSxGUnW6EO5YCrhI_vHNmU9y09-H-fupzN77Ta3FCzWyBKqS1rnMTRgoF97pdUxkKx2lZl-ZLsVIl-9coK5NIFMYNo30MrrYewadFrXIApPOVBv4Y4k9G6HhHpkeXlX2iO2muyeRG3_bIljPenm5H7Z3WONGRiMnRHcWDLN9KvbOreTfE-QbvigxAH" alt="Hero background" />
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px]"></div>
    </div>
    <div class="relative z-10 w-full max-w-4xl px-6 text-center">
        <h1 class="text-white text-5xl md:text-6xl mb-6 drop-shadow-lg font-bold">Experience more than just a seat.</h1>
        <p class="text-white/90 mb-10 max-w-2xl mx-auto drop-shadow-md text-lg">Unlock access to the world's most sought-after concerts, sporting events, and theater performances with guaranteed security and seamless delivery.</p>
        
        <div class="bg-white/95 backdrop-blur shadow-2xl p-2 rounded-2xl flex flex-col md:flex-row gap-2 max-w-3xl mx-auto border border-white/20">
            <div class="flex-grow flex items-center px-4 py-3 gap-3">
                <span class="material-symbols-outlined text-[#777587]">search</span>
                <input class="w-full bg-transparent border-none focus:ring-0 text-[#191c1e]" placeholder="Find your next event..." type="text"/>
            </div>
            <div class="w-px bg-slate-200 hidden md:block my-2"></div>
            <div class="flex items-center px-4 py-3 gap-3">
                <span class="material-symbols-outlined text-[#777587]">calendar_month</span>
                <span class="text-[#464555] font-medium whitespace-nowrap text-sm">Any Date</span>
            </div>
            <button class="bg-[#3525cd] text-white px-8 py-3 rounded-xl flex items-center justify-center gap-2 hover:bg-[#3525cd]/90 transition-all active:scale-95 font-semibold">
                Search
            </button>
        </div>
    </div>
</section>

<!-- Categories Bento Grid -->
<section class="py-20 px-6 max-w-screen-2xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
        <div>
            <span class="text-[#3525cd] text-sm font-medium tracking-widest uppercase">Categories</span>
            <h2 class="text-[#191c1e] text-3xl font-semibold mt-2">Explore Your Passions</h2>
        </div>
        <button class="text-[#3525cd] text-sm font-medium flex items-center gap-1 hover:gap-2 transition-all">
            View All Categories <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-4 h-[600px]">
        <div class="md:col-span-2 md:row-span-2 relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCktb3_b-lkBwOA69AfR0Hq9UFOy0W8fyDrZajblcCPceegpnXHX4B5nT5Ql5gd8Dzb3BOeWoDUaCwnXGwR-apojf4qzVQ9FioyaHzcBUVCwAajjtkKS6AAIeitLxqdIDOsZfL_u9LwElHRnXJhnKuIPRsnfZUx9nQ3B9BXvu-ULDnpdP5RY3G1AVzoYrXyOC9MgMVujM32obCJnhWEy2YcTIjAoUMXGQtREoQdh82Yk8P8hWldKg_DJ1PVVvVfhT-rJJdxgHOjq6O8" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-8 left-8">
                <h3 class="text-white text-3xl font-semibold mb-2">Concerts & Live Music</h3>
                <p class="text-white/80 text-sm">From stadiums to intimate clubs.</p>
            </div>
        </div>
        <div class="md:col-span-2 relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7jKwMzxKLdIfO29ptdOXB1nLMa9bL-V0KkvANzpWzfLQupIAsDpTfMdmp5KV8tLCWD87n5QdCjLiMT_J39SvxRUDsezT_izUTImsFgCSutTprSTh-mgPEWQTefW7KQUEYTDXFDIUYyvBq5YKqXobZN9G7kPA7uCFyDsDSZPaZiijR2Tvwo0YTez_qgWHRxbksCXCvxF9lC-lWDyl3Dnr_Zfq_cjykZZ20xMqpR_OL-HVXq2J1GIW8bdjPlJ00YGkc77U05XJ5jgeU" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6">
                <h3 class="text-white text-2xl font-semibold">Sports & Athletics</h3>
            </div>
        </div>
        <div class="relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCsVIij4Kz8cLw9rYfmulKBaOvoGnkfYpPdI9coVQclBgEYbuC6IF7mMqtPfbbumAibKrBhANstuq3omYDZPwWoTkOTYMcIFvQCwT0OzqLIumyag0T4bYq2vP3rcNm5NXLRzsrg4NPk7JyEo4yQ6a0okUJDeC7jT-5f740aCsiUm_E3gFj3RXJtN4GKn_f7YlU3K7Nx1lh7cGkCaMzPZ9XtC2Ct5Bap9Lks4fGng4DQuE853fEharsMVM28eiKwFTqYCMsY7GDYoNII" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6">
                <h3 class="text-white text-2xl font-semibold">Theater</h3>
            </div>
        </div>
        <div class="relative rounded-3xl overflow-hidden group cursor-pointer shadow-sm border border-[#c7c4d8]/30">
            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDT6gQUdeiKktjUlA73E13q6-Q048baorFE_vjVJe-AxUkzoan7Rngk__y9vDaj90KhdvbZde7GbY5ABZvGbFUKudUzwOkNmlYSEK1rJMArPJO3YCxY2GureACBI0222BDt-LwPP91iUyfghPN2am3weBJXFerO8IMhx5iVY9mPMkIkmMx8s-RccMT5YScjglIopASgO8989lriwMwEPcWALlkUTPp7OSlcTl3S32OAyK6o7xpPHKBhaXPrURqjxnp3HQQAnYZ-9HvZ" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-6 left-6">
                <h3 class="text-white text-2xl font-semibold">Festivals</h3>
            </div>
        </div>
    </div>
</section>

<!-- Trending Listings -->
<section class="bg-[#f2f4f6] py-20">
    <div class="max-w-screen-2xl mx-auto px-6">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-[#191c1e] text-3xl font-semibold">Trending Now</h2>
            <div class="flex gap-2">
                <button class="p-2 border border-[#c7c4d8] rounded-full hover:bg-white transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="p-2 border border-[#c7c4d8] rounded-full hover:bg-white transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Ticket Card 1 -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 group hover:shadow-lg transition-all flex flex-col">
                <div class="relative h-48 overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCYKCWxaB_E19q22k9tyXCw3LdAUh9PfSfWDXIn_nspPl0Vc0vFkQQesKKjG6DhIRDaRTBg97afRcHDhiHWQyzOt4GqaVMMZ5NXESf4FO6phxT5BU5PzyZsnV10_OyvgoIIKYzWWg1ImI0whwgno-AK4ccYeZpAIhIG8lUBN7IAIcYe2yPGdPdA6IFtAFrZPbLybPUIsCQttMP7ZzFKkBzXA2-XcGPlGHEfEVP6qhLAeUH7cIVgzwMkaohSZCMcVqwKUPXUu1OcYw3J" />
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-[#3525cd] uppercase tracking-tighter shadow-sm">Hot</div>
                </div>
                <div class="p-5 flex-grow border-l-4 border-indigo-600">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-semibold text-[#3525cd] uppercase">Music • Pop</span>
                        <span class="flex items-center gap-1 text-xs font-semibold text-[#464555]">
                            <span class="material-symbols-outlined text-sm">star</span> 4.9
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 line-clamp-1">The Midnight Odyssey Tour</h3>
                    <div class="flex items-center gap-2 text-[#464555] mb-4 text-sm"><span class="material-symbols-outlined text-sm">location_on</span> Sportpaleis, Antwerp</div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-[#777587] block">Starting from</span>
                            <span class="text-[#3525cd] font-semibold text-xl">€149.00</span>
                        </div>
                        <button class="bg-[#d5e3fd] text-[#57657b] px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#3525cd] hover:text-white transition-colors">
                            Book
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Ticket Card 2 -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 group hover:shadow-lg transition-all flex flex-col">
                <div class="relative h-48 overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDiAJ7Svm3hE41o6LBGRcnggpA9W1TMxcE2tD4PYgA_faN_ro2qqoL_bvGENYSV4UGvztNo5dunAVldPKrCZKSgb2VUkXMCOjPOh7_HaM--MzTKuSxpT0hgaS4D5iCRfv3v3HImN3H11Mfb4ACZZ5N-jVJhDBl_Ndw4KqLvB4SiXM1sKbwTb9MDQ7tjN4VisRqPV4FMdyeGELbnymhcRIgwc71VxIag_WW_b-6MYG0f0bJ4TyFlOwI_tVwGdhXG_2-Zn9O5Ab0HDMe9" />
                </div>
                <div class="p-5 flex-grow border-l-4 border-[#41485e]">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-semibold text-[#41485e] uppercase">Sports • Finals</span>
                        <span class="flex items-center gap-1 text-xs font-semibold text-[#464555]">
                            <span class="material-symbols-outlined text-sm">star</span> 5.0
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 line-clamp-1">European Championship Final</h3>
                    <div class="flex items-center gap-2 text-[#464555] mb-4 text-sm"><span class="material-symbols-outlined text-sm">location_on</span> King Baudouin Stadium, Brussels</div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-[#777587] block">Starting from</span>
                            <span class="text-[#3525cd] font-semibold text-xl">€850.00</span>
                        </div>
                        <button class="bg-[#d5e3fd] text-[#57657b] px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#3525cd] hover:text-white transition-colors">
                            Book
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Ticket Card 3 -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 group hover:shadow-lg transition-all flex flex-col">
                <div class="relative h-48 overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrmBb2u2mPve2cHXGnn45yurde_YT3F5tux9-zNtjnX01UNN_TXPLX9ZQmgjlc1DdF7ypgmsMa2dsvgWawOYTKqaBn1y-sdiLnZakjFwDjp1Ydn2bguZb_mW6ZWKOVijR1iWC5AVM9jQCb1gqiFccBacn5iC0QkdeC-3hl2rwETQEZv_5csbRZgUQe_glIvgMiOHJAI4Ape2pUbzru8xCDl3d7MPAL7GO6Z6dO2pdrcq8O6cnuJCypcIe0Qb3j628_t9Bve7J92o9e" />
                </div>
                <div class="p-5 flex-grow border-l-4 border-[#ba1a1a]">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-semibold text-[#ba1a1a] uppercase">Theater • Musical</span>
                        <span class="flex items-center gap-1 text-xs font-semibold text-[#464555]">
                            <span class="material-symbols-outlined text-sm">star</span> 4.8
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 line-clamp-1">Echoes of the Opera</h3>
                    <div class="flex items-center gap-2 text-[#464555] mb-4 text-sm"><span class="material-symbols-outlined text-sm">location_on</span> Théâtre Royal de la Monnaie, Brussels</div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-[#777587] block">Starting from</span>
                            <span class="text-[#3525cd] font-semibold text-xl">€120.00</span>
                        </div>
                        <button class="bg-[#d5e3fd] text-[#57657b] px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#3525cd] hover:text-white transition-colors">
                            Book
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Ticket Card 4 -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 group hover:shadow-lg transition-all flex flex-col">
                <div class="relative h-48 overflow-hidden">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCmWQt9DDAFdqyknnD7SitIIjyLe-sBBKifsf2Zi0P2U3NFmCM6LjYLpcMB9ARmjuYqdaaspc6VeLL-4-uqRMOM9gnbSJmZzDih5rrvJAH5-0zvkc1UkXjFJfiOu2hX-osXXgLOqlCA2jhu5y0HbKqgeB0OBIM4DSK0CgADCuconms9QaNGaOPxlEx4Ci13ln07sJo28NRWVGBl9wYuRLoVh9S_NuNrktqAf3IQ7r9txzk5_RHZPfh4DBt8CxI1u3oelL62c-aveU5y" />
                </div>
                <div class="p-5 flex-grow border-l-4 border-[#57657b]">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-semibold text-[#57657b] uppercase">Events • Tech</span>
                        <span class="flex items-center gap-1 text-xs font-semibold text-[#464555]">
                            <span class="material-symbols-outlined text-sm">star</span> 4.7
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 line-clamp-1">Neon Pulse: Future Beats</h3>
                    <div class="flex items-center gap-2 text-[#464555] mb-4 text-sm"><span class="material-symbols-outlined text-sm">location_on</span> Fuse, Brussels</div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xs text-[#777587] block">Starting from</span>
                            <span class="text-[#3525cd] font-semibold text-xl">€65.00</span>
                        </div>
                        <button class="bg-[#d5e3fd] text-[#57657b] px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#3525cd] hover:text-white transition-colors">
                            Book
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
        <!-- Abstract pattern overlays -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-80 h-80 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white rounded-full blur-3xl"></div>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 relative z-10 max-w-2xl">Don't miss the next big moment.</h2>
        <p class="text-[#dad7ff] text-lg mb-10 relative z-10 max-w-lg">Get first access to presales, exclusive discounts, and personalized event recommendations delivered to your inbox.</p>
        <div class="flex flex-col sm:flex-row gap-3 w-full max-w-md relative z-10">
            <input class="flex-grow px-6 py-4 rounded-xl border-none focus:ring-2 focus:ring-[#0f0069] bg-white/10 text-white placeholder:text-white/60 backdrop-blur-md" placeholder="Enter your email" type="email"/>
            <button class="bg-white text-[#3525cd] px-8 py-4 rounded-xl text-md font-semibold hover:bg-white/90 transition-all active:scale-95">Subscribe</button>
        </div>
        <p class="text-white/50 text-[10px] mt-6 relative z-10">By subscribing, you agree to our Terms of Service and Privacy Policy.</p>
    </div>
</section>
@endsection
