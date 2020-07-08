@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}
	</style>

	<link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
	<script src="{{ asset('frontend/owl.carousel.min.js') }}" ></script>

	{{-- <script src="//vjs.zencdn.net/ie8/1.1.1/videojs-ie8.min.js"></script>
	<link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet">
	<script src="//vjs.zencdn.net/5.4.6/video.min.js"></script> --}}
	
    <!-- video.js -->
    <link href="http://vjs.zencdn.net/6.6.3/video-js.css" rel="stylesheet">
    <script src="http://vjs.zencdn.net/6.6.3/video.js"></script>

    <!-- Brightcove DVRUX plugin -->
    <link href="//players.brightcove.net/videojs-live-dvrux/1/videojs-live-dvrux.min.css" rel="stylesheet">
    <script src="//players.brightcove.net/videojs-live-dvrux/1/videojs-live-dvrux.min.js"></script>

    <!-- Brightcove quality picker -->
    <link href="//players.brightcove.net/videojs-quality-menu/1/videojs-quality-menu.css" rel="stylesheet">
    <script src="//players.brightcove.net/videojs-quality-menu/1/videojs-quality-menu.min.js"></script>

		<script src="https://cdn.streamroot.io/videojs-hlsjs-plugin/1/stable/videojs-hlsjs-plugin.js"></script>
		
    <script>
			var options = {
					plugins: {
							// JSON config added by Brightcove player generator
							streamrootHls: {
									hlsjsConfig: {
											// Your Hls.js config
									},
									// captionConfig: {
									//     line: -1,
									//     align: 'center',
									//     position: 50,
									//     size: 40,
									// }
							},
							qualityMenu: {
									useResolutionLabels: true
							}
					}
			};
			var player = videojs('example-video', options);
			player.qualityMenu();
			player.dvrux();
	</script>
	<script>
		
		$(document).ready(function() {
			var owl = $('.owl-carousel');
			owl.owlCarousel({
				
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
			})
		});
	</script>
	<style>

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}

		.owl-carousel .owl-item img {
		    display: block;
		    width: auto;
		    margin: 0 auto;
		    height: 1.5rem;
		}

		.owl-item {
	        background-color: white;
		    padding: 2rem 0;
		    text-align: center;
		    background: #FFFFFF 0% 0% no-repeat padding-box;
		    box-shadow: 0px 3px 6px #00000029;
		    border-radius: 8px;
		}

		.owl-nav {
		    position: absolute;
		    top: 0;
		    width: 100%;
		    line-height: 0.7;
		    font-size: 2rem;
		    height: 100%;
		    z-index: -1;
		}

		.owl-nav button.disabled span {
		    color: #21252961;
		    cursor: auto;
		}

		.owl-nav button.disabled span:focus {
		    outline: none;
		}

		.owl-nav button.owl-prev {
		    float: left;
		    position: relative;
		    left: -20px;
		}

		.owl-nav button {
		    top: 30%;
		}

		.owl-nav button.owl-next {
		    float: right;
		    position: relative;
		    right: -20px;
		}

		.page-section {
		    padding: 5.91rem 0;
		}

		.arrow-custom-car button.owl-prev {
		    float: right !important;
		    top: -30px !important;
		}

		.arrow-custom-car button.owl-next {
		    top: -30px;
		}

		.full-width {
			background: #000000 0% 0% no-repeat padding-box;
		}

		.video-container {
			/*width: 100%;*/
		}

		.video-container > div {
			/*margin: 0 auto;*/
		}

		.video-js .vjs-big-play-button{
			top: 35%;
    		left: 45%;
		}

		.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		.channel-detail {
			position: absolute;
		    top: 20%;
		    right: -10%;
		    color: #ffffff;
		    padding: 20px;
		    background: #333333 0% 0% no-repeat padding-box;
		    box-shadow: 0px 3px 6px #00000029;
		    opacity: 1;
		    backdrop-filter: blur(30px);
		    -webkit-backdrop-filter: blur(30px);
		}

		.channel-detail:before {
		    content: '';
		    width: 0px;
		    height: 0px;
		    background-color: transparent;
		    z-index: 999;
		    display: block;
		    left: -20px;
		    position: absolute;
		    border-left: 10px solid transparent;
		    border-top: 10px solid transparent;
		    border-right: 10px solid #333333;
		    border-bottom: 10px solid transparent;
		}

		.owl-stage-outer {
			height: 100%;
		}
	</style>
@endsection

@section('content')


	<section class="page-section">
{{-- {{$channelData}} --}}
		<div class="full-width">
			<div class="container">
				<div class="row">
				
					<div class="col-md-8 col-centered">
						<video id="example-video" width="600" height="300" class="video-js vjs-default-skin" controls autoplay muted>
							<!-- <source src="http://sample.vodobox.net/skate_phantom_flex_4k/skate_phantom_flex_4k.m3u8" type="application/x-mpegURL"/> -->
							<source src={{$url[0]->url}} type="application/x-mpegURL"/>
					</video>

						<div class="channel-detail">
							<p>{{$channelData[0]->callSign}}</p>
						</div>
					</div>
				</div>
				<!-- <div class="row">
					
					<div class="offset-md-3 col-xs-6 video-container">
						<video id="example_video_1" class="video-js vjs-default-skin"
						  controls preload="auto" width="640" height="264"
						  poster="//vjs.zencdn.net/v/oceans.png"
						  data-setup='{"example_option":true}'>
						 <source src="//vjs.zencdn.net/v/oceans.mp4" type="video/mp4" />
						</video>

					</div>
					<div class="col-xs-3">
						<div>
							<p>Times Now Global</p>
							<p>News Bulletin</p>
							<p>04:00 - 05:00PM</p>
							<p>Genre : <a href="javascript:void(0)">News</a></p>
						</div>
					</div>
				</div> -->
			</div>
		</div>

		<div class="container" style="margin-top: 4%;">
			<h4 class="section-heading">Similar Channels</h4>
			<div class="row">
				<div class="live-news-carousel arrow-custom-car owl-carousel">
					@foreach ($relatedchannel as $channeldet)
					<a href="{{url('livetv/'.$channeldet->callSign.'/'.$genre)}}"><div class="live-news-item">
						<img src="{{  $channeldet->logo}}">
						<p>{{  $channeldet->callSign}}</p>
					</div> </a>
					@endforeach
					</div>
					
				</div>
			</div>
		</div>
	</section>
	
@endsection