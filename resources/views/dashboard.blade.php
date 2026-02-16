@extends('layouts.app')

@section('title', 'Dashboard - Clever Creator AI')

@section('content')
<!-- Hero Section -->
<section class="relative rounded-3xl overflow-hidden p-12">
<div class="absolute inset-0 bg-gradient-to-r from-primary/20 via-secondary/10 to-transparent z-0"></div>
<div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 z-0"></div>
<div class="relative z-10 max-w-2xl">
<h2 class="text-5xl font-black tracking-tight text-white mb-4">Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">{{ auth()->user()->name ?? 'User' }}!</span></h2>
<p class="text-lg text-slate-400 font-medium">What will you imagine today? Your creative tools are ready and waiting.</p>
</div>
</section>
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="glass p-6 rounded-2xl flex items-center justify-between border-l-4 border-l-primary hover:translate-y-[-4px] transition-transform">
<div>
<p class="text-sm text-slate-400 font-medium mb-1">Images Generated</p>
<p class="text-3xl font-black text-white">342</p>
</div>
<div class="p-3 bg-primary/10 rounded-xl text-primary">
<span class="material-symbols-outlined text-3xl">image</span>
</div>
</div>
<div class="glass p-6 rounded-2xl flex items-center justify-between border-l-4 border-l-secondary hover:translate-y-[-4px] transition-transform">
<div>
<p class="text-sm text-slate-400 font-medium mb-1">Available Tokens</p>
<p class="text-3xl font-black text-white">178</p>
</div>
<div class="p-3 bg-secondary/10 rounded-xl text-secondary">
<span class="material-symbols-outlined text-3xl">toll</span>
</div>
</div>
<div class="glass p-6 rounded-2xl flex items-center justify-between border-l-4 border-l-emerald-500 hover:translate-y-[-4px] transition-transform">
<div>
<p class="text-sm text-slate-400 font-medium mb-1">Total Likes</p>
<p class="text-3xl font-black text-white">1.2k</p>
</div>
<div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-500">
<span class="material-symbols-outlined text-3xl">favorite</span>
</div>
</div>
</div>
<!-- Quick Start Prompt -->
<section class="glass p-8 rounded-3xl border border-primary/20 bg-gradient-to-b from-white/[0.02] to-transparent">
<h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
<span class="material-symbols-outlined text-primary">rocket_launch</span>
                        Quick Start
                    </h3>
<div class="relative group">
<textarea class="w-full bg-background-dark/50 border-white/10 rounded-2xl p-6 pr-32 text-white placeholder:text-slate-600 focus:ring-primary/50 focus:border-primary transition-all resize-none" placeholder="Describe the image you want to create... (e.g., 'Cyberpunk city street at night in 8k resolution, cinematic lighting, neon blue and pink')" rows="3"></textarea>
<button class="absolute bottom-4 right-4 bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 transition-all shadow-lg shadow-primary/20">
<span>Generate</span>
<span class="material-symbols-outlined">auto_fix</span>
</button>
</div>
<div class="flex gap-4 mt-4">
<span class="text-xs text-slate-500">Presets:</span>
<button class="text-[10px] px-3 py-1 bg-white/5 hover:bg-white/10 rounded-full text-slate-400 border border-white/5 transition-colors uppercase font-bold tracking-wide">Photorealistic</button>
<button class="text-[10px] px-3 py-1 bg-white/5 hover:bg-white/10 rounded-full text-slate-400 border border-white/5 transition-colors uppercase font-bold tracking-wide">Oil Painting</button>
<button class="text-[10px] px-3 py-1 bg-white/5 hover:bg-white/10 rounded-full text-slate-400 border border-white/5 transition-colors uppercase font-bold tracking-wide">Cyberpunk</button>
<button class="text-[10px] px-3 py-1 bg-white/5 hover:bg-white/10 rounded-full text-slate-400 border border-white/5 transition-colors uppercase font-bold tracking-wide">3D Render</button>
</div>
</section>
<!-- Recent Generations -->
<section>
<div class="flex justify-between items-end mb-8">
<div>
<h3 class="text-2xl font-black text-white">Recent Generations</h3>
<p class="text-slate-400 text-sm">Your latest creative outputs</p>
</div>
<button class="text-primary text-sm font-bold flex items-center gap-1 hover:underline">
                            View all gallery
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Card 1 -->
<div class="group relative rounded-2xl overflow-hidden glass aspect-square border-none">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-alt="Abstract vibrant nebula AI generation" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD31e_R7BnXWEkjhAuA8SqdCRObvdLA1FmICuCO0tO7WLZqYkOKbM2yvN5A2ZYVcPsroM6ff5pB50Tx3_yqhLoorpOHERhG8JSYeSBhgRsW4LNnEIduR-ElJm-C7HP9ugGil_OLbmurpFONcAtcMWyXkmuoFjijW7u6pMzaRZ-UEvk1Yj1KfV5tEGgxGXlOUxXehXR7FwZ6tAvEYlaifRGJN7QqNTcsE_lewc_vPkCDtQ35Y9Nz3POl7aCXAJAWmvHnuN6QFPQFltw"/>
<div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-5 flex flex-col justify-end">
<p class="text-white text-sm font-bold mb-3 truncate">Cosmic Nebula Abstract</p>
<button class="w-full py-2 bg-primary text-white text-xs font-bold rounded-lg flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-base">high_quality</span>
                                    Enhance
                                </button>
</div>
</div>
<!-- Card 2 -->
<div class="group relative rounded-2xl overflow-hidden glass aspect-square border-none">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-alt="Cyberpunk street samurai aesthetic AI art" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBP6VLBnXkg0mh2lLFTuasX0ATy9t1Sdo0PrGfLs60ad7fuGIWFEWzeKfBMeXHSZnFK8vbs6QohZvEoVVZMV3Xw-KgeAJULgEEEKDlPGOO6ZzDtKcDrwHIlUmORY64UZXM28UnfVAs0_GjnplPl8tL9G2Tlk96aapyj1TbuLt_j4RAZwlP99eZC2nb5jsh83lw_REVb7GsJHChwcmtlmYiVHq_AoJKL94FPwhVv-PHY14JKaGg1pFC-ea8PDOA4SgUP7aWvQOVPsmA"/>
<div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-5 flex flex-col justify-end">
<p class="text-white text-sm font-bold mb-3 truncate">Neon Samurai 2077</p>
<button class="w-full py-2 bg-primary text-white text-xs font-bold rounded-lg flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-base">high_quality</span>
                                    Enhance
                                </button>
</div>
</div>
<!-- Card 3 -->
<div class="group relative rounded-2xl overflow-hidden glass aspect-square border-none">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-alt="Ethereal landscape floating islands AI art" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCgeLLAfm1EJ7Hl7XJZOWHB6jSk6EQoA0stLjg49nWXdE2z_hZrfSZPPOARt9UrkdcS9crwy0l8TdSXkNGs2zvQ-d25pdJhy5Py_QOiMV3CDqtjKMgnjpryJZXa7guETFV7oM7KY9W2Tx1U2qFpEq9PY7nway3C1lQi3_JtrjMlCaO1kcuwfJgC4N2c5831SQNxPFkgyIkp-a7T6kYdawk36vOi1sXm45V7HR3MVedj493aEts4LTtllrlxb3pFgx140lGXG0937M8"/>
<div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-5 flex flex-col justify-end">
<p class="text-white text-sm font-bold mb-3 truncate">Floating Islands Ethereal</p>
<button class="w-full py-2 bg-primary text-white text-xs font-bold rounded-lg flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-base">high_quality</span>
                                    Enhance
                                </button>
</div>
</div>
<!-- Card 4 -->
<div class="group relative rounded-2xl overflow-hidden glass aspect-square border-none">
<img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-alt="Futuristic glass architecture minimal AI art" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAcd5t05N1BxIWSMYg_UNmi1KHVdPlL5JGZI-aMBURvx1xsospAoqnHYzBO2i42f7mupza6FXIebPB_I-HWhY4geCvvsrHWaDDvyrHbjRoC2RQBU3nJBNOI0ZvHPI028dWNgqS-trchE-xDdTNGPvrW5hAugv4zqv8T664M7pwiSlR6zAy86mG7Kv9CSL6Ynt2Yzd-2wqFr_MwEaRPyG9kRxQ54YNZi8AiayJ-gq2v8ipAoxlDbRIe80Q4SeC01Q9r79_z8LFOb9bo"/>
<div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-5 flex flex-col justify-end">
<p class="text-white text-sm font-bold mb-3 truncate">Minimalist Architecture</p>
<button class="w-full py-2 bg-primary text-white text-xs font-bold rounded-lg flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-base">high_quality</span>
                                    Enhance
                                </button>
</div>
</div>
</div>
</section>
@endsection