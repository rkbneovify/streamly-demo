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


@include('frontend.partials.sidenavRegisterAndLogin')
<section class="page-section custom-row">

@if(session()->has('mailmessage'))
 <div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('mailmessage') }}
</div>
@endif
@if(session()->has('mailerror'))
 <div class="alert alert-danger alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('mailerror') }}
</div>
@endif
<div class="container">
	<h1 class="section-heading text-center">Bug Report Form</h1>
				<p>We appreciate your assistance in making StreamlyTV the best it can be. Share any bugs with the form below, and we’ll respond as soon as possible!</p>
				<ol>
					<li><div id="minor">Minor:</div> These are bugs that don't really affect the app's functionality. Things like missing words, or broken links would fit into this category.
					.</li>
					<li><div id="major">Major:</div> A major bug affects the playability of the app. A channel not playing, or the audio not working on a channel.</li>
					<li><div id="app_breaking">app-breaking:</div> A app-breaking bug is a glitch that makes the app unplayable, either immediately after launch or during appplay. app crashes (especially persistent crashes) or other issues that make the app completely unplayable would fall under this category.</li>
				</ol>
			</div>
			<div class="container">
			 <form method="POST" enctype="multipart/form-data" action="{{ url('sendbugreport') }}">
				{{ csrf_field() }}
				<div class="form-group">
						
					<label for="email_input" class="control-label">Email</label>
					<input type="email" id="email_input" name="email_input" class="form-control" required>
						</div>
			<div class="row">	<div class="form-group col-md-6">
				<label for="occurrence" class="control-label">How often does it occur?</label>
						<select class="form-control"  name="occurrence" id="occurrence" required>
							<option value="--Please Select--">--Please Select--</option>
							<option value="Occasionally">Occasionally</option>
							<option value="Often">Often</option>
							<option value="Always">Always</option>
						</select>
			</div>
					<div class="form-group col-md-6">
						
						<label for="bug_severity" class="control-label">Bug Severity</label>
						<select class="form-control" name="bug_severity" id="bug_severity" required>
							<option value="--Please Select--">--Please Select--</option>
							<option value="Minor">Minor</option>
							<option value="Major">Major</option>
							<option value="app-breaking">App-breaking</option>
						</select></div></div>
					
						<div class="row">
<div class="col-md-6 form-group">

	<label for="platform_info" class="control-label">Platform</label>
	<select class="form-control" name="platform_info" id="platform_info" required>
		<option value="--Please Select--">--Please Select--</option>
		<option value="Website">Website</option>
		<option value="Roku">Roku</option>
		<option value="FireTV">FireTV</option>
		<option value="AppleTV">AppleTV</option>
	</select>
	{{-- <input type="text" class="form-control" name="platform_info" id="platform_info" placeholder="Ex. Exception at 0x0000000 - memory map"> --}}
</div>
	<div class="col-md-6 form-group">

	<label for="details" class="control-label">Any further questions, comments or issues:</label>
	<input type="text" class="form-control" name="details" id="details" placeholder="Ex. I noticed lagging in some areas."></div></div>

					
					
					
	@if(env('GOOGLE_RECAPTCHA_KEY'))
	<div class="form-group">
	<div class="col-md-12 text-right">
	<div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
	</div>
</div>
@endif
					<p>Once you have confirmed everything in your bug report, hit 'submit'. You won't be able to change your bug report later.</p>
					<input type="submit" id="submit" name="submit" value="Submit">
				</form>
			</div>
		</section>
	
@endsection
		
