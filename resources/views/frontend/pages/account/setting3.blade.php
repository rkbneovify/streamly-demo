<?php
use Laravel\Cashier\Cashier; 
//dd($invoices->data[0]);
?>
@extends('frontend/partials/master')

@section('stylesheets-bottom')
<script defer src="{{ asset('frontend/jquery-1.12.1-ui.js') }}" ></script> 

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

.ui-autocomplete {
    z-index: 2000; /* Set a higher z-index value than the modal */
}

.ui-widget-content {
    border: 1px solid #dddddd;
    background: #ffffff;
    color: #333333;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

#hash-id{
	display: none;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
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
    		text-align: center;
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
.field_icon{
	float: right;
    margin-right: 10px;
    margin-top: -30px;
    position: relative;
    z-index: 2;
}

.popupwarning {
			display: none;
			position: absolute;
            padding: 30px 20px;
            margin: auto;
			width: 40vw;
			height: fit-content;
			text-align: center;
            left: 0;
			right: 0;
			top: 0;
			bottom: 0;
            z-index: 999;
            background-color: #004e92;
            color: #fff;
            border-radius: 5px;	
			transition: background-color 0.5s ease-out;
		}
		.button {
            background-color: #fff;
            border: none;
            color: #000;
            padding: 6px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
		.geolocation {
			text-align: center;
            margin-top: 1vw;
		}
		.mixs {
            filter: blur(20px);
			pointer-events: none;
		}
		.mixm {
			filter: blur(0px)!important;
			pointer-events: visible!important;
		}

@include('components.deviceCSS')

	</style>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

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

			jQuery('.delete-account').on('click', function(){
				jQuery('#deleteAccount').modal('show');
			});
			
			jQuery('.show-hide').on('click', function(){
				var checkStyle = $('#hash-id').css("display");
				if(checkStyle === "none"){
					$("#hash-id").css("display", "block");
					$("#hide-id").css("display", "none");
					$('.show-hide').text("Hide");
				} else {
					$("#hash-id").css("display", "none");
					$("#hide-id").css("display", "block");
					$('.show-hide').text("Show");
				}
			});

			jQuery('.change-name').on('click', function(){
				jQuery('#changeName').modal('show');
			});

			jQuery('.change-zipcode').on('click', function(){
				jQuery('#changeZipcode').modal('show');
			});

@if ($errors->has('old_password')||$errors->has('password')||$errors->has('password_confirmation'||$errors->has('Error')) )
		jQuery('#changePassword').modal('show');
		@endif

		@if(session()->has('deleteerror'))
        jQuery('#deleteAccount').modal('show');
		@endif
		
		@if(session()->has('errormsgname'))
		jQuery('#changeName').modal('show');
		@endif

		@if(session()->has('message2'))
        jQuery('#changePassword').modal('show');
		@endif

		@if(session()->has('errormsg'))
		jQuery('#changeZipcode').modal('show');
		@endif

		@if(session()->get('message') == 'Password Updated Successfully')
        const targetDiv = document.getElementById("hideElement");
		const targetNav = document.getElementById("mainNav");
     	targetDiv.style.display = "block";
		targetNav.style.display = "none";
		jQuery('#blur').addClass('mixs');
		setTimeout(
        function() { 
			window.location.href = '/logout';
        }, 2000);
		@endif

		});

		</script>
@endsection

@section('content')

            <div id="hideElement" class="popupwarning">
			Your Password Updated Successfully. Redirecting You To Login Screen.
			</div>
	

	<section id="blur" class="page-section">

		<div class="modal fade" id="cancelSubscription">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					<h4>Cancel Subscription</h4>
					<p>Are you sure you want to cancel your 		{{$plan->nickname ?? ''}} at {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD') }}/{{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}?</p>
					<div>

					
					<form></form>


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
	<div class="modal fade" id="changeName">
	    <div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body text-center">

				@if(session()->has('errormsgname'))
					<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>{{ session()->get('errormsgname') }}</strong>
					</div>
              @endif
				<form action="{{ url("/userupdatename", [Auth::user()->id]) }}" id="my_form" method = "POST" class="login-form mt-3" autocomplete="off">
				@csrf
            @method('PUT')
						<div class="form-group" id="second-element-introduction" >
								<label for="name" class="control-label text-center">Name</label>
								<input  type="text"  id="name" name="name" class="form-control text-center" required>
						</div>
						<div class="form-group " id="fourth-element-introduction" >
								<button type="submit" class="btn btn-lg login-btn">Submit</button>
						</div>
						{!! csrf_field() !!}
				</form>
				</div>
			</div>
	    </div>
	</div>

	<div class="modal fade" id="changeZipcode">
	    <div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body text-center">

				@if(session()->has('errormsg'))
					<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>{{ session()->get('errormsg') }}</strong>
					</div>
              @endif
				<form action="{{ url("/userupdatezip", [Auth::user()->id]) }}" id="my_form" method = "POST" class="login-form mt-3" autocomplete="off">
				@csrf
            @method('PUT')
						<div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}" id="second-element-introduction" >
								<label for="zip" class="control-label text-center">Enter your zipcode</label>
								<input  type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" placeholder="Enter your Zip" id="zip" name="zip" class="form-control text-center"  required><span id="zipanswer"></span>
						</div>
							<div class="form-group">
							<label for="City" class="control-label">City</label>
							<input type="text" id="City" name="City" class="form-control" disabled>
					</div>			<div class="form-group">
							<label for="State" class="control-label">State</label>
							<input type="text" id="State" name="State" class="form-control" disabled>
					</div>
						<small id="ziphelp" ></small>
						<small id="addresshelp" ></small>
						<div class="form-group " id="fourth-element-introduction" >
								<button type="submit" class="btn btn-lg login-btn">Submit Zip</button>
						</div>



						{!! csrf_field() !!}
				</form>
				</div>

			</div>
	    </div>
	</div>

	<div class="modal fade" id="deleteAccount">
	    <div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body text-center">
		  		@if(session()->has('deleteerror'))
					<div class="alert alert-danger">
					<li>{{ session()->get('deleteerror') }}</li>
					</div>
              	@endif
					 <form action="{{ url("/userdelete", [Auth::user()->id]) }}" class="text-left" method="POST" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<strong>Are sure you want to permanently delete your account?</strong>
						<br>
						<p>* This cannot be Undone</p>
						<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
							<label for="password">Password</label>
							<div>
							<input type="password" minlength="6" class="form-control" name="passworddelete" id="passworddelete" required>
							<span class="bi bi-eye-slash field_icon" id="togglePasswordDelete"></span>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-lg login-btn">
								Delete Account
							</button>
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						</div>
					</form>
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
		  @if(session()->has('message2'))
			  <div class="alert alert-danger">
			  <strong>Whoops!</strong> There were some problems with your input.<br><br>
			  <li>{{ session()->get('message2') }}</li>
			  </div>
              @endif
					 <form action="{{ url("/userupdate", [Auth::user()->id]) }}" class="text-left" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
						@if($userinfo->provider_name == 'google' && !($userinfo->password))
						@else
						<div class="form-group {{ $errors->has('old_password') ? ' has-error' : '' }}">
							<label for="oldpassword">Old Password</label>
							<input type="password" class="form-control" name="old_password" id="oldpassword" required>
						</div>
						@endif

						<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
							<label for="newpassword">New Password</label>
							<div>
							<input type="password" minlength="6" class="form-control" name="password" id="newpassword" required>
							<span class="bi bi-eye-slash field_icon" id="togglePasswordnew"></span>
							</div>
						</div>

						<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
							<label for="cnewpassword">Confirm New Password</label>
					
						
							<div>
							<input type="password" minlength="6" class="form-control" name="password_confirmation" id="cnewpassword" required>
							<span class="bi bi-eye-slash field_icon" id="togglePassword"></span>
							</div>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-lg login-btn">
								Update
							</button>
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						</div>
					</form>
				</div>

			</div>
	    </div>
	</div>
<script>

		document.getElementById('newpassword').addEventListener('input', function (event) {
					// Remove spaces from the input value
					event.target.value = event.target.value.replace(/\s/g, '');
				});
		document.getElementById('cnewpassword').addEventListener('input', function (event) {
			// Remove spaces from the input value
			event.target.value = event.target.value.replace(/\s/g, '');
		});
        const togglePasswordnew = document.querySelector("#togglePasswordnew");
        const passwordnew = document.querySelector("#newpassword");
		const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#cnewpassword");

        togglePasswordnew.addEventListener("click", function () {
            // toggle the type attribute
            const type = newpassword.getAttribute("type") === "password" ? "text" : "password";
            newpassword.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });
		togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

		togglePasswordDelete.addEventListener("click", function () {
            // toggle the type attribute
            const type = passworddelete.getAttribute("type") === "password" ? "text" : "password";
            passworddelete.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        // prevent form submit
        const form = document.querySelector("form");
        form.addEventListener('submit', function (e) {
            e.preventDefault();
        });
</script>

		

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
      <th scope="col" colspan="3" class="text-left pl-4">Profile Management</th>
      
    </tr>
  </thead>
  <tbody style="color:#5E5E5E;">
  	<tr>
      <td class="text-left text-md-center">Name:</td>
      <td class="text-left text-md-center text-break" >{{Auth::user()->name}}</td>
	  <td class="text-left text-md-center text-wrap"><a href="javascript:void(0)" class="change-name">Edit</a></td>
    </tr>
    <tr>
      <td class="text-left text-md-center">Email:</td>
      <td class="text-left text-md-center text-break" >{{Auth::user()->email}}</td>
    </tr>
	<tr>
      <td class="text-left text-md-center">Home Timezone</td>
      <td class="text-left text-md-center text-wrap">{{ $timezone }}</td>
    </tr>
	<tr>
      <td class="text-left text-md-center">Current Timezone</td>
      <td id="timezone" class="text-left text-md-center text-wrap"></td>
    </tr>
	@if ( Auth::user()->subscribed('main') && Auth::user()->subscription('main')->active()) 
	<tr>
      <td class="text-left text-md-center">Zipcode:</td>
      <td class="text-left text-md-center text-wrap">{{$zipcode}}</td>
    </tr>
	@else
	<tr>
      <td class="text-left text-md-center">Zipcode:</td>
	  <td class="text-left text-md-center text-wrap">{{$zipcode}}</td>
	  <td class="text-left text-md-center text-wrap"><a href="javascript:void(0)" class="change-zipcode">Edit</a></td>
    </tr>
	@endif
	<tr>
      <td class="text-left text-md-center">Password:</td>
      <td class="text-left text-md-center text-wrap">*****</td>
	  <td class="text-left text-md-center text-wrap"><a href="javascript:void(0)" class="change-password">Edit</a></td>
    </tr>



	@if ( Auth::user()->subscribed('main'))
	<tr>
      <td class="text-left text-md-center">PassCode:</td>
      <td class="text-left text-md-center text-wrap" id="hash-id">{{$hasid}}</td>
	  <td class="text-left text-md-center text-wrap" id="hide-id">******</td>
	  <td class="text-left text-md-center text-wrap"><a href="javascript:void(0)" class="show-hide">Show</a></td>
    </tr>
	@endif
	<tr>
	<td class="text-left text-md-center"></td>
	<td class="text-left text-md-center"><a href="javascript:void(0)" class="delete-account">Delete Account</a> </td>
	</tr>
  </tbody>
</table></ul></div></div>

@if ($plan == null)
<div class="row setting-cards mt-5">
				<div class="col-centered col-md-8 ">
				<ul class="setting-card table-responsive " style="
    border-top-right-radius: 6px;
    border-top-left-radius: 6px;">
  <summary style="text-align: center; color: red; font-weight: bold; padding: 1rem 0px;">Other user details are not visible since your account is a test user account, and our site is operating in live mode.</summary>
</ul></div></div>
@endif


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
				
				@elseif (Auth::user()->subscribed('main') && $plan !== null) 
@if (!empty($plan->tiers))

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
      <th scope="col" class="text-md-center"><details>
						<summary>
							<h3>Tiers {{$plan->interval_count}} {{$plan->interval}}</h3></summary>
       @component('components.tiertable',['singlePlansDetail' => $plan,'type'=>'tiered'])
		
		@endcomponent</th>
      
    </tr>
  </thead>
  <tbody style="color:#5E5E5E;">
    <tr>
      <td class="text-left text-md-center">{{$plan->nickname ?? ''}}
 {{"with ".$subscription->quantity ." quantity" ?? ''}} {{Cashier::formatAmount($invoices->data[0]->total??'0', $invoices->data[0]->currency??'USD') }}
	per {{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}</td>
      <td class="text-left text-md-center text-break" >{{$subscription->created_at ?? ''}}</td>
    </tr>
    <tr>
      <td class="text-left text-md-center">Payment method:</td>
      <td class="text-left text-md-center text-wrap">{{$userinfo->card_brand ?? ''}} {{$userinfo->card_last_four ?? ''}}  </td>
    </tr>
					
				 <tr class=" text-md-center text-wrap">
      <td>
        <a href="javascript:void(0)" class="cancel-subscription">Cancel subscription</a></td>
    </tr>

   
  </tbody>
</table></ul></div></div>

@else

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
      <th scope="col" class="text-md-center">Plan Renewal Date</th>
      
    </tr>
  </thead>
  <tbody style="color:#5E5E5E;">
    <tr>
      <td class="text-left text-md-center">{{$plan->nickname ?? ''}} {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD') }}/{{$plan->interval_count ?? ''}} {{$plan->interval ?? ''}}:</td>
      <td class="text-left text-md-center text-break" >{{$renewaldatetime ?? ''}}</td>
    </tr>
    <tr>
      <td class="text-left text-md-center">Payment method:</td>
      <td class="text-left text-md-center text-wrap">{{$userinfo->card_brand ?? ''}} {{$userinfo->card_last_four ?? ''}}  </td>
    </tr>
					
				 <tr class=" text-md-center text-wrap">
      <td colspan=2>
        <a href="javascript:void(0)" class="cancel-subscription">Cancel subscription</a></td>
    </tr>

   
  </tbody>
</table></ul></div></div>


@endif

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
      <th scope="col" colspan="2" class="text-left pl-4">Card Details</th>
    </tr>
  </thead>
  <tbody style="color:#5E5E5E;">
  <tr>
      <td class="text-left text-md-center">Card Type</td>
      <td class="text-left text-md-center text-break" >{{$userinfo->card_brand}}</td>
    </tr>
    <tr>
      <td class="text-left text-md-center">Card No:</td>
      <td class="text-left text-md-center text-wrap"><img src="/uploads/icons/creditcard.png" width="40"> ****{{$userinfo->card_last_four}}</td>
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
      <th scope="col" class="text-left pl-4 text-md-center">Invoice {{$invoices->paid}}</th>
	  <th scope="col" class="text-left text-md-center">Date</th>
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
	  @if($invoice->paid == '1')
	  <td class="text-left text-md-center">{{ date("Y-m-d H:i:s", $invoice->status_transitions->paid_at) }}</td>
	  <td class="text-left text-md-center text-break font-weight-bold" ><a  target="_blank" href="{{ $invoice->charge->receipt_url ?? '' }}">View Receipt</a></td>
	  @else
	  	  <td class="text-left text-md-center">{{ date("Y-m-d H:i:s", $invoice->status_transitions->paid_at) }}</td>
	  <td class="text-left text-md-center text-break font-weight-bold" ><a  target="_blank" href="{{ $invoice->charge->receipt_url ?? '' }}">View Receipt</a></td>
	  @endif
    </tr>
  @endforeach
    
   
  </tbody>
</table></ul></div></div>

        @endif
				
		</div>
		
	</section>
	<section></section>
	<script>
	//hoursoffset start
	String.prototype.toHHMMSS = function () {

var sec_num = parseInt(this, 10); // don't forget the second param
var hours   = Math.floor(sec_num / 3600);
var minutes = Math.floor((sec_num - (hours * 3600)) / 60);


if (hours   < 10) {hours   = "0"+hours;}
if (minutes < 10) {minutes = "0"+minutes;}

return hours + ':' + minutes ;
}
const d = new Date();
const datee = d.toTimeString()

const pattern = /\(([^)]+)\)/;

// Extract the content inside parentheses using match() method
const matches = datee.match(pattern);

// Extracted timezone value is in matches[1]
const timezone12 = matches ? matches[1] : null;

let diff = d.getTimezoneOffset();
var newdiff = String(diff * 60);
if (newdiff < 0) {
  var resultHours = newdiff.substr(1);
  var signvalue = true;
}else{
	var resultHours = newdiff;
	var signvalue = false;
  }  
var newtz = resultHours.toHHMMSS();
if(signvalue == true){
var resultoffset = "+".concat(newtz)
}else{ 
	var resultoffset = "-".concat(newtz);
	}
	console.log(resultoffset)
	//hoursoffset end
	/* if(resultoffset == "+05:30"){
		var timezone = "Indian Standard Time";
	}else if(resultoffset == "-05:00"){
		var timezone = "Eastern Standard Time";
	}else if(resultoffset == "-08:00"){
		var timezone = "Pacific Standard Time";
	}else if(resultoffset == "-06:00"){
		var timezone = "Central Standard Time";
	}else if(resultoffset == "-07:00"){
		var timezone = "Mountain Standard Time";
	}else if(resultoffset == "-09:00"){
		var timezone = "Alaska Standard Time";
	}else if(resultoffset == "-10:00"){
		var timezone = "Hawaii Standard Time";
	}else if(resultoffset == "-04:00"){
		var timezone = "Canada Atlantic Time";
	}else if(resultoffset == "-03:30"){
		var timezone = "Newfoundland Time";
	}else{
		var timezone = "GMT".concat(resultoffset)
	} */
	document.getElementById("timezone").innerHTML = timezone12;
	</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyDZUPtq5LXm8bbcX5P-z0r-B08V65xq2aw",
    authDomain: "streamly-tv-android.firebaseapp.com",
    databaseURL: "https://streamly-tv-android.firebaseio.com",
    projectId: "streamly-tv-android",
    storageBucket: "streamly-tv-android.appspot.com",
    messagingSenderId: "187207008307",
    appId: "1:187207008307:web:40473f0b50d96ab0d76346",
    measurementId: "G-5DTH6FMY71"
    };
	firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
	messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });
</script>	
<script>
$(function() {
$( "#zip" ).autocomplete({
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
           $('#zip').val(ui.item.label); // display the selected text
          //  $('#employeeid').val(ui.item.value); // save selected id to input
					$('#City').val(ui.item.city); 
			$('#State').val(ui.item.state); 
           return false;
        },
				open: function() {
        $("ul.ui-menu").width( $(this).innerWidth() );
        
    }
      });
	});
</script>
@endsection