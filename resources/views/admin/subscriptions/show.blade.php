@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Subscription</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Subscription</li>
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
            <div class="card-body col-md-12">
              {{-- <div class="form-group{{ $errors->has('stripe_plan') ? ' has-error' : '' }}">
                <label for="stripe_plan">Subscription</label>
                
              <input type="text" name="stripe_plan" disabled   class="form-control"  disabled value={{ $subscription->subscriptionpack->name }}>
                @if ($errors->has('stripe_plan'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('stripe_plan') }}</strong>
                                    </span>
                                    @endif
              </div> --}}

              <div class="form-group{{ $errors->has('product') ? ' has-error' : '' }}">
                <label for="product">Plans coming from stripe dashboard</label>
  
                <select name="product" id="product" class="form-control" disabled>
                  
                  @foreach($plans as $id => $pack)
                  @if($subscription->stripe_plan==$pack->id)
                  <option value="{{ $pack->id }}" selected="true">{{ $pack->nickname }}</option>
                  @else
                  <option value="{{ $pack->id }}">{{ "Value not found" }}</option>
                  @endif 
                  @endforeach
                </select>
                @if ($errors->has('product'))
                <span class="help-block">
                  <strong>{{ $errors->first('product') }}</strong>
                </span>
                @endif
              </div>
              
              <div class="form-group{{ $errors->has('users_id') ? ' has-error' : '' }}">
                <label for="users_id">User</label>
                <input type="text" name="users_id" class="form-control" disabled value={{ $subscription->user->name  }}>
                
                @if ($errors->has('users_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('users_id') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-group {{ $errors->has('created_at') ? ' has-error' : '' }}">
              
                <label for="created_at">start date</label>
                 <input type="date" name="created_at" placehoder="Start Date" class="form-control" disabled value={{$subscription->created_at}} />
                 <!-- <input type="text" name="created_at" placehoder="Start Date" class="form-control" id="created_at"/> -->
                @if ($errors->has('created_at'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('created_at') }}</strong>
                                    </span>
                                    @endif
              </div>
              <div class="form-group">
                <label for="inputProjectLeader">expiry date</label>
                <!-- <input type="text" placehoder="End Date" name="renews_at" id="end_date" class="form-control"/> -->
                <input type="date" placehoder="End Date" name="renews_at" class="form-control" disabled value={{$subscription->renews_at}}/>
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