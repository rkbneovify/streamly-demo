@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets')
    <style>
        .grow { transition: width 2s;}
.grow:hover {     width: 300px;
     background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box;
     
}

        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
    </style>
@endsection

@section('stylesheets-bottom')

@include('frontend.partials.sidenavRegisterAndLoginJS')
@endsection
@section('content')
@if(session()->has('message'))
         <div class="alert alert-success alert-dismissible ">
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

    @include('frontend.partials.sidenavRegisterAndLogin')



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
                <p>{{ $plan->nickname }} </p>

                  <h3>$ {{$plan->amount_decimal/100}}/ {{$plan->interval_count}} {{$plan->interval}}</h3>

                  <hr/>

                  <ul style="position: relative;width: 75%;padding-left: 20px;margin: 0 auto;">
                    <li>Trial days: {{$plan->trial_period_days??'no trials'}} days</li>
                    
                    @foreach ($plan->metadata->jsonSerialize() as $key => $value)
                  
                    
                    <li>{{$value??'Lorem Ipsum is simply dummy text of the printing'}}</li>
                    @endforeach
                  </ul>

                      <?php

                      if ( isset($login) && $login ) {
                          $selectPLanLink = 'href=/payment/'.$plan->id;
                      }

                      ?>

                  <a class="btn btn-primary btn-select-plan btn-xl js-scroll-trigger" {{$selectPLanLink}} >SELECT PLAN</a>
                </div>
              </div> @endforeach
          </div>
      </div>
  </section>
  <section class="page-section"></section>
  @endsection
  <script>
    (function () {
        var base_url = window.location.origin;
    let url=base_url+'/api/auth/frontendepg';
        fetch(url).then(
    function(u){ return u.json();}
    ).then(
    function(json){
        localStorage.setItem("mychanneldata", JSON.stringify(json.data)); 
    }
    )
})();
    </script>
