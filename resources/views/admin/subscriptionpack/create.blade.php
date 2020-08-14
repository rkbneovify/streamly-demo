@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create  Subscription pack</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Subscription pack</li>
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
                        
                        
            <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertsubscriptionpack') }}">
              
              @if($errors->first('plan_id')) <p class="text-danger text-sm danger_text">Please refresh this page and try again.</p> @endif
                 
        
        {{ csrf_field() }}
            <div class="card-body">
              {{-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">Name</label>
                
              <input type="text" name="name"    class="form-control"   >
                @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
              </div> --}}
              <div class="form-group">
                <label for="">Plan name: <span class="text-red text-md">*</span></label>
                <input type="text" class="form-control" value="{{old('plan_name')}}" name="plan_name" id="" placeholder="Enter Plan Name...">
                @if($errors->first('plan_name')) <p class="text-danger text-sm danger_text">{{ $errors->first('plan_name') }}</p> @endif
            </div>
              {{-- <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                <label for="duration">Duration(in months)</label>
                <input type="text" name="duration" class="form-control"  >
                
                @if ($errors->has('duration'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('duration') }}</strong>
                                    </span>
                                    @endif
              </div> --}}

              <div class="form-group">
                <label for="">Interval:</label>
                <select name="plan_interval" id="plan_interval">
                    <option value="day">Daily</option>
                    <option value="month">Monthly</option>
                    <option value="year">Yearly</option>
                    <option value="week">Weekly</option>
                    <option value="3-month">Every 3 months</option>
                    <option value="6-month">Every 6 months</option>
                    <option value="custom" id="custom_interval">Custom</option>
                </select>
                    <div class="custom" style="display: none">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="interval_count" value="0" maxlength="3" class="" id="interval_count" style="width: 5%">
                        <select name="coustom_interval" id="custom_interval">
                            <option value="month" id="mnth">months</option>
                            <option value="week" id="week">weeks</option>
                            <option value="day" id="day">days</option>
                        </select>
                    </div>
            </div>
            <div class="form-group{{ $errors->has('product') ? ' has-error' : '' }}">
              <label for="product">Products coming from stripe dashboard</label>

              <select name="product" id="product" class="form-control">
                @foreach($Product as $id => $pack)
                <option value="{{ $pack->id }}">
                  {{ $pack->name }}
                </option>
                @endforeach
              </select>
              @if ($errors->has('product'))
              <span class="help-block">
                <strong>{{ $errors->first('product') }}</strong>
              </span>
              @endif
            </div>
              {{-- <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                <label for="rate">Rate($)</label>
                <input type="text" name="rate" class="form-control" >
                
                @if ($errors->has('rate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                    @endif
              </div> --}}


              <div class="form-group">
                <label for="">Amount: <span class="text-red text-md">*</span></label><span id="symbol"> $</span>
                <input type="text" class="form-control amount" value="{{old('plan_amount')}}" name="plan_amount" id="plan_amount" placeholder="5.25">
                @if($errors->first('plan_amount')) <p class="text-danger text-sm danger_text">{{ $errors->first('plan_amount') }}</p> @endif
            </div>
              <div class="form-group">
                <label for="">Trial period days: <span class="text-red text-md">*</span></label>
                <input type="text" class="form-control amount" value="{{old('trial_period_days')}}" name="trial_period_days" id="trial_period_days" placeholder="10">
                @if($errors->first('trial_period_days')) <p class="text-danger text-sm danger_text">{{ $errors->first('trial_period_days') }}</p> @endif
            </div>
            <div class="form-row">
              <div class="col-md-6">
                  <div class='form-group cvc required'>
                      <label class='control-label'>Text 1</label>
                      <textarea name="text1" class='form-control' rows="5" cols="20"></textarea>
                      <small class="help-block" style="display:none;"></small>
                  </div>
              </div><div class="col-md-6">
                  <div class='form-group zip required'>
                      <label class='control-label'>Text 2</label>
                      <textarea name="text2" class='form-control' rows="5" cols="20"></textarea>
                      <small id="ziphelp" ></small>
                  </div>
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
          Create
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
  
@section('scripts')

<script>
$(document).on('change','#plan_interval',function(e){
  var interval = $('#plan_interval').val();
  if(interval == 'custom'){
      $("#interval_count").val(0);
      $('.custom').css('display','block');
  }
  else{
      $("#interval_count").val(0);
      $('.custom').css('display','none');
  }
});


$('#interval_count').keypress(function (e) {
    var regex = new RegExp("^[0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }else{
        alert("Only number is allowed.");
    }
    e.preventDefault();
    return false;
});


$('#interval_count').on('blur',function () {
    if(this.value > 12 && this.value <= 52){
        $('#week').css('display','block');
        $('#week')
            .removeAttr('selected')
            .attr('selected', true);
        $('#mnth').css('display','none');
    }else if(this.value > 52 && this.value <= 365){
        $('#day')
            .removeAttr('selected')
            .attr('selected', true);
        $('#week').css('display','none');
    }else if(this.value <=12){

        $('#mnth').css('display','block');
        $('#week').css('display','block');

        $('#mnth')
            .removeAttr('selected')
            .attr('selected', true);
    }else{
        $('#interval_count').val(0);
        alert("Please input valid days.");
    }
});
</script>
@stop
