@extends('frontend/partials/master')

@section('stylesheets-bottom')
<style>
	nav#mainNav {
		background-color: #004E92;
	}
	video {
			width: 100%;
	}

	.dash-video-player {
			position: relative;  /* This position relative is needed to position the menus */
			width: 640px;
			margin: 0 auto;
	}
	.inputForm div {
					margin-bottom: 10px;
			}

			label {
					width: 135px;
					display: inline-block;
			}

			input[type="text"] {
					width: 500px;
			}
</style>

<link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
<script src="{{ asset('frontend/owl.carousel.min.js') }}" ></script>
<link rel="stylesheet" href="{{ asset('frontend/controlbar/controlbar.css') }}">
<script src="{{ asset('frontend/dashjs/dash.all.min.js') }}" ></script>
<script src="{{ asset('frontend/dashjs/dash.mss.min.js') }}" ></script>
<script src="{{ asset('frontend/controlbar/ControlBar.js') }}" ></script>


<script>
	$('document').ready(function(){
		var owl = $('.owl-carousel');
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
		
	});
	var player,firstLoad = true;
 
  function init() {
      player = dashjs.MediaPlayer().create();
      // player.updateSettings({ 'debug': { 'logLevel': dashjs.Debug.LOG_LEVEL_NONE }});
      player.updateSettings({
          // 'debug': {'logLevel': dashjs.Debug.LOG_LEVEL_DEBUG},
          'streaming': {
						'liveDelay': 10,
						'fastSwitchEnabled': true  ,
              //Set retryIntervals of MPD (file with description of the resources forming a streaming service, that a DASH client uses to
              //determine which assets to request in order to perfom adaptive streaming of the content.
              //In other word, make 3 attemps to load files, each attemps with an interval of 5 seconds (5000 milliseconds).
              'retryIntervals': {MPD: 5000},
              'retryAttempts': {MPD: 3}
          },'abr': { 'autoSwitchBitrate': { 'video': false } }
      });
      player.setProtectionData({
        "com.widevine.alpha": {
          serverURL: "//wv-keyos.licensekeyserver.com/",
          httpRequestHeaders: {customdata:'{{$encoded_auth_xml}}'},
          clearkeys: ""
        },
        "com.microsoft.playready": {
          serverURL: "//pr-keyos.licensekeyserver.com/core/rightsmanager.asmx",
          httpRequestHeaders: {customdata:'{{$encoded_auth_xml}}'},
          clearkeys: ""
        }
      });
      firstLoad = false;
                  
              var url = "{{$url[0]->url}}";
              // var url = "http://videos.electroteque.org/playready/big_buck_bunny.ism/Manifest";
                  player.initialize(document.querySelector("video"), url, true);
                  var controlbar = new ControlBar(player); //Player is instance of Dash.js MediaPlayer;
  controlbar.initialize();
  }
  
  document.addEventListener("DOMContentLoaded", function () {
                  init();
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
						<video  autoplay="" preload="auto" id="videoPlayer"  autoplay > </video>
            <div id="videoController" class="video-controller unselectable">
              <div id="playPauseBtn" class="btn-play-pause" title="Play/Pause">
                  <span id="iconPlayPause" class="icon-play"></span>
              </div>
              <span id="videoTime" class="time-display">00:00:00</span>
              <div id="fullscreenBtn" class="btn-fullscreen control-icon-layout" title="Fullscreen">
                  <span class="icon-fullscreen-enter"></span>
              </div>
              <div id="bitrateListBtn" class="control-icon-layout" title="Bitrate List">
                  <span class="icon-bitrate"></span>
              </div>
              <input type="range" id="volumebar" class="volumebar" value="1" min="0" max="1" step=".01"/>
              <div id="muteBtn" class="btn-mute control-icon-layout" title="Mute">
                  <span id="iconMute" class="icon-mute-off"></span>
              </div>
              <div id="trackSwitchBtn" class="control-icon-layout" title="A/V Tracks">
                  <span class="icon-tracks"></span>
              </div>
              <div id="captionBtn" class="btn-caption control-icon-layout" title="Closed Caption">
                  <span class="icon-caption"></span>
              </div>
              <span id="videoDuration" class="duration-display">00:00:00</span>
              <div class="seekContainer">
                  <div id="seekbar" class="seekbar seekbar-complete">
                      <div id="seekbar-buffer" class="seekbar seekbar-buffer"></div>
                      <div id="seekbar-play" class="seekbar seekbar-play"></div>
                  </div>
              </div>
          </div>

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