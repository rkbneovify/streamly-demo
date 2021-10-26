<?php
use Laravel\Cashier\Cashier; 
//dd($plan);
?>
@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}
		
		.page-section {
		    padding: 8rem 0;
		}

		ul.setting-card {
		    margin: 0 auto;
		    list-style: none;
		    box-shadow: 0px 3px 6px #00000029;
    		padding: 0;
		}

		ul.setting-card li:first-child {
			font-weight: bold;
			background: #004E92 0% 0% no-repeat padding-box;
			padding: 12px 34px;
			letter-spacing: 0.24px;
			color: #FFFFFF;
			border-top-right-radius: 6px;
    		border-top-left-radius: 6px;
		}

		ul.setting-card {
		    margin: 0 auto;
		    color: #5E5E5E;
		}	

		.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		ul.setting-card li:not(:first-child) {
		    padding: 12px 34px;
		    letter-spacing: 0.24px;
		}	

		.light-brown{
			color: #989898;
		}

		.account-save-btn {
			background: #C4C4C4 0% 0% no-repeat padding-box;
			border-radius: 6px;
		}

		.account-cancel-btn {
			border: 1px solid #707070;
			border-radius: 6px;
		}

		.cancel-subscription {
			display: block;
    		text-align: right;
    		color: #CF0000;
		}

		i.fa.fa-trash {
		    cursor: pointer;
		}

		i.fa.fa-edit {
			cursor: pointer;
		}

		.modal-dialog {
			min-height: calc(100vh - 60px);
		    display: flex;
		    flex-direction: column;
		    justify-content: center;
		    overflow: auto;
		}

		.modal-body {
		    position: relative;
		    flex: 1 1 auto;
		    padding: 3rem 5rem;
		}

		.modal-body p{
			letter-spacing: 0.24px;
			color: #5E5E5E;
		}
		.bd-highlight {
    background-color: rgba(86,61,124,.15);
    border: 1px solid rgba(86,61,124,.15);
}

@include('components.deviceCSS')

	</style>

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
		jQuery(document).ready(function(){
			@include('components.deviceJS')

			jQuery('.cancel-subscription').on('click', function(){
				jQuery('#cancelSubscription').modal('show');
			});

			jQuery('.change-password').on('click', function(){
				jQuery('#changePassword').modal('show');
			});

@if ($errors->has('old_password')||$errors->has('password')||$errors->has('password_confirmation') )
		jQuery('#changePassword').modal('show');
		@endif

		});


		</script>
@endsection

@section('content')
	

	<section class="page-section">

		<div class="modal fade" id="cancelSubscription">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					<h4>Cancel Subscription</h4>
					<p>Are you sure you want to cancel your 		{{$plan->nickname ?? ''}} at {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD') }}/{{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}?</p>
					<div>
						<form action="{{ url('user/subscriptiondestroy', Auth::user()->id) }}" method="POST">
							<input type="hidden" name="_method" value="DELETE">
															<input type="hidden" name="_token" value="{{ csrf_token() }}">
															<input style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" type="submit" class="btn btn-sm" value="Yes">

						<a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">No</a>
														</form>
						{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a> --}}
					</div>
				</div>

			</div>
	    </div>
	</div>
		<div class="modal fade" id="changePassword">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
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
					 <form action="{{ url("/userupdate", [Auth::user()->id]) }}" class="text-left" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

						<div class="form-group {{ $errors->has('old_password') ? ' has-error' : '' }}">
							<label for="oldpassword">Old Password</label>
							<input type="password" class="form-control" name="old_password" id="oldpassword">
						</div>

						<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
							<label for="newpassword">New Password</label>
							<input type="text" class="form-control" name="password" id="newpassword">
						</div>

						<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
							<label for="cnewpassword">Confirm New Password</label>
							<input type="password" class="form-control" name="password_confirmation" id="cnewpassword">
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-sm" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">
								Update
							</button>
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						</div>
					</form>
				</div>

			</div>
	    </div>
	</div>
		

@if(session()->has('message'))
 <div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif
		<div class="container">
				<h2 class="section-heading text-center">Account Settings</h2>
				
				


						<div class="row setting-cards mt-5">
				<div class="col-centered col-md-8 ">
				<ul class="setting-card table-responsive " style="
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;">
				<table class="table table-borderless" >
  <thead style="font-weight: bold;
    background: #004E92 0% 0% no-repeat padding-box;
    
    letter-spacing: 0.24px;
    color: #FFFFFF;">

    <tr>
      <th scope="col" colspan="2" class="text-left pl-4">Profile Management</th>
      
    </tr>
  </thead>
  <tbody style="color:#5E5E5E;">
    <tr>
      <td class="text-left text-md-center">Email:</td>
      <td class="text-left text-md-center text-break" >{{Auth::user()->email}}<i class="fa fa-edit light-brown" aria-hidden="true"></i></td>
    </tr>
    <tr>
      <td class="text-left text-md-center">Password:</td>
      <td class="text-left text-md-center text-wrap"><a href="javascript:void(0)" class="change-password">Change password</a></td>
    </tr>
							@if ( Auth::user()->subscribed('main'))
				 <tr>
      <td class="text-left text-md-center text-wrap" colspan="2">
      <details>
    <summary>Get PassCode for logging in TV</summary>
    {{$hasid}}
</details></td>
    </tr>
@endif
   
  </tbody>
</table></ul></div></div>


				@if ( Auth::user()->subscribed('main') && Auth::user()->subscription('main')->cancelled())
				<div class="row setting-cards mt-5">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>
								Current Pack
								<b><span class="pull-right">You canceled you subscription</span></b>
							</li>
							
							<li>
								Select Plan
								{{-- {{$subscription}} --}}
								<b><span class="pull-right"><a  href="/selectplan">Select plan</a></span></b>
								
							</li>
						</ul>
					</div>
				</div>
				
				@elseif (Auth::user()->subscribed('main')) 


<div class="row setting-cards mt-5">
				<div class="col-centered col-md-8 ">
				<ul class="setting-card table-responsive " style="
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;">
				<table class="table table-borderless" >
  <thead style="font-weight: bold;
    background: #004E92 0% 0% no-repeat padding-box;
    
    letter-spacing: 0.24px;
    color: #FFFFFF;">

    <tr>
      <th scope="col" class="text-left pl-4 text-md-center">Current Pack</th>
      <th scope="col" class="text-md-center">{{$plan->nickname ?? ''}} at  {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD') }}/{{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}</th>
      
    </tr>
  </thead>
  <tbody style="color:#5E5E5E;">
    <tr>
      <td class="text-left text-md-center">{{$plan->nickname ?? ''}} at {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD') }}/{{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}:</td>
      <td class="text-left text-md-center text-break" >{{$subscription->created_at ?? ''}}<i class="fa fa-edit light-brown" aria-hidden="true"></i></td>
    </tr>
    <tr>
      <td class="text-left text-md-center">Payment method:</td>
      <td class="text-left text-md-center text-wrap">{{$userinfo->card_brand ?? ''}} {{$userinfo->card_last_four ?? ''}}  <i class="fa fa-edit light-brown" aria-hidden="true"></i></td>
    </tr>
					
				 <tr class=" text-md-center text-wrap">
      <td>
        <a href="javascript:void(0)" class="cancel-subscription">Cancel subscription</a></td>
    </tr>

   
  </tbody>
</table></ul></div></div>

<div class="row setting-cards mt-5">
				<div class="col-centered col-md-8 ">
				<ul class="setting-card table-responsive " style="
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;">
				<table class="table table-borderless" >
  <thead style="font-weight: bold;
    background: #004E92 0% 0% no-repeat padding-box;
    
    letter-spacing: 0.24px;
    color: #FFFFFF;">

    <tr>
      <th scope="col" class="text-left pl-4 text-md-center">Invoice</th>
      <th scope="col" class="text-left text-md-center">Download</th>
      
    </tr>
  </thead>
  <tbody style="color:#5E5E5E;">
  @foreach ($invoices as $invoice)
  							<?php if ($invoice->date)
{
    $invoicedate = date('M d, Y', $invoice->date);
}
else
{
    $invoicedate = '';
} ?>
  <tr>
      <td class="text-left text-md-center">{{$invoicedate??'' }} {{Cashier::formatAmount($invoice->total??'0', $invoice->currency??'USD')}}</td>
      <td class="text-left text-md-center text-break font-weight-bold" ><a  target="_blank" href="{{ $invoice->charge->receipt_url ?? '' }}">Download</a></td>
    </tr>
  @endforeach
    
   
  </tbody>
</table></ul></div></div>

        @endif
				
		</div>
		
	</section>
	<section></section>
	
@endsection