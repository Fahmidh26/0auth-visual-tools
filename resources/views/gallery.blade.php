@extends('layouts.app')

@section('title', 'My Gallery - Clever Creator AI')

@section('content')
<section>
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-black text-white">My Gallery</h2>
            <p class="text-slate-400 text-sm">All your AI-generated images</p>
        </div>
        <a href="{{ route('nano.visual.tools') }}"
           class="bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 transition-all">
            <span class="material-symbols-outlined">add</span>
            Create New
        </a>
    </div>

    <!-- Gallery Grid -->
    <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Skeleton placeholders while loading -->
        @for ($i = 0; $i < 8; $i++)
        <div class="skeleton-card glass rounded-2xl aspect-square animate-pulse bg-white/5"></div>
        @endfor
    </div>

    <!-- Empty state (hidden by default) -->
    <div id="gallery-empty" class="hidden glass p-12 rounded-2xl text-center">
        <span class="material-symbols-outlined text-5xl text-slate-600 mb-4 block">image_not_supported</span>
        <p class="text-white font-bold text-lg mb-2">No images yet</p>
        <p class="text-slate-400 text-sm mb-6">Generate your first image with our visual tools.</p>
        <a href="{{ route('nano.visual.tools') }}"
           class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-xl font-bold transition-all">
            <span class="material-symbols-outlined">auto_fix</span>
            Open Studio
        </a>
    </div>

    <!-- Error state (hidden by default) -->
    <div id="gallery-error" class="hidden glass p-8 rounded-2xl text-center">
        <span class="material-symbols-outlined text-4xl text-red-400 mb-3 block">error</span>
        <p class="text-red-300 font-bold mb-1">Failed to load gallery</p>
        <p id="gallery-error-msg" class="text-slate-500 text-sm mb-4"></p>
        <button onclick="loadGallery(1)" class="text-primary text-sm font-bold hover:underline">Try again</button>
    </div>

    <!-- Pagination -->
    <div id="gallery-pagination" class="hidden flex justify-center items-center gap-3 mt-10">
        <button id="btn-prev"
                onclick="loadGallery(currentPage - 1)"
                class="flex items-center gap-1 px-4 py-2 rounded-xl glass text-slate-300 hover:text-white hover:bg-white/10 transition-all disabled:opacity-30 disabled:cursor-not-allowed font-medium">
            <span class="material-symbols-outlined text-sm">chevron_left</span>
            Prev
        </button>
        <span id="page-info" class="text-slate-400 text-sm"></span>
        <button id="btn-next"
                onclick="loadGallery(currentPage + 1)"
                class="flex items-center gap-1 px-4 py-2 rounded-xl glass text-slate-300 hover:text-white hover:bg-white/10 transition-all disabled:opacity-30 disabled:cursor-not-allowed font-medium">
            Next
            <span class="material-symbols-outlined text-sm">chevron_right</span>
        </button>
    </div>
</section>

<!-- Lightbox -->
<div id="lightbox" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" onclick="closeLightbox(event)">
    <div class="relative max-w-3xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeLightbox()" class="absolute -top-10 right-0 text-white/70 hover:text-white flex items-center gap-1 text-sm">
            <span class="material-symbols-outlined">close</span> Close
        </button>
        <img id="lightbox-img" src="" alt="" class="w-full rounded-2xl shadow-2xl">
        <div class="mt-4 glass rounded-xl p-4">
            <p id="lightbox-prompt" class="text-slate-300 text-sm leading-relaxed"></p>
            <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                <span id="lightbox-model"></span>
                <span id="lightbox-date"></span>
                <span id="lightbox-tool"></span>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let lastPage = 1;

function showSkeletons() {
    const grid = document.getElementById('gallery-grid');
    grid.innerHTML = '';
    for (let i = 0; i < 8; i++) {
        const div = document.createElement('div');
        div.className = 'skeleton-card glass rounded-2xl aspect-square animate-pulse bg-white/5';
        grid.appendChild(div);
    }
    grid.classList.remove('hidden');
    document.getElementById('gallery-empty').classList.add('hidden');
    document.getElementById('gallery-error').classList.add('hidden');
    document.getElementById('gallery-pagination').classList.add('hidden');
}

function formatDate(iso) {
    const d = new Date(iso);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function openLightbox(image) {
    document.getElementById('lightbox-img').src = image.image_url;
    document.getElementById('lightbox-img').alt = image.prompt || '';
    document.getElementById('lightbox-prompt').textContent = image.prompt || 'No prompt';
    document.getElementById('lightbox-model').textContent = image.model ? '🤖 ' + image.model : '';
    document.getElementById('lightbox-date').textContent = image.created_at ? '📅 ' + formatDate(image.created_at) : '';
    document.getElementById('lightbox-tool').textContent = image.tool ? '🛠 ' + image.tool.name : '';
    document.getElementById('lightbox').classList.remove('hidden');
}

function closeLightbox(event) {
    if (!event || event.target === document.getElementById('lightbox')) {
        document.getElementById('lightbox').classList.add('hidden');
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});

function renderGallery(images) {
    const grid = document.getElementById('gallery-grid');
    grid.innerHTML = '';

    if (!images.length) {
        grid.classList.add('hidden');
        document.getElementById('gallery-empty').classList.remove('hidden');
        document.getElementById('gallery-pagination').classList.add('hidden');
        return;
    }

    grid.classList.remove('hidden');

    images.forEach(function(image) {
        const card = document.createElement('div');
        card.className = 'group relative rounded-2xl overflow-hidden glass aspect-square cursor-pointer hover:ring-2 hover:ring-primary/50 transition-all';
        card.onclick = function() { openLightbox(image); };

        const img = document.createElement('img');
        img.src = image.image_url;
        img.alt = image.prompt || 'Generated image';
        img.className = 'w-full h-full object-cover transition-transform duration-500 group-hover:scale-105';
        img.loading = 'lazy';

        const overlay = document.createElement('div');
        overlay.className = 'absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4';

        const prompt = document.createElement('p');
        prompt.className = 'text-white text-xs font-medium line-clamp-2 mb-1';
        prompt.textContent = image.prompt || 'No prompt';

        const meta = document.createElement('div');
        meta.className = 'flex items-center gap-2 text-slate-400 text-[10px]';

        if (image.tool) {
            const toolBadge = document.createElement('span');
            toolBadge.className = 'bg-primary/20 text-primary px-2 py-0.5 rounded-full font-bold';
            toolBadge.textContent = image.tool.name;
            meta.appendChild(toolBadge);
        }

        if (image.created_at) {
            const dateSpan = document.createElement('span');
            dateSpan.textContent = formatDate(image.created_at);
            meta.appendChild(dateSpan);
        }

        overlay.appendChild(prompt);
        overlay.appendChild(meta);
        card.appendChild(img);
        card.appendChild(overlay);
        grid.appendChild(card);
    });
}

function loadGallery(page) {
    page = page || 1;
    if (page < 1 || page > lastPage) return;

    showSkeletons();

    fetch('/api/gallery?page=' + page + '&per_page=20', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (!data.success) {
            document.getElementById('gallery-grid').classList.add('hidden');
            document.getElementById('gallery-error').classList.remove('hidden');
            document.getElementById('gallery-error-msg').textContent = data.error || 'Unknown error';
            return;
        }

        currentPage = data.meta.current_page;
        lastPage    = data.meta.last_page;

        renderGallery(data.data);

        // Update pagination
        if (data.meta.last_page > 1) {
            document.getElementById('gallery-pagination').classList.remove('hidden');
            document.getElementById('gallery-pagination').classList.add('flex');
            document.getElementById('page-info').textContent = 'Page ' + currentPage + ' of ' + lastPage;
            document.getElementById('btn-prev').disabled = currentPage <= 1;
            document.getElementById('btn-next').disabled = currentPage >= lastPage;
        } else {
            document.getElementById('gallery-pagination').classList.add('hidden');
        }
    })
    .catch(function(err) {
        document.getElementById('gallery-grid').classList.add('hidden');
        document.getElementById('gallery-error').classList.remove('hidden');
        document.getElementById('gallery-error-msg').textContent = err.message;
    });
}

// Load on page ready
loadGallery(1);
</script>
@endsection
