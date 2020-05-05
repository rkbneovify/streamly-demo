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
              <li class="breadcrumb-item active">View ZIPCODES </li>
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
                        
                        <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertquotation') }}">
                      
        
        {{ csrf_field() }}
            <div class="card-body">
              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">zipcode</label>
                
              <input type="text" name="zipcode" disabled   class="form-control"  disabled value={{ $zipcode->zipcode }}>
                @if ($errors->has('zipcode'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                <label for="city">city</label>
                <input type="text" name="city" class="form-control" disabled value={{ $zipcode->city  }}>
                
                @if ($errors->has('city'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                <label for="state">state</label>
                <input type="text" name="state" class="form-control" disabled value={{ $zipcode->state  }}>
                
                @if ($errors->has('state'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group {{ $errors->has('state_abbr') ? ' has-error' : '' }}">
              
                <label for="state_abbr">Created on</label>
                 <input type="text" name="state_abbr" placehoder="Start Date" class="form-control" disabled value={{$zipcode->created_on}} />
                 <!-- <input type="text" name="state_abbr" placehoder="Start Date" class="form-control" id="state_abbr"/> -->
                @if ($errors->has('state_abbr'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('state_abbr') }}</strong>
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
      
      </form>   
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection