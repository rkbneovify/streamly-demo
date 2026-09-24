@extends('master')
@section('stylesheets')
    <link href="{{ asset('dist/css/custom.css') }}" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endsection
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Announcement</h1>

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
                    <form id="frm" action="{{ route('admin.announcement.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            {{-- Content --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">
                                        Content <span class="text-danger">*</span>
                                    </label>

                                    {{-- <textarea name="content" id="content" rows="10"
                                        class="form-control @error('content') is-invalid @enderror"
                                        placeholder="Write your blog content...">{{ old('content') }}</textarea> --}}

                                    <div id="editor" style="height:250px;" class="@error('content') is-invalid @enderror">
                                    </div>
                                    <input type="hidden" name="content" id="content">

                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                             {{-- Title --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="url">Url <span class="text-danger">*</span></label>
                                    <input type="text" name="url" id="url"
                                        class="form-control @error('url') is-invalid @enderror"
                                        value="{{ old('url') }}" placeholder="Enter url">

                                    @error('url')
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

            success: function (res) {
                $('#divSubCategory').show();
                let select = $('#selSubCategory');

                select.empty();
                select.append('<option value="">Select Sub Category</option>');

                if (res.success && res.data.length > 0) {
                    $.each(res.data, function (index, item) {
                        select.append(
                            `<option value="${item}">${item}</option>`
                        );
                    });
                }
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
            }
        });
    }
    $(document).ready(function () {
        $('#divSubCategory').hide();
    })

    $(document).on('change', '#selCategory', function (e) {
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
                    'header': [1, 2, 3, false]
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
                ['link'],
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

    quill.on('text-change', function () {
        $('#content').val(quill.root.innerHTML);
        // console.log(quill.root.innerHTML);
    });




    $('#frm').on('submit', function (e) {
        e.preventDefault();
        showLoader();
        this.submit();
    });
</script>




@stop