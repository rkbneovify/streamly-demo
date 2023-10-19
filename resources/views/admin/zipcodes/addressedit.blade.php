@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View ZIPCODES </h1>
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
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">General</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
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
                        
                        <form action="{{ url("admin/addressupdate", [$zipstate->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                @method('PUT')
        {{ csrf_field() }}
            <div class="card-body">
            
              <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
                <label for="address2">state abbr</label>
                
              <input type="text" name="address2"    class="form-control"   value="{{ $zipstate->address2 }}">
                @if ($errors->has('address2'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address2') }}</strong>
                                    </span>
                                    @endif
              </div>
              {{-- {{ $zipstate->zip5  }} --}}
              <div class="form-group{{ $errors->has('zip5') ? ' has-error' : '' }}">
                  <label for="state">county area</label>
                  <input type="text" name="zip5" class="form-control"  value="{{ $zipstate->zip5  }}">
                  
                  @if ($errors->has('zip5'))
                                          <span class="help-block">
                                          <strong>{{ $errors->first('zip5') }}</strong>
                                      </span>
                                      @endif
                </div>
              <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                <label for="state">state</label>
                <input type="text" name="state" class="form-control"  value="{{ $zipstate->state  }}">
                
                @if ($errors->has('state'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
              
                <label for="city">city </label>
                 <input type="text" name="city" placehoder="city" class="form-control"  value="{{$zipstate->city}}" />
                 <!-- <input type="text" name="address2" placehoder="Start Date" class="form-control" id="address2"/> -->
                @if ($errors->has('city'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
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
      <div class="row">
        <div class="col-12">
          
        <button type="submit" class="btn btn-success">
          Update
                                    </button>
                                    <a href="{{ url('admin/zipstate') }}" class="btn btn-secondary">Cancel</a>
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

<script type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>   
<script>
$('.select2').select2();
</script>
@stop