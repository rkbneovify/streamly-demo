@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Subscription</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Subscription</li>
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
                        
                        <form action="{{ url("admin/subscriptionupdate", [$subscription->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
            @method('PUT')
            {{---dd($subscription)---}}
            <div class="card-body">
            <div class="form-group{{ $errors->has('subscriptionpack_id') ? ' has-error' : '' }}">
                                
                       
                                <div class="col-md-6">
                                <label for="subscriptionpack_id" class="col-md-4 control-label">Subscription packs</label>
                                <select name="subscriptionpack_id" id="subscriptionpack_id" class="form-control" >
                    @foreach($packs as   $key => $value)
                        <option value="{{ $key }}"  {{ ( $key == $subscription->subscriptionpack_id) ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>

                                    @if ($errors->has('subscriptionpack_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('subscriptionpack_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
              
              
              <div class="form-group{{ $errors->has('users_id') ? ' has-error' : '' }}">
                <label for="users_id">User</label>
                
                <select name="users_id" id="users_id" class="form-control" >
                    @foreach($user as   $key => $value)
                        <option value="{{ $key }}"  {{ ( $key == $subscription->users_id) ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('users_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('users_id') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                      <input type="checkbox" class="custom-control-input" name="toggleswitch" id="customSwitch3">
                      <label class="custom-control-label" for="customSwitch3">Disable subscription</label>
                    </div>
              <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
              
                <label for="start_date">start date</label>
                 <input type="date" name="start_date" placehoder="Start Date" class="form-control" value={{$subscription->start_date}} />
                 <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
                @if ($errors->has('start_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                    @endif
              </div> <div class="form-group {{ $errors->has('expiry_date') ? ' has-error' : '' }}">
              
                <label for="expiry_date">Expiry date</label>
                 <input type="date" name="expiry_date" placehoder="Start Date" class="form-control" value={{$subscription->expiry_date}} />
                 <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
                @if ($errors->has('expiry_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('expiry_date') }}</strong>
                                    </span>
                                    @endif
              </div>
              
             
</div>
              
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <div class="row">
      <div class="col-md-8 col-md-offset-4">
          
          <button type="submit" class="btn btn-success">
          Update
                                    </button>
                                    <a href="{{ url('admin/subscriptions') }}" class="btn btn-secondary">Cancel</a>
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