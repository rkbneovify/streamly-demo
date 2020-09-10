
  
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
          <h1>Channel Add</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Channel Add</li>
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
            <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertchannel') }}">
                      
        
                      {{ csrf_field() }}

        </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class="col-md-4 control-label">Name</label>


              <input id="display_name" type="text" class="form-control" name="name"  required
                autofocus>

              @if ($errors->has('name'))
              <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('genres') ? ' has-error' : '' }}">
              <label for="genre_id" class="col-md-4 control-label">genres id</label>



              <select name="genre_id" id="genre_id" class="form-control" >
                    
                    @foreach($genres as $id => $genre)
                        <option value="{{ $id }}" >
                            {{ $genre }}
                        </option>
                    @endforeach
                        
                    
                </select>

              @if ($errors->has('genres'))
              <span class="help-block">
                <strong>{{ $errors->first('genres') }}</strong>
              </span>
              @endif

            </div>

            <div class="form-group{{ $errors->has('genres') ? ' has-error' : '' }}">
              <label for="genres" class="col-md-4 control-label">genres</label>



              <select name="genres[]" id="genres" class="form-control select2" multiple="multiple >
             
                    @foreach($genres as $id => $genre)
                        <option value="{{ $id }}" >
                            {{ $genre }}
                        </option>
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


              <input id="url" type="text" class="form-control" name="url"  required
                autofocus>

              @if ($errors->has('url'))
              <span class="help-block">
                <strong>{{ $errors->first('url') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('smooth_url') ? ' has-error' : '' }}">
              <label for="smooth_url" class="col-md-4 control-label">smooth url</label>


              <input id="smooth_url" type="text" class="form-control" name="smooth_url"  required
                autofocus>

              @if ($errors->has('smooth_url'))
              <span class="help-block">
                <strong>{{ $errors->first('smooth_url') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('dash_url') ? ' has-error' : '' }}">
              <label for="dash_url" class="col-md-4 control-label">dash url</label>


              <input id="dash_url" type="text" class="form-control" name="dash_url"  required
                autofocus>

              @if ($errors->has('dash_url'))
              <span class="help-block">
                <strong>{{ $errors->first('dash_url') }}</strong>
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
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
              <label for="status" class="col-md-4 control-label">Status</label>
              <select name="status" id="status" class="form-control">
                    <option value="">Choose a status</option>
                    
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                    </select>

              @if ($errors->has('status'))
              <span class="help-block">
                <strong>{{ $errors->first('status') }}</strong>
              </span>
              @endif

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


@stop
