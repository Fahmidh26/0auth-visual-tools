@extends('layouts.app')

@section('title', 'My Gallery - Clever Creator AI')

@section('content')
<!-- Gallery Page Content -->
<section>
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-black text-white">My Gallery</h2>
            <p class="text-slate-400 text-sm">View all your generated images</p>
        </div>
        <button class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 transition-all">
            <span class="material-symbols-outlined">add</span>
            Create New
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Your gallery content here -->
        <div class="glass p-6 rounded-2xl text-center">
            <p class="text-slate-400">No images yet</p>
        </div>
    </div>
</section>
@endsection
