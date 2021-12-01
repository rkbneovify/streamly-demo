<?php 
use Laravel\Cashier\Cashier;
$login= Auth::check()?true:false;?>
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
        .grow {
	transition: width 2s;
}

.grow:hover {
	width: 300px;
	background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box;
}
 #botmanWidgetRoot>div{
    z-index: 1000 !important;
 }

        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
        	
	.css-thjkr7,
	[data-css-thjkr7] {
		position: relative;
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		padding: 0;
		margin: 0;
		/* background: -webkit-linear-gradient(98deg, #000428 0%, #004E92 100%);
		background: -moz-linear-gradient(98deg, #000428 0%, #004E92 100%);
		background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box; */
		/* background-color: #15101E; */
		background-image: null;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
	}
    .css-5nata0,
	[data-css-5nata0] {
		width: 100%;
		height: 100%;
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		/* padding: 130px 0 44px; */
		position: relative;
		overflow: hidden;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
	}

	.css-5nata0 .upper-section,
	[data-css-5nata0] .upper-section {
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-wrap: wrap;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		width: 100%;
		margin-bottom: 52px;
		-webkit-box-lines: multiple;
		-webkit-flex-wrap: wrap;
		-webkit-box-orient: horizontal;
		-webkit-box-direction: normal;
		-webkit-flex-direction: row;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		-webkit-box-align: center;
		-webkit-align-items: center;
	}
	
	.css-5nata0 .upper-section .content,
	[data-css-5nata0] .upper-section .content {
		position: absolute;
		left: 100px;
	}
	
	.css-5nata0 .upper-section .content .title,
	[data-css-5nata0] .upper-section .content .title {
		font-size: 48px;
		font-weight: 800;
		letter-spacing: 0.25px;
		max-width: 856px;
		margin-bottom: 20px;
	}
	
	.css-5nata0 .upper-section .content .sub-title,
	[data-css-5nata0] .upper-section .content .sub-title {
		font-size: 18px;
		line-height: 25px;
		font-weight: 500;
		letter-spacing: 0.19px;
		max-width: 856px;
	}
	
    .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                margin: 26px 0 20px;
                padding: 8px 30px;
                font-size: 16px;
                line-height: 21px;
                background-color: transparent;
                border: 2px solid #FFFFFF;
                border-radius: 20px;
                align-self: flex-start;
                -webkit-align-self: flex-start;
            }

            .css-5nata0 .upper-section .devices-preview,[data-css-5nata0] .upper-section .devices-preview {
                position: relative;
                right: -35%;
                width: 100%;
                max-width: 1200px;
            }

            .css-5nata0 .upper-section .devices-preview img,[data-css-5nata0] .upper-section .devices-preview img {
                width: 100%;
            }

            .css-5nata0 .support-platforms img,[data-css-5nata0] .support-platforms img {
                width: 100%;
                height: auto;
                padding: 0 36px;
            }

            @media(max-width: 1100px) {
                .css-5nata0,[data-css-5nata0] {
                    padding:100px 0;
                }

                .css-5nata0 .upper-section .content,[data-css-5nata0] .upper-section .content {
                    position: unset;
                    margin-bottom: 60px;
                    display: -webkit-box;
                    display: -moz-box;
                    display: -ms-flexbox;
                    display: -webkit-flex;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    -webkit-box-align: center;
                    -webkit-align-items: center;
                }

                .css-5nata0 .upper-section .content .title br,[data-css-5nata0] .upper-section .content .title br {
                    display: none;
                }

                .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                    align-self: center;
                    -webkit-align-self: center;
                }

                .css-5nata0 .upper-section .devices-preview,[data-css-5nata0] .upper-section .devices-preview {
                    right: 0;
                }
            }

            @media(max-width: 768px) {
                .css-5nata0 .upper-section .content .title,[data-css-5nata0] .upper-section .content .title {
                    font-size:32px;
                    letter-spacing: 0.34px;
                }

                .css-5nata0 .upper-section .content .sub-title,[data-css-5nata0] .upper-section .content .sub-title {
                    font-size: 16px;
                    letter-spacing: 0.17px;
                }

                .css-5nata0 .upper-section .content .apps,[data-css-5nata0] .upper-section .content .apps {
                    align-self: center;
                    -webkit-align-self: center;
                }
            }

            @media(max-width: 425px) {
                .css-5nata0 .support-platforms img,[data-css-5nata0] .support-platforms img {
                    padding:0 10px;
                }
            }

/* for genre channel items*/

.item1{
    height: 80px;
    width: 80px;
    
    /* background-color:#fff; */
}

.grid-container > div {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  /* justify-content: center;
  align-items: center; */
  font-size: 2em;
  /* color: #20263f; */
  border-radius: 15px;
      background-color:  #4D5659; 
      text-align: center;
      padding: 10px 0;
}

.grid-container {
/* width: 100%; */
  display: -ms-grid;
  display: grid;
  grid-gap: 40px;
  grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
  grid-auto-rows: 50px;
  justify-items: center;
  
  -webkit-box-pack: space-evenly;
  
      -ms-flex-pack: space-evenly;
  
          justify-content: space-evenly;
  padding: 10px;
    }
    .grid-container > div.item1 > img {
  width: 100%;
  height: 100%;
  -o-object-fit: contain;
     object-fit: contain;
}

/*devices circle*/

.flex-container {
        width: 100%;   
    
  padding: 0;
  margin: 0;
  list-style: none;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
      flex-wrap: wrap;
}

/* Responsive layout - makes a one column-layout instead of two-column layout */
@media (max-width: 800px) {
    
    .flex-container>div {
    text-align: center ;
    -webkit-box-flex: 0;
        -ms-flex: 0 50%;
            flex: 0 50%;
  }
  .flex-container>div:not(:nth-last-child(-n+2))  {
    /* background-color: red; */
    padding-bottom: 5%;
}
}

.space-evenly { 
  -webkit-box-pack: space-evenly; 
      -ms-flex-pack: space-evenly; 
          justify-content: space-evenly; 
}
    .hovicon {
        display: inline-block;
        font-size: 45px;
        line-height: 90px;
        cursor: pointer;
        margin: 20px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        text-align: center;
        position: relative;
        text-decoration: none;
        z-index: 1;
        color: #fff;
    }
    
.hovicon:after {
    pointer-events: none;
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    content:'';
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
}
.hovicon:before {
    speak: none;
    font-size: 48px;
    line-height: 90px;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    display: block;
    -webkit-font-smoothing: antialiased;
}
/* Effect 1 */
.hovicon.effect-1 {
    background: rgba(255, 255, 255, 0.1);
    -webkit-transition: background 0.2s, color 0.2s;
    -moz-transition: background 0.2s, color 0.2s;
    transition: background 0.2s, color 0.2s;
}
.hovicon.effect-1:after {
    top: -7px;
    left: -7px;
    padding: 7px;
    box-shadow: 0 0 0 4px #FF712F;
    -webkit-transition: -webkit-transform 0.2s, opacity 0.2s;
    -webkit-transform: scale(1);
    -moz-transition: -moz-transform 0.2s, opacity 0.2s;
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    transition: transform 0.2s, opacity 0.2s;
    transform: scale(1);
    opacity: 0;
    
}
/* Effect 1a */
 .hovicon.effect-1.sub-a:hover {
    background: #000428;
    
}
.hovicon.effect-1.sub-a:hover i {
    
}
.hovicon.effect-1.sub-a:hover:after {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    transform: scale(1);
    opacity: 1;
}

circle {
  fill: white;
  fill-opacity: 0;
  stroke: #FF712F;
  stroke-width: 2px;
  transition: stroke-opacity 0.8s, stroke-width 0.8s, transform 0.8s;
  transform-origin: 50% 50%;
  stroke-opacity: 0;
}

circle:hover {
  stroke-opacity: 1;
  stroke-width: 0.5;
  transform: scale(15);
}

.circle
{ width:140px;
    height:140px;
    border-radius:70px;
    font-size:20px;
    color:#fff;
    line-height:100px;
    text-align:center;
    background:#000;
    }
    #roku{

      background-image: url("../frontend/img/newicon/roku.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }
    #amazonfiretv{

      background-image: url("../frontend/img/newicon/andriodtv.png");

/*background-size: cover;*/
background-size: 100px;
background-repeat: no-repeat;
background-position: center;
    }
    #appletv{

        background-image: url("../frontend/img/newicon/appletv.png");

/*background-size: cover;*/
background-size: 60px;
background-repeat: no-repeat;
background-position: center;
    }
    #iostv{

      background-image: url("../frontend/images/s/phones-and-tablets/apple_logo_360.png");

/*background-size: cover;*/
background-size: 50px;
background-repeat: no-repeat;
background-position: center;
    } 
     #android{

      background-image: url("../frontend/img/newicon/android.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }
    #samsungtv{

      background-image: url("../frontend/img/newicon/smarttv.png");

/*background-size: cover;*/
background-size: 87px;
background-repeat: no-repeat;
background-position: center;
    }

    .circle:hover .innerCircle{
      stroke-opacity: 1;
  stroke-width: 0.5;
  transform: scale(15);
    }
	
    </style>
@endsection
@section('content')
    {{-- {{$errors}} --}}
    {{-- @if($errors->any())
         <div class="alert alert-danger alert-dismissible ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{$errors->first()}}
        </div>
    @endif --}}

    

	<!-- Masthead-->
	<header class="masthead">
	    <div class="container">
            <p style=" text-transform: uppercase;"><strong>Your alternative to Cable!</strong></p>
	        <div class="masthead-heading">
                <img src="{{ asset('frontend/img/streamlylogo5.png') }}"  loading="lazy" alt="logo of streamly" class="img-fluid" />
                </div>
	       
            <p ><strong>Watch live TV on your iOS or Android, Amazon Fire, Roku, and SmartTV's </strong> </p>
            <p>STREAMLY good channels. STREAMLY flexible terms. STREAMLY fair pricing!</p>
	        <a class="btn btn-primary btn-xl js-scroll-trigger grow"  href="javascript:void(0);">START TODAY</a>
	    </div>
	</header>


    @include('frontend.partials.sidenavRegisterAndLogin')

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
    <style>
    
details > summary {
    list-style-type: none;
}

details > summary::-webkit-details-marker {
    display: none;
}

details > summary::before {
    content: '\002630';
}
details[open] > summary::before {
    content: '\2715';
}</style>

	<!-- Select Plan-->
<section class="page-section ">
    <div class="container">
        <div class="text-center">

            <h2 class="section-heading">Available Plans</h2>
           
            <div class="row">
                <div class="col-centered" id="tab-buttons">

 @foreach ($plankey as $plank) 
 @if ($loop->first)  
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn" data-toggle="collapse">{{Str::title($plank)}}</a>
 @else
 <a href="#{{$plank}}" class="btn btn-sm btn-default  faq-btn collapsed" data-toggle="collapse">{{Str::title($plank)}}</a>
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

</style>

<section class="page-section bg-light">
    <div class="container">
        <div class="text-center">

            <h2 class="section-heading">Available Channels</h2>
            </div>
      
<div class="row ">
<div class="col-centered"><ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

@foreach ($Genrekey as $genre) 
 @if ($loop->first)  

 <li class="nav-item">
    <a class="btn btn-sm btn-default active" id="pills-{{$genre}}-tab" data-toggle="pill" href="#pills-{{$genre}}" role="tab" aria-controls="pills-{{$genre}}" aria-selected="true">{{$genre}}</a>
  </li>
 @else
 <li class="nav-item">
    <a class="btn btn-sm btn-default" id="pills-{{$genre}}-tab" data-toggle="pill" href="#pills-{{$genre}}" role="tab" aria-controls="pills-{{$genre}}" aria-selected="false">{{$genre}}</a>
  </li>
 @endif
 @endforeach
</ul></div>
</div>
<div class="tab-content row col-centered " id="pills-tabContent">

@foreach($pagechannel as $key => $user)
        @if ($loop->first)  
        <div class="tab-pane fade show active col-sm-12 no-gutters" id="pills-{{$key}}" role="tabpanel" aria-labelledby="pills-{{$key}}-tab"><div class="h"> <div class="grid-container">@foreach ($user as $channeldet2)
            <div class="item1"> 
                <img  class="lazy" src="../uploads/channellogo/notfound.png" data-src="../uploads/channellogo/{{  $channeldet2->image}}" loading="lazy" alt="Logo of {{$channeldet2->CallSign}}">
            </div>  @endforeach</div></div></div>
        <!-- <div id="{{$key}}" class="col-sm-12 no-gutters collapse  text-center show" data-parent="#accordion">
            
        </div>  -->
   
    @else
    <div class="tab-pane fade col-sm-12 no-gutters" id="pills-{{$key}}" role="tabpanel" aria-labelledby="pills-{{$key}}-tab"><div class="h"><div class="grid-container">@foreach ($user as $channeldet2)
        <div class="item1"> 
            <img class="lazy" src="../uploads/channellogo/notfound.png" data-src="../uploads/channellogo/{{  $channeldet2->image}}" loading="lazy" alt="Logo of {{$channeldet2->CallSign}}">
        </div>  @endforeach</div></div></div>
    <!-- <div id="{{$key}}" class="col-sm-12 no-gutters collapse  text-center " data-parent="#accordion">
        
    </div>  -->
@endif
    @endforeach
</div>
    </div>
</section>
      <section class="page-section " id="portfolio">

    <div class="css-thjkr7">
        <div class="css-5nata0">
            <div class="upper-section">
                <div class="content">
                    <div class="title">Watch on all
                        <br> your devices.</div>
                    <div class="sub-title">Download the free Streamly apps for all your devices.
                        <br> Watch what you want, when you want.</div>
                    <div  ><a class="btn  btn-see-all-plan btn-xl" href="{{ url('/devices') }}" style=" text-transform: uppercase;">See all apps and devices</a></div>
                </div>
                {{-- <div class="content"><div class="title">Watch on all <br> your devices.</div><div class="sub-title">Download the free fuboTV apps for all your devices. <br> Watch what you want, when you want.</div>
                <div role="button" aria-label="" class="apps css-q9bl1b"><a href="/welcome/apps">See all apps and devices</a></div></div> --}}
                <div class="devices-preview"><img class="lazy img-fluid" data-src="{{url('../frontend/img/devices3.png')}}" 
  
alt="image of tv guide"
></div>
            </div>
        </div>

        <div class="flex-container space-evenly">
            
            <div><svg class = "circle" id="roku" expanded = "true" height = "200px" width = "200px" >
            <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
            </svg></div>
              
              <div><svg class = "circle" id="amazonfiretv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="appletv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="iostv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="android" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div>
              <div><svg class = "circle" id="samsungtv" expanded = "true" height = "200px" width = "200px" >
              <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg></div> </div>
    </div>
    </section>
<!--     
<section class="page-section " >
<div class="container"><div class="accordion" id="accordion1">

@foreach($Genrekey as $keyer =>$genrewe)
<?php 
?>
@component('components.card.card', ['cardHeadingId' => 'heading'.$keyer, 'cardCollapseId' => 'collapse'.$keyer, 'parentId' => 'accordion1','index'=>$keyer])
  @slot('cardTitle')
  my card title #{{$keyer}}
  @endslot
  
  @slot('cardBody')
    <strong> this the content of my card{{$keyer}} body</strong> 
  @endslot
  
@endcomponent
@endforeach
</div></div>
</section> -->
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
@endsection