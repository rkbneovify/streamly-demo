@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets')
<style>

        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
    </style>
@endsection
@section('stylesheets-bottom')

@include('frontend.partials.sidenavRegisterAndLoginJS')
<script type="text/javascript">

/*
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
*/

$(function() {

	jQuery('.cancel-subscription').on('click', function(){
				jQuery('#checkAvailibiltyModal').modal('show');
			});
	$("#my_form").submit(function(event){

		event.preventDefault(); //prevent default action 
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
			 $('#citymodal').text(data.city); 
			$('#statemodal').text(data.state); 
			$('#modalzip').text(zipnumber.val()); 
			$('#address-country').val("US"); 
								//  $('#ziphelp').html('<p style="color:green">'+data.message+' <a href="javascript:void(0)" onclick="openNavReg();"style="padding: 0;">Start you trial now!</a></p>');
								
								$('.circle').attr('id', 'allowed');
								 $('#zipanswer').html("<span style='text-align: left;font: normal normal normal 16px/25px Poppins;letter-spacing: 1.6px;color: #1BB525;'>&#x2705; " +data.message+"</span>");
								//  $('#zipanswer').html("<strong style='color:#5E5E5E'>Join us:</strong><a href='javascript:void(0)' onclick='openNavReg();'style='padding: 0;'>Click Me! Start you trial now!</a>");
								 $('#modalbutton').html('<button type="button" class="btn btn-primary" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" onclick="openNavReg();">Start now</button>');
								 
								 $('#zip').css("color", "#1BB525");
								 jQuery('#checkAvailibiltyModal').modal('show');

							 },
							 error:function(xhr, status, error){ 
								 if(xhr.status==404) {
									$('#City').val(xhr.responseJSON.city); 
			$('#State').val(xhr.responseJSON.state); 
									$('#citymodal').text(xhr.responseJSON.city); 
			$('#statemodal').text(xhr.responseJSON.state); 
			$('#modalzip').text(zipnumber.val()); 
			$('#address-country').val("US"); 
									 // alert('status:' + xhr.status + ', status text: ' + xhr.responseText);
									 $('#card-button').prop('disabled', true);
								//  $('#ziphelp').html('<p style="color:red">'+xhr.responseJSON.message+' Our apologies, your location is outside of the streamly service area. Please call 1111111111 for more information.</p>');
								$('.circle').attr('id', 'error');
								 $('#zipanswer').html("<span style='text-align: left;font: normal normal normal 16px/25px Poppins;letter-spacing: 1.6px; color:#FC243B;'>&#10060; Our apologies, your location is outside of the streamly service area.<br /> Please call 1111111111 for more information</span>");
								 $('#zip').css("color", "#FC243B");
								 $('#modalbutton').html('<button type="button" class="btn btn-primary" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" onclick="location.href=`{{ url("/contactform") }}`;" >Contact Us</button>');
							
								 jQuery('#checkAvailibiltyModal').modal('show');

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
		    /* flex: 1 1 auto; */
		    padding: 3rem 5rem;
		}

		.modal-body p{
			letter-spacing: 0.24px;
			color: #2C2828;
			
		}
		.logo-margin-negative{
margin-top: -80px;
}
.whitecircle
{ width:76px;
    height:76px;
    border-radius:36px;
    font-size:20px;
    color:#fff;
    line-height:100px;
    text-align:center;
    background:#fff;
    }
.circle
{ width:88px;
    height:88px;
    border-radius:44px;
    font-size:20px;
    color:#fff;
    line-height:100px;
    text-align:center;
    background:#fff;
    }
    #allowed{

      background-image: url("../frontend/img/newicon/allowed.png");
      /* background-image: url("../frontend/img/streamlylogo5.png"); */

/*background-size: cover;*/
background-size: 76px;
background-repeat: no-repeat;
background-position: center;
    }
		 #error{

      background-image: url("../frontend/img/newicon/error.png");
      /* background-image: url("../frontend/img/streamlylogo5.png"); */

/*background-size: cover;*/
background-size: 76px;
background-repeat: no-repeat;
background-position: center;
    }
	</style>


@endsection

@section('content')

<div class="modal fade" id="checkAvailibiltyModal">
	<div class="modal-dialog">
	<div class="modal-content">
		{{-- <div class="modal-header"><button type="button" class="close" data-dismiss="modal">×</button><h4 class="modal-title">10020 CAMPBELL LN E</h4></div> --}}
		<div class="modal-header">
			{{-- <h4 class="modal-title">Your Zip:<span id="modalzip"></span> </h4> --}}
			<h4 class="modal-title w-100 text-center">	<svg class = "circle logo-margin-negative" expanded = "true" height = "200px" width = "200px" >
			
			</svg>
			{{-- <img src="https://www.fillmurray.com/76/76" class="rounded-circle logo-margin-negative"></h4> --}}
		</div>
		<div class="modal-body text-center">
			{{-- <h4>Your check availbilty status</h4> --}}
			<h4 style="height: height: 31px;">Your Zipcode:<span id="modalzip"></span> </h4>
			<p ><span id="zipanswer"></span> </p>
			<p><strong style="color: #909090;">City:</strong><span id="citymodal"></span> </p>
			<p><strong style="color: #909090;">State:</strong><span id="statemodal"></span> </p>
			
			<div>
				<form action="" method="POST">
					{{-- <input type="hidden" name="_method" value="DELETE">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<input style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" type="submit" class="btn btn-sm" value="Yes"> --}}

				{{-- <a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">Close</a> --}}
												</form>
				{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a> --}}
			</div>
		</div>
		<div class="modal-footer" ><span id="modalbutton"></span><button type="button" class="btn " style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;" data-dismiss="modal">Cancel</button>
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

@include('frontend.partials.sidenavRegisterAndLogin')

	<section class="page-section">
		<div class="container">
			{{-- <svg class = "circle" id="roku" expanded = "true" height = "200px" width = "200px" >
			
			</svg> --}}
				<h2 class="section-heading text-center" id="first-element-introduction" >Check Availability</h2>
				<div style="text-align: center; "><p  style=" display: inline-block; background-color: #eff0f1; border-radius: 6px; padding-right: 1rem; padding-left: 1rem; "><img src="{{url('../frontend/img/location.png')}}" 
  
					alt="location image" style="">To display pricing and offers available for your location, please enter an address.</p></div>
				<div class="row setting-cards " >
					<div class=" col-md-6 " style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 4px; border: 1px solid #CED4DA;">
						<div class="mr-2">
						{{-- <ul class="setting-card"> --}}
							
							
								@if($errors->has('email'))
								 <div class="alert alert-danger alert-dismissible ">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong>Error!</strong> {{$errors->first()}}
								</div>
						@endif 
						<img src="{{url('../frontend/img/user.png')}}" 
  
						alt="user icon" class="mx-auto d-block"  style="padding-top:5%">
						<h5 class="text-center" style="color: #212529;padding-top:2%"> New Customer</h5>
									<form  id="my_form" class="login-form mt-3">
										@csrf
											<div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}" id="second-element-introduction" >
													<label for="zip" class="control-label text-center">Enter your zipcode</label>
													<input type="number"  placeholder="Enter your Zip" id="zip" name="zip" class="form-control text-center" required><span id="zipanswer"></span>
											</div>
											{{-- <div class="form-group" id="third-element-introduction" >
												<label for="Address2"  class="control-label ">Address</label>
												<input type="text" id="Address2" name="Address2" class="form-control" >
											<pre>222 Example St.</pre>	
										</div>	 --}}
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
							
								{{-- </ul> --}}
							
					</div></div>
					<div class="col-md-1"></div>
					<div class="col-md-5" style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 4px; border: 1px solid #CED4DA;">
						<div class="ml-2">
						<img src="{{url('../frontend/img/user.png')}}" 
  
	alt="user icon" class="mx-auto d-block" style="padding-top:5%">
	<h5 class="text-center" style="color: #212529;padding-top:2%"> Existing Customer</h5>
							
	<p style="font-weight: 30;" class="text-center">	<strong >Already have an account? </strong><a onclick="openNav(event)" href="javascript:void(0)">Log in</a> </p>
							
								
							
					</div></div>
				
				


		</div>
		
	</section><section class="page-section">
	
		
	</section>
	
@endsection