<style>
    .svg-editor {
        width: 100%;
    }

    .preview-wrapper {
        width: 100%;
        min-height: 220px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 10px;
        background: #f8f8f8;
        border: 1px solid #eee;
    }

    .icon-preview {
        width: 100px;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
        border-radius: 50%;
        background-color: #dffbff;
        overflow: hidden;
        transition: background-color 0.2s ease;
    }

    .icon-preview svg {
        display: block;
        width: 35px;
        height: 35px;
        max-width: none;
        max-height: none;
        flex-shrink: 0;
    }

    .upload-label {
        display: block;
        width: 100%;
        padding: 12px;
        text-align: center;
        border: 1px dashed #cfcfcf;
        border-radius: 7px;
        cursor: pointer;
        color: #555;
        font-size: 14px;
        transition: 0.2s;
    }

    .upload-label:hover {
        border-color: #777;
        background: #fafafa;
    }

    #svgInput {
        display: none;
    }

    .file-name {
        min-height: 16px;
        margin-top: 8px;
        text-align: center;
        font-size: 12px;
        color: #888;
    }

    .control {
        margin-top: 20px;
    }

    .control>label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .color-control {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .color-picker {
        width: 45px;
        height: 40px;
        padding: 2px;
        border: 1px solid #ccc;
        border-radius: 6px;
        cursor: pointer;
        background: #fff;
    }

    .color-input {
        flex: 1;
        width: 100%;
        height: 40px;
        padding: 0 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
        font-size: 14px;
    }

    .color-input:focus {
        border-color: #777;
    }

    .range-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .range-wrapper input[type="range"] {
        flex: 1;
        width: 100%;
        cursor: pointer;
    }

    .size-value {
        min-width: 48px;
        text-align: right;
        font-size: 13px;
        color: #666;
    }

    .editor-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .editor-buttons button {
        flex: 1;
    }

    @media (max-width: 480px) {
        .preview-wrapper {
            min-height: 180px;
        }

        .icon-preview {
            width: 90px;
            height: 90px;
        }
    }
</style>


<!-- Add / Edit Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frm_category_store" action="{{ route('admin.blog.category.store') }}" method="POST"> @csrf {{--
                This will be changed to PUT while editing --}} <input type="hidden" name="_method" id="category_method"
                    value="POST"> {{-- Category ID --}} <input type="hidden" name="category_id" id="category_id"
                    value=""> <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel"> Add Category </h5>
                    <button id="btnClose" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> <!-- Body -->
                <div class="modal-body"> <!-- Category -->
                    <div class="form-group"> <label for="category_title"> Category </label> <input type="text"
                            name="title" id="category_title" class="form-control" placeholder="Enter category" required>
                    </div> <!-- SVG Editor -->
                    <div class="svg-editor"> <!-- Preview -->
                        <div class="preview-wrapper">
                            <div class="icon-preview" id="iconPreview"> <!-- Default SVG --> <svg width="35" height="35"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="3" width="6" height="6" rx="1" stroke="#00BBD4" stroke-width="1.5" />
                                    <rect x="15" y="3" width="6" height="6" rx="1" stroke="#00BBD4"
                                        stroke-width="1.5" />
                                    <rect x="3" y="15" width="6" height="6" rx="1" stroke="#00BBD4"
                                        stroke-width="1.5" />
                                    <rect x="15" y="15" width="6" height="6" rx="1" stroke="#00BBD4"
                                        stroke-width="1.5" />
                                </svg> </div>
                        </div> <!-- Upload SVG --> <label for="svgInput" class="upload-label"> Upload SVG Icon </label>
                        <input type="file" id="svgInput" accept=".svg,image/svg+xml">
                        <div class="file-name" id="fileName"> </div> <!-- Icon Color -->
                        <div class="control"> <label for="iconColor"> Icon Color </label>
                            <div class="color-control"> <input type="color" id="iconColorPicker" class="color-picker"
                                    value="#00BBD4"> <input type="text" id="iconColor" name="iconColor"
                                    class="color-input" value="#00BBD4" maxlength="7" placeholder="#000000"> </div>
                        </div> <!-- Background Color -->
                        <div class="control"> <label for="backgroundColor"> Background Color </label>
                            <div class="color-control"> <input type="color" id="backgroundColorPicker"
                                    class="color-picker" value="#DFFBFF"> <input type="text" id="backgroundColor"
                                    name="backgroundColor" class="color-input" value="#DFFBFF" maxlength="7"
                                    placeholder="#FFFFFF"> </div>
                        </div> <!-- Icon Size -->
                        <div class="control"> <label for="iconSize"> Icon Size </label>
                            <div class="range-wrapper"> <input type="range" id="iconSize" name="iconSize" min="10"
                                    max="70" value="35"> <span class="size-value" id="sizeValue"> 35px </span> </div>
                        </div> <!-- Editor Buttons -->
                        <div class="editor-buttons"> <button type="button" class="btn btn-light" id="resetButton"> Reset
                            </button> <button type="button" class="btn btn-info" id="copySvgButton"> Copy SVG </button>
                        </div>
                    </div>
                </div> <!-- Footer -->
                <div class="modal-footer"> <button id="btnAddCategoryCancel" type="button" class="btn btn-secondary"
                        data-dismiss="modal"> Cancel </button> <button type="submit" class="btn btn-primary"
                        id="categorySubmitButton"> Save </button> </div>
            </form>
        </div>
    </div>
</div>



<script> (function () {
        'use strict'; /* ===================================================== ELEMENTS ===================================================== */
        const form = document.getElementById('frm_category_store');
        const svgInput = document.getElementById('svgInput');
        const iconPreview = document.getElementById('iconPreview');
        const fileName = document.getElementById('fileName');
        const iconColorPicker = document.getElementById('iconColorPicker');
        const iconColor = document.getElementById('iconColor');
        const backgroundColorPicker = document.getElementById('backgroundColorPicker');
        const backgroundColor = document.getElementById('backgroundColor');
        const iconSize = document.getElementById('iconSize');
        const sizeValue = document.getElementById('sizeValue');
        const resetButton = document.getElementById('resetButton');
        const copySvgButton = document.getElementById('copySvgButton');
        const categoryId = document.getElementById('category_id');
        const categoryMethod = document.getElementById('category_method');
        const modalTitle = document.getElementById('addCategoryModalLabel');
        const submitButton = document.getElementById('categorySubmitButton');

        /* ===================================================== DEFAULT VALUES ===================================================== */
        const DEFAULT_ICON_COLOR = '#00BBD4';
        const DEFAULT_BACKGROUND_COLOR = '#DFFBFF';
        const DEFAULT_ICON_SIZE = 35; let originalSvg = '';



        /* ===================================================== GET SVG ===================================================== */
        function getSvg() { return iconPreview.querySelector('svg'); }
        /* ===================================================== COPY ===================================================== */
        function showCopied(button, defaultText) {
            button.textContent = 'Copied!';
            setTimeout(function () { button.textContent = defaultText; }, 1200);
        }
        function copyText(text, button, defaultText) {
            if (!navigator.clipboard) {
                alert('Copy is not supported in this browser.');
                return;
            }

            navigator.clipboard.writeText(text).then(function () { showCopied(button, defaultText); }).catch(function () { alert('Unable to copy to the clipboard.'); });
        }

        /* ===================================================== UPLOAD SVG ===================================================== */
        svgInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) { return; }
            const isSvg = file.type === 'image/svg+xml' || file.name.toLowerCase().endsWith('.svg');
            if (!isSvg) { alert('Please select a valid SVG file.'); this.value = ''; return; }
            fileName.textContent = file.name; const reader = new FileReader();
            reader.onload = function (event) {
                originalSvg = event.target.result;
                const parser = new DOMParser();
                const doc = parser.parseFromString(originalSvg, 'image/svg+xml');
                const svg = doc.documentElement;
                if (!svg || svg.tagName.toLowerCase() !== 'svg') { alert('Invalid SVG file.'); return; }
                iconPreview.innerHTML = '';
                const importedSvg = document.importNode(svg, true);
                iconPreview.appendChild(importedSvg); updateIconSize(); updateIconColor(); updateBackground();
            };
            reader.readAsText(file);
        });
        /* ===================================================== ICON COLOR ===================================================== */
        iconColorPicker.addEventListener('input', function () {
            iconColor.value = this.value;
            updateIconColor();
        });
        iconColor.addEventListener('input', function () {
            const value = this.value.trim();
            if (/^#[0-9A-F]{6}$/i.test(value)) {
                iconColorPicker.value = value;
                updateIconColor();
            }
        });
        function updateIconColor() {
            const svg = getSvg();
            if (!svg) { return; }
            const color = iconColor.value;
            const svgFill = svg.getAttribute('fill');
            const svgStroke = svg.getAttribute('stroke');
            if (svgFill && svgFill.toLowerCase() !== 'none') {
                svg.setAttribute('fill', color);
            } if (svgStroke && svgStroke.toLowerCase() !== 'none') {
                svg.setAttribute('stroke', color);
            }
            svg.querySelectorAll('*').forEach(function (element) {
                const fill = element.getAttribute('fill');
                const stroke = element.getAttribute('stroke');
                if (fill && fill.toLowerCase() !== 'none') {
                    element.setAttribute('fill', color);
                } if (stroke && stroke.toLowerCase() !== 'none') {
                    element.setAttribute('stroke', color);
                } if (element.style.fill && element.style.fill !== 'none') {
                    element.style.fill = color;
                } if (element.style.stroke && element.style.stroke !== 'none') {
                    element.style.stroke = color;
                }
            });
            svg.style.color = color;
            svg.querySelectorAll('[fill="currentColor"]').forEach(function (element) {
                element.setAttribute('fill', color);
            });
            svg.querySelectorAll('[stroke="currentColor"]').forEach(function (element) {
                element.setAttribute('stroke', color);
            });
        }
        /* ===================================================== BACKGROUND COLOR ===================================================== */
        backgroundColorPicker.addEventListener('input', function () {
            backgroundColor.value = this.value; updateBackground();
        });
        backgroundColor.addEventListener('input', function () {
            const value = this.value.trim();
            if (/^#[0-9A-F]{6}$/i.test(value)) {
                backgroundColorPicker.value = value;
                updateBackground();
            }
        });
        function updateBackground() {
            const color = backgroundColor.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                iconPreview.style.backgroundColor = color;
            }
        }
        /* ===================================================== ICON SIZE ===================================================== */
        iconSize.addEventListener('input', function () { updateIconSize(); });
        function updateIconSize() {
            const svg = getSvg();
            const size = iconSize.value;
            sizeValue.textContent = size + 'px';
            if (!svg) { return; }
            svg.style.width = size + 'px';
            svg.style.height = size + 'px';
        }
        /* ===================================================== COPY SVG ===================================================== */
        copySvgButton.addEventListener('click', function () {
            const svg = getSvg();
            if (!svg) {
                alert('Please upload an SVG first.');
                return;
            }
            const svgClone = svg.cloneNode(true);
            svgClone.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            const size = iconSize.value; svgClone.setAttribute('width', size);
            svgClone.setAttribute('height', size);
            svgClone.style.width = '';
            svgClone.style.height = '';
            const svgString = new XMLSerializer().serializeToString(svgClone);
            copyText(svgString, copySvgButton, 'Copy SVG');
        });

        /* ===================================================== RESET ===================================================== */
        resetButton.addEventListener('click', function () {
            iconColorPicker.value = DEFAULT_ICON_COLOR;
            iconColor.value = DEFAULT_ICON_COLOR;
            backgroundColorPicker.value = DEFAULT_BACKGROUND_COLOR;
            backgroundColor.value = DEFAULT_BACKGROUND_COLOR;
            iconSize.value = DEFAULT_ICON_SIZE;
            sizeValue.textContent = DEFAULT_ICON_SIZE + 'px';
            if (originalSvg) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(originalSvg, 'image/svg+xml');
                iconPreview.innerHTML = '';
                const svg = document.importNode(doc.documentElement, true);
                iconPreview.appendChild(svg);
            }
            updateBackground();
            updateIconColor();
            updateIconSize();
        });

        /* ===================================================== FORM SUBMIT ===================================================== */

        form.addEventListener('submit', function (event) {
            const svg = getSvg();
            if (!svg) {
                event.preventDefault();
                alert('Please upload an SVG icon.');
                return;
            } const svgClone = svg.cloneNode(true);
            svgClone.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            const size = iconSize.value;
            svgClone.setAttribute('width', size);
            svgClone.setAttribute('height', size);
            svgClone.style.width = '';
            svgClone.style.height = '';
            const svgString = new XMLSerializer().serializeToString(svgClone);
            let hiddenInput = form.querySelector('input[name="svg_content"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'svg_content';
                form.appendChild(hiddenInput);
            } hiddenInput.value = svgString;
        });
        /* ===================================================== ADD CATEGORY ===================================================== */
        window.addCategory = function () {
            form.reset();
            categoryId.value = '';
            categoryMethod.value = 'POST';
            form.action = "{{ route('admin.blog.category.store') }}";
            modalTitle.textContent = 'Add Category';
            submitButton.textContent = 'Save';
            iconColorPicker.value = DEFAULT_ICON_COLOR;
            iconColor.value = DEFAULT_ICON_COLOR;
            backgroundColorPicker.value = DEFAULT_BACKGROUND_COLOR;
            backgroundColor.value = DEFAULT_BACKGROUND_COLOR;
            iconSize.value = DEFAULT_ICON_SIZE;
            sizeValue.textContent = DEFAULT_ICON_SIZE + 'px';
            fileName.textContent = '';
            svgInput.value = '';
            originalSvg = '';
            /* * Restore default SVG */
            iconPreview.innerHTML = ` <svg width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <rect x="3" y="3" width="6" height="6" rx="1" stroke="#00BBD4" stroke-width="1.5"/> <rect x="15" y="3" width="6" height="6" rx="1" stroke="#00BBD4" stroke-width="1.5"/> <rect x="3" y="15" width="6" height="6" rx="1" stroke="#00BBD4" stroke-width="1.5"/> <rect x="15" y="15" width="6" height="6" rx="1" stroke="#00BBD4" stroke-width="1.5"/> </svg> `;
            updateBackground();
            updateIconColor();
            updateIconSize();
            $('#addCategoryModal').modal('show');
        };

        /* ===================================================== EDIT CATEGORY Call: editCategory(category) ===================================================== */
        window.editCategory = function (category) {
            /* * Category ID */
            categoryId.value = category.id;
            /* * Change method to PUT */
            categoryMethod.value = 'POST';
            /* * Change URL */


            form.action = "{{ route('admin.blog.category.update', ':id') }}"
                .replace(':id', category.id);
            /* * Change title/button */
            modalTitle.textContent = 'Edit Category';
            submitButton.textContent = 'Update';
            /* * Category title */
            document.getElementById('category_title').value = category.title || '';
            /* * Icon color */
            const savedIconColor = category.icon_color || category.iconColor || DEFAULT_ICON_COLOR;
            iconColor.value = savedIconColor; iconColorPicker.value = savedIconColor;
            /* * Background color */
            const savedBackground = category.background_color || category.backgroundColor || DEFAULT_BACKGROUND_COLOR;
            backgroundColor.value = savedBackground;
            backgroundColorPicker.value = savedBackground;
            /* * Icon size */
            const savedSize = category.icon_size || category.iconSize || DEFAULT_ICON_SIZE;
            iconSize.value = savedSize;
            sizeValue.textContent = savedSize + 'px';
            /* * Existing SVG * * Supports: * category.svg_content * category.icon */
            const savedSvg = category.svg_content || category.icon || '';
            if (savedSvg) {
                originalSvg = savedSvg;
                const parser = new DOMParser();
                const doc = parser.parseFromString(savedSvg, 'image/svg+xml');
                const svg = doc.documentElement;
                if (svg && svg.tagName.toLowerCase() === 'svg') {
                    iconPreview.innerHTML = '';
                    const importedSvg = document.importNode(svg, true);
                    iconPreview.appendChild(importedSvg);
                }
            }
            /* * File input cannot be populated * programmatically for security reasons. */ svgInput.value = ''; fileName.textContent = savedSvg ? 'Existing SVG icon' : ''; /* * Apply settings */ updateBackground(); updateIconColor(); updateIconSize(); /* * Open modal */ $('#addCategoryModal').modal('show');
        };
        /* ===================================================== INITIAL STATE ===================================================== */

        updateBackground();
        updateIconColor();
        updateIconSize();

        document.getElementById('btnClose').addEventListener('click', function () {
            location.reload();
        });

    })();


</script>