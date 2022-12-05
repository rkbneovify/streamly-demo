<?php 
use Laravel\Cashier\Cashier;?>
@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets-bottom')
@include('frontend.partials.sidenavRegisterAndLoginJS')
<script>

    var botmanWidget = {
    title:'Streamly Bot',
    introMessage: 'Hello, I am a Streamly Bot! I am here to assist you and answer all your questions about our products and services!',
    mainColor:'#004E92',
    aboutText:'',
    bubbleBackground:'#004E92',
    headerTextColor: '#fff',
};

</script>


<script defer src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
@endsection
@section('stylesheets')
    <style>
nav#mainNav {
    background-color: #004E92!important;
}

 #botmanWidgetRoot>div{
    z-index: 1000 !important;
 }
.unlock {
    margin: 60px 0px;
}
body {
    background-color: #edecec!important;
}
*{
    margin:0;
    padding:0;
}
.inner{
    position:relative;
    top:0px;
}
.outer{
    position: absolute;
    overflow: hidden;
    z-index: -1;
    width: 100%;
    height: 65%;
    opacity: 0.5;
}
.bxoutside {
    display: flex;
    justify-content: space-around;
    margin: 25px 10px;
}
.bxinside {
    height: 4vw;
    width: 4vw;
    padding: 5px;
    background-color: #4D5659;
    border-radius: 15px;
}
.bximages {
    height: 100%;
    width: 100%;
}
.overshadow {
    background: -webkit-radial-gradient(rgb(108 116 131 / 50%) 0%, #edecec 80%);
    background: -o-radial-gradient(rgba(108 116 131 / 50%) 0%, #edecec 80%);
    position: absolute;
    width: 100%;
    height: 65%;
    max-height: 282px;
    z-index: -1;
}
@media screen and (max-width: 767px) {
  .outer {
     display: none;
  }
  .overshadow {
     display: none;
  }
}
</style>
@include('frontend.partials.sidenavRegisterAndLogin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="outer">
    <div class="inner">
        <div class="bxoutside">
        <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/BBC-W.png">
            </div>
            <div class="bxinside">
            <img class="bximages"src="/uploads/channellogo/ACCN.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/COMEDY.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/COOK.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/DXD.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/ESPNEWS.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/WE.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/NICK.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/MAVTV.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/ID.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/IFC.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/HSN.png">
            </div>
        </div> 
        <div class="bxoutside">
        <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/TVLAND.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/BET.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/MTV.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/USA.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/TNT.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/VH1.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/HGTV.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/HALL.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/GAC.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/FXM.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/FX.png">
            </div>
        </div> 
        <div class="bxoutside">
        <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/GSN.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/HLN.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/HIST-W.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/FOOD.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/FNC.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/DEST.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/CNN.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/CARTOON.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/ANE.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/AMC.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/AHC.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/AETV.png">
            </div>
        </div> 
        <div class="bxoutside">
        <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/TVLAND.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/BET.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/MTV.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/USA.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/TNT.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/VH1.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/HGTV.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/HALL.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/GAC.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/FXM.png">
            </div>
            <div class="bxinside">
            <img class="bximages" src="/uploads/channellogo/FX.png">
            </div>
        </div> 
    </div>
</div>
<div class="overshadow"></div>

	<!-- Select Plan-->
<section class="page-section ">
    <div class="container">
        <div class="text-center">
        <div class="unlock">
        <h2 style="text-shadow: 0.5px 0px #ffffff;" class="section-heading">Subscribe to watch all content on Streamly</h2>
        <p>Please select a plan to get access on all our platforms. <br>Explore and watch live TV on your iOS or Android, Amazon Fire, Roku, and SmartTV's</p>
        </div>
            <h2 class="section-heading">Available Plans</h2>
           
            <div class="row">
                <div class="col-centered" id="tab-buttons">

 @foreach ($plankey as $plank) 
 <?php
    if($plank == "per_unit"){
        $title = "Basic Plan";}
    else{
        $title = $plank;}
?>
 @if ($loop->first)  
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn" data-toggle="collapse">{{Str::title($title)}}</a>
 @else
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn collapsed" data-toggle="collapse">{{Str::title($title)}}</a>
 @endif
 @endforeach
                    
                </div>
            </div>
        </div>
   <div class=" " id="accordion">
        @foreach($plansdetails as $keyp => $singlePlansDetails)
        @if ($loop->first)  
        <div id="{{$keyp}}" class=" collapse  text-center show" data-parent="#accordion">
        <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
           @foreach ($singlePlansDetails as $singlePlansDetail)
            
           @component('components.plan',['singlePlansDetail' => $singlePlansDetail,'type'=>'per_unit'])
		
		@endcomponent
              @endforeach</div>
        </div> 
   
    @else

    <div id="{{$keyp}}" class=" collapse  text-center " data-parent="#accordion">
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
<style>.custom-row .row{
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
@media screen and (max-width: 540px){
       #pills-tab {
            justify-content: center!important;
        }
	}
</>

<style>
 #pills-tab>.nav-item>a{
  position:relative;
  box-shadow:0 0 10px rgba(0,0,0,0.5);
  font:400 15px 'pt sans',sans-serif;
  color:#666;
  padding:0 15px;
  background-color:#fff;
  line-height:40px !important;
  border: 2px solid #FF712F;
}
#pills-tab>.nav-item>a.active { display:block;
  float:left;
  cursor:pointer;
  color:#fff;
  background:#FF712F;
  border: 2px solid #FF712F;}
 
    </style>
<script>
    function autoScrollDown(){
    $(".inner").css({top:-$(".outer").outerHeight()}) // jump back
               .animate({top:0},5000,"linear", autoScrollDown); // and animate
}
function autoScrollUp(){
    $(".inner").css({top:0}) // jump back
               .animate({top:-$(".outer").outerHeight()},15000,"linear", autoScrollUp); // and animate
}
// fix hight of outer:
$('.outer').css({maxHeight: $('.inner').height()});
// duplicate content of inner:
$('.inner').html($('.inner').html() + $('.inner').html());
autoScrollUp();
</script>
@endsection