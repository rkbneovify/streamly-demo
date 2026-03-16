@extends('master')
@section('stylesheets')
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet">@stop
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <style>
        .warning {
            font-size: 14px;
            color: red;
            margin-top: 10px;
        }
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Channel</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Channel </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">General</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="panel-body">
                                <!-- Display Validation Errors -->
                                {{-- @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}
                                <form method="POST" enctype="multipart/form-data"
                                    action="{{ url('admin/insertchannel') }}">


                                    {{ csrf_field() }}

                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>


                                <input id="display_name" type="text" class="form-control" name="name"
                                    value="{{ old('name') }}" autofocus>

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="form-group{{ $errors->has('callSign') ? ' has-error' : '' }}">
                                <label for="callSign" class="col-md-4 control-label">callSign</label>


                                <input id="callSign" type="text" class="form-control" name="callSign"
                                    value="{{ old('callSign') }}" autofocus>

                                @error('callSign')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>



                            <div class="form-group{{ $errors->has('genres') ? ' has-error' : '' }}">
                                <label for="genre_id" class="col-md-4 control-label">genres id</label>



                                <select name="genre_id" id="genre_id" class="form-control">

                                    @foreach ($genres as $id => $genre)
                                        <option value="{{ $id }}"
                                            {{ old('genre_id') == $id ? 'selected' : '' }}>
                                            {{ $genre }}
                                        </option>
                                    @endforeach


                                </select>
                                @error('genre_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-group{{ $errors->has('genres') ? ' has-error' : '' }}">
                                <label for="genres" class="col-md-4 control-label">genres</label>



                                <select name="genres[]" id="genres" class="form-control select2"
                                    multiple="multiple >
             
                    @foreach ($genres as $id => $genre)
<option value="{{ $id }}"
                                    {{ in_array($id, old('genres', [])) ? 'selected' : '' }}>
                                    {{ $genre }}
                                    </option>
                                    @endforeach


                                </select>

                                @error('genres')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                                <label for="url" class="col-md-4 control-label">url</label>


                                <input id="url" type="text" class="form-control" name="url"
                                    value="{{ old('url') }}" autofocus>

                                @error('url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="form-group{{ $errors->has('smooth_url') ? ' has-error' : '' }}">
                                <label for="smooth_url" class="col-md-4 control-label">smooth url</label>


                                <input id="smooth_url" type="text" class="form-control" name="smooth_url"
                                    value="{{ old('smooth_url') }}" autofocus>

                                @error('smooth_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="form-group{{ $errors->has('dash_url') ? ' has-error' : '' }}">
                                <label for="dash_url" class="col-md-4 control-label">dash url</label>


                                <input id="dash_url" type="text" class="form-control" name="dash_url"
                                    value="{{ old('dash_url') }}" autofocus>

                                @error('dash_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label for="callSign" class="col-md-4 control-label">Channel Type</label>


                                <select id="selChannelType" class="form-control" name="type">
                                    <option value="">Select
                                    </option>
                                    <option value="all" {{ old('type') == 'all' ? 'selected' : '' }}>All
                                    </option>
                                    <option value="local" {{ old('type') == 'local' ? 'selected' : '' }}>Local
                                    </option>
                                    <option value="premium" {{ old('type') == 'premium' ? 'selected' : '' }}>Premium
                                    </option>
                                    <option value="phx-locals" {{ old('type') == 'phx-locals' ? 'selected' : '' }}>PHX
                                        Locals
                                    </option>
                                </select>

                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div id="divZipcode" class="form-group{{ $errors->has('zip_code') ? ' has-error' : '' }}">
                                <label for="zipcode" class="col-md-4 control-label">Zipcode</label>
                                <input type="text" id="zip_codes" class="form-control" name="zip_codes"
                                    value="{{ old('zip_codes') }}" placeholder="e.g., 12345, 67890">

                                @error('zip_codes')
                                    <span class="text-danger mb-2">{{ $message }}</span>
                                @enderror
                                <p class="warning">Please enter a ZIP code if you wish to restrict the display of this
                                    channel to a specific area. If you leave the field empty, the channel will be available
                                    for viewing in all ZIP codes by default.</p>



                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input"
                                            id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                                </div>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                <label for="status" class="col-md-4 control-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Choose a status</option>

                                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>

                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Add
                        </button>

                        <a class="btn btn-link" href="{{ url('admin/channelstatus') }}">
                            Cancel
                        </a>
                    </div>
                </div>
                </form>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const zipCodesInput = document.getElementById("zip_codes");

            zipCodesInput.addEventListener("input", function() {
                const inputValue = zipCodesInput.value.replace(/[^0-9,]/g,
                    ""); // Remove non-numeric and non-comma characters
                const formattedValue = inputValue.replace(/(\d{5})(?=\d)/g,
                    "$1,"); // Add a comma after every 5 digits
                zipCodesInput.value = formattedValue;
            });
        });
    </script>
    <script>
        $('#divZipcode').hide();
        $('#selChannelType').on('change', function() {
            var selectedType = $(this).val();
            if (selectedType !== 'local') {
                $('#zip_codes').val('');
                $('#divZipcode').hide();
            } else if (selectedType === 'local') {
                $('#divZipcode').show();
                $('#zip_codes').val('');

            }

        });
    </script>

@stop
