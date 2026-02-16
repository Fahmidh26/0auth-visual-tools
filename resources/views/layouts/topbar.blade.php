<!-- Top Bar -->
<header class="h-20 glass sticky top-0 z-40 px-10 border-b border-white/5 flex items-center justify-between">
<div class="flex-1 max-w-xl">

</div>
<div class="flex items-center gap-6">
<div class="hidden lg:flex items-center gap-4 px-4 py-2 rounded-xl bg-white/5 border border-white/10">
<div class="text-right">
<p class="text-[10px] font-bold text-slate-400 uppercase leading-none">Balance</p>
<p class="text-sm font-bold text-white">120 / 500 <span class="text-primary tracking-tighter ml-1">Credits</span></p>
</div>
<div class="h-8 w-px bg-white/10"></div>
<button class="p-1.5 rounded-lg hover:bg-white/10 transition-colors text-slate-300">
<span class="material-symbols-outlined">refresh</span>
</button>
</div>
<div class="flex items-center gap-3">
<div class="relative">
<button class="p-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white transition-colors">
<span class="material-symbols-outlined">notifications</span>
</button>
<span class="absolute top-2 right-2 size-2 bg-secondary rounded-full border-2 border-background-dark"></span>
</div>
<div class="flex items-center gap-3 pl-4 border-l border-white/10">
<div class="text-right">
<p class="text-sm font-bold text-white">{{ auth()->user()->name ?? 'User' }}</p>
<p class="text-[10px] text-primary font-medium">Pro Plan</p>
</div>
<div class="size-10 rounded-xl bg-gradient-to-tr from-primary to-secondary p-0.5">
<div class="w-full h-full rounded-[10px] overflow-hidden bg-background-dark">
<img class="w-full h-full object-cover" data-alt="User profile avatar portrait" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBMYzj3P7sWCBwO4pRDXDT3IhXMdYYTeiRmevYL7qO7tN_hfdFS3c_3dvWIJAzR3V0UzS6BzIBgrFsXC-TU1keMFMor-GZV_9L6Qg3mwbK0uFvjixm51mZR9ENlo4DKK4mMw9Dma_IsDb6y49hyDyuzibNwJwqRPCV8EIc_NXiFpNiR9ybyUt9gJPYJX259VN4QdotfActWg1lRmoIr08k6_vyLwMp-Znyb478OdjPgIoBMFa63N0a_f0CtuuR9QhqvnSZIdy9j3ZM"/>
</div>
</div>
</div>
</div>
</div>
</header>
