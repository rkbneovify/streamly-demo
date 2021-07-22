<?php 
use Laravel\Cashier\Cashier;
$login= Auth::check()?true:false;
?>
@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets')
    <style>
        .grow {
	transition: width 2s;
}

.grow:hover {
	width: 300px;
	background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box;
}

.alert.alert-position {
	position: absolute;
	width: 100%;
	z-index: 1040;
}
   
.custom-row .row{
    margin-top: 2rem;
}
.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		.faq-btn{
			background: #004E92 0% 0% no-repeat padding-box;
			border: 0.25px solid #707070;
			border-radius: 18px;
			color: white;
			padding: 0.4rem 1rem;
		}

		.faq-btn.collapsed {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			color: #5E5E5E;
		}

		#accordion-child .card {
		    margin-top: 5px;
		}

		[data-toggle="collapse"] .fa:before {   
		  content: "\f107";
		}

		[data-toggle="collapse"].collapsed .fa:before {
		  content: "\f106";
		}
        #tab-buttons>a { display:block;
  float:left;
  cursor:pointer;
  color:#666;
  font:400 15px 'pt sans',sans-serif;
  
  padding:0 15px;
  line-height:40px !important;
  border: 2px solid #FF712F;}
  #tab-buttons>a:not(.collapsed){
  background:#FF712F;
  color:#FFFFFF;
  position:relative;
  box-shadow:0 0 10px rgba(0,0,0,0.5);
  
    /* width: 163px;
    height: 41px; */

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
<section class="page-section bg-light">
    <div class="container">
        <div class="text-center">

            <h2 class="section-heading">Available Plans</h2>
           
            <div class="row">
                <div class="col-centered" id="tab-buttons">

 @foreach ($plankey as $plank) 
 @if ($loop->first)  
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn" data-toggle="collapse">{{$plank}}</a>
 @else
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn collapsed" data-toggle="collapse">{{$plank}}</a>
 @endif
 @endforeach
                    
                </div>
            </div>
        </div>
   <div class="row " id="accordion">
        @foreach($plansdetails as $key => $singlePlansDetails)
        @if ($loop->first)  
        <div id="{{$key}}" class=" collapse  text-center show" data-parent="#accordion">
        <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
           @foreach ($singlePlansDetails as $singlePlansDetail)
            
           @component('components.plan',['singlePlansDetail' => $singlePlansDetail,'type'=>'per_unit'])
		
		@endcomponent
              @endforeach</div>
        </div> 
   
    @else

    <div id="{{$key}}" class=" collapse  text-center " data-parent="#accordion">
        <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
           @foreach ($singlePlansDetails as $singlePlansDetail)
            
           @component('components.plan',['singlePlansDetail' => $singlePlansDetail,'type'=>'tiered'])
		
		@endcomponent
              @endforeach</div>
        </div>
@endif
    @endforeach
</div>
    </div>
</section>

  <section class="page-section bg-light"></section>
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
