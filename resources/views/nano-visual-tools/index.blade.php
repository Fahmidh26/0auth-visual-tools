@extends('layouts.app')

@section('title', 'Image Tools - Clever Creator AI')

@push('styles')
<style>
    .view-toggle button.active {
        background: rgba(19, 164, 236, 0.2);
        color: #13a4ec;
    }
    .tool-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        backdrop-filter: blur(12px);
    }
    .tool-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(19, 164, 236, 0.15), 0 2px 6px rgba(0, 0, 0, 0.1);
        border-color: rgba(19, 164, 236, 0.4) !important;
    }
    .tool-card:active {
        transform: translateY(0);
    }
    .glass-panel {
        background: rgba(22, 27, 34, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(19, 164, 236, 0.3);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(19, 164, 236, 0.5);
    }
    .list-view .tool-card {
        display: flex;
        align-items: center;
        gap: 0;
        overflow: visible;
    }
    .list-view .tool-card .tool-thumbnail {
        border-radius: 0.75rem 0 0 0.75rem;
    }
    .list-view .tool-card .tool-icon {
        flex-shrink: 0;
    }
    .list-view .tool-card .tool-content {
        flex: 1;
        min-width: 0;
    }
    .tool-thumbnail {
        position: relative;
        width: 100%;
        padding-bottom: 56%;
        background: linear-gradient(135deg, rgba(19, 164, 236, 0.08) 0%, rgba(139, 92, 246, 0.08) 100%);
        border-radius: 0.75rem 0.75rem 0 0;
        overflow: hidden;
        margin-bottom: 0;
    }
    .tool-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .tool-thumbnail .icon-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .tool-thumbnail img {
        transition: transform 0.3s ease;
    }
    .tool-card:hover .tool-thumbnail img {
        transform: scale(1.05);
    }
    .list-view .tool-thumbnail {
        width: 100px;
        padding-bottom: 65px;
        margin-bottom: 0;
        flex-shrink: 0;
    }
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(10, 10, 12, 0.8);
        backdrop-filter: blur(8px);
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-content {
        background: rgba(22, 27, 34, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.5rem;
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        padding: 2rem;
    }
    .image-preview {
        position: relative;
        padding-bottom: 100%;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .image-preview img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="h-full">
<!-- Directory/Grid View -->
<div id="directoryView">
<!-- Search and Filter Bar -->
<div class="glass p-4 rounded-xl mb-6 border border-white/5">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1 relative group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary transition-colors">search</span>
            <input
                type="text"
                id="toolSearch"
                class="w-full bg-white/5 border-white/10 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-primary focus:border-primary transition-all placeholder:text-slate-600"
                placeholder="Search tools by name or description..."
            />
        </div>

        <!-- View Toggle -->
        <div class="view-toggle flex items-center gap-2 bg-white/5 p-1 rounded-lg">
            <button
                id="gridViewBtn"
                class="active p-2 rounded-lg transition-all"
                onclick="setView('grid')"
            >
                <span class="material-symbols-outlined text-sm">grid_view</span>
            </button>
            <button
                id="listViewBtn"
                class="p-2 rounded-lg transition-all"
                onclick="setView('list')"
            >
                <span class="material-symbols-outlined text-sm">view_list</span>
            </button>
        </div>
    </div>
</div>

<!-- Tools Container -->
<div id="toolsContainer">
    <div class="glass p-8 rounded-2xl text-center">
        <div class="inline-block p-4 bg-primary/10 rounded-xl mb-4">
            <span class="material-symbols-outlined text-4xl text-primary">autorenew</span>
        </div>
        <p class="text-slate-400">Loading available tools...</p>
    </div>
</div>
</div>

<!-- Tool Interface View -->
<div id="toolInterfaceView" class="hidden -m-10">
    <div class="flex min-h-screen overflow-hidden">
        <!-- Left Configuration Panel -->
        <section class="w-80 lg:w-96 glass-panel border-r border-white/5 flex flex-col overflow-hidden">
            <!-- Header with Back Button -->
            <div class="p-4 border-b border-white/5 flex items-center gap-3 flex-shrink-0">
                <button onclick="backToDirectory()" class="p-2 hover:bg-white/5 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-slate-400 hover:text-white">arrow_back</span>
                </button>
                <div>
                    <h2 id="toolInterfaceTitle" class="font-bold text-white text-sm"></h2>
                    <p class="text-[10px] text-slate-500">Configure & Generate</p>
                </div>
            </div>

            <!-- Form Content - Scrollable -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-4">
                <form id="toolInterfaceForm" class="space-y-4">
                    <input type="hidden" id="interfaceToolId" name="tool_id">
                    <input type="hidden" id="interfaceToolSlug" name="tool">

                    <!-- Dynamic Form Content -->
                    <div id="interfaceFormContent"></div>

                    <!-- Generate Button -->
                    <button
                        type="submit"
                        id="interfaceGenerateBtn"
                        class="w-full py-2.5 bg-primary hover:bg-primary/90 rounded-lg font-bold text-sm text-white shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group mt-4"
                    >
                        <span class="material-symbols-outlined text-lg group-hover:animate-pulse">bolt</span>
                        GENERATE
                    </button>

                    <!-- Status Message -->
                    <div id="interfaceFormStatus" class="hidden p-3 rounded-lg text-xs"></div>
                </form>
            </div>
        </section>

        <!-- Right Preview Area -->
        <section class="flex-1 bg-black/40 flex flex-col items-center justify-center p-6 relative overflow-hidden">
            <!-- Preview Controls -->
            <div id="previewControls" class="absolute top-4 right-4 flex items-center gap-2 z-20 hidden">
                <div class="bg-background-dark/90 backdrop-blur-md border border-white/10 rounded-lg flex p-1 shadow-xl">
                    <button class="p-2 hover:bg-white/5 rounded-md text-slate-400 hover:text-white transition-colors" onclick="downloadCurrentImage()" title="Download">
                        <span class="material-symbols-outlined text-xl">download</span>
                    </button>
                    <button class="p-2 hover:bg-white/5 rounded-md text-slate-400 hover:text-white transition-colors" onclick="shareCurrentImage()" title="Share">
                        <span class="material-symbols-outlined text-xl">share</span>
                    </button>
                </div>
            </div>

            <!-- Preview Content -->
            <div id="previewContent" class="w-full h-full flex items-center justify-center">
                <div class="text-center">
                    <div class="inline-block p-5 bg-primary/10 rounded-xl mb-3">
                        <span class="material-symbols-outlined text-5xl text-primary">image</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-1">Preview Area</h3>
                    <p class="text-sm text-slate-400">Your generated image will appear here</p>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Tool Modal (Hidden - kept for backwards compatibility) -->
<div id="toolModal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex items-center justify-between mb-6">
            <h3 id="modalToolName" class="text-2xl font-bold text-white">Tool Name</h3>
            <button onclick="closeToolModal()" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-slate-400">close</span>
            </button>
        </div>

        <form id="runToolForm" class="space-y-6">
            <input type="hidden" id="toolId" name="tool_id">
            <input type="hidden" id="toolSlug" name="tool">

            <!-- Prompt Input -->
            <div id="promptGroup" style="display: none;">
                <label class="block text-sm font-medium text-slate-300 mb-2">Prompt</label>
                <textarea
                    id="prompt"
                    name="prompt"
                    rows="3"
                    class="w-full bg-white/5 border-white/10 rounded-xl p-4 text-white placeholder:text-slate-600 focus:ring-primary focus:border-primary transition-all resize-none"
                    placeholder="Enter your prompt here..."
                ></textarea>
                <p id="promptHelp" class="text-xs text-slate-500 mt-2"></p>
            </div>

            <!-- Prefix Text -->
            <div id="prefixTextGroup" style="display: none;">
                <label class="block text-sm font-medium text-slate-300 mb-2">Prefix Text (Optional)</label>
                <input
                    type="text"
                    id="prefixText"
                    name="prefix_text"
                    class="w-full bg-white/5 border-white/10 rounded-xl p-3 text-white placeholder:text-slate-600 focus:ring-primary focus:border-primary transition-all"
                    placeholder="e.g., Change color to"
                >
            </div>

            <!-- Image Uploads Container -->
            <div id="imageUploadsContainer"></div>

            <!-- Features Container -->
            <div id="featuresContainer"></div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-white/10">
                <button
                    type="submit"
                    id="runToolBtn"
                    class="flex-1 bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined">auto_fix</span>
                    Generate Image
                </button>
                <button
                    type="button"
                    onclick="closeToolModal()"
                    class="px-6 py-3 bg-white/5 hover:bg-white/10 text-slate-300 rounded-xl font-medium transition-all"
                >
                    Cancel
                </button>
            </div>

            <!-- Status Message -->
            <div id="formStatus" class="hidden p-4 rounded-xl"></div>
        </form>
    </div>
</div>

<!-- Generated Images Gallery (Hidden - using preview area instead) -->
<div id="imageGallerySection" class="mt-8 hidden">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-white">Generated Images</h3>
        <button id="clearGalleryBtn" onclick="clearGallery()" class="text-sm text-slate-400 hover:text-white transition-colors">
            Clear Gallery
        </button>
    </div>
    <div id="imageGallery" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
</div>
</div>
@endsection

@push('scripts')
<script>
    let availableTools = [];
    let selectedTool = null;
    let currentView = 'grid';

    // Load tools on page load
    async function loadTools() {
        const container = document.getElementById('toolsContainer');

        try {
            const response = await fetch('{{ route("api.nano.visual.tools.get") }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.error || 'Failed to load tools');
            }

            availableTools = data.data || [];
            renderTools(availableTools);
        } catch (e) {
            container.innerHTML = `
                <div class="glass p-8 rounded-2xl border border-red-500/20 bg-red-500/5">
                    <div class="flex items-center gap-3 text-red-400">
                        <span class="material-symbols-outlined">error</span>
                        <p>Error: ${e.message}</p>
                    </div>
                </div>
            `;
        }
    }

    function renderTools(tools) {
        const container = document.getElementById('toolsContainer');

        if (tools.length === 0) {
            container.innerHTML = `
                <div class="glass p-8 rounded-2xl text-center">
                    <p class="text-slate-400">No tools available at the moment.</p>
                </div>
            `;
            return;
        }

        const viewClass = currentView === 'list' ? 'list-view' : '';
        const gridClass = currentView === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4' : 'space-y-3';

        container.innerHTML = `
            <div class="${gridClass} ${viewClass}">
                ${tools.map(tool => {
                    const thumbnailHtml = tool.preview_image
                        ? `<img src="${escapeHtml(tool.preview_image)}"
                               alt="${escapeHtml(tool.name)}"
                               loading="lazy"
                               onerror="this.style.display='none'; this.parentElement.innerHTML = '<div class=\\'icon-placeholder\\'><div class=\\'size-12 rounded-lg bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center\\'><span class=\\'material-symbols-outlined text-3xl text-primary\\'>auto_awesome</span></div></div>';">`
                        : `<div class="icon-placeholder">
                            <div class="size-12 rounded-lg bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl text-primary">auto_awesome</span>
                            </div>
                           </div>`;

                    return `
                        <div class="tool-card glass rounded-xl border border-white/5 hover:border-primary/30 cursor-pointer transition-all overflow-hidden" onclick="selectTool(${tool.id})">
                            <div class="tool-thumbnail">
                                ${thumbnailHtml}
                            </div>
                            <div class="tool-content p-4">
                                <h3 class="text-base font-bold text-white mb-1.5">${escapeHtml(tool.name)}</h3>
                                <p class="text-xs text-slate-400 mb-3 line-clamp-2 leading-relaxed">${escapeHtml(tool.description || 'No description available')}</p>
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-semibold text-primary/90 bg-primary/10 px-2 py-1 rounded">${tool.credits_per_generation || 2} credits</span>
                                    <button class="px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">play_arrow</span>
                                        Use
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('')}
            </div>
        `;
    }

    function selectTool(toolId) {
        selectedTool = availableTools.find(t => t.id === toolId);
        if (!selectedTool) return;

        // Hide directory view and show interface view
        document.getElementById('directoryView').classList.add('hidden');
        document.getElementById('toolInterfaceView').classList.remove('hidden');

        // Set tool info
        document.getElementById('toolInterfaceTitle').textContent = selectedTool.name;
        document.getElementById('interfaceToolId').value = selectedTool.id;
        document.getElementById('interfaceToolSlug').value = selectedTool.slug;

        // Setup form fields
        setupInterfaceForm(selectedTool);

        // Reset preview
        resetPreview();
    }

    function backToDirectory() {
        document.getElementById('toolInterfaceView').classList.add('hidden');
        document.getElementById('directoryView').classList.remove('hidden');
        document.getElementById('toolInterfaceForm').reset();
        selectedTool = null;
    }

    function resetPreview() {
        const previewContent = document.getElementById('previewContent');
        previewContent.innerHTML = `
            <div class="text-center">
                <div class="inline-block p-5 bg-primary/10 rounded-xl mb-3">
                    <span class="material-symbols-outlined text-5xl text-primary">image</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Preview Area</h3>
                <p class="text-sm text-slate-400">Your generated image will appear here</p>
            </div>
        `;
        document.getElementById('previewControls').classList.add('hidden');
    }

    function setupInterfaceForm(tool) {
        const formContent = document.getElementById('interfaceFormContent');
        formContent.innerHTML = '';

        let sectionNumber = 1;

        // Prompt field
        if (tool.prompt_required) {
            const promptDiv = document.createElement('div');
            promptDiv.innerHTML = `
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">${sectionNumber}. Prompt</label>
                <textarea
                    id="interface_prompt"
                    name="prompt"
                    rows="2"
                    required
                    class="w-full bg-white/5 border-white/10 rounded-lg p-2.5 text-sm text-white placeholder:text-slate-600 focus:ring-primary focus:border-primary transition-all resize-none"
                    placeholder="${escapeHtml(tool.prompt_placeholder || 'Enter your prompt...')}"
                ></textarea>
                ${tool.default_prompt ? `<p class="text-[10px] text-slate-500 mt-1">Default: ${escapeHtml(tool.default_prompt)}</p>` : ''}
            `;
            formContent.appendChild(promptDiv);
            sectionNumber++;
        }

        // Image uploads
        if (tool.image_uploads && tool.image_uploads.length > 0) {
            const uploadsWrapper = document.createElement('div');
            uploadsWrapper.innerHTML = `
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">
                    ${sectionNumber}. Image Uploads
                </label>
            `;

            const uploadsGrid = document.createElement('div');
            uploadsGrid.className = 'grid grid-cols-3 gap-3';

            tool.image_uploads.forEach(upload => {
                const uploadDiv = document.createElement('div');
                uploadDiv.className = 'space-y-1';
                uploadDiv.innerHTML = `
                    <label class="text-[10px] font-medium text-slate-300 block">
                        ${escapeHtml(upload.label || upload.name)}
                        ${upload.required ? '<span class="text-red-400">*</span>' : ''}
                    </label>
                    <div class="relative group">
                        <div class="aspect-[4/3] rounded-lg border-2 border-dashed border-white/10 hover:border-primary/50 transition-colors flex flex-col items-center justify-center bg-white/5 cursor-pointer overflow-hidden">
                            <input
                                type="file"
                                id="interface_upload_${upload.name}"
                                name="${upload.name}"
                                accept="image/*"
                                ${upload.required ? 'required' : ''}
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="previewUploadedImage(this, 'preview_${upload.name}')"
                            >
                            <div id="preview_${upload.name}" class="absolute inset-0 hidden">
                                <img class="w-full h-full object-cover" alt="Preview">
                            </div>
                            <div class="relative z-0 flex flex-col items-center pointer-events-none">
                                <span class="material-symbols-outlined text-primary text-lg mb-1">cloud_upload</span>
                                <span class="text-[10px] font-medium">Upload</span>
                                <span class="text-[8px] text-slate-500 mt-0.5">PNG/JPG</span>
                            </div>
                        </div>
                    </div>
                    ${upload.description ? `<p class="text-[9px] text-slate-500 mt-1 leading-tight">${escapeHtml(upload.description)}</p>` : ''}
                `;
                uploadsGrid.appendChild(uploadDiv);
            });

            uploadsWrapper.appendChild(uploadsGrid);
            formContent.appendChild(uploadsWrapper);
            sectionNumber++;
        }

        // Features
        if (tool.features && tool.features.length > 0) {
            const featuresDiv = document.createElement('div');
            featuresDiv.className = 'space-y-4';
            featuresDiv.innerHTML = `<label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">${sectionNumber}. Settings</label>`;

            tool.features.forEach(feature => {
                const featureDiv = document.createElement('div');
                featureDiv.className = 'space-y-1.5';

                let inputHtml = '';
                if (feature.type === 'select') {
                    inputHtml = `
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] font-medium">${escapeHtml(feature.label || feature.name)}</span>
                        </div>
                        <select
                            id="interface_feature_${feature.name}"
                            name="features[${feature.name}]"
                            class="w-full bg-white/5 border-white/10 rounded-lg p-2 text-sm text-white focus:ring-primary focus:border-primary transition-all"
                        >
                            ${(feature.options || []).map(opt => `
                                <option value="${escapeHtml(opt)}" ${opt === feature.default ? 'selected' : ''}>
                                    ${escapeHtml(opt)}
                                </option>
                            `).join('')}
                        </select>
                    `;
                } else if (feature.type === 'number' || feature.type === 'range') {
                    const value = feature.default || feature.min || 50;
                    inputHtml = `
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] font-medium">${escapeHtml(feature.label || feature.name)}</span>
                            <span class="text-[10px] text-primary font-bold" id="interface_feature_${feature.name}_value">${value}${feature.unit || ''}</span>
                        </div>
                        <input
                            type="range"
                            id="interface_feature_${feature.name}"
                            name="features[${feature.name}]"
                            value="${value}"
                            min="${feature.min || 0}"
                            max="${feature.max || 100}"
                            class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-primary"
                            oninput="document.getElementById('interface_feature_${feature.name}_value').textContent = this.value + '${feature.unit || ''}'"
                        >
                    `;
                } else {
                    inputHtml = `
                        <label class="text-[10px] font-medium block mb-1">${escapeHtml(feature.label || feature.name)}</label>
                        <input
                            type="text"
                            id="interface_feature_${feature.name}"
                            name="features[${feature.name}]"
                            value="${feature.default || ''}"
                            placeholder="${escapeHtml(feature.placeholder || '')}"
                            class="w-full bg-white/5 border-white/10 rounded-lg p-2 text-sm text-white placeholder:text-slate-600 focus:ring-primary focus:border-primary transition-all"
                        >
                    `;
                }

                featureDiv.innerHTML = inputHtml;
                if (feature.description) {
                    featureDiv.innerHTML += `<p class="text-[9px] text-slate-500 mt-1">${escapeHtml(feature.description)}</p>`;
                }
                featuresDiv.appendChild(featureDiv);
            });

            formContent.appendChild(featuresDiv);
        }
    }

    function previewUploadedImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
                preview.previousElementSibling.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function closeToolModal() {
        document.getElementById('toolModal').classList.remove('active');
        document.getElementById('runToolForm').reset();
        document.getElementById('formStatus').classList.add('hidden');
    }

    function setupToolForm(tool) {
        // Prompt field
        const promptGroup = document.getElementById('promptGroup');
        const promptInput = document.getElementById('prompt');
        const promptHelp = document.getElementById('promptHelp');

        if (tool.prompt_required) {
            promptGroup.style.display = 'block';
            promptInput.required = true;
            promptInput.placeholder = tool.prompt_placeholder || 'Enter your prompt...';
            promptHelp.textContent = tool.default_prompt ? `Default: ${tool.default_prompt}` : '';
        } else {
            promptGroup.style.display = 'none';
            promptInput.required = false;
        }

        // Prefix text
        const prefixGroup = document.getElementById('prefixTextGroup');
        prefixGroup.style.display = 'block';

        // Image uploads
        const uploadsContainer = document.getElementById('imageUploadsContainer');
        uploadsContainer.innerHTML = '';

        if (tool.image_uploads && tool.image_uploads.length > 0) {
            tool.image_uploads.forEach(upload => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        ${escapeHtml(upload.label || upload.name)}
                        ${upload.required ? '<span class="text-red-400">*</span>' : ''}
                    </label>
                    <input
                        type="file"
                        id="upload_${upload.name}"
                        name="${upload.name}"
                        accept="image/*"
                        ${upload.required ? 'required' : ''}
                        class="w-full bg-white/5 border-white/10 rounded-xl p-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-white file:font-medium hover:file:bg-primary/90 transition-all"
                    >
                    <p class="text-xs text-slate-500 mt-2">${escapeHtml(upload.description || '')}</p>
                `;
                uploadsContainer.appendChild(div);
            });
        }

        // Features
        const featuresContainer = document.getElementById('featuresContainer');
        featuresContainer.innerHTML = '';

        if (tool.features && tool.features.length > 0) {
            tool.features.forEach(feature => {
                const div = document.createElement('div');

                let inputHtml = '';
                if (feature.type === 'select') {
                    inputHtml = `
                        <select
                            id="feature_${feature.name}"
                            name="features[${feature.name}]"
                            class="w-full bg-white/5 border-white/10 rounded-xl p-3 text-white focus:ring-primary focus:border-primary transition-all"
                        >
                            ${(feature.options || []).map(opt => `
                                <option value="${escapeHtml(opt)}" ${opt === feature.default ? 'selected' : ''}>
                                    ${escapeHtml(opt)}
                                </option>
                            `).join('')}
                        </select>
                    `;
                } else if (feature.type === 'number') {
                    inputHtml = `
                        <input
                            type="number"
                            id="feature_${feature.name}"
                            name="features[${feature.name}]"
                            value="${feature.default || ''}"
                            min="${feature.min || ''}"
                            max="${feature.max || ''}"
                            class="w-full bg-white/5 border-white/10 rounded-xl p-3 text-white focus:ring-primary focus:border-primary transition-all"
                        >
                    `;
                } else {
                    inputHtml = `
                        <input
                            type="text"
                            id="feature_${feature.name}"
                            name="features[${feature.name}]"
                            value="${feature.default || ''}"
                            placeholder="${escapeHtml(feature.placeholder || '')}"
                            class="w-full bg-white/5 border-white/10 rounded-xl p-3 text-white placeholder:text-slate-600 focus:ring-primary focus:border-primary transition-all"
                        >
                    `;
                }

                div.innerHTML = `
                    <label class="block text-sm font-medium text-slate-300 mb-2">${escapeHtml(feature.label || feature.name)}</label>
                    ${inputHtml}
                    ${feature.description ? `<p class="text-xs text-slate-500 mt-2">${escapeHtml(feature.description)}</p>` : ''}
                `;
                featuresContainer.appendChild(div);
            });
        }
    }

    // Handle tool interface form submission
    document.getElementById('toolInterfaceForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn = document.getElementById('interfaceGenerateBtn');
        const statusEl = document.getElementById('interfaceFormStatus');
        const previewContent = document.getElementById('previewContent');

        btn.disabled = true;
        statusEl.className = 'p-3 rounded-lg bg-primary/10 border border-primary/20 text-primary text-xs';
        statusEl.classList.remove('hidden');
        statusEl.innerHTML = `
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined animate-spin text-sm">autorenew</span>
                <span>Generating...</span>
            </div>
        `;

        // Show loading in preview
        previewContent.innerHTML = `
            <div class="text-center">
                <div class="inline-block p-5 bg-primary/10 rounded-xl mb-3 animate-pulse">
                    <span class="material-symbols-outlined text-5xl text-primary animate-spin">autorenew</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Generating...</h3>
                <p class="text-sm text-slate-400">Your image is being created</p>
            </div>
        `;

        try {
            const formData = new FormData(e.target);

            // Collect features
            const features = {};
            document.querySelectorAll('[name^="features["]').forEach(input => {
                const name = input.name.match(/features\[(.*?)\]/)[1];
                features[name] = input.value;
            });

            if (Object.keys(features).length > 0) {
                formData.set('features', JSON.stringify(features));
            }

            const response = await fetch('{{ route("api.nano.visual.tools.run") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.error || 'Image generation failed');
            }

            statusEl.className = 'p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs';
            statusEl.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    <span>Success! Credits: ${data.credits_used || 0}</span>
                </div>
            `;

            // Display generated image in preview
            if (data.image_url) {
                displayInPreview(data);
            }
        } catch (error) {
            statusEl.className = 'p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs';
            statusEl.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span>Error: ${error.message}</span>
                </div>
            `;

            // Reset preview on error
            resetPreview();
        } finally {
            btn.disabled = false;
        }
    });

    function displayInPreview(data) {
        const previewContent = document.getElementById('previewContent');
        const previewControls = document.getElementById('previewControls');

        previewContent.innerHTML = `
            <div class="relative w-full max-w-2xl px-4">
                <div class="relative w-full aspect-square rounded-2xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.4)] ring-1 ring-white/10">
                    <img src="${escapeHtml(data.image_url)}" alt="Generated image" class="w-full h-full object-contain bg-black/20" id="currentPreviewImage">
                </div>
            </div>
        `;

        previewControls.classList.remove('hidden');

        // Store current image URL for download/share
        window.currentImageUrl = data.image_url;
        window.currentImageData = data;
    }

    function downloadCurrentImage() {
        if (window.currentImageUrl) {
            const link = document.createElement('a');
            link.href = window.currentImageUrl;
            link.download = `generated-image-${Date.now()}.png`;
            link.click();
        }
    }

    function shareCurrentImage() {
        if (window.currentImageUrl && navigator.share) {
            navigator.share({
                title: 'Generated Image',
                text: 'Check out this AI-generated image!',
                url: window.currentImageUrl
            }).catch(() => {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.currentImageUrl);
                alert('Image URL copied to clipboard!');
            });
        } else if (window.currentImageUrl) {
            navigator.clipboard.writeText(window.currentImageUrl);
            alert('Image URL copied to clipboard!');
        }
    }

    // Handle form submission (modal - kept for backwards compatibility)
    document.getElementById('runToolForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn = document.getElementById('runToolBtn');
        const statusEl = document.getElementById('formStatus');

        btn.disabled = true;
        statusEl.className = 'p-4 rounded-xl bg-primary/10 border border-primary/20 text-primary';
        statusEl.classList.remove('hidden');
        statusEl.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined animate-spin">autorenew</span>
                <span>Generating image... This may take a minute.</span>
            </div>
        `;

        try {
            const formData = new FormData(e.target);

            // Collect features
            const features = {};
            document.querySelectorAll('[name^="features["]').forEach(input => {
                const name = input.name.match(/features\[(.*?)\]/)[1];
                features[name] = input.value;
            });

            if (Object.keys(features).length > 0) {
                formData.set('features', JSON.stringify(features));
            }

            const response = await fetch('{{ route("api.nano.visual.tools.run") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.error || 'Image generation failed');
            }

            statusEl.className = 'p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400';
            statusEl.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>Image generated successfully! Credits used: ${data.credits_used || 0}</span>
                </div>
            `;

            // Display generated image
            if (data.image_url) {
                displayGeneratedImage(data);
                closeToolModal();
            }
        } catch (error) {
            statusEl.className = 'p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400';
            statusEl.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">error</span>
                    <span>Error: ${error.message}</span>
                </div>
            `;
        } finally {
            btn.disabled = false;
        }
    });

    function displayGeneratedImage(data) {
        const gallerySection = document.getElementById('imageGallerySection');
        const gallery = document.getElementById('imageGallery');

        gallerySection.style.display = 'block';

        const card = document.createElement('div');
        card.className = 'group relative rounded-2xl overflow-hidden glass border border-white/5';
        card.innerHTML = `
            <div class="image-preview">
                <img src="${escapeHtml(data.image_url)}" alt="Generated image" class="group-hover:scale-110 transition-transform duration-500">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-5 flex flex-col justify-end">
                <p class="text-white text-sm font-medium mb-3 line-clamp-2">${escapeHtml(data.image_data?.prompt || 'Generated image')}</p>
                <div class="flex gap-2">
                    <a href="${escapeHtml(data.image_url)}" target="_blank" class="flex-1 py-2 bg-primary text-white text-xs font-bold rounded-lg flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">open_in_new</span>
                        View Full Size
                    </a>
                </div>
            </div>
        `;
        gallery.insertBefore(card, gallery.firstChild);
    }

    function clearGallery() {
        if (confirm('Clear all generated images from this session?')) {
            document.getElementById('imageGallery').innerHTML = '';
            document.getElementById('imageGallerySection').style.display = 'none';
        }
    }

    function setView(view) {
        currentView = view;

        document.getElementById('gridViewBtn').classList.remove('active');
        document.getElementById('listViewBtn').classList.remove('active');

        if (view === 'grid') {
            document.getElementById('gridViewBtn').classList.add('active');
        } else {
            document.getElementById('listViewBtn').classList.add('active');
        }

        renderTools(availableTools);
    }

    // Search functionality
    document.getElementById('toolSearch').addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const filtered = availableTools.filter(tool =>
            tool.name.toLowerCase().includes(searchTerm) ||
            (tool.description && tool.description.toLowerCase().includes(searchTerm))
        );
        renderTools(filtered);
    });

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close modal on overlay click
    document.getElementById('toolModal').addEventListener('click', (e) => {
        if (e.target.id === 'toolModal') {
            closeToolModal();
        }
    });

    // Initialize
    loadTools();
</script>
@endpush
