@extends('master')
@section('stylesheets')
    <link href="{{ asset('dist/css/custom.css') }}" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
#editor .ql-editor img {
    cursor: wait;
}

#editor .image-remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 100px;
    height: 100px;
    border: none;
    border-radius: 50%;
    background: #dc3545;
    color: white;
    font-size: 20px;
    cursor: pointer;
    z-index: 100;
}
</style>
@endsection
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Blog</h1>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Blog</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="card shadow-sm">


                <div class="card-body">
                    <form id="frm" action="{{ route('admin.blog.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            {{-- Title --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" placeholder="Enter blog title">

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Category --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="category_id">
                                        Category <span class="text-danger">*</span>
                                    </label>

                                    <select name="category" id="selCategory"
                                        class="form-control @error('category') is-invalid @enderror">
                                        <option value="">Select Category</option>

                                        @foreach ($categories as $item)
                                            <option value="{{ $item }}"
                                                {{ old('category') == $item ? 'selected' : '' }}>
                                                {{ $item }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            {{-- Category --}}
                            <div id="divSubCategory" class="col-md-12">
                                <div class="form-group">
                                    <label for="selSubCategory">
                                        Sub Category <span class="text-danger">*</span>
                                    </label>

                                    <select name="sub_category" id="selSubCategory"
                                        class="form-control @error('sub_category') is-invalid @enderror">
                                        <option value="">Select Sub Category</option>


                                    </select>

                                    @error('sub_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>








                            {{-- Content --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">
                                        Content <span class="text-danger">*</span>
                                    </label>

                                    {{-- <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror"
                                        placeholder="Write your blog content...">{{ old('content') }}</textarea> --}}

                                    <div id="editor" style="height:950px;"
                                        class="@error('content') is-invalid @enderror"></div>
                                    <input type="hidden" name="content" id="content">

                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-secondary mr-3">

                                Cancel
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection




@section('scripts')
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
        function loadSubCategories(category) {
            let url = "{{ route('admin.blog.subcategory.list', ['category' => '__CATEGORY__']) }}";

            url = url.replace('__CATEGORY__', category);
            $.ajax({
                url,
                type: "GET",

                success: function(res) {
                    $('#divSubCategory').show();
                    let select = $('#selSubCategory');

                    select.empty();
                    select.append('<option value="">Select Sub Category</option>');

                    if (res.success && res.data.length > 0) {
                        $.each(res.data, function(index, item) {
                            select.append(
                                `<option value="${item}">${item}</option>`
                            );
                        });
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON);
                }
            });
        }
        $(document).ready(function() {
            $('#divSubCategory').hide();
        })

        $(document).on('change', '#selCategory', function(e) {
            e.preventDefault();
            let category = $(this).val();
            console.log(category);
            if (category == "") {
                $('#divSubCategory').hide();
            } else {
                loadSubCategories(category);

            }

        });

        // fetch sub category data
    </script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Write here...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'header': [1, 2, 3, 4, false]
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    ['blockquote', 'code-block'],
                    ['link','image'],
                    [{
                        'align': []
                    }],

                    ['clean']
                ]
            }
        });

        var oldContent = @json(old('content', ''));

        if (oldContent) {
            quill.root.innerHTML = oldContent;
            $('#editor').css('border', '');
        }

        if (oldContent == null) {
            $('#editor').css('border', '1px solid #dc3545');
        }

        quill.on('text-change', function() {
            $('#content').val(quill.root.innerHTML);
            // console.log(quill.root.innerHTML);
        });




        $('#frm').on('submit', function(e) {
            e.preventDefault();
            showLoader();
            this.submit();
        });
    </script>
<script>
    var toolbar = quill.getModule('toolbar');

toolbar.addHandler('image', function () {
    var input = document.createElement('input');

    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = function () {
        var file = input.files[0];

        if (!file) return;

        var formData = new FormData();
        formData.append('image', file);

        fetch('/admin/blog/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Image upload failed');
            }

            return response.json();
        })
        .then(data => {
            var range = quill.getSelection(true);

            quill.insertEmbed(range.index, 'image', data.url);
            quill.setSelection(range.index + 1);

            $('#content').val(quill.root.innerHTML);
        })
        .catch(error => {
            console.error(error);
            alert('Unable to upload image.');
        });
    };
});
</script>
<script>
  quill.on('text-change', function (delta, oldDelta, source) {
    if (source !== 'user') return;

    const oldImages = [];
    const newImages = [];

    quill.getContents().ops.forEach(op => {
        if (op.insert && op.insert.image) {
            newImages.push(op.insert.image);
        }
    });

    oldDelta.ops?.forEach(op => {
        if (op.insert && op.insert.image) {
            oldImages.push(op.insert.image);
        }
    });

    const removedImages = oldImages.filter(
        image => !newImages.includes(image)
    );

   removedImages.forEach(imageUrl => {
        try {
            const url = new URL(imageUrl, window.location.origin);

            // Only process images hosted on this website
            if (url.origin !== window.location.origin) return;

            const prefix = '/storage/blog-images/';

            if (!url.pathname.startsWith(prefix)) return;

            const path = 'blog-images/' +
                decodeURIComponent(url.pathname.slice(prefix.length));

            fetch("{{ route('admin.blog.image.delete') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ path: path })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Image deletion failed');
                }

                return response.json();
            })
            .then(data => {
                console.log(data.message);
            })
            .catch(error => {
                console.error(error);
            });

        } catch (error) {
            console.error('Invalid image URL:', error);
        }
    });
});
</script>


@stop
