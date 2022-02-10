@extends('master')

@section('stylesheets') 
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet">@stop
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Channel Edit</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Channel Edit</li>
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
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
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
          <form action="{{ url("admin/channelupdate", [$channel->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


        </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class="col-md-4 control-label">Name</label>


              <input id="display_name" type="text" class="form-control" name="name" value="{{$channel->name}}" required
                autofocus>

              @if ($errors->has('name'))
              <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('callSign') ? ' has-error' : '' }}">
              <label for="callSign" class="col-md-4 control-label">callSign</label>


              <input id="callSign" type="text" class="form-control" name="callSign" value="{{$channel->callSign}}"  
                autofocus>

              @if ($errors->has('callSign'))
              <span class="help-block">
                <strong>{{ $errors->first('callSign') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('genre_id') ? ' has-error' : '' }}">
              <label for="genre_id" class="col-md-4 control-label">genres id</label>

            

              <select name="genre_id" id="genre_id" class="form-control" >
                    @foreach($genres as   $key => $value)
                    
                        <option value="{{ $key }}"  {{ ( $key == $channel->genre_id) ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>

              @if ($errors->has('genre_id'))
              <span class="help-block">
                <strong>{{ $errors->first('genre_id') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('genres') ? ' has-error' : '' }}">
              <label for="genres" class="col-md-4 control-label">genres</label>
              
<select name="genres[]" id="genres" class="form-control select2" multiple="multiple">
@foreach($genres as $id => $genre)
                        @if(in_array($id, $arrVal))
                        <option value="{{ $id }}" selected="true">{{ $genre }}</option>
                        @else
                        <option value="{{ $id }}">{{ $genre }}</option>
                        @endif 
                    @endforeach
                </select>
         

              @if ($errors->has('genres'))
              <span class="help-block">
                <strong>{{ $errors->first('genres') }}</strong>
              </span>
              @endif

            </div>

            <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
              <label for="url" class="col-md-4 control-label">url</label>


              <input id="url" type="text" class="form-control" name="url" value="{{$channel->url}}" required
                autofocus>

              @if ($errors->has('url'))
              <span class="help-block">
                <strong>{{ $errors->first('url') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('dash_url') ? ' has-error' : '' }}">
              <label for="dash_url" class="col-md-4 control-label">dash_url</label>


              <input id="dash_url" type="text" class="form-control" name="dash_url" value="{{$channel->dash_url}}" required
                autofocus>

              @if ($errors->has('dash_url'))
              <span class="help-block">
                <strong>{{ $errors->first('dash_url') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('smooth_url') ? ' has-error' : '' }}">
              <label for="smooth_url" class="col-md-4 control-label">smooth_url</label>


              <input id="smooth_url" type="text" class="form-control" name="smooth_url" value="{{$channel->smooth_url}}" required
                autofocus>

              @if ($errors->has('smooth_url'))
              <span class="help-block">
                <strong>{{ $errors->first('smooth_url') }}</strong>
              </span>
              @endif

            </div>

            <div class="form-group">
              <label for="exampleInputFile">File input</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                  <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                </div>
                <div class="input-group-append">
                  <span class="input-group-text" id="">Upload</span>
                </div>
              </div>
            </div>
            <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                      <input type="checkbox" class="custom-control-input" name="toggleswitch" id="customSwitch3">
                      <label class="custom-control-label" for="customSwitch3">Disable Channel</label>
                    </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <div class="form-group">
          <div class="col-md-8 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
              Update
            </button>

            <a class="btn btn-link" href="{{ url('admin/channels') }}">
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
<script defer type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

<script  type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>   
<script>
$('.select2').select2();
</script>
@stop
