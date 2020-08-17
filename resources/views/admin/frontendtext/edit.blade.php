@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>User Edit</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">User Edit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-6">
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
          <form action="{{ url("admin/userupdate", ) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


        </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class="col-md-4 control-label">Name</label>


              <input id="display_name" type="text" class="form-control" name="name" value="{{$frontendjson['0']->sex}}" required
                autofocus>

              @if ($errors->has('name'))
              <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif
            </div>
            
            <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
              <label for="contact" class="col-md-4 control-label">contact</label>


              <input id="display_name" type="text" class="form-control" name="name" value="{{$frontendjson['0']->skills}}" required
                autofocus>

              @if ($errors->has('contact'))
              <span class="help-block">
                <strong>{{ $errors->first('contact') }}</strong>
              </span>
              @endif

            </div>
          

            <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
              <label for="country" class="col-md-4 control-label">country</label>


             <input id="display_name" type="text" class="form-control" name="name" value="{{$frontendjson['0']->id}}" required
                autofocus>

              @if ($errors->has('country'))
              <span class="help-block">
                <strong>{{ $errors->first('country') }}</strong>
              </span>
              @endif

            </div> 
            <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
              <label for="country" class="col-md-4 control-label">country</label>


             <input id="display_name" type="text" class="form-control" name="name" value="{{$frontendjson}}" required
                autofocus>

              @if ($errors->has('country'))
              <span class="help-block">
                <strong>{{ $errors->first('country') }}</strong>
              </span>
              @endif

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

            <a class="btn btn-link" href="{{ url('admin/users') }}">
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
<script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
@stop