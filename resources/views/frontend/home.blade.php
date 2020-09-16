@extends('frontend/partials/master')

@section('stylesheets')
    <style>
               .grow { transition: width 2s;}
.grow:hover {     width: 300px;
     background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box;
     
}
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
    @if ($errors->has('email'))
    // document.getElementById("mySidenav").style.width = "34%";
    document.getElementById("mySidenav").classList.add("show");
@endif
@if ($errors->has('user-email')||$errors->has('user-password')||$errors->has('zip_code'))
    // document.getElementById("mySidenav").style.width = "34%";
    document.getElementById("mySidenavRegister").classList.add("show");
@endif
</script>
{{-- <script>
    jQuery(document).ready(function(){
        

        jQuery('.forgot-password').on('click', function(){
            jQuery('#resetPassword').modal('show');
        });
    });
</script> --}}
{{-- <script type="text/javascript">
    @if ($errors->has('forgotpassword-email'))
    jQuery('#resetPassword').modal('show');
    @endif
    </script> --}}
@endsection
@section('content')
    {{-- {{$errors}} --}}
    {{-- @if($errors->any())
        <div class="alert alert-danger alert-dismissible alert-position">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{$errors->first()}}
        </div>
    @endif --}}

    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible alert-position">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
        </div>
    @endif

	<!-- Masthead-->
	<header class="masthead">
	    <div class="container">
	        <div class="masthead-heading">Your Alternative to Cable!</div>
	        <div class="masthead-subheading">Watch live TV on your iOS or Android, Amazon Fire, Roku, and SmartTV's </div>
	        <div class="masthead-subheading">STREAMLY good channels. STREAMLY flexible terms. STREAMLY fair pricing! </div>
	        <a class="btn btn-primary btn-xl js-scroll-trigger grow" href="#services">START TODAY</a>
	    </div>
	</header>

    <div id="mySidenav" class="sidenav">

        <div style="width: 100%;height: 5%;">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        </div>

        <div class="container" style="padding: 0px 50px;">
            <div class="row mt-4">
                @if($errors->has('email'))
        <div class="alert alert-danger alert-dismissible alert-position">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{$errors->first()}}
        </div>
    @endif
                <h4 class="login_txt">Log In</h4>    
                <form action="login" method="post" class="login-form mt-3">
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
        <div class="alert alert-danger alert-dismissible alert-position">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> @foreach ($errors->all() as $message) 
                {{$message}}
            
            @endforeach
        </div>
    @endif 
                <h4 class="login_txt">Create Your Account</h4>    
                <form action="user/register" method="post" class="login-form mt-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}">
                    </div>

                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" id="user-email" name="user-email" class="form-control" value="{{old('user-email')}}">
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" id="user-password" name="user-password" class="form-control" value="{{old('user-password')}}">
                    </div>

                    <div class="form-group">
                        <label for="zip-code" class="control-label">Home zip code</label>
                        <input type="text" id="zip-code" name="zip_code" class="form-control" value="{{old('zip_code')}}">
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

{{--   
	<div class="modal fade" id="resetPassword">
        
	    <div class="modal-dialog">
			<div class="modal-content"><h3 class="login_txt" style="
                padding-left: 20px;
                padding-top: 20px;
            ">Forget password</h3>
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
          {{-- <form action="{{ url('/forgotpassword') }}" method="POST"> --}}
             {{-- <form method="POST" action="{{ route('password.email') }}"> --}}
            {!! csrf_field() !!}
						{{-- <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email" class="control-label">Send reset password link at                        </label>
							
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
						</div>


						<div class="text-center">
							<button type="submit" class="btn btn-lg login-btn" >
								Email me!
							</button> --}}
							{{-- <a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a> --}}
						{{-- </div>
					</form>
				</div>

			</div>
	    </div>
	</div> --}}


	<!-- Select Plan-->
    <section class="page-section" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading">Select Your Plan</h2>
            </div>
            <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
                @foreach ($plans->data as $plan)
                <div class="col-sm-4 select-plan-card">
                	<div style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 4px;">
                		<p>{{ $plan->nickname }}</p>

                    <h3>$ {{$plan->amount_decimal/100}}/ {{$plan->interval_count}} {{$plan->interval}}</h3>

                		<hr/>

                        <ul style="position: relative;width: 75%;padding-left: 20px;margin: 0 auto;">
                            <li>Trial days: {{$plan->trial_period_days??'no trials'}} days</li>
                            <li>{{$plan->metadata->text1??'Lorem Ipsum is simply dummy text of the printing'}}</li>
                            <li>{{$plan->metadata->text2??"Lorem Ipsum is simply dummy text of the printing Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,"}}</li>
        
                          </ul>

                        <?php

                        if ( isset($login) && $login ) {
                            $selectPLanLink = 'href=/payment';
                        } else {
                            $selectPLanLink = 'onclick=openNavReg() href=javascript:void(0);';
                        }

                        ?>

                		<a class="btn btn-primary btn-select-plan btn-xl js-scroll-trigger" {{$selectPLanLink}} >SELECT PLAN</a>
                	</div>
                </div> @endforeach
            </div>
        </div>
    </section>
<!-- Channel logo Grid-->
<section class="page-section bg-light" id="portfolio">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading">Available Channels</h2>
        </div>
        <div class="">
           
            <div class="grid-container">
                @foreach ($pagechannel as $channeldet)
                <div class="item1"> 
                    <img style='height: 100%; width: 100%; object-fit: contain' src="{{  $channeldet->logo}}">
                </div>
                @endforeach
                

              </div>
        </div>
    </div>
</section>
<style>

    .grid-container {
      display: grid;
      grid-template-columns: auto auto auto auto auto auto auto auto auto auto auto auto auto auto ;
      grid-gap: 5px;
      /* background-color: #2196F3; */
      padding: 10px;
    }
    
    .grid-container > div {
        border-radius: 15px;
      background-color: rgba(255, 255, 255, 0.8);
      text-align: center;
      padding: 10px 0;
      font-size: 15px;
      
    }
    </style>
    <!-- Portfolio Grid-->
    <section class="page-section bg-light" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading">Download Our App</h2>
            </div>
            <div class="row h-100 w-100">
                <div class="col-sm-5 align-self-center">
                	<h3>Now Watch Live TV any time Anywhere at your ease</h3>
                	<p>We are available on:</p>
                	<div class="row tv-logos">
                		<div class="col-sm-3">
                			<img class="img-fluid" src="{{ asset('frontend/img/apple-tv.png') }}">
                		</div>
                		<div class="col-sm-3">
                			<img class="img-fluid" src="{{ asset('frontend/img/fire-tv.png') }}">
                		</div>
                		<div class="col-sm-3">
                			<img class="img-fluid" src="{{ asset('frontend/img/android-tv.png') }}">
                		</div>
                		<div class="col-sm-3">
                			<img class="img-fluid" src="{{ asset('frontend/img/roku-tv.png') }}">
                		</div>
                	</div>
                </div>
                <div class="col-sm-7">
                	<img class="img-fluid" src="{{ asset('frontend/img/ce_mobile.png') }}">
                </div>
            </div>
        </div>
    </section>
@endsection