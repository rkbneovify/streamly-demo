<?php 
use Laravel\Cashier\Cashier;?>
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
	</style>

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
		jQuery(document).ready(function(){
			jQuery('.remove-device').on('click', function(){
				const id = $(this).data('id');
				const devicename = $(this).data('devicename');
				const accesstoken = $(this).data('accesstoken');
				$('#accesstoken').val(accesstoken); 
			 $('#devicename').html(devicename); 
				$('#deleteFormDevice').attr('action', '/user/sessiondestroy/'+ id );
				jQuery('#removeDevice').modal('show');
			});

			jQuery('.cancel-subscription').on('click', function(){
				jQuery('#cancelSubscription').modal('show');
			});

			jQuery('.change-password').on('click', function(){
				jQuery('#changePassword').modal('show');
			});
		});
	</script>
	<script type="text/javascript">
		@if (count($errors) > 0)
		jQuery('#changePassword').modal('show');
		@endif
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
	<div class="modal fade" id="removeDevice">
		<div class="modal-dialog">
		<div class="modal-content">
 
			<div class="modal-body text-center">
				<h4>Remove Device</h4>
				<p><span id="devicename"></span> will be removed. Would you like to continue?</p>
				<div>
					<form  method="POST" id="deleteFormDevice">
						<input type="hidden" name="_method" value="DELETE">
														<input type="hidden" name="_token" value="{{ csrf_token() }}">
														<input type="hidden" name="access_token" id="accesstoken">
					{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a> --}}
					<input style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" type="submit" class="btn btn-sm" value="Delete">
					<a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">No</a></form>
				</div>
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
				<div class="row setting-cards">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>Device Management:</li>
							
							@foreach($allSession as $key => $user)
							<li> {{$key+1}}. {{ $user->device_name ?? '' }} @if(session()->get('jwttoken')==$user->access_token)<span class="badge badge-success">current</span>@endif
								 <i class="fa fa-trash pull-right light-brown remove-device" aria-hidden="true" data-id="{{ $user->id }}" data-devicename="{{$key+1}}.{{ $user->device_name }}" data-accesstoken="{{ $user->access_token }}"></i></li>
							@endforeach
							
						</ul>
					</div>
				</div>

				<div class="row setting-cards mt-5">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>Profile Management</li>
							<li>
								Email:
							<span class="pull-right">{{Auth::user()->email}}<i class="fa fa-edit light-brown" aria-hidden="true"></i></span>
							</li>
							<li>
								Password:
								<span class="pull-right">
									<a href="javascript:void(0)" class="change-password">Change password</a>
								</span>
							</li>
						</ul>
					</div>
				</div>
				{{-- {{show_currency_symbol( $plan->currency) ?? ''}} --}}
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
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>
								Current Pack
								<b><span class="pull-right">		{{$plan->nickname ?? ''}} at  {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD') }}/{{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}</span></b>
							</li>
							<li>
								{{$plan->nickname ?? ''}} at {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD') }}/{{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}
								<b><span class="pull-right">{{$subscription->created_at ?? ''}}</span></b>
							</li>
							<li>
								Payment method 
								{{-- {{$subscription}} --}}
								<b><span class="pull-right">{{$userinfo->card_brand ?? ''}} {{$userinfo->card_last_four ?? ''}}  <i class="fa fa-edit light-brown" aria-hidden="true"></i></span></b>
								
							</li>
							<li>
								<a href="javascript:void(0)" class="cancel-subscription">Cancel subscription</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="row setting-cards mt-5">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>
								Invoice
								<b><span class="pull-right">Download</span></b>
							</li>
							@foreach ($invoices as $invoice)
							<li>
								<?php if($invoice->date){
									$invoicedate=date('M d, Y',$invoice->date);} else {$invoicedate='';}?>
								{{-- {{$invoice}} --}}
								{{$invoicedate??'' }} {{Cashier::formatAmount($invoice->total??'0', $invoice->currency??'USD')}}
								{{-- {{ $invoice->date()->toFormattedDateString() }} {{ $invoice->total() }} --}}
								{{-- {{$subscription}} --}}
								<b><span class="pull-right"><a  target="_blank" href="{{ $invoice->charge->receipt_url ?? '' }}">Download</a> 
									{{-- <a href="/user/invoice/{{ $invoice->id }}">Download se</a> --}}
								</span></b>
								
							</li>
							@endforeach
						</ul>
					</div>
				</div>
				@else
				<div class="row setting-cards mt-5">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>
								Current Pack
								<b><span class="pull-right">		No Plan Selected</span></b>
							</li>
							
							<li>
								Select Plan
								{{-- {{$subscription}} --}}
								<b><span class="pull-right"><a  href="/selectplan">Select plan</a></span></b>
								
							</li>
						</ul>
					</div>
				</div>
        @endif
				{{-- <div class="row setting-cards mt-5">
					<div class="text-center col-centered col-md-8">
						<a class="btn btn-lg account-save-btn" href="javascript:void(0)">SAVE CHANGES</a>
						<a class="btn btn-lg account-cancel-btn ml-4" style="width: 12rem;" href="javascript:void(0)">Cancel</a>
					</div>
				</div> --}}
		</div>
		
	</section>
	
@endsection