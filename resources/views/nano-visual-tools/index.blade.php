<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nano Visual Tools</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            padding: 2rem;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 { color: #1f2937; margin-bottom: 0.5rem; }
        .subtitle { color: #6b7280; margin-bottom: 2rem; }
        
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .tool-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .tool-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .tool-card.selected {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .tool-card h3 {
            margin: 0 0 0.5rem 0;
            color: #1f2937;
        }
        .tool-card .description {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .tool-card .credits {
            color: #059669;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .tool-form {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: none;
        }
        .tool-form.active {
            display: block;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #374151;
            font-weight: 500;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
        }
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-group input[type="file"] {
            width: 100%;
            padding: 0.5rem;
        }
        .form-group .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        .feature-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover:not(:disabled) {
            background: #1d4ed8;
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .btn-secondary {
            background: #6b7280;
        }
        .btn-secondary:hover:not(:disabled) {
            background: #4b5563;
        }
        
        .status {
            margin-top: 1rem;
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 0.5rem;
            color: #374151;
        }
        .status.loading {
            background: #dbeafe;
            color: #1e40af;
        }
        .status.success {
            background: #d1fae5;
            color: #065f46;
        }
        .status.error {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .image-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .image-card img {
            width: 100%;
            height: auto;
            display: block;
        }
        .image-card .info {
            padding: 1rem;
        }
        .image-card .prompt {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        .image-card .actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav style="margin-bottom: 1rem;">
            <a href="{{ route('dashboard') }}" style="color: #2563eb; text-decoration: none; margin-right: 1rem;">← Back to Dashboard</a>
        </nav>
        
        <h1>Nano Visual Tools</h1>
        <p class="subtitle">Generate stunning images using AI-powered visual tools</p>

        <!-- Tools Selection -->
        <div id="toolsContainer">
            <div class="status">Loading available tools...</div>
        </div>

        <!-- Tool Form -->
        <div id="toolForm" class="tool-form">
            <h2 id="selectedToolName">Select a tool to get started</h2>
            <form id="runToolForm">
                <input type="hidden" id="toolId" name="tool_id">
                <input type="hidden" id="toolSlug" name="tool">

                <!-- Prompt Input -->
                <div class="form-group" id="promptGroup" style="display: none;">
                    <label for="prompt">Prompt</label>
                    <textarea id="prompt" name="prompt" placeholder="Enter your prompt here..."></textarea>
                    <div class="help-text" id="promptHelp"></div>
                </div>

                <!-- Prefix Text -->
                <div class="form-group" id="prefixTextGroup" style="display: none;">
                    <label for="prefixText">Prefix Text (Optional)</label>
                    <input type="text" id="prefixText" name="prefix_text" placeholder="e.g., Change color to">
                </div>

                <!-- Image Uploads -->
                <div id="imageUploadsContainer"></div>

                <!-- Features -->
                <div id="featuresContainer"></div>

                <div class="form-group">
                    <button type="submit" class="btn" id="runToolBtn">Generate Image</button>
                    <button type="button" class="btn btn-secondary" onclick="resetToolSelection()">Select Different Tool</button>
                </div>
            </form>

            <div id="formStatus" class="status hidden"></div>
        </div>

        <!-- Generated Images -->
        <div id="imageGallery" class="image-gallery"></div>
    </div>

    <script>
        let availableTools = [];
        let selectedTool = null;

        // Load tools on page load
        async function loadTools() {
            const container = document.getElementById('toolsContainer');
            container.innerHTML = '<div class="status loading">Loading available tools...</div>';

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
                container.innerHTML = `<div class="status error">Error: ${e.message}</div>`;
            }
        }

        function renderTools(tools) {
            const container = document.getElementById('toolsContainer');
            
            if (tools.length === 0) {
                container.innerHTML = '<div class="status">No tools available at the moment.</div>';
                return;
            }

            container.innerHTML = `
                <div class="tools-grid">
                    ${tools.map(tool => `
                        <div class="tool-card" onclick="selectTool(${tool.id})" data-tool-id="${tool.id}">
                            <h3>${escapeHtml(tool.name)}</h3>
                            <div class="description">${escapeHtml(tool.description || 'No description available')}</div>
                            <div class="credits">${tool.credits_per_generation || 2} credits per generation</div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function selectTool(toolId) {
            selectedTool = availableTools.find(t => t.id === toolId);
            if (!selectedTool) return;

            // Update UI
            document.querySelectorAll('.tool-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.querySelector(`[data-tool-id="${toolId}"]`).classList.add('selected');

            // Show form
            document.getElementById('toolForm').classList.add('active');
            document.getElementById('selectedToolName').textContent = selectedTool.name;
            document.getElementById('toolId').value = selectedTool.id;
            document.getElementById('toolSlug').value = selectedTool.slug;

            // Setup form fields
            setupToolForm(selectedTool);
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
                    div.className = 'form-group';
                    div.innerHTML = `
                        <label for="upload_${upload.name}">
                            ${escapeHtml(upload.label || upload.name)}
                            ${upload.required ? '<span style="color: red;">*</span>' : ''}
                        </label>
                        <input type="file" id="upload_${upload.name}" name="${upload.name}" 
                               accept="image/*" ${upload.required ? 'required' : ''}>
                        <div class="help-text">${escapeHtml(upload.description || '')}</div>
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
                    div.className = 'form-group';
                    
                    let inputHtml = '';
                    if (feature.type === 'select') {
                        inputHtml = `
                            <select id="feature_${feature.name}" name="features[${feature.name}]">
                                ${(feature.options || []).map(opt => `
                                    <option value="${escapeHtml(opt)}" ${opt === feature.default ? 'selected' : ''}>
                                        ${escapeHtml(opt)}
                                    </option>
                                `).join('')}
                            </select>
                        `;
                    } else if (feature.type === 'number') {
                        inputHtml = `
                            <input type="number" id="feature_${feature.name}" 
                                   name="features[${feature.name}]" 
                                   value="${feature.default || ''}"
                                   min="${feature.min || ''}"
                                   max="${feature.max || ''}">
                        `;
                    } else {
                        inputHtml = `
                            <input type="text" id="feature_${feature.name}" 
                                   name="features[${feature.name}]" 
                                   value="${feature.default || ''}"
                                   placeholder="${escapeHtml(feature.placeholder || '')}">
                        `;
                    }
                    
                    div.innerHTML = `
                        <label for="feature_${feature.name}">${escapeHtml(feature.label || feature.name)}</label>
                        ${inputHtml}
                        ${feature.description ? `<div class="help-text">${escapeHtml(feature.description)}</div>` : ''}
                    `;
                    featuresContainer.appendChild(div);
                });
            }
        }

        // Handle form submission
        document.getElementById('runToolForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const btn = document.getElementById('runToolBtn');
            const statusEl = document.getElementById('formStatus');
            
            btn.disabled = true;
            statusEl.className = 'status loading';
            statusEl.classList.remove('hidden');
            statusEl.textContent = 'Generating image... This may take a minute.';

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

                statusEl.className = 'status success';
                statusEl.textContent = `Image generated successfully! Credits used: ${data.credits_used || 0}`;

                // Display generated image
                if (data.image_url) {
                    displayGeneratedImage(data);
                }

                // Load images for this tool
                loadToolImages(selectedTool.id);
            } catch (error) {
                statusEl.className = 'status error';
                statusEl.textContent = `Error: ${error.message}`;
            } finally {
                btn.disabled = false;
            }
        });

        function displayGeneratedImage(data) {
            const gallery = document.getElementById('imageGallery');
            const card = document.createElement('div');
            card.className = 'image-card';
            card.innerHTML = `
                <img src="${escapeHtml(data.image_url)}" alt="Generated image">
                <div class="info">
                    <div class="prompt">${escapeHtml(data.image_data?.prompt || 'Generated image')}</div>
                    <div class="actions">
                        <a href="${escapeHtml(data.image_url)}" target="_blank" class="btn btn-sm">View Full Size</a>
                        <button class="btn btn-sm btn-secondary" onclick="regenerateImage(${data.image_data?.id || ''})">Regenerate</button>
                    </div>
                </div>
            `;
            gallery.insertBefore(card, gallery.firstChild);
        }

        async function loadToolImages(toolId) {
            try {
                const response = await fetch(`{{ route("api.nano.visual.tools.images") }}?tool_id=${toolId}&per_page=12`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                const data = await response.json();
                if (data.success && data.data) {
                    const gallery = document.getElementById('imageGallery');
                    gallery.innerHTML = data.data.map(img => `
                        <div class="image-card">
                            <img src="${escapeHtml(img.imageUrl)}" alt="Generated image">
                            <div class="info">
                                <div class="prompt">${escapeHtml(img.prompt || 'Generated image')}</div>
                                <div class="actions">
                                    <a href="${escapeHtml(img.imageUrl)}" target="_blank" class="btn btn-sm">View Full Size</a>
                                    <button class="btn btn-sm btn-secondary" onclick="regenerateImage(${img.id})">Regenerate</button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            } catch (e) {
                console.error('Failed to load images:', e);
            }
        }

        async function regenerateImage(imageId) {
            if (!confirm('Generate a variation of this image?')) return;

            const statusEl = document.getElementById('formStatus');
            statusEl.className = 'status loading';
            statusEl.classList.remove('hidden');
            statusEl.textContent = 'Generating variation...';

            try {
                const response = await fetch('{{ route("api.nano.visual.tools.regenerate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        image_id: imageId,
                        count: 1,
                    }),
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'Regeneration failed');
                }

                statusEl.className = 'status success';
                statusEl.textContent = 'Variation generated successfully!';

                if (data.images && data.images.length > 0) {
                    data.images.forEach(img => displayGeneratedImage(img));
                }
            } catch (error) {
                statusEl.className = 'status error';
                statusEl.textContent = `Error: ${error.message}`;
            }
        }

        function resetToolSelection() {
            selectedTool = null;
            document.getElementById('toolForm').classList.remove('active');
            document.querySelectorAll('.tool-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.getElementById('runToolForm').reset();
            document.getElementById('formStatus').classList.add('hidden');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Initialize
        loadTools();
    </script>
</body>
</html>
