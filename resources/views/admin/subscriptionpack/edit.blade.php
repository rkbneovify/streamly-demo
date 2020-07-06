@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit  Subscription pack</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Subscription pack</li>
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
                        
                        <form action="{{ url("admin/subscriptionpackupdate", [$plan->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
            @method('PUT')
            <div class="card-body">
              <div class="form-group{{ $errors->has('nickname') ? ' has-error' : '' }}">
                <label for="name">Name</label>
                
              <input type="text" name="nickname"    class="form-control"   value={{ $plan->nickname }}>
                @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('nickname') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              
              <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                <label for="duration">Duration</label>
                <input type="text" name="duration" class="form-control" disabled value="{{ $plan->interval_count  }} {{$plan->interval }}">
                
                @if ($errors->has('duration'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('duration') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                <label for="rate">Rate($)</label>
                <input type="text" name="rate" class="form-control" disabled value="{{ $plan->currency  }} {{ $plan->amount/100  }}">
                
                @if ($errors->has('rate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group {{ $errors->has('created') ? ' has-error' : '' }}">
              
                <label for="created">Created on</label>
                 <input type="text" name="created" placehoder="Start Date" class="form-control" disabled value={{date('m/d/Y H:i:s',$plan->created)}} />
                 <!-- <input type="text" name="created" placehoder="Start Date" class="form-control" id="created"/> -->
                @if ($errors->has('created'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('created') }}</strong>
                                    </span>
                                    @endif
              </div>

              <div class="form-group">
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                  <input type="checkbox" class=" custom-control-input" name="toggleswitch" id="customSwitch3">
                  <label class="custom-control-label" for="customSwitch3">Block Plan</label>
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
          
        <button type="submit" class="btn btn-success">
          Update
                                    </button>
                                    <a href="{{ url('admin/subscriptionpack') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
      </form>   
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection