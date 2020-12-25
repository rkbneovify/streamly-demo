@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets')
<script src="https://unpkg.com/driver.js/dist/driver.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/driver.js/dist/driver.min.css">
<style>


        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
    </style>
@endsection
@section('stylesheets-bottom')
<script type="text/javascript">

@include('frontend.partials.sidenavRegisterAndLoginJS')
// introJs().start();
const driver = new Driver();
// Define the steps for introduction
driver.defineSteps([
  {
    element: '#first-element-introduction',
    popover: {
      className: 'first-step-popover-class',
      title: 'Welcome to check availbilty page',

      description: 'This page will tell you if our service is available in your location',
      position: 'bottom'
    }
  },
  {
    element: '#second-element-introduction',
    popover: {
      title: 'Enter you zip',
      description: 'enter zip code and wait it to fill the city and state',
      position: 'bottom'
    }
  },
  {
    element: '#third-element-introduction',
    popover: {
      title: 'Add your address',
      description: 'Add you address and click on submit ',
      position: 'bottom'
    }
  },
  {
    element: '#fourth-element-introduction',
    popover: {
      title: 'Click on submit',
      description: 'Click on submit if address is validated then you can start your trial',
      position: 'bottom'
    }
  },
]);
// Start the introduction
driver.start();

$("#my_form").submit(function(event){
	event.preventDefault(); //prevent default action 
	var zipnumber = $("[name='zip']");
	var city = $("[name='City']");
	var state = $("[name='State']");
	var address = $("[name='Address2']");
	// alert(zip); exit;

	$.ajax({
							 
							 url:"{{ route('address-check') }}",
					 
							 type:"GET",
							 data:{zip: zipnumber.val(),City:city.val(),State:state.val(),Address2:address.val()},
							 dataType: 'json',
							 success: function (data) {
								 $('#card-button').prop('disabled', false);
								 $('#addresshelp').html('<p style="color:green">'+data.message+'</p>');
							 },
							 error:function(xhr, status, error){ 
								 if(xhr.status==404) {
									 // alert('status:' + xhr.status + ', status text: ' + xhr.responseText);
									 $('#card-button').prop('disabled', true);
								 $('#addresshelp').html('<p style="color:red">'+xhr.responseJSON.message+'.</p>');
												 
						 
							 }
									// coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
							 }
					 });
	
});

$(function() {

$('body').on('blur','[name="zip"]',function(){

	var zipnumber = $("[name='zip']");
	// alert(zip); exit;

	$.ajax({
							 
							 url:"{{ route('ajax-check') }}",
					 
							 type:"GET",
							 data:{zip: zipnumber.val()},
							 dataType: 'json',
							 success: function (data) {
								 $('#card-button').prop('disabled', false);
								 $('#City').val(data.city); 
			$('#State').val(data.state); 
			$('#address-country').val("US"); 
								 $('#ziphelp').html('<p style="color:green">'+data.message+' <a href="javascript:void(0)" onclick="openNavReg();"style="padding: 0;">Start you trial now!</a></p>');
							 },
							 error:function(xhr, status, error){ 
								 if(xhr.status==404) {
									 // alert('status:' + xhr.status + ', status text: ' + xhr.responseText);
									 $('#card-button').prop('disabled', true);
								 $('#ziphelp').html('<p style="color:red">'+xhr.responseJSON.message+' Our apologies, your location is outside of the streamly service area. Please call 1111111111 for more information.</p>');
												 
						 
							 }
									// coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
							 }
					 });
	
});});
</script>
	<style>
		nav#mainNav {
			background-color: #004E92;
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


@endsection

@section('content')
@if(session()->has('message'))
 <div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif

@include('frontend.partials.sidenavRegisterAndLogin')

	<section class="page-section">
		<div class="container">
				<h2 class="section-heading text-center" id="first-element-introduction" >Check Availability</h2><div style="text-align: center; "><p  style=" display: inline-block; background-color: #eff0f1; border-radius: 6px; padding-right: 1rem; padding-left: 1rem; "><img src="{{url('../frontend/img/location.png')}}" 
  
					alt="location image" style="">To display pricing and offers available for your location, please enter an address.</p></div>
				<div class="row setting-cards">
					<div class=" col-md-6 " style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 4px; border: 1px solid #CED4DA;">
						<ul class="setting-card">
							
							
								@if($errors->has('email'))
								 <div class="alert alert-danger alert-dismissible ">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Error!</strong> {{$errors->first()}}
								</div>
						@endif 
									<form  id="my_form" class="login-form mt-3">
										@csrf
											<div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}" id="second-element-introduction" >
													{{-- <label for="email" class="control-label text-center">Email</label> --}}
													<input type="number"  placeholder="Enter your Zip" id="zip" name="zip" class="form-control text-center">
											</div>
											<div class="form-group" id="third-element-introduction" >
												<label for="Address2"  class="control-label ">Address</label>
												<input type="text" id="Address2" name="Address2" class="form-control" >
											<pre>222 Example St.</pre>	
										</div>			<div class="form-group">
												<label for="City" class="control-label">City</label>
												<input type="text" id="City" name="City" class="form-control" disabled>
										</div>			<div class="form-group">
												<label for="State" class="control-label">State</label>
												<input type="text" id="State" name="State" class="form-control" disabled>
										</div>
											<small id="ziphelp" ></small>
											<small id="addresshelp" ></small>
											<div class="form-group " id="fourth-element-introduction" >
													<button type="submit" class="btn btn-lg login-btn">Submit Address</button>
											</div>
				
				
				
											{!! csrf_field() !!}
									</form>
							
								</ul>
							
					</div>
					<div class="col-md-6" style="text-align: center; ">
						
						
							<h5><img src="{{url('../frontend/img/user.png')}}" 
  
	alt="user icon" style="height: 21px;padding-left:10px;padding-right:10px;  font-weight: 40;">Existing Customer</h5>
							
	<p style="font-weight: 30;">	<strong >Already have an account? </strong><a onclick="openNav(event)" href="javascript:void(0)">Log in</a> </p>
							
								
							
					</div>
				
				


		</div>
		
	</section><section class="page-section">
	
		
	</section>
	
@endsection