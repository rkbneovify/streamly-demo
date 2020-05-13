@extends('frontend/partials/master')

@section('content')

	<!-- Masthead-->
	<header class="masthead">
	    <div class="container">
	        <div class="masthead-heading">100+ Cable TV Channels One Low Price!</div>
	        <div class="masthead-subheading">Watch live TV on your iOS or Android, Amazon Fire, Roku, and SmartTV's</div>
	        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#services">START TODAY</a>
	    </div>
	</header>

	<!-- Select Plan-->
    <section class="page-section" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading">Select Your Plan</h2>
            </div>
            <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
                <div class="col-sm-4 select-plan-card">
                	<div style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 4px;">
                		<p>Streamly live TV</p>

                		<h3>$ 69/ Month.</h3>

                		<hr/>

                		<ul style="position: relative;width: 75%;padding-left: 20px;margin: 0 auto;">
                			<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
                			<li>Lorem Ipsum is simply dummy text of the printing</li>
                			<li>Lorem Ipsum is simply dummy text of the printing Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</li>

                		</ul>

                		<a class="btn btn-primary btn-select-plan btn-xl js-scroll-trigger" href="#services">SELECT PLAN</a>
                	</div>
                </div>
            </div>
        </div>
    </section>


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