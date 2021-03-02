@extends('master')
@section('stylesheets')
<script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
  .ui-widget.ui-widget-content {
    border: 1px solid #c5c5c5;
}
.ui-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    display: block;
    outline: 0;
}
.ui-widget-content {
    border: 1px solid #dddddd;
    background: #ffffff;
    color: #333333;
}

  				.ui-menu .ui-menu-item-wrapper {
    position: relative;
    padding: 3px 1em 3px .4em;
}

.ui-menu .ui-menu-item {
    margin: 0;
    cursor: pointer;
    list-style-image: url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7);
}

.ui-menu-item .ui-menu-item-wrapper.ui-state-active {
    background:#004E92 !important;
    font-weight: bold !important;
    color: #ffffff !important;
} 

</style>
@stop
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>User Edit</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">User Edit</li>
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
          <form action="{{ url("admin/userupdate", [$user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


        </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class="col-md-4 control-label">Name</label>


              <input id="display_name" type="text" class="form-control" name="name" value="{{$user->name}}" required
                autofocus>

              @if ($errors->has('name'))
              <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
              <label for="contact" class="col-md-4 control-label">contact</label>


              <input id="contact" type="text" class="form-control" name="contact" value="{{$user->contact}}" 
                autofocus>

              @if ($errors->has('contact'))
              <span class="help-block">
                <strong>{{ $errors->first('contact') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
              <label for="gender" class="col-md-4 control-label">gender</label>


              <input id="gender" type="text" class="form-control" name="gender" value="{{$user->gender}}" 
                autofocus>

              @if ($errors->has('gender'))
              <span class="help-block">
                <strong>{{ $errors->first('gender') }}</strong>
              </span>
              @endif

            </div>

            <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
              <label for="country" class="col-md-4 control-label">country</label>


              <input id="country" type="text" class="form-control" name="country" value="{{$user->country}}" 
                autofocus>

              @if ($errors->has('country'))
              <span class="help-block">
                <strong>{{ $errors->first('country') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
              <label for="address" class="col-md-6 control-label">address</label>


              <input id="address" type="text" class="form-control" name="address" value="{{$user->address}}" 
                autofocus>

              @if ($errors->has('address'))
              <span class="help-block">
                <strong>{{ $errors->first('address') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-4 control-label">E-Mail</label>


              <input id="email" type="text" class="form-control" name="email" value="{{$user->email}}" required
                autofocus>

              @if ($errors->has('email'))
              <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
              @endif

            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-md-4 control-label">Password</label>


              <input id="password" type="password" class="form-control" name="password" autofocus>
              @if ($errors->has('password'))
              <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
              @endif

            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
              <label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>


              <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"
                autofocus>
              @if ($errors->has('password_confirmation'))
              <span class="help-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
              </span>
              @endif

            </div>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <div class="col-md-6">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Info</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
              <label for="dob">Date of birth</label>

              <input type="date" name="dob" placehoder="dob" class="form-control" />
              <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
              @if ($errors->has('dob'))
              <span class="help-block">
                <strong>{{ $errors->first('dob') }}</strong>
              </span>
              @endif
            </div>
            <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
              <label for="roles" class="col-md-4 control-label">Roles</label>



              <select id="role" class="form-control" name="roles[]">
                @foreach ($roles as $role)
                <option value="{{$role->id}}" {{in_array($role->id, $userRoles) ? "selected" : null}}>
                  {{$role->display_name}}
                </option>
                @endforeach
              </select>

              @if ($errors->has('roles'))
              <span class="help-block">
                <strong>{{ $errors->first('roles') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
              <label for="zipcode" class="col-md-4 control-label">zipcode</label>

              
                <input id="zipcode" type="text" class="form-control" name="zipcode" autofocus value="{{$user->zipcode}}" >

                @if ($errors->has('zipcode'))
                <span class="help-block">
                  <strong>{{ $errors->first('zipcode') }}</strong>
                </span>
                @endif
              
            </div>
            <div class="form-group">
              <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class=" custom-control-input" name="toggleswitch" id="customSwitch3">
                <label class="custom-control-label" for="customSwitch3">Block user</label>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <div class="form-group">
          <div class="col-md-8 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
              Update
            </button>

            <a class="btn btn-link" href="{{ url('admin/users') }}">
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

@section('scripts')
<script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript">

  $(document).ready(function(){	
         $( "#zipcode" ).autocomplete({
    source: function( request, response ) {
      // Fetch data
      $.ajaxSetup({
              headers: {
                  
'X-CSRF-TOKEN': $("input[name=_token]").val()
              }
          });
      $.ajax({
              url: "{{ url('/getzipcodes') }}",
        type: 'post',
        dataType: "json",
        data: {
           search: request.term
        },
        success: function( data ) {
           response( data );
          //  console.log(data);
        }
      });
    },
    select: function (event, ui) {
       // Set selection
       $('#zipcode').val(ui.item.label); // display the selected text
      //  $('#employeeid').val(ui.item.value); // save selected id to input
       return false;
    },
    open: function() {
    $("ul.ui-menu").width( $(this).innerWidth() );
    
}
  });
  });
  </script>

@stop