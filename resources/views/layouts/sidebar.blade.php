<!-- Sidebar -->
<aside class="w-72 glass border-r border-white/5 flex flex-col fixed h-screen z-50">
<div class="p-8">
<div class="flex items-center gap-3">
<div class="size-10 rounded-lg bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white">
<span class="material-symbols-outlined font-bold">bolt</span>
</div>
<div>
<h1 class="text-lg font-bold tracking-tight text-white">Clever Creator</h1>
<p class="text-[10px] uppercase tracking-widest text-primary font-semibold">Premium AI Suite</p>
</div>
</div>
</div>
<nav class="flex-1 px-4 space-y-1">
<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors cursor-pointer">
<span class="material-symbols-outlined">dashboard</span>
<span class="text-sm font-medium">Dashboard</span>
</a>
<a href="{{ route('nano.visual.tools') }}" class="{{ request()->routeIs('nano.visual.tools') ? 'sidebar-active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('nano.visual.tools') ? 'text-primary' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors cursor-pointer">
<span class="material-symbols-outlined">auto_awesome</span>
<span class="text-sm font-medium">Image Tools</span>
<span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-primary text-white rounded-full uppercase">New</span>
</a>
<a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'sidebar-active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('gallery') ? 'text-primary' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors cursor-pointer">
<span class="material-symbols-outlined">photo_library</span>
<span class="text-sm font-medium">My Gallery</span>
</a>
<a href="{{ route('community.gallery') }}" class="{{ request()->routeIs('community.gallery') ? 'sidebar-active' : '' }} flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('community.gallery') ? 'text-primary' : 'text-slate-400 hover:text-white hover:bg-white/5' }} transition-colors cursor-pointer">
<span class="material-symbols-outlined">public</span>
<span class="text-sm font-medium">Community Gallery</span>
</a>
<div class="pt-8 pb-2 px-4">
<p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold">Account</p>
</div>
<div class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
<span class="material-symbols-outlined">settings</span>
<span class="text-sm font-medium">Settings</span>
</div>
<div class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
<span class="material-symbols-outlined">help</span>
<span class="text-sm font-medium">Help Center</span>
</div>
</nav>
<div class="p-6">
<div class="rounded-xl p-4 bg-gradient-to-br from-primary/10 to-secondary/10 border border-primary/20">
@if($userData)
@php
    $creditsLeft = $userData['credits_left'] ?? 0;
    $tokensLeft = $userData['tokens_left'] ?? 0;
    // Assuming max credits/tokens (you can adjust these or fetch from API)
    $maxCredits = 500;
    $maxTokens = 10000;
    $creditsPercentage = $maxCredits > 0 ? round(($creditsLeft / $maxCredits) * 100) : 0;
    $tokensPercentage = $maxTokens > 0 ? round(($tokensLeft / $maxTokens) * 100) : 0;
@endphp
<div class="flex justify-between items-center mb-2">
<span class="text-xs font-medium text-slate-300">Token Usage</span>
<span class="text-xs font-bold text-white">{{ $tokensPercentage }}%</span>
</div>
<div class="w-full bg-white/10 rounded-full h-1.5 mb-2 overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: {{ $tokensPercentage }}%"></div>
</div>
<p class="text-[10px] text-slate-400">{{ number_format($tokensLeft) }}/{{ number_format($maxTokens) }} Tokens</p>
<div class="mt-3 pt-3 border-t border-white/10">
<div class="flex justify-between items-center">
<span class="text-xs font-medium text-slate-300">Credits</span>
<span class="text-xs font-bold text-white">{{ $creditsLeft }}</span>
</div>
</div>
@else
<div class="flex justify-between items-center mb-2">
<span class="text-xs font-medium text-slate-300">Token Usage</span>
<span class="text-xs font-bold text-white">--</span>
</div>
<div class="w-full bg-white/10 rounded-full h-1.5 mb-2 overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 0%"></div>
</div>
<p class="text-[10px] text-slate-400">Loading...</p>
@endif
<button class="w-full mt-4 py-2 px-4 rounded-lg bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-sm">add_circle</span>
                        Refill Credits
                    </button>
</div>
</div>
</aside>
