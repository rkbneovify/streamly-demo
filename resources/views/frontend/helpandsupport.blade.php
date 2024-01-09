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
								 $('#ziphelp').html('<p style="color:green">'+data.message+'  <a href="javascript:void(0)" onclick="openNavReg();"style="padding: 0;">Start Your Trial Now!</a></p>');
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
	
});
</script>
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
				<h2 class="section-heading text-center">Help and support</h2>
				
					<div class="col-centered col-md-6">
						<div class="row setting-cards mt-5">
							<div class="col-centered col-md-8">
								<ul class="setting-card">
									<li>For help and support contact on email below</li>
									<li>
										Email:
									<span class="pull-right">contact@streamlytv.com
									{{-- <i class="fa fa-edit light-brown" aria-hidden="true"> --}}
									</i></span>
									</li>
								</ul>
							</div>
						</div>
							
					</div>
				
				

				@if ($errors->any())
				<div class="alert alert-danger">
						<ul>
								@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
								@endforeach
						</ul>
				</div>
		@endif

		</div>
		
	</section><section class="page-section">
	
		
	</section>
	
@endsection