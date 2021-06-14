<?php 
use Laravel\Cashier\Cashier;?>
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

    @if (!empty($plan->tiers))
    <div class="form-group {{ $errors->has('created') ? ' has-error' : '' }}">
              
              <label for="created">Tier</label>
              
    <table class="table table-bordered">
	<thead >
		<tr >
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><span>Quantity</span></span>
				</div>
			</td>
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><span>Price per unit</span></span>
				</div>
			</td>
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><span>Flat amount</span></span>
				</div>
			</td>
		</tr>
	</thead>
	<tbody >
  @foreach($plan->tiers as $id => $tier)
    @if($loop->first)
    <tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the first <strong>{{$id+1}} to {{ $tier->up_to }}</strong></span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @if(!($id==0) && !($id == array_key_last($plan->tiers)))
		<tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the next <strong>{{$plan->tiers[$id-1]->up_to + 1}}   to {{$tier->up_to}}</strong></span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @if($loop->last)  
		<tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the rest {{ $tier->up_to }}</span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @endforeach
	</tbody>
</table>
 </div>
@elseif (!empty($plan->amount))
<div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                <label for="rate">Rate($)</label>
                <input type="text" name="rate" class="form-control" disabled value="{{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD')}} ">
                
                @if ($errors->has('rate'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                    @endif
              </div>
@else
unknown
@endif
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
                <label for="">Trial period days: <span class="text-red text-md">*</span></label>
                <input type="text" class="form-control amount" value="{{ $plan->trial_period_days }}" name="trial_period_days" id="trial_period_days" placeholder="10">
                @if($errors->first('trial_period_days')) <p class="text-danger text-sm danger_text">{{ $errors->first('trial_period_days') }}</p> @endif
            </div>
            <div class="form-row" id="dynamic_field">
              @foreach ($plan->metadata->jsonSerialize() as $key => $value)
              <div class="col-md-6">
                <div class='form-group cvc required'>
                    <label class='control-label'>{{$key}}</label>
                    <textarea name="text[{{$key}}]" class='form-control' rows="5" cols="20">{{$value}}</textarea>
                    <small class="help-block" style="display:none;"></small>
                </div>
            </div>
                    
                    @endforeach
              {{-- <div class="col-md-6">
                  <div class='form-group cvc required'>
                      <label class='control-label'>Text 1</label>
                      <textarea name="text1" class='form-control' rows="5" cols="20">{{$plan->metadata->text1}}</textarea>
                      <small class="help-block" style="display:none;"></small>
                  </div>
              </div><div class="col-md-6">
                  <div class='form-group zip required'>
                      <label class='control-label'>Text 2</label>
                      <textarea name="text2" class='form-control' rows="5" cols="20">{{$plan->metadata->text2}}</textarea>
                      <small id="ziphelp" ></small>
                  </div>
              </div> --}}
              <div class="row">  <div class="col-12"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></div></div>
                 
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

  @section('scripts')
  <script>
     $(document).ready(function(){ 
      var i=1;
      var number_of_child_divs =  $('#dynamic_field').children('.col-md-6').length;
      var j=0;
    $('#add').click(function(){  
      j=i+ number_of_child_divs;
    i++;  
    
    $('#dynamic_field').append(`<div class="col-md-6" id="row`+i+`" ><div class="form-group cvc required"><label class="control-label">Text `+j+` </label>
        <textarea name="text[text`+j+`]" class="form-control" rows="5" cols="20"></textarea> <small class="help-block" style="display:none;"></small> </div>
      <div class="row"><button type="button" name="remove" id="`+i+`" class="btn btn-danger btn_remove">X</button></div></div>`);  
});  


$(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();  
});     }); 
  </script>
  
@stop