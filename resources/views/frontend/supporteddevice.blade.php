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
<script type="text/javascript">
(function($, document, window) {
    "use strict";

    $(".device-slider li").on("click", function() {
        var category = $(this).data("category");


        $(".device-slider li.active, .device-slider-content.active").removeClass("active");

        $(this).addClass("active");
        $(".device-slider-content[data-category=" + category + "]").addClass("active");
		});
		
		var fixes = window.languageFixes;
    if (fixes) {
        var htmlEl = $("html");

        for (var name in fixes) {
            if (htmlEl.is(fixes[name].className)) {
                var links = fixes[name].links;

                $(".device-logos a, .manufacturer-list a").each(function rewriteLinks() {
                    var original = $(this).attr("href");

                    if (links[original]) {
                        $(this).attr("href", links[original]);
                    }
                });
                break;
            }
        }
    }


}($, document, window));
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
.container,
.nrtv-banner-2017 .nrtv-header-wrapper-2017 {
    position: relative;
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.column,
.columns {
    width: 100%;
    float: left;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

@media (min-width: 600px) {
    .container,
    .nrtv-banner-2017 .nrtv-header-wrapper-2017 {
        width: 98%;
    }
    .column,
    .columns {
        margin-left: 4%;
    }
    .column:first-child,
    .columns:first-child {
        margin-left: 0;
    }
    .one.column,
    .one.columns {
        width: 4.66666666667%;
    }
    .two.columns {
        width: 13.3333333333%;
    }
    .three.columns {
        width: 22%;
    }
    .four.columns {
        width: 30.6666666667%;
    }
    .five.columns {
        width: 39.3333333333%;
    }
    .six.columns {
        width: 48%;
    }
    .seven.columns {
        width: 56.6666666667%;
    }
    .eight.columns {
        width: 65.3333333333%;
    }
    .nine.columns {
        width: 74.0%;
    }
    .ten.columns {
        width: 82.6666666667%;
    }
    .eleven.columns {
        width: 91.3333333333%;
    }
    .twelve.columns {
        width: 100%;
        margin-left: 0;
    }
    .one-third.column {
        width: 30.6666666667%;
    }
    .two-thirds.column {
        width: 65.3333333333%;
    }
    .one-half.column {
        width: 48%;
    }
    /* Offsets */
    .offset-by-one.column,
    .offset-by-one.columns {
        margin-left: 8.66666666667%;
    }
    .offset-by-two.column,
    .offset-by-two.columns {
        margin-left: 17.3333333333%;
    }
    .offset-by-three.column,
    .offset-by-three.columns {
        margin-left: 26%;
    }
    .offset-by-four.column,
    .offset-by-four.columns {
        margin-left: 34.6666666667%;
    }
    .offset-by-five.column,
    .offset-by-five.columns {
        margin-left: 43.3333333333%;
    }
    .offset-by-six.column,
    .offset-by-six.columns {
        margin-left: 52%;
    }
    .offset-by-seven.column,
    .offset-by-seven.columns {
        margin-left: 60.6666666667%;
    }
    .offset-by-eight.column,
    .offset-by-eight.columns {
        margin-left: 69.3333333333%;
    }
    .offset-by-nine.column,
    .offset-by-nine.columns {
        margin-left: 78.0%;
    }
    .offset-by-ten.column,
    .offset-by-ten.columns {
        margin-left: 86.6666666667%;
    }
    .offset-by-eleven.column,
    .offset-by-eleven.columns {
        margin-left: 95.3333333333%;
    }
    .offset-by-one-third.column,
    .offset-by-one-third.columns {
        margin-left: 34.6666666667%;
    }
    .offset-by-two-thirds.column,
    .offset-by-two-thirds.columns {
        margin-left: 69.3333333333%;
    }
    .offset-by-one-half.column,
    .offset-by-one-half.columns {
        margin-left: 52%;
    }
}

		nav#mainNav {
			background-color: #004E92;
		}

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}

/* Self Clearing Goodness */

.container:after,
.nrtv-banner-2017 .nrtv-header-wrapper-2017:after,
.row:after,
.u-cf {
    content: "";
    display: table;
    clear: both;
}
		.unstyled-list,
footer ul,
footer li,
nav ul,
nav nav li,
.device-slider ul,
.device-slider li,
html.compatible-devices nav ul,
html.compatible-devices nav nav li {
    margin: 0;
    padding: 0;
    list-style: none;
}

header {
    text-align: center;
    background: #222 center no-repeat;
    background-size: cover;
    overflow: hidden;
    margin-top: 60px;
    padding: 80px 0;
    position: relative;
}

@media (min-width: 600px) {
    header {
        padding: 100px 0;
    }
}

header:before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

header>* {
    position: relative;
    z-index: 2;
}

header .video-background {
    z-index: 0;
}

header h2,
header .nrtv-header-wrapper {
    max-width: 785px;
    margin: 0 auto 20px auto;
    line-height: 1;
}

header p {
    max-width: 800px;
    margin: 0 auto;
}


.shadowed {
    -webkit-box-shadow: 0 0 40px #444;
    box-shadow: 0 0 40px #444;
    -webkit-box-shadow: 0 0 40px rgba(0, 0, 0, 0.4);
    box-shadow: 0 0 40px rgba(0, 0, 0, 0.4);
}

.device-slider {
    padding: 0;
    overflow-x: auto;
    width: 100%;
    position: relative;
}

.device-slider ul,
.device-slider li {
    height: 160px;
}
.vibrant-divider {
    height: 30px;
    background: #004E92;
}

.fancy-header {
    padding-left: 20px;
    border-left: 2px solid #004E92;
    line-height: 1 !important;
    font-weight: normal;
}
.device-slider .vibrant-divider,
.device-slider ul {
    text-align: center;
    width: 100%;
    min-width: 800px;
    margin: 0 auto;
}

.device-slider li {
    width: 100%;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    display: inline-block;
    float: left;
    border-left: 1px solid #eee;
    position: relative;
    -moz-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    background-position: center 20%;
    background-repeat: no-repeat;
    background-size: auto 50px;
}

.device-slider li.active,
.device-slider li:active,
.device-slider li:hover {
    background-color: #fafafa;
    cursor: pointer;
}

.device-slider li.active,
.device-slider li:active {
    z-index: 2;
}

.device-slider li+li {
    border-left: 1px solid #eee;
}

.device-slider .device-name {
    color: #555;
    font-weight: bold;
    font-size: 0.9em;
    display: block;
    margin: 80px auto 0 auto;
    max-width: 160px;
    width: 95%;
}

.device-slider .vibrant-divider {
    height: 20px;
    position: absolute;
    bottom: 0;
    width: 100%;
}

.device-slider.device-count-2 li {
    width: 50%;
    width: -webkit-calc(100% / 2);
    width: calc(100% / 2);
}

.device-slider.device-count-3 li {
    width: 33.33%;
    width: -webkit-calc(100% / 3);
    width: calc(100% / 3);
}

.device-slider.device-count-4 li {
    width: 25%;
    width: -webkit-calc(100% / 4);
    width: calc(100% / 4);
}

.device-slider.device-count-5 li {
    width: 20%;
    width: -webkit-calc(100% / 5);
    width: calc(100% / 5);
}

.device-slider.device-count-6 li {
    width: 16.66%;
    width: -webkit-calc(100% / 6);
    width: calc(100% / 6);
}

.device-slider.device-count-7 li {
    width: 14.285%;
    width: -webkit-calc(100% / 7);
    width: calc(100% / 7);
}

.device-slider.device-count-8 li {
    width: 12.5%;
    width: -webkit-calc(100% / 8);
    width: calc(100% / 8);
}

.device-slider.device-count-9 li {
    width: 11.11%;
    width: -webkit-calc(100% / 9);
    width: calc(100% / 9);
}

.device-slider.device-count-10 li {
    width: 10%;
    width: -webkit-calc(100% / 10);
    width: calc(100% / 10);
}

.device-slider-content {
    display: none;
    background: #000 url(../frontend/images/device-panel-background2.jpg) bottom left no-repeat;
    background-size: cover;
    color: #fff;
    position: relative;
    padding: 50px 0;
}

.device-slider-content.active {
    display: block;
}

.device-slider-content .container,
.device-slider-content .nrtv-banner-2017 .nrtv-header-wrapper-2017,
.nrtv-banner-2017 .device-slider-content .nrtv-header-wrapper-2017 {
    min-height: 305px;
}

.device-slider-content h3 {
    font-weight: 300;
    line-height: 1;
    font-size: 2.5rem;
}
html.compatible-devices .vibrant-divider {
    position: relative;
    z-index: 10;
}

@media (min-width: 600px) {
    .device-slider-content .learn-more-link {
        position: absolute;
        bottom: 0;
        margin: 0;
    }
}


.blu_ray_players-icon {
    background-image: url(../frontend/images/icons/blu-ray-player.png);
}

.blu_ray_players-icon.active,
.blu_ray_players-icon:active {
    background-image: url(../frontend/images/icons/blu-ray-player-active.png);
}

.game_consoles-icon-compatible-devices {
    background-image: url(../frontend/images/icons/compatible-devices/game-console.png);
}

.game_consoles-icon {
    background-image: url(../frontend/images/icons/game-console.png);
}

.game_consoles-icon.active,
.game_consoles-icon:active {
    background-image: url(../frontend/images/icons/game-console-active.png);
}

.hdtvs-icon-compatible-devices {
    background-image: url(../frontend/images/icons/compatible-devices/hdtv.png);
}

.hdtvs-icon {
    background-image: url(../frontend/images/icons/hdtv.png);
}

.hdtvs-icon.active,
.hdtvs-icon:active {
    background-image: url(../frontend/images/icons/hdtv-active.png);
}

.media_players-icon-compatible-devices {
    background-image: url(../frontend/images/icons/compatible-devices/media-player.png);
}

.media_players-icon {
    background-image: url(../frontend/images/icons/media-player.png);
}

.media_players-icon.active,
.media_players-icon:active {
    background-image: url(../frontend/images/icons/media-player.png);
}

.mvpds-icon-compatible-devices {
    background-image: url(../frontend/images/icons/compatible-devices/mvpd.png);
}

.mvpds-icon {
    background-image: url(../frontend/images/icons/mvpd.png);
}

.mvpds-icon.active,
.mvpds-icon:active {
    background-image: url(../frontend/images/icons/mvpd-active.png);
}

.pcs_and_laptops-icon-compatible-devices {
    background-image: url(../frontend/images/icons/compatible-devices/laptop.png);
}

.pcs_and_laptops-icon {
    background-image: url(../frontend/images/icons/laptop.png);
}

.pcs_and_laptops-icon.active,
.pcs_and_laptops-icon:active {
    background-image: url(../frontend/images/icons/laptop-active.png);
}

.phones_and_tablets-icon-compatible-devices {
    background-image: url(../frontend/images/icons/compatible-devices/phones-and-tablet.png);
}

.phones_and_tablets-icon {
    background-image: url(../frontend/images/icons/phones-and-tablet.png);
}

.phones_and_tablets-icon.active,
.phones_and_tablets-icon:active {
    background-image: url(../frontend/images/icons/phones-and-tablet-active.png);
}


.device-logos {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}

@media (min-width: 600px) {
    .device-logos {
        width: 300px;
        position: absolute;
        right: 0;
        top: -50px;
        height: 405px;
        overflow: hidden;
    }
}

.device-logos>a,
.device-logos>div {
    display: block;
    width: 49%;
    margin: 0 1px 1px 0;
    height: 80px;
    background: #000 no-repeat center;
    background-size: 50% auto;
    opacity: 0.8;
    overflow: hidden;
    text-indent: -999px;
    float: left;
}

.device-logos>a:hover {
    background-color: #111;
    opacity: 1;
}

.device-logos>div {
    background: #1a1a1a url(/images/checkerboard.gif);
}

.device-logos.grid-2>a,
.device-logos.grid-2>div {
    height: 202px;
    width: 100%;
}

.device-logos.grid-4>a,
.device-logos.grid-4>div {
    height: 202px;
}

.device-logos.grid-6>a,
.device-logos.grid-6>div {
    height: 134px;
}

.device-logos.grid-8>a,
.device-logos.grid-8>div {
    height: 101px;
}

.device-logos.grid-10>a,
.device-logos.grid-10>div {
    height: 80px;
}

.device-logos.grid-12>a,
.device-logos.grid-12>div {
    height: 67px;
}

.device-logos.grid-15>a,
.device-logos.grid-15>div {
    width: 33%;
    height: 80px;
}

.device-logos.grid-20>a,
.device-logos.grid-20>div {
    width: 24.5%;
    height: 80px;
}


.country-in .country-visible-in {
    display: inherit !important;
}

.country-in .logo-list li.country-visible-in {
    display: inline-block !important;
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

							<span>Don't have an account?</span> <a href="javascript:void(0)" onclick="openNavReg();"style="padding: 0;">Start you trial now!</a>

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
									<a href="{{ url('termsofuse') }}">Terms of Use</a>
									<span>and</span>
									<a href="{{ url('privacy') }}">Privacy Policy</a>
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
	{{-- url("../frontend/img/banner.webp"); --}}
</div>
<header id="video-background" style="background-image: url(../frontend/img/banner.webp)" data-cms-original-style="background-image: url('../frontend/images/header-2560-2.jpg')">
	<h2 class="editable" data-i18n="devices-hero-title" data-cms-source="/src/index.html" data-cms-index="0" style="color: #fff; margin:0px 545.5px 20px;">Connect to Streamly using your favorite devices.</h2>
	<div class="video-background" id="video-background-container"></div>
</header>
	{{-- <section class="page-section">
		<div class="container">
				<h2 class="section-heading text-center">Contact form</h2>
		
				

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
		
	</section> --}}
	<section class="shadowed device-slider device-count-3">
		<ul>
				{{-- <li data-category="media_players" class="active media_players-icon"> <span data-i18n="media_players-device-name" class="device-name">Streaming Media Players</span> </li> --}}
				{{-- <li data-category="hdtvs" class="hdtvs-icon"> <span data-i18n="hdtvs-device-name" class="device-name">Smart TVs</span> </li> --}}
				{{-- <li data-category="game_consoles" class="game_consoles-icon"> <span data-i18n="game_consoles-device-name" class="device-name">Game Consoles</span> </li> --}}
				<li data-category="mvpds" class="hdtvs-icon"> <span data-i18n="mvpds-device-name" class="device-name">Smart TVs</span> </li>
				{{-- <li data-category="blu_ray_players" class="blu_ray_players-icon"> <span data-i18n="blu_ray_players-device-name" class="device-name">Blu-ray Players</span> </li> --}}
				<li data-category="phones_and_tablets" class="phones_and_tablets-icon"> <span data-i18n="phones_and_tablets-device-name" class="device-name">Smartphones &amp; Tablets</span> </li>
				<li data-category="pcs_and_laptops" class="pcs_and_laptops-icon"> <span data-i18n="pcs_and_laptops-device-name" class="device-name">PCs &amp; Laptops</span> </li>
		</ul>
		<div class="shadowed vibrant-divider"></div>
</section>
{{-- <section data-category="media_players" class="active device-slider-content">
	<div class="container">
			<div class="row">
					<div class="one-half column">
							<h4 class="fancy-header" data-i18n="media_players-device-title">PLUG AND PLAY</h4>
							<p data-i18n="media_players-device-description">The newest generation of media players and streaming sticks offer a fast, easy, and affordable way to watch Streamly on your TV.</p>
					</div>
			</div>
		<div class="device-logos grid-2 country-visible-in" >
			<a href="https://www.apple.com/appletv/" style="background-image: url(../frontend/images/media_players/appletv_rev.png)" title="Apple TV" data-cms-original-style="background-image: url('./images/media_players/appletv_rev.png')">Apple TV</a> <a href="https://store.google.com/product/chromecast"
							style="background-image: url(../frontend/images/media_players/chromecast_logo.png)" title="Chromecast" data-cms-original-style="background-image: url('./images/media_players/chromecast_logo.png')">Chromecast</a> </div>
		
	</div>
</section> --}}
<section data-category="hdtvs" class=" device-slider-content">
	<div class="container">
			<div class="row">
					<div class="one-half column">
							<h4 class="fancy-header" data-i18n="hdtvs-device-title">BUILT-IN CONNECTION</h4>
							<p data-i18n="hdtvs-device-description">Smart TVs have Streamly already on them—ready for you to watch. There’s no need to add another device.</p>
							<div class="nrtv-callout-wrapper"> <img src="./images/nrtv.png" class="language-invisible-ja" /> <img src="./images/nrtv-ja.png" class="language-visible-ja" />
									<p data-i18n="devices-nrtv-callout-text"> Want a smart TV that’s built for a great Streamly experience? Look for smart TVs with the Streamly Recommended TV logo. </p>
									<p><a href="/en/recommendedtv/2020/" class="button" data-i18n="devices-nrtv-callout-cta" data-cms-original-href="/recommendedtv/2020/">LEARN MORE ▸</a></p>
							</div>
					</div>
			</div>
			<div class="device-logos grid-10 country-visible-in"> <a href="https://www.hisense-usa.com" style="background-image: url(../frontend/images/s/hdtvs/hisenselogo.png)" title="Hisense TV" data-cms-original-style="background-image: url('./images/hdtvs/hisenselogo.png')">Hisense TV</a> <a href="https://www.lg.com"
							style="background-image: url(../frontend/images/s/hdtvs/lg-logo-3d-tagline-white.png)" title="LG TV" data-cms-original-style="background-image: url('./images/hdtvs/lg-logo-3d-tagline-white.png')">LG TV</a> <a href="https://www.panasonic.com" style="background-image: url(../frontend/images/s/hdtvs/panasonic_logo_bl_posi_png.png)"
							title="Panasonic TV" data-cms-original-style="background-image: url('./images/hdtvs/panasonic_logo_bl_posi_png.png')">Panasonic TV</a> <a href="https://www.philips.com" style="background-image: url(../frontend/images/s/hdtvs/philips_transparent_logo.png)"
							title="Philips TV" data-cms-original-style="background-image: url('./images/hdtvs/philips_transparent_logo.png')">Philips TV</a> <a href="https://www.samsung.com" style="background-image: url(../frontend/images/s/hdtvs/samsung_lettermark_white_rgb.png)"
							title="Samsung TV" data-cms-original-style="background-image: url('./images/hdtvs/samsung_lettermark_white_rgb.png')">Samsung TV</a> <a href="https://www.sharpusa.com/ForHome/HomeEntertainment/LCDTV/Features/Smart.aspx" style="background-image: url(../frontend/images/s/hdtvs/sharp-light.png)"
							title="Sharp TV" data-cms-original-style="background-image: url('./images/hdtvs/sharp-light.png')">Sharp TV</a> <a href="https://www.sony.com" style="background-image: url(../frontend/images/s/hdtvs/sony-light.png)" title="SONY TV" data-cms-original-style="background-image: url('./images/hdtvs/sony-light.png')">SONY TV</a>                <a href="https://toshiba-tv.com" style="background-image: url(../frontend/images/s/hdtvs/toshiba.png)" title="TOSHIBA TV" data-cms-original-style="background-image: url('./images/hdtvs/toshiba.png')">TOSHIBA TV</a> <a href="https://www.mi.com/in" style="background-image: url(/uploads/1024px-xiaomi-logo-svg.png)"
							title="Xiaomi" data-cms-original-style="background-image: url('/uploads/1024px-xiaomi-logo-svg.png')">Xiaomi</a>
					<div></div>
			</div>
	</div>
</section>
<section data-category="game_consoles" class="device-slider-content">
	<div class="container">
			<div class="row">
					<div class="one-half column">
							<h4 class="fancy-header" data-i18n="game_consoles-device-title">PLAY GAMES<br />PLAY MOVIES</h4>
							<p data-i18n="game_consoles-device-description">You can also watch Streamly on a variety of game consoles.</p>
					</div>
			</div>
			<div class="device-logos grid-4 country-visible-in">
			<a href="https://www.playstation.com/explore/ps3/" style="background-image: url(../frontend/images/s/game-consoles/ps3.png)" title="PS3" data-cms-original-style="background-image: url('./images/game-consoles/ps3.png')">PS3</a> <a href="https://www.playstation.com/explore/ps4/"
							style="background-image: url(../frontend/images/s/game-consoles/ps4.png)" title="PS4" data-cms-original-style="background-image: url('./images/game-consoles/ps4.png')">PS4</a> <a href="https://www.microsoft.com/en-us/p/Streamly/9wzdncrfj3tj?rtc=1&activetab=pivot:overviewtab"
							style="background-image: url(../frontend/images/s/game-consoles/xbox360_2013_stacked_wht_rgb.png)" title="Xbox 360" data-cms-original-style="background-image: url('./images/game-consoles/xbox360_2013_stacked_wht_rgb.png')">Xbox 360</a> <a href="https://www.xbox.com"
							style="background-image: url(../frontend/images/s/game-consoles/xboxone_stacked_wht_rgb.png)" title="Xbox One" data-cms-original-style="background-image: url('./images/game-consoles/xboxone_stacked_wht_rgb.png')">Xbox One</a> </div>
		
	</div>
</section>
<section data-category="mvpds" class="active device-slider-content">
	<div class="container">
			<div class="row">
					<div class="one-half column">
							<h4 class="fancy-header" data-i18n="mvpds-device-title">EASY AS CHANGING CHANNELS</h4>
							<p data-i18n="mvpds-device-description">Now you can switch from live TV to Streamly right from your set-top box. Streamly is available through a growing number of pay TV providers. Ask yours for Streamly if you don’t see it here.</p>
					</div>
            </div>
            <div class="device-logos grid-4 country-visible-in"> <a href="https://www.android.com" style="background-image: url(../frontend/img/newicon/roku.png)" title="Roku" data-cms-original-style="background-image: url('./images/phones-and-tablets/android.png')">Android</a> <a href="https://www.apple.com"
                style="background-image: url(../frontend/img/newicon/andriodtv.png)" title="Apple iOS" data-cms-original-style="background-image: url('./images/phones-and-tablets/apple_logo_360.png')">Apple iOS</a> <a href="https://www.microsoft.com/en-us/mobile/phones/all/"
                style="background-image: url(../frontend/img/newicon/appletv.png)" title="Windows" data-cms-original-style="background-image: url('./images/phones-and-tablets/windows-phone.png')">Windows</a>
        <div></div>
</div>
	</div>
</section>
<section data-category="blu_ray_players" class="device-slider-content">
	<div class="container">
			<div class="row">
					<div class="one-half column">
							<h4 class="fancy-header" data-i18n="blu_ray_players-device-title">ENJOY THE SIGHTS & SOUNDS</h4>
							<p data-i18n="blu_ray_players-device-description">Stream Streamly using your Blu-ray player or Home Theater System.</p>
					</div>
			</div>
		<div class="device-logos grid-4 country-visible-in"> <a href="https://www.lg.com" style="background-image: url(../frontend/images/s/hdtvs/lg-light.png)" title="LG" data-cms-original-style="background-image: url('./images/hdtvs/lg-light.png')">LG</a> <a href="https://www.panasonic.com" style="background-image: url(../frontend/images/s/hdtvs/panasonic_logo_bl_posi_png.png)"
							title="Panasonic" data-cms-original-style="background-image: url('./images/hdtvs/panasonic_logo_bl_posi_png.png')">Panasonic</a> <a href="https://www.samsung.com" style="background-image: url(../frontend/images/s/hdtvs/samsung_lettermark_white_rgb.png)"
							title="Samsung" data-cms-original-style="background-image: url('./images/hdtvs/samsung_lettermark_white_rgb.png')">Samsung</a> <a href="https://www.sony.com" style="background-image: url(../frontend/images/s/hdtvs/sony-light.png)" title="Sony" data-cms-original-style="background-image: url('./images/hdtvs/sony-light.png')">Sony</a>                </div>
		
	</div>
</section>
<section data-category="phones_and_tablets" class="device-slider-content">
	<div class="container">
			<div class="row">
					<div class="one-half column">
							<h4 class="fancy-header" data-i18n="phones_and_tablets-device-title">TAKE Streamly WITH YOU</h4>
							<p data-i18n="phones_and_tablets-device-description">It’s easy to watch Streamly from anywhere. If Streamly isn’t already on your phone or tablet, you can download our free app from the Apple App Store, Google Play, or the Windows Phone Store.</p>
					</div>
			</div>
			
			<div class="device-logos grid-4 country-visible-in"> <a href="https://www.android.com" style="background-image: url(../frontend/images/s/phones-and-tablets/android.png)" title="Android" data-cms-original-style="background-image: url('./images/phones-and-tablets/android.png')">Android</a> <a href="https://www.apple.com"
							style="background-image: url(../frontend/images/s/phones-and-tablets/apple_logo_360.png)" title="Apple iOS" data-cms-original-style="background-image: url('./images/phones-and-tablets/apple_logo_360.png')">Apple iOS</a> <a href="https://www.microsoft.com/en-us/mobile/phones/all/"
							style="background-image: url(../frontend/images/s/phones-and-tablets/windows-phone.png)" title="Windows" data-cms-original-style="background-image: url('./images/phones-and-tablets/windows-phone.png')">Windows</a>
					<div></div>
			</div>
	</div>
</section>
<section data-category="pcs_and_laptops" class="device-slider-content">
	<div class="container">
			<div class="row">
					<div class="one-half column">
							<h4 class="fancy-header" data-i18n="pcs_and_laptops-device-title">WATCH ON WHAT YOU HAVE</h4>
							<p data-i18n="pcs_and_laptops-device-description">Streamly is optimized for today’s most popular browsers so you can watch on your PC or laptop.</p>
					</div>
			</div>
			<div class="device-logos grid-2 country-visible-se"> 
                <a href="http://www.toshiba.se/laptops/" style="background-image: url(../frontend/images/s/pc.png);" title="Toshiba" data-cms-original-style="background-image: url('./images/hdtvs/toshiba.png')">Toshiba</a>
                <a href="http://www.toshiba.se/laptops/" style="background-image: url(../frontend/images/s/laptop.png);" title="Toshiba" data-cms-original-style="background-image: url('./images/hdtvs/toshiba.png')">Toshiba</a>
               	
			</div>
	</div>
</section>
@endsection