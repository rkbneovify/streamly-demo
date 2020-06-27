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
        <div class="col-md-6">
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
                        
                        
        <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertzipcode') }}">
            {{ csrf_field() }}

            <div class="card-body">
              <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                <label for="zipcode">zipcode</label>
                
              <input type="text" name="zipcode"    class="form-control"  required autofocus>
                @if ($errors->has('zipcode'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                <label for="city">city</label>
                <input type="text" name="city" class="form-control"  autofocus>
                
                @if ($errors->has('city'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                <label for="state">state</label>
                <input type="text" name="state" class="form-control"  required autofocus>
                
                @if ($errors->has('state'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                    @endif
              </div>
              
             

              <div class="form-group {{ $errors->has('longitude') ? ' has-error' : '' }}">
        
                <label for="longitude">longitude </label>
                 <input type="text" name="longitude" placehoder="longitude" class="form-control"   autofocus>
                 <!-- <input type="text" name="state_abbr" placehoder="Start Date" class="form-control" id="state_abbr"/> -->
                @if ($errors->has('longitude'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('longitude') }}</strong>
                                    </span>
                                    @endif
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
             
</div>

<div class="col-md-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"></h3>

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
                  
      <div class="card-body">
        <div class="form-group{{ $errors->has('state_abbr') ? ' has-error' : '' }}">
          <label for="state_abbr">state abbr</label>
          
        <input type="text" name="state_abbr"    class="form-control"   required autofocus>
          @if ($errors->has('state_abbr'))
                                  <span class="help-block">
                                  <strong>{{ $errors->first('state_abbr') }}</strong>
                              </span>
                              @endif
        </div>
        {{-- {{ $zipcode->county_area  }} --}}
        <div class="form-group{{ $errors->has('county_area') ? ' has-error' : '' }}">
            <label for="code">county area</label>
            <input type="text" name="county_area" class="form-control"  required autofocus>
            
            @if ($errors->has('county_area'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('county_area') }}</strong>
                                </span>
                                @endif
          </div>
        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
          <label for="code">code</label>
          <input type="text" name="code" class="form-control" required autofocus>
          
          @if ($errors->has('code'))
                                  <span class="help-block">
                                  <strong>{{ $errors->first('code') }}</strong>
                              </span>
                              @endif
        </div>
        
        <div class="form-group {{ $errors->has('latitude') ? ' has-error' : '' }}">
        
          <label for="latitude">latitude </label>
           <input type="text" name="latitude" placehoder="latitude" class="form-control"  required autofocus>
           <!-- <input type="text" name="state_abbr" placehoder="Start Date" class="form-control" id="state_abbr"/> -->
          @if ($errors->has('latitude'))
                                  <span class="help-block">
                                  <strong>{{ $errors->first('latitude') }}</strong>
                              </span>
                              @endif
        </div>
        <div class="form-group">
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
              <input type="checkbox" class="custom-control-input" name="toggleswitch" id="customSwitch3" >
              <label class="custom-control-label" for="customSwitch3">Disable zipcode</label>  <sub>turn this "ON" to set inactive status</sub>
            </div>
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
          
            <input class="btn btn-primary" type="submit" value="Save">
                                    <a href="{{ url('admin/zipcode') }}" class="btn btn-secondary">Cancel</a>
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