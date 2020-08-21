@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Frontend Edit</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Frontend Edit</li>
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
          @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
          <form action="{{ url("admin/fronttextupdate", ) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


        </div>
        {{-- {{dd($frontendjson)}} --}}
            <div class="form-group{{ $errors->has('billingdetailspage') ? ' has-error' : '' }}">
              <label for="billingdetailspage" class="col-md-4 control-label">Billing details page</label>

              <textarea name="billingdetailspage" class='form-control' rows="5" cols="20">{{$frontendjson['billingdetailspage']}}</textarea>
              {{-- <input id="billingdetailspage" type="text" class="form-control" name="billingdetailspage" value="{{$frontendjson['billingdetailspage']}}" 
                autofocus> --}}

              @if ($errors->has('billingdetailspage'))
              <span class="help-block">
                <strong>{{ $errors->first('billingdetailspage') }}</strong>
              </span>
              @endif
            </div>
            
            <div class="form-group{{ $errors->has('supportapitext') ? ' has-error' : '' }}">
              <label for="supportapitext" class="col-md-4 control-label">Support Api message</label>


              <input id="supportapitext" type="text" class="form-control" name="supportapitext" value="{{$frontendjson['supportapitext']}}" 
                autofocus>

              @if ($errors->has('supportapitext'))
              <span class="help-block">
                <strong>{{ $errors->first('supportapitext') }}</strong>
              </span>
              @endif

            </div>
          

            <div class="form-group{{ $errors->has('supportemail') ? ' has-error' : '' }}">
              <label for="supportemail" class="col-md-4 control-label">Support email</label>


             <input id="supportemail" type="text" class="form-control" name="supportemail" value="{{$frontendjson['supportemail']}}" 
                autofocus>

              @if ($errors->has('supportemail'))
              <span class="help-block">
                <strong>{{ $errors->first('supportemail') }}</strong>
              </span>
              @endif

            </div> 
            <div class="form-group{{ $errors->has('supportphone') ? ' has-error' : '' }}">
              <label for="supportphone" class="col-md-4 control-label">Support phone</label>


             <input id="display_name" type="text" class="form-control" name="supportphone" value="{{$frontendjson['supportphone']}}" 
                autofocus>

              @if ($errors->has('supportphone'))
              <span class="help-block">
                <strong>{{ $errors->first('supportphone') }}</strong>
              </span>
              @endif

            </div> 
            <div class="form-group{{ $errors->has('supportphone2') ? ' has-error' : '' }}">
              <label for="supportphone" class="col-md-4 control-label">Support phone2</label>


             <input id="display_name" type="text" class="form-control" name="supportphone2" value="{{$frontendjson['supportphone2']}}" 
                autofocus>

              @if ($errors->has('supportphone2'))
              <span class="help-block">
                <strong>{{ $errors->first('supportphone2') }}</strong>
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