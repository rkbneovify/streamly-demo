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

                @if (!empty($plan->tiers))
                        <details>
  <summary><h3>Tiers {{$plan->interval_count}} {{$plan->interval}}</h3></summary>
  <table class="table table-bordered">
	<thead style="background-color:#FFFFFF;">
		<tr >
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Quantity</strong></span>
				</div>
			</td>
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Price per unit</strong></span>
				</div>
			</td>
			<td >
				<div  style="align-items: center; justify-content: flex-start;"><span class="Text-color--dark Text-fontSize--13 Text-fontWeight--medium Text-lineHeight--20 Text-numericSpacing--proportional Text-typeface--upper Text-wrap--noWrap Text-display--inline"><strong>Flat amount</strong></span>
				</div>
			</td>
		</tr>
	</thead>
	<tbody >
  @foreach($plan->tiers as $id => $tier)
    @if($loop->first)
    <tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the first <strong>{{$id+1}} to {{ $tier->up_to }}</strong></span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @if(!($id==0) && !($id == array_key_last($plan->tiers)))
		<tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the next <strong>{{$plan->tiers[$id-1]->up_to + 1}}   to {{$tier->up_to}}</strong></span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @if($loop->last)  
		<tr >
			<td class="">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau"><span>For the rest {{ $tier->up_to }}</span></span>
				</div>
			</td>
			<td class="" style="max-width: 150px; min-width: 150px; width: 150px;">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span >{{Cashier::formatAmount($tier->amount??'0', $plan->currency??'USD')}}</span></div>
			</td>
			<td class="Table-cel">
				<div class="" style="align-items: flex-start; justify-content: flex-start;"><span class="Text-color--defau">{{Cashier::formatAmount($tier->flat_amount??'-', $plan->currency??'USD')}}</span></div>
			</td>
		</tr>
        @endif
        @endforeach
	</tbody>
</table>
 </details>
                        @elseif (!empty($plan->amount))
                        <h3> {{Cashier::formatAmount($plan->amount??'0', $plan->currency??'USD')}}/ {{$plan->interval_count}} {{$plan->interval}}</h3>

                        @else
<h3>unknown</h3>
@endif
                  <hr/>

                  <ul style="position: relative;width: 75%;padding-left: 20px;margin: 0 auto;">
                    <li>Trial days: {{$plan->trial_period_days??'no trials'}} days</li>
                    
                    @foreach ($plan->metadata->jsonSerialize() as $key => $value)
                  
                    
                    <li>{{$value??'Lorem Ipsum is simply dummy text of the printing'}}</li>
                    @endforeach
                  </ul>

                      <?php

                      if ( isset($login) && $login ) {
                        if(!empty($plan->tiers)){
                            $selectPLanLink = 'href=/tierpayment/'.$plan->id;
                        }else{
                            $selectPLanLink = 'href=/payment/'.$plan->id;
                        }
                          
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
