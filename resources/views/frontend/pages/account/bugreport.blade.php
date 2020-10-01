@extends('frontend/partials/master')

@section('stylesheets')
<script src="https://www.google.com/recaptcha/api.js"></script>
    <style>
        
        .sidenav {
            height: 100%;
            width: 0%;
            position: fixed;
            z-index: 1100;
            top: 0;
            right: 0;
            background-color: #fff;
            overflow-x: hidden;
            transition: 0.5s;
            /*padding-top: 60px;*/
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            color: #004E92;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        #mySidenav.show{
            width: 34%;
        }

        #mySidenavRegister.show{
            width: 34%;
        }

        @media only screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
        }

        @media only screen and (max-width: 450px) {
            #mySidenav.show{width:100%;}
        }

        @media (min-width: 451px) and (max-width: 900px) {
            #mySidenav.show{width:50%;}
        }

        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
    </style>
@endsection
@section('stylesheets-bottom')
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
								 $('#ziphelp').html('<p style="color:green">'+data.message+'  <a href="javascript:void(0)" onclick="openNavReg();"style="padding: 0;">Start you trial now</a></p>');
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
	@if ($errors->has('email'))
	// document.getElementById("mySidenav").style.width = "34%";
	document.getElementById("mySidenav").classList.add("show");
@endif
@if ($errors->has('user-email')||$errors->has('user-password')||$errors->has('zip_code'))
	// document.getElementById("mySidenav").style.width = "34%";
	document.getElementById("mySidenavRegister").classList.add("show");
@endif
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

<div id="mySidenav" class="sidenav">

	<div style="width: 100%;height: 5%;">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	</div>

	<div class="container" style="padding: 0px 50px;">
			<div class="row mt-4">
				@if($errors->has('email'))
         <div class="alert alert-danger alert-dismissible ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{$errors->first()}}
        </div>
    @endif
					<h4 class="login_txt">Log In</h4>    
					<form action="{{ env("APP_URL") }}/login"  method="post" class="login-form mt-3">
						@csrf
							<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
									<label for="email" class="control-label">Email</label>
									<input type="email" id="email" name="email" class="form-control">
							</div>
							<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
									<label for="password" class="control-label">Password</label>
									<input type="password" id="password" name="password" class="form-control">
							</div>

							<div class="form-check">
									<input type="checkbox" class="form-check-input" id="remember-me">
									<label class="no-font-s form-check-label" for="remember-me">Remember me</label>

									<label class="no-font-s float-right"><a href="{{ url('/password/reset') }}" class="forgot-password">Forgot password?</a></label>
							</div>

							<div class="form-group mt-3">
									<button type="submit" class="btn btn-lg login-btn">LOG IN</button>
							</div>

							<span>Don't have an account?</span> <a href="javascript:void(0)" onclick="openNavReg();"style="padding: 0;">Start you trial now</a>

							<div class="mt-4">
									<div style="border: 0.5px solid #C4C4C4;display: inline-block;width: 40%;"></div>
									<span style="padding: 0 4%;">or</span>
									<div style="border: 0.5px solid #C4C4C4;display: inline-block;width: 40%;"></div>
							</div>

							<div class="form-group mt-4 text-center">
									<a class="btn btn-sm google-btn" href="{{ route('google.login') }}"> <img src="{{ asset('frontend/img/google-login.svg') }}"> <span>LOG IN WITH GOOGLE<span></a>
							</div>
							{!! csrf_field() !!}
					</form>
			</div>
			
	</div>

</div>

<div id="mySidenavRegister" class="sidenav">

	<div style="width: 100%;height: 5%;">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNavReg()">&times;</a>
	</div>

	<div class="container" style="padding: 0px 50px;">
			<div class="row mt-4">
				@if($errors->any())
         <div class="alert alert-danger alert-dismissible ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> @foreach ($errors->all() as $message) 
                {{$message}}
            
            @endforeach
        </div>
    @endif 
					<h4 class="login_txt">Create Your Account</h4>    
					<form action="{{ env("APP_URL") }}/user/register" method="post" class="login-form mt-3">
							<div class="form-group">
									<label for="name" class="control-label">Name</label>
									<input type="text" id="name" name="name" class="form-control">
							</div>

							<div class="form-group">
									<label for="user-email" class="control-label">Email</label>
									<input type="email" id="user-email" name="user-email" class="form-control">
							</div>

							<div class="form-group">
									<label for="user-password" class="control-label">Password</label>
									<input type="password" id="user-password" name="user-password" class="form-control">
							</div>

							<div class="form-group">
									<label for="zip-code" class="control-label">Home zip code</label>
									<input type="text" id="zip-code" name="zip_code" class="form-control">
							</div>

							<span class="remove-p-a-r">By clicking “REGISTER” you agree to the Streamly <br />
									<a href="">Terms of Use</a>
									<span>and</span>
									<a href="">Privacy Policy</a>
									<span>.</span>
							</span>

							<div class="form-group mt-3">
									<button type="submit" class="btn btn-lg login-btn">REGISTER</button>
							</div>

							<span>Already have an account?</span> <a href="javascript:void(0)" onclick="openNav(event);"style="padding: 0;">Log in</a>

							{!! csrf_field() !!}
					</form>
			</div>
			
	</div>

</div>
<section class="page-section custom-row">

@if(session()->has('message'))
 <div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif
@if(session()->has('error'))
 <div class="alert alert-danger alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('error') }}
</div>
@endif
<div class="container">
	<h1 class="section-heading text-center">Bug Report Form</h1>
				<p>Use this form to report any bugs you may find. Here is what each of the "bug severity" options mean:</p>
				<ol>
					<li><div id="minor">Minor:</div> These are bugs that don't really affect the app's playability. Things like missing or broken textures would fit into this category.</li>
					<li><div id="moderate">Moderate:</div> The moderate category would include bugs that affect the functionality of some of the app's less important features. For example, a problem with an object the player can pick up would fall under the moderate category.</li>
					<li><div id="major">Major:</div> A major bug affects the playability of the app's more important feature. A door not opening would be an example of a major bug.</li>
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
							<option value="Rarely">Rarely</option>
							<option value="Somewhat Rarely">Somewhat Rarely</option>
							<option value="Somewhat Often">Somewhat Often</option>
							<option value="Commonly">Commonly</option>
							<option value="Very Commonly">Very Commonly</option>
						</select>
			</div>
					<div class="form-group col-md-6">
						
						<label for="bug_severity" class="control-label">Bug Severity</label>
						<select class="form-control" name="bug_severity" id="bug_severity" required>
							<option value="--Please Select--">--Please Select--</option>
							<option value="Minor">Minor</option>
							<option value="Moderate">Moderate</option>
							<option value="Major">Major</option>
							<option value="app-breaking">App-breaking</option>
						</select></div></div>
					
						<div class="row">
<div class="col-md-6 form-group">

	<label for="debug_info" class="control-label">Please input any debug information here:</label>
	<input type="text" class="form-control" name="debug_info" id="debug_info" placeholder="Ex. Exception at 0x0000000 - memory map"></div>
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
		
