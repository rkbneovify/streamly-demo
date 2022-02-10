@extends('master')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Channel Edit</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Channel Edit</li>
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
          <form action="{{ url("admin/channelupdate", [$model->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')


        </div>
            <div class="form-group{{ $errors->has('StationName') ? ' has-error' : '' }}">
              <label for="StationName" class="col-md-4 control-label">StationName</label>


              <input id="StationName" type="text" class="form-control" name="StationName" value="{{$model->StationName}}" 
                autofocus>

              @if ($errors->has('StationName'))
              <span class="help-block">
                <strong>{{ $errors->first('StationName') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('CallSign') ? ' has-error' : '' }}">
              <label for="CallSign" class="col-md-4 control-label">CallSign</label>


              <input id="CallSign" type="text" class="form-control" name="CallSign" value="{{$model->CallSign}}" 
                autofocus>

              @if ($errors->has('CallSign'))
              <span class="help-block">
                <strong>{{ $errors->first('CallSign') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('Rating') ? ' has-error' : '' }}">
              <label for="Rating" class="col-md-4 control-label">Rating</label>


              <input id="Rating" type="text" class="form-control" name="Rating" value="{{$model->Rating}}" 
                autofocus>

              @if ($errors->has('Rating'))
              <span class="help-block">
                <strong>{{ $errors->first('Rating') }}</strong>
              </span>
              @endif

            </div>

            <div class="form-group{{ $errors->has('EventType') ? ' has-error' : '' }}">
              <label for="EventType" class="col-md-4 control-label">EventType</label>


              <input id="EventType" type="text" class="form-control" name="EventType" value="{{$model->EventType}}" 
                autofocus>

              @if ($errors->has('EventType'))
              <span class="help-block">
                <strong>{{ $errors->first('EventType') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('EventID') ? ' has-error' : '' }}">
              <label for="EventID" class="col-md-6 control-label">EventID</label>


              <input id="EventID" type="text" class="form-control" name="EventID" value="{{$model->EventID}}" 
                autofocus>

              @if ($errors->has('EventID'))
              <span class="help-block">
                <strong>{{ $errors->first('EventID') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('StartDate') ? ' has-error' : '' }}">
              <label for="StartDate" class="col-md-4 control-label">StartDate</label>


              <input id="StartDate" type="text" class="form-control" name="StartDate" value="{{$model->StartDate}}" 
                autofocus>

              @if ($errors->has('StartDate'))
              <span class="help-block">
                <strong>{{ $errors->first('StartDate') }}</strong>
              </span>
              @endif

            </div> 
            <div class="form-group{{ $errors->has('StartDateTime') ? ' has-error' : '' }}">
              <label for="StartDateTime" class="col-md-4 control-label">StartDateTime</label>


              <input id="StartDateTime" type="text" class="form-control" name="StartDateTime" value="{{$model->StartDateTime}}" 
                autofocus>

              @if ($errors->has('StartDateTime'))
              <span class="help-block">
                <strong>{{ $errors->first('StartDateTime') }}</strong>
              </span>
              @endif

            </div> 
            <div class="form-group{{ $errors->has('RunLenHour') ? ' has-error' : '' }}">
              <label for="RunLenHour" class="col-md-4 control-label">RunLenHour</label>


              <input id="RunLenHour" type="text" class="form-control" name="RunLenHour" value="{{$model->RunLenHour}}" 
                autofocus>

              @if ($errors->has('RunLenHour'))
              <span class="help-block">
                <strong>{{ $errors->first('RunLenHour') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('RunLenMinute') ? ' has-error' : '' }}">
              <label for="RunLenMinute" class="col-md-4 control-label">RunLenMinute</label>


              <input id="RunLenMinute" type="text" class="form-control" name="RunLenMinute" value="{{$model->RunLenMinute}}" 
                autofocus>

              @if ($errors->has('RunLenMinute'))
              <span class="help-block">
                <strong>{{ $errors->first('RunLenMinute') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('EventTitle') ? ' has-error' : '' }}">
              <label for="EventTitle" class="col-md-4 control-label">EventTitle</label>


              <input id="EventTitle" type="text" class="form-control" name="EventTitle" value="{{$model->EventTitle}}" 
                autofocus>

              @if ($errors->has('EventTitle'))
              <span class="help-block">
                <strong>{{ $errors->first('EventTitle') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('EventDescription') ? ' has-error' : '' }}">
              <label for="EventDescription" class="col-md-4 control-label">EventDescription</label>


              <input id="EventDescription" type="text" class="form-control" name="EventDescription" value="{{$model->EventDescription}}" 
                autofocus>

              @if ($errors->has('EventDescription'))
              <span class="help-block">
                <strong>{{ $errors->first('EventTEventDescriptionitle') }}</strong>
              </span>
              @endif

            </div>
            <div class="form-group{{ $errors->has('UnixStartDate') ? ' has-error' : '' }}">
              <label for="UnixStartDate" class="col-md-4 control-label">UnixStartDate</label>


              <input id="UnixStartDate" type="text" class="form-control" name="UnixStartDate" value="{{$model->UnixStartDate}}" 
                autofocus>

              @if ($errors->has('UnixStartDate'))
              <span class="help-block">
                <strong>{{ $errors->first('UnixStartDate') }}</strong>
              </span>
              @endif

            </div>
           
             <div class="form-group{{ $errors->has('TimeZoneName') ? ' has-error' : '' }}">
              <label for="TimeZoneName" class="col-md-4 control-label">TimeZoneName</label>


              <input id="TimeZoneName" type="text" class="form-control" name="TimeZoneName" value="{{$model->TimeZoneName}}" 
                autofocus>

              @if ($errors->has('TimeZoneName'))
              <span class="help-block">
                <strong>{{ $errors->first('TimeZoneName') }}</strong>
              </span>
              @endif

            </div>
             <div class="form-group{{ $errors->has('EndDate') ? ' has-error' : '' }}">
              <label for="EndDate" class="col-md-4 control-label">EndDate</label>


              <input id="EndDate" type="text" class="form-control" name="EndDate" value="{{$model->EndDate}}" 
                autofocus>

              @if ($errors->has('EndDate'))
              <span class="help-block">
                <strong>{{ $errors->first('EndDate') }}</strong>
              </span>
              @endif

            </div>
            
            <div class="form-group{{ $errors->has('EndaDateTime') ? ' has-error' : '' }}">
              <label for="EndaDateTime" class="col-md-4 control-label">EndaDateTime</label>


              <input id="EndaDateTime" type="text" class="form-control" name="EndaDateTime" value="{{$model->EndaDateTime}}" 
                autofocus>

              @if ($errors->has('EndaDateTime'))
              <span class="help-block">
                <strong>{{ $errors->first('EndaDateTime') }}</strong>
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
            <div class="form-group{{ $errors->has('StartTime') ? ' has-error' : '' }}">
              <label for="StartTime">StartTime</label>

              <input type="date" name="StartTime" placehoder="StartTime" class="form-control" />
              <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
              @if ($errors->has('StartTime'))
              <span class="help-block">
                <strong>{{ $errors->first('StartTime') }}</strong>
              </span>
              @endif
            </div>
            
            <div class="form-group">
              <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class=" custom-control-input" name="toggleswitch" id="customSwitch3">
                <label class="custom-control-label" for="customSwitch3">Block Channel</label>
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
@stop