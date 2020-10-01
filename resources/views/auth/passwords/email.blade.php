@extends('frontend/partials/master')

@section('stylesheets')
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
@endsection

@section('content')
@if(session()->has('message'))
 <div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif

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
	 <div class="alert alert-danger alert-dismissible ">
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
	<section class="page-section">
		<div class="container">
<div class="login-box">
  
  <!-- /.login-logo -->
  <div class="card">
  @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
    <div class="card-body ">
      <h3 class="login_txt" >You forgot your password?<br>Here you can easily retrieve a new password.</h3>

      <form method="POST" action="{{ route('password.email') }}">
                        @csrf
        {{-- <div class="input-group mb-3">
        <input id="email" type="email" placeholder="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

@if ($errors->has('email'))
    <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('email') }}</strong>
    </span>
@endif
        </div> --}}
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
          <label for="email" class="control-label">Send reset password link at                        </label>
          
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-lg login-btn">Request new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div></div>
		
</section>

@endsection