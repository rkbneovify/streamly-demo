@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit  Channel </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Channel</li>
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
                        
                        <form action="{{ url("admin/subscriptionpackupdate", [$subscriptionpack->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
            @method('PUT')
            <div class="card-body">
              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">Name</label>
                
              <input type="text" name="name"    class="form-control"   value={{ $subscriptionpack->name }}>
                @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                <label for="duration">Duration(in months)</label>
                <input type="text" name="duration" class="form-control"  value={{ $subscriptionpack->duration  }}>
                
                @if ($errors->has('duration'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('duration') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                <label for="rate">Rate($)</label>
                <input type="text" name="rate" class="form-control"  value={{ $subscriptionpack->rate  }}>
                
                @if ($errors->has('rate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
              
                <label for="start_date">Created on</label>
                 <input type="text" name="start_date" placehoder="Start Date" class="form-control"  value={{$subscriptionpack->created_on}} />
                 <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
                @if ($errors->has('start_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
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
                                    <a href="{{ url('admin/subscriptionpack') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
      </form>   
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection