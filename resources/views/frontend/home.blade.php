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



<script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>

  


	<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.2/owl.theme.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.2/owl.theme.min.css"> -->

	<link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
	<script defer src="{{ asset('frontend/owl.carousel.min.js') }}" ></script>

	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script> -->
	<script>

$(function () {
    $.FindContainer = function () {
        $('.tab-content>div').each(function findcontent() {
            var newindex = $('.activetab').index();
            var newheight = $('.activetab').height();
            $('.tab-content').animate({
                'height': newheight+20
            }, 100);
            var otherindex = $(this).index();
            var substractindex = otherindex - newindex;
            var currentwidth = $('.multipletab').width();
            var newpositions = substractindex * currentwidth;
            $(this).animate({
                'left': newpositions
            });
        });
    };
    $.FindId = function () {
        $('.tab-content>div').each(function () {
            if ($(this).attr('id') == $('.active').attr('id')) {
                $('.tab-content>div').removeClass('activetab');
                $(this).addClass('activetab');
            }
        });
    };
    $('.tab-buttons>span').first().addClass('active');
    $('.tab-content>div').each(function () {
        var activeid = $('.active').attr('id');
        if ($(this).attr('id') == activeid) {
            $(this).addClass('activetab');
        }
        var currentheight = $('.activetab').height();
        var currentwidth = $('.multipletab').width();
        var currentindex = $(this).index();
        var currentposition = currentindex * currentwidth;
        $(this).css({
            'left': currentposition,
                'width': currentwidth - 40,
                'padding': '10px 20px'
        });
        $(this).attr('data-position', currentposition);
        $('.tab-content').css('height', currentheight+20);
    });
    $('.tab-buttons>span').click(function () {

        $('.tab-buttons>span').removeClass('active');
        $(this).addClass('active');
        var currentid = $('.active').attr('id');
        $.FindId();
        $.FindContainer();
    });
    $('.next').click(function () {
        var activetabindex = $('.activetab').index() + 1;
        var containers = $('.tab-content>div').length;
        if (containers == activetabindex) {
            $('.tab-buttons>span').removeClass('active');
            $('.tab-buttons>span').first().addClass('active');
            var currentid = $('.active').attr('id');
            $.FindId();
            $.FindContainer();
        } else {
            var currentopen = $('.active').next();
            $('.active').removeClass('active');
            currentopen.addClass('active');
            $.FindId();
            $.FindContainer();
        }
    });
  $('.prev').click(function(){
    var activetabindex = $('.activetab').index();
        if (activetabindex == 0) {
            $('.tab-buttons>span').removeClass('active');
            $('.tab-buttons>span').last().addClass('active');
            var currentid = $('.active').attr('id');
            $.FindId();
            $.FindContainer();
        } else {
            var currentopen = $('.active').prev();
            $('.active').removeClass('active');
            currentopen.addClass('active');
            $.FindId();
            $.FindContainer();
        }
  });
});
		/*$('.live-news-carousel').owlCarousel({
		    center: true,
		    items:6,
		    loop:true,
		    margin:10,
		    nav: true,
		    responsive:{
		        600:{
		            items:4
		        }
		    }

		});*/
		$(document).ready(function() {
     
		var owl = $('.owl-carousel');
		$.ajaxSetup({

headers: {

		'X-CSRF-TOKEN': $("input[name=_token]").val()

}

});
			owl.owlCarousel({
				slideBy: 6,
				loop:true,
			    margin:20,
			    nav: true,
			    responsiveClass:true,
			    responsive:{
			        0:{
			            items:1,
			            nav:true
			        },
			        600:{
			            items:3,
			            nav:false
			        },
			        1000:{
			            items:6,
			            nav:true,
			            loop:false
			        }
			    }
			});
		
		$(document).on('click','.btn-submit',function(e){


e.preventDefault();
let channelid = $(this).data('id');


$.ajax({

	 type:'POST',

	 url:"{{ url('favorite') }}",

	 data:{channelid:channelid,
	 },

	 success:function(data){
	$('#'+channelid).html('<p>Added to favorite</p> <button data-id='+channelid+' class="btn btn-danger btn-remove" >Remove</button>');

	 }

});



});	
$(document).on('click','.btn-remove',function(e){
	



e.preventDefault();
let channelid = $(this).data('id');

$.ajax({

	 type:'POST',

	 url:"{{ url('unfavorite') }}",

	 data:{channelid:channelid,
	 },

	 success:function(data){

	$('#'+channelid).html('<p>Add to favorite</p> <button data-id='+channelid+' class="btn btn-success btn-submit" >Add</button>');

	 }

});



});
		});
	</script>


@endsection
@section('stylesheets')
    <style>
               /* .grow { transition: width 2s;} */
.grow:hover {     
    /* width: 300px; */
     /* background: transparent linear-gradient(98deg, #000428 0%, #004E92 100%) 0% 0% no-repeat padding-box; */
     
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
	
	.css-5nata0 .upper-section .content .apps,
	[data-css-5nata0] .upper-section .content .apps {
        margin: 20px 0;
        width: 296px;
height: 64px;
  background: linear-gradient(to right, #F7B292 50%, #FF712F 50%);
  background-size: 200% 100%;
  background-position: right bottom;
  transition: all .5s ease-out;
  color: #202020;
  padding: 1rem 2rem !important;
		/* background-color: transparent; */
		
		align-self: flex-start;
		-webkit-align-self: flex-start;
	}
	
	.css-5nata0 .upper-section .devices-preview,
	[data-css-5nata0] .upper-section .devices-preview {
		position: relative;
		right: -35%;
		width: 100%;
		max-width: 1200px;
	}
	
	.css-5nata0 .upper-section .devices-preview img,
	[data-css-5nata0] .upper-section .devices-preview img {
		width: 100%;
	}
	
	.css-5nata0 .support-platforms img,
	[data-css-5nata0] .support-platforms img {
		width: 100%;
		height: auto;
		padding: 0 36px;
	}
	
	@media(max-width: 1100px) {
		.css-5nata0,
		[data-css-5nata0] {
			padding: 100px 0;
		}
		.css-5nata0 .upper-section .content,
		[data-css-5nata0] .upper-section .content {
			position: unset;
			margin-bottom: 60px;
			display: -webkit-box;
			display: -moz-box;
			display: -ms-flexbox;
			display: -webkit-flex;
			display: flex;
			align-items: center;
			text-align: center;
			-webkit-box-align: center;
			-webkit-align-items: center;
		}
		.css-5nata0 .upper-section .content .title br,
		[data-css-5nata0] .upper-section .content .title br {
			display: none;
		}
		.css-5nata0 .upper-section .content .apps,
		[data-css-5nata0] .upper-section .content .apps {
			align-self: center;
			-webkit-align-self: center;
		}
		.css-5nata0 .upper-section .devices-preview,
		[data-css-5nata0] .upper-section .devices-preview {
			right: 0;
		}
	}
	
	@media(max-width: 768px) {
		.css-5nata0 .upper-section .content .title,
		[data-css-5nata0] .upper-section .content .title {
			font-size: 32px;
			letter-spacing: 0.34px;
		}
		.css-5nata0 .upper-section .content .sub-title,
		[data-css-5nata0] .upper-section .content .sub-title {
			font-size: 16px;
			letter-spacing: 0.17px;
		}
		.css-5nata0 .upper-section .content .apps,
		[data-css-5nata0] .upper-section .content .apps {
			align-self: center;
			-webkit-align-self: center;
		}
	}
	
	@media(max-width: 425px) {
		.css-5nata0 .support-platforms img,
		[data-css-5nata0] .support-platforms img {
			padding: 0 10px;
		}
	}
    .css-5nata0 .upper-section .content,
	[data-css-5nata0] .upper-section .content {
		position: absolute;
		left: 100px;
	}
    .css-fxmkwd .content,
	[data-css-fxmkwd] .content {
		display: -webkit-box;
		display: -moz-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		flex-grow: 1;
		flex-direction: column;
		-webkit-flex-grow: 1;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
	}
    .css-5nata0 .upper-section .content, [data-css-5nata0] .upper-section .content {
    position: absolute;
    left: 100px;
    /* color: #fff; */
}

/* for genre channel */


	
    </style>
@endsection
@section('stylesheets-bottom')

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
         <div class="alert alert-danger alert-dismissible ">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{$errors->first()}}
        </div>
    @endif --}}

    

	<!-- Masthead-->
	<header class="masthead">
	    <div class="container">
            <p style=" text-transform: uppercase;"><strong>Your alternative to Cable!</strong></p>
	        <div class="masthead-heading"><img src="{{ asset('frontend/img/streamlylogo5.png') }}"  loading="lazy" alt="logo of streamly" /></div>
	       
            <p ><strong>Watch live TV on your iOS or Android, Amazon Fire, Roku, and SmartTV's </strong> </p>
            <p>STREAMLY good channels. STREAMLY flexible terms. STREAMLY fair pricing!</p>
	        <a class="btn btn-primary btn-xl js-scroll-trigger grow" href="#services">START TODAY</a>
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


	<!-- Select Plan-->
    <section class="page-section" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading">Select Your Plan</h2>
            </div>

<!-- Stats Card -->
<!--
<div class="card">
    <div class="card-body">
      <h5 class="card-title">Stats</h5>
      <table class="table table-striped">
        <tr>
          <td>Set commands</td>
          {{-- <td>{{ $stats1['cmd_set'] }}</td> --}}
        </tr>item1
        <tr>
          <td>Get hits</td>
          {{-- <td>{{ $stats1['get_hits'] }}</td> --}}
        </tr>
        <tr>
          <td>Get misses</td>
          {{-- <td>{{ $stats1['get_misses'] }}</td> --}}
        </tr>
      </table>
    </div>
  </div>
-->
            <div class="row text-center justify-content-center align-items-center h-100 select-card-parent" style="padding-top: 40px;">
                @foreach ($plans->data as $plan)
                <div class="col-sm-4 select-plan-card">
                	<div style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 4px;">
                		<p>{{ $plan->nickname }}</p>

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
  
    width: 163px;
    height: 41px;

}
</style>
<section class="page-section bg-light">
    <div class="container">
        <div class="text-center">

            <h2 class="section-heading">Available Channels</h2>
            <div class="row">
                <div class="col-centered" id="tab-buttons">
                    <a href="#Movies" class="btn btn-sm btn-default faq-btn" data-toggle="collapse">Movies</a>
                
                    <a href="#Others" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Others</a>
                
                    <a href="#Comedy" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Comedy</a>
                    <a href="#News" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">News</a>
                    <a href="#Sports" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Sports</a>
                    <a href="#Family" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Family</a>
                    <a href="#Kids" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Kids</a>
                </div>
            </div>
        </div>
    <div class="row col-centered" id="accordion">
        @foreach($pagechannel2 as $key => $user)
        
        @if($key=='Movies')
    <div id="{{$key}}" class="col-sm-12 collapse  text-center show" data-parent="#accordion">
        <div class=""> <div class="grid-container">@foreach ($user as $channeldet2)
        <div class="item1"> 
            <img style='height: 100%; width: 100%; object-fit: contain' class="lazy" data-src="{{  $channeldet2->logo}}" loading="lazy" alt="Logo of {{$channeldet2->callSign}}">
        </div>  @endforeach</div></div>
    </div>
    @else

    <div id="{{$key}}" class="col-sm-12 collapse  text-center " data-parent="#accordion">
        <div class=""><div class="grid-container">@foreach ($user as $channeldet2)
        <div class="item1"> 
            <img style='height: 100%; width: 100%; object-fit: contain' class="lazy" data-src="{{  $channeldet2->logo}}" loading="lazy" alt="Logo of {{$channeldet2->callSign}}">
        </div>  @endforeach</div></div>
    </div>
@endif
    @endforeach
</div>
    </div>
</section>
<style>
.item1{
    height: 80px;
    width: 80px;
}
    .grid-container {
      display: grid;
      grid-template-columns: auto auto auto auto auto auto auto auto auto auto auto auto auto ;
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
    {{-- <section class="page-section bg-light" id="portfolio">
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
    </section>  --}}
     <section class="page-section " id="portfolio">
        {{-- <div class="container">
            <div class="text-center">
                <h2 class="section-heading">Download Our App</h2>
            </div>
            
        </div> --}}

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
                <div class="devices-preview"><img class="lazy" data-src="{{url('../frontend/img/devices3.png')}}" 
  
alt="image of tv guide"
></div>
            </div>
        </div>

        <div class="flex-container space-evenly">
            
            <svg class = "circle" id="roku" expanded = "true" height = "200px" width = "200px" >
                <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg>
              
              <svg class = "circle" id="amazonfiretv" expanded = "true" height = "200px" width = "200px" >
                <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg>
              <svg class = "circle" id="appletv" expanded = "true" height = "200px" width = "200px" >
                <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg>
              <svg class = "circle" id="iostv" expanded = "true" height = "200px" width = "200px" >
                <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg>
              <svg class = "circle" id="android" expanded = "true" height = "200px" width = "200px" >
                <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg>
              <svg class = "circle" id="samsungtv" expanded = "true" height = "200px" width = "200px" >
                <circle class = "innerCircle" cx = "50%" cy = "50%" r = "5px">
              </svg> </div>
    </div>
    </section>
<style>
    .flex-container {
        width: 100%;   
    
  padding: 0;
  margin: 0;
  list-style: none;
  display: flex;
}

.space-evenly { 
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

      background-image: url("../frontend/img/newicon/ios.png");

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