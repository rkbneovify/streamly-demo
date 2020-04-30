@extends('master')
@section('content')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User view</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View user</li>
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
          
            <div class="card-body">
            <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Role</label>
                            @if(!empty($user->roles))
                                @foreach($user->roles as $role)
                                    <label class="label label-success">{{ $role->display_name }}</label>
                                @endforeach
                            @endif
                        </div>
              <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" id="inputName" class="form-control" value="{{ $user->name }}" disabled>
              </div>
              <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="text" id="inputEmail" class="form-control" value="{{ $user->email }}" disabled>
              </div>
              <div class="form-group">
                <label for="inputStatus">Status</label>
                @if ($user->is_active===1)
                <input type="text" id="inputEmail" class="form-control" value="Active " disabled>
@elseif ($user->is_active===0)
<input type="text" id="inputEmail" class="form-control" value="Inactive " disabled>
@else
<input type="text" id="inputEmail" class="form-control" value="Unknown " disabled>
@endif
                
              </div>
              <div class="form-group">
                <label for="inputZip">Zip code</label>
                <input type="text" id="inputZip" class="form-control" value="{{ $user->zipcode }}" disabled>
              </div>
              <div class="form-group">
                <label for="inputProjectLeader">Address</label>
                <input type="text" id="inputProjectLeader" class="form-control" value="{{ $user->address }}" disabled>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="col-md-6">
          <div class="card card-secondary">
            <div class="card-header">
              <h3 class="card-title">Personal details</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="inputEstimatedBudget">DOB</label>
                <input type="text" id="inputEstimatedBudget" class="form-control" value="{{ $user->dob }}" disabled>
              </div>
              <div class="form-group">
                <label for="inputSpentBudget">Gender</label>
                <input type="text" id="inputSpentBudget" class="form-control" value="{{ $user->gender }}" disabled>
              </div>

              <div class="form-group">
                <label for="inputEstimatedDuration">Country</label>
                <input type="text" id="inputEstimatedDuration" class="form-control" value="{{ $user->country }}" disabled>
              </div>
              <div class="form-group">
                <label for="inputEstimatedDuration">Some detail</label>
                <input type="text" id="inputEstimatedDuration" class="form-control" disabled>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      <!-- </div>
      <div class="row">
        <div class="col-12">
          <a href="#" class="btn btn-secondary">Cancel</a>
          <input type="submit" value="Create new Porject" class="btn btn-success float-right">
        </div>
      </div> -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection