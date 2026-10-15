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
            <form id="frm_category_store" action="{{ route('admin.support-ticket.category.store') }}" method="POST">
                @csrf {{--
                This will be changed to PUT while editing --}} <input type="hidden" name="_method" id="category_method"
                    value="POST"> {{-- Category ID --}} <input type="hidden" name="category_id" id="category_id"
                    value=""> <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel"> Add Category </h5>
                    <button id="btnClose" data-dismiss="modal" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> <!-- Body -->
                <div class="modal-body"> <!-- Category -->
                    <div class="form-group"> <label for="category_title"> Category </label> <input type="text"
                            name="title" id="category_title" class="form-control" placeholder="Enter category" required>
                    </div>

                </div> <!-- Footer -->
                <div class="modal-footer"> <button id="btnAddCategoryCancel" type="button" class="btn btn-secondary"
                        data-dismiss="modal"> Cancel </button> <button type="submit" class="btn btn-primary"
                        id="categorySubmitButton"> Save </button> </div>
            </form>
        </div>
    </div>
</div>




<script>
    $('#btnAddCategoryCancel').on('click', function () {
        $('#btnAddCategoryCancel').closest('.modal').modal('hide');

        setTimeout(function () {
            location.reload();
        }, 300);
    });
    $('#btnClose').on('click', function () {
        $('#btnClose').closest('.modal').modal('hide');

        setTimeout(function () {
            location.reload();
        }, 300);
    });


    const form = document.getElementById('frm_category_store');
    const categoryId = document.getElementById('category_id');
    const categoryMethod = document.getElementById('category_method');
    const modalTitle = document.getElementById('addCategoryModalLabel');
    const submitButton = document.getElementById('categorySubmitButton');


    function editCategory(category) {
        /* * Category ID */
        categoryId.value = category.id;
        /* * Change method to PUT */
        categoryMethod.value = 'POST';
        /* * Change URL */


        form.action = "{{ route('admin.support-ticket.category.update', ':id') }}"
            .replace(':id', category.id);
        /* * Change title/button */
        modalTitle.textContent = 'Edit Category';
        submitButton.textContent = 'Update';
        /* * Category title */
        document.getElementById('category_title').value = category.title || '';
        $('#addCategoryModal').modal('show');
    };
</script>