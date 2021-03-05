@extends('frontend/partials/master')

@section('top-stylesheets')

{{-- <link defer rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css" integrity="sha512-oM24YOsgj1yCDHwW895ZtK7zoDQgscnwkCLXcPUNsTRwoW1T1nDIuwkZq/O6oLYjpuz4DfEDr02Pguu68r4/3w==" crossorigin="anonymous" /> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" /> --}}
<script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@include('frontend.partials.sidenavRegisterAndLoginCSS')

@endsection
@section('stylesheets')
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

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
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
								//  $('#ziphelp').html('<p style="color:green">'+data.message+' <a href="javascript:void(0)" onclick="openNavReg();"style="padding: 0;">Start Your Trial Now!</a></p>');
								
								$('.circle').attr('id', 'allowed');
								$("#emailzipquery").hide();
								 $('#zipanswer').html("<span style='text-align: left;font-family: Poppins, sans-serif !important;letter-spacing: 1.6px;color: #1BB525 ;'>&#x2705; " +data.message+"</span>");
								//  $('#zipanswer').html("<strong style='color:#5E5E5E'>Join us:</strong><a href='javascript:void(0)' onclick='openNavReg();'style='padding: 0;'>Click Me! Start Your Trial Now!</a>");
								 $('#modalbutton').html('<button type="button" class="btn btn-primary" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" onclick="openNavReg();">Subscribe</button>');
								 
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
								 $('#zipanswer').html("<span style='text-align: left;font-family: Poppins, sans-serif !important; letter-spacing: 1.6px; color:#FC243B;'> Unfortunately, the location you have entered is outside of the Streamly service area.<br> Enter your email in the box below to be notified if Streamly comes to your area.</span>");
								 $('#zip').css("color", "#FC243B");
								 $('#modalbutton').html('<button type="button" class="btn btn-primary" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" id="ajaxSubmit" >Submit</button>');
							
								 jQuery('#checkAvailibiltyModal').modal('show');

							 }
									// coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
							 }
					 });
	
});
jQuery('body').on('click','#ajaxSubmit',function(e){
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      
		'X-CSRF-TOKEN': $("input[name=_token]").val()
                  }
              });
               jQuery.ajax({
                  url: "{{ url('/checkAvailabiltyStore') }}",
                  method: 'post',
                  data: {
										email: jQuery('#email').val(),
										zip: jQuery('#modalzip').text(),
										city: jQuery('#citymodal').text(),
										state: jQuery('#statemodal').text(),
                  },
                  success: function(result){
										
                  	if(result.errors)
                  	{ console.log("fail");
                  		jQuery('.alert-danger').html('');

                  		jQuery.each(result.errors, function(key, value){
                  			jQuery('.alert-danger').show();
                  			jQuery('.alert-danger').append('<li>'+value+'</li>');
                  		});
                  	}
                  	else
                  	{ console.log("success");
                  		jQuery('.alert-danger').hide();
                  		// $('#open').hide();
                  		$('#checkAvailibiltyModal').modal('hide');
                  	}
                  }});
               });

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
		<div class="alert alert-danger" style="display:none"></div>
		<div class="modal-body text-center">
			{{-- <h4>Your check availbilty status</h4> --}}
			<form method="post" action="{{url('checkAvailabiltyStore')}}" id="form">
        @csrf
			<h4 style="height: height: 31px;">Your Zipcode:<span id="modalzip"></span> </h4>
			<p ><span id="zipanswer"></span> </p>
			<p><strong style="color: #909090;">City:</strong><span id="citymodal"></span> </p>
			<p><strong style="color: #909090;">State:</strong><span id="statemodal"></span> </p>
			<span id="emailzipquery">Enter your email:<input type="email" id="email" ></span>
			{{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
			<div>
				{{-- <form action="" method="POST"> --}}
					{{-- <input type="hidden" name="_method" value="DELETE">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<input style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;" type="submit" class="btn btn-sm" value="Yes"> --}}

				{{-- <a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">Close</a> --}}
												{{-- </form> --}}
				{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a> --}}
			</div>
		</div>
		<div class="modal-footer" >
			<span id="modalbutton"></span>
			
			<button type="button" class="btn " style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;" data-dismiss="modal">Cancel</button>
		</form>	</div>
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
						  <!-- For defining autocomplete -->
    {{-- <input type="number" id='zip_search' autocomplete="off"> --}}

    <!-- For displaying selected option value from autocomplete suggestion -->
    {{-- <input type="text" id='employeeid' readonly> --}}
									<form  id="my_form" class="login-form mt-3" autocomplete="off">
										@csrf
											<div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}" id="second-element-introduction" >
													<label for="zip" class="control-label text-center">Enter your zipcode</label>
													<input  type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" placeholder="Enter your Zip" id="zip" name="zip" class="form-control text-center"  required><span id="zipanswer"></span>
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