@extends('master')
@section('content')



          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1>Frontendtext Add</h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Home</a></li>
                      <li class="breadcrumb-item active">Frontendtext Add</li>
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
                        <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertfrontendtext') }}">
                {{ csrf_field() }}


                      </div>
                     
                  <div class="form-group{{ $errors->has('billingdetailspage') ? ' has-error' : '' }}">
                    <label for="billingdetailspage" class="col-md-4 control-label">billingdetailspage</label>

                      <input type="text" class="form-control" name="billingdetailspage" required autofocus>

                      @if ($errors->has('billingdetailspage'))
                      <span class="help-block">
                        <strong>{{ $errors->first('billingdetailspage') }}</strong>
                      </span>
                      @endif
                    
                  </div>
                  <div class="form-group{{ $errors->has('supportapitext') ? ' has-error' : '' }}">
                    <label for="supportapitext" class="col-md-4 control-label">supportapitext</label>

                    
                      <input id="email" type="text" class="form-control" name="supportapitext" required autofocus>

                      @if ($errors->has('supportapitext'))
                      <span class="help-block">
                        <strong>{{ $errors->first('supportapitext') }}</strong>
                      </span>
                      @endif
                    
                  </div>
                  <div class="form-group{{ $errors->has('supportemail') ? ' has-error' : '' }}">
                    <label for="supportemail" class="col-md-4 control-label">supportemail</label>

                    
                      <input id="supportemail" type="text" class="form-control" name="supportemail" autofocus>
                      @if ($errors->has('supportemail'))
                      <span class="help-block">
                        <strong>{{ $errors->first('supportemail') }}</strong>
                      </span>
                      @endif
                
                  </div>

                  <div class="form-group{{ $errors->has('supportphone') ? ' has-error' : '' }}">
                    <label for="supportphone" class="col-md-4 control-label">supportphone</label>

                    
                      <input id="supportphone" type="text" class="form-control"
                        name="supportphone" autofocus>
                      @if ($errors->has('supportphone'))
                      <span class="help-block">
                        <strong>{{ $errors->first('supportphone') }}</strong>
                      </span>
                      @endif
                    
                  </div> 
                  <div class="form-group{{ $errors->has('supportphone2') ? ' has-error' : '' }}">
                    <label for="supportphone2" class="col-md-4 control-label">supportphone2</label>

                    
                      <input id="supportphone2" type="text" class="form-control"
                        name="supportphone2" autofocus>
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
                <input class="btn btn-primary" type="submit" value="Save">

                  <a class="btn btn-link" href="{{ url('admin/frontendtext') }}">
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