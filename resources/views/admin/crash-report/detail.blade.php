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
                        <h1>Crash Report Detail</h1>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Home</a></li>
                            <li class="breadcrumb-item active">Crash Report Detail</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="card shadow-sm">


                <div class="card-body p-5">

                    @foreach(\Illuminate\Support\Arr::except($data->toArray(), [
                            'id',
                            'updated_at',
                            'session_id'
                        ]) as $key => $value)

                        <div class="row">
                            <div class="col-md-3">
                                <strong> {{ ucwords(str_replace('_', ' ', $key)) }}</strong>
                            </div>
                            @if ($key === 'user_id' && $value)
                                <div class="col-md-9 text-justify">
                                    <a href="{{ route('admin.users.view', $value) }}"> {{ $value }}</a>
                                </div>
                            @elseif($key === 'stack_trace' && $value)
                                <div class="col-md-9  text-justify">
                                    {{ $value }}
                                </div>

                            @else
                                <div class="col-md-9">
                                    @if($key === 'created_at' && $value)
                                        {{ \Carbon\Carbon::parse($value)->setTimezone(config('app.timezone'))->format('d/m/Y h:i:s A') }}
                                    @elseif(is_array($value))
                                        {{ json_encode($value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </div>
                            @endif

                        </div>

                        @if(!$loop->last)
                            <hr>
                        @endif

                    @endforeach

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
