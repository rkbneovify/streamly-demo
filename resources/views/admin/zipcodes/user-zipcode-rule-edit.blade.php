@extends('master')
@section('stylesheets')
    <style>
        .select2-container .select2-selection--single {

            height: 39px !important;
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
                        <h1>Edit ZIPCODES </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Edit ZIPCODES </li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row" style="margin-left: 10px; margin-right:10px">
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
                        <div class="panel-body">
                            <!-- Display Validation Errors -->
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ url('admin/userzipcoderuleupdate', [$UserZipcodeRule->id]) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                                        <label for="zipcode">zipcode</label>

                                        <input type="text" name="zipcode" class="form-control"
                                            value="{{ $UserZipcodeRule->zipcode }}">
                                        @if ($errors->has('zipcode'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('zipcode') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email">email</label>
                                        <select name="email" id="email" class="form-control select2">
                                            @foreach ($emails as $email)
                                                <option value="{{ $email->email }}"
                                                    @if ($email->email == $UserZipcodeRule->email) selected @endif>{{ $email->email }}
                                                </option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('validate') ? ' has-error' : '' }}">
                                        <label for="validate">validate</label> 1 = zip check, 2 = strict zip
                                        <select name="validate" class="form-control">
                                            <option value="1" @if ($UserZipcodeRule->validate == 1) selected @endif>1
                                            </option>
                                            <option value="2" @if ($UserZipcodeRule->validate == 2) selected @endif>2
                                            </option>
                                        </select>
                                        @if ($errors->has('validate'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('validate') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                </div>

            </div>
            <div class="row" style="margin-left: 10px; margin-right:10px">
                <div class="col-12">

                    <button type="submit" class="btn btn-success">
                        Update
                    </button>
                    <a href="{{ url('admin/userzipcoderule') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
            </form>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

@section('scripts')
    <link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

    <script src="{{ asset('dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select an email...',
                width: '100%' // Ensure full width for styling
            });
        });
    </script>
@endsection
