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
            <div class="form-group{{ $errors->has('stripe_plan') ? ' has-error' : '' }}">
                                
                       
                                <div class="col-md-6">
                                <label for="stripe_plan" class="col-md-4 control-label">Subscription packs</label>
                                <select name="stripe_plan" id="stripe_plan" class="form-control" >
                    @foreach($plans as   $key => $value)
                        <option value="{{ $value->id }}"  {{ ( $value->id == $subscription->stripe_plan) ? 'selected' : '' }}>
                            {{ $value->nickname }}
                        </option>
                    @endforeach
                </select>

                                    @if ($errors->has('stripe_plan'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('stripe_plan') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
              
              
              <div class="form-group col-md-6{{ $errors->has('user_id') ? ' has-error' : '' }}">
                <label for="user_id">User</label>
                
                <select name="user_id" id="user_id" class="form-control" >
                    @foreach($user as   $key => $value)
                        <option value="{{ $key }}"  {{ ( $key == $subscription->user_id) ? 'selected' : '' }} disabled>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('user_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                      <input type="checkbox" class="custom-control-input" name="toggleswitch" id="customSwitch3">
                      <label class="custom-control-label" for="customSwitch3">Disable subscription</label>
                    </div></div>
              {{-- <div class="form-group col-md-6 {{ $errors->has('created_at') ? ' has-error' : '' }}">
              
                <label for="created_at">start date</label>
                 <input type="date" name="created_at" placehoder="Start Date" class="form-control" value={{$subscription->created_at}} />
                 <!-- <input type="text" name="created_at" placehoder="Start Date" class="form-control" id="created_at"/> -->
                @if ($errors->has('created_at'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('created_at') }}</strong>
                                    </span>
                                    @endif
              </div> <div class="form-group col-md-6 {{ $errors->has('renews_at') ? ' has-error' : '' }}">
              
                <label for="renews_at">Expiry date</label>
                 <input type="date" name="renews_at" placehoder="Start Date" class="form-control" value={{$subscription->renews_at}} />
                 <!-- <input type="text" name="created_at" placehoder="Start Date" class="form-control" id="created_at"/> -->
                @if ($errors->has('renews_at'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('renews_at') }}</strong>
                                    </span>
                                    @endif
              </div> --}}
              
             

              
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