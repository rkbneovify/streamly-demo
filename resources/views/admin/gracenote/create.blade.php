@extends('master')
@section('content')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Channel</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Channel</li>
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
    <div class="card-body">
    <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertuser') }}">
    {{ csrf_field() }}
    <div class="card-body">
    <div class="form-group">
        <label for="CallSign">CallSign</label>
        <input type="text" class="form-control {{ $errors->has('CallSign') ? ' is-invalid' : '' }}" name="CallSign" id="CallSign" placeholder="" maxlength="32" required="required" >
          @if($errors->has('CallSign'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('CallSign') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="Rating">Rating</label>
        <input type="text" class="form-control {{ $errors->has('Rating') ? ' is-invalid' : '' }}" name="Rating" id="Rating"  placeholder="" maxlength="32" >
          @if($errors->has('Rating'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('Rating') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="EventType">EventType</label>
        <input type="text" class="form-control {{ $errors->has('EventType') ? ' is-invalid' : '' }}" name="EventType" id="EventType"  placeholder="" maxlength="8" >
          @if($errors->has('EventType'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('EventType') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="EventID">EventID</label>
        <input type="number" class="form-control {{ $errors->has('EventID') ? ' is-invalid' : '' }}" name="EventID" id="EventID"  placeholder="" >
          @if($errors->has('EventID'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('EventID') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="StartDate">StartDate</label>
        <input type="number" class="form-control {{ $errors->has('StartDate') ? ' is-invalid' : '' }}" name="StartDate" id="StartDate"  placeholder="" >
          @if($errors->has('StartDate'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('StartDate') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="StartTime">StartTime</label>
        <input type="number" class="form-control {{ $errors->has('StartTime') ? ' is-invalid' : '' }}" name="StartTime" id="StartTime"  placeholder="" >
          @if($errors->has('StartTime'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('StartTime') }}</strong>
    </div>
  @endif 
    </div>

<div class="form-group">
    <label for="StartDateTime">StartDateTime</label>
    <div class="input-group">
        <input type="datetime" class="form-control {{ $errors->has('StartDateTime') ? ' is-invalid' : '' }}" name="StartDateTime" id="StartDateTime"
               
               placeholder="" >
        <div class="input-group-addon">
            <label for="StartDateTime" class="fa fa-calendar">
            </label>
        </div>
    </div>
      @if($errors->has('StartDateTime'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('StartDateTime') }}</strong>
    </div>
  @endif
</div>

    <div class="form-group">
        <label for="RunLenHour">RunLenHour</label>
        <input type="number" class="form-control {{ $errors->has('RunLenHour') ? ' is-invalid' : '' }}" name="RunLenHour" id="RunLenHour"  placeholder="" >
          @if($errors->has('RunLenHour'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('RunLenHour') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="RunLenMinute">RunLenMinute</label>
        <input type="number" class="form-control {{ $errors->has('RunLenMinute') ? ' is-invalid' : '' }}" name="RunLenMinute" id="RunLenMinute"  placeholder="" >
          @if($errors->has('RunLenMinute'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('RunLenMinute') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="EventTitle">EventTitle</label>
        <input type="number" class="form-control {{ $errors->has('EventTitle') ? ' is-invalid' : '' }}" name="EventTitle" id="EventTitle"  placeholder="" >
          @if($errors->has('EventTitle'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('EventTitle') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="EventDescription">EventDescription</label>
        <input type="text" class="form-control {{ $errors->has('EventDescription') ? ' is-invalid' : '' }}" name="EventDescription" id="EventDescription"  placeholder="" maxlength="256" >
          @if($errors->has('EventDescription'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('EventDescription') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="UnixStartDate">UnixStartDate</label>
        <input type="text" class="form-control {{ $errors->has('UnixStartDate') ? ' is-invalid' : '' }}" name="UnixStartDate" id="UnixStartDate"  placeholder="" maxlength="32" >
          @if($errors->has('UnixStartDate'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('UnixStartDate') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="TimeZoneName">TimeZoneName</label>
        <input type="text" class="form-control {{ $errors->has('TimeZoneName') ? ' is-invalid' : '' }}" name="TimeZoneName" id="TimeZoneName"  placeholder="" maxlength="64" >
          @if($errors->has('TimeZoneName'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('TimeZoneName') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="EndDate">EndDate</label>
        <input type="number" class="form-control {{ $errors->has('EndDate') ? ' is-invalid' : '' }}" name="EndDate" id="EndDate"  placeholder="" >
          @if($errors->has('EndDate'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('EndDate') }}</strong>
    </div>
  @endif 
    </div>

<div class="form-group">
    <label for="EndaDateTime">EndaDateTime</label>
    <div class="input-group">
        <input type="datetime" class="form-control {{ $errors->has('EndaDateTime') ? ' is-invalid' : '' }}" name="EndaDateTime" id="EndaDateTime"
               
               placeholder="" >
        <div class="input-group-addon">
            <label for="EndaDateTime" class="fa fa-calendar">
            </label>
        </div>
    </div>
      @if($errors->has('EndaDateTime'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('EndaDateTime') }}</strong>
    </div>
  @endif
</div>

                              
                                
                    

                  
            
            <div>
                <input class="btn btn-danger" type="submit" value="Save">
            </div>
        </form>
    </div>
</div>
@endsection