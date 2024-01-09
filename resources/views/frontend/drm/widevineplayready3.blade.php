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
  <link rel="stylesheet" href="{{ asset('frontend/controlbar/controlbar.css') }}">
	<script src="{{ asset('frontend/dashjs/dash.all.min.js') }}" ></script>
	<script src="{{ asset('frontend/dashjs/dash.mss.min.js') }}" ></script>
<script src="{{ asset('frontend/controlbar/ControlBar.js') }}" ></script>
	

<script> var player,firstLoad = true;
 
  function init() {
      player = dashjs.MediaPlayer().create();
			// player.updateSettings({ 'debug': { 'logLevel': dashjs.Debug.LOG_LEVEL_NONE }});
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
      player.updateSettings({
          // 'debug': {'logLevel': dashjs.Debug.LOG_LEVEL_DEBUG},
          'streaming': {
						'liveDelay': 2,
                        'liveCatchUpMinDrift': 0.05,
                        'liveCatchUpPlaybackRate': 0.5,
                        'stableBufferTime': 2,
                        'bufferTimeAtTopQuality': 2,
                        'bufferTimeAtTopQualityLongForm': 2,
                        'bufferToKeep': 2,
                        'bufferAheadToKeep': 2,
                        'lowLatencyEnabled': true,
                        'fastSwitchEnabled': true,
          },'abr': { 'autoSwitchBitrate': { 'video': false } }
      });
      
      firstLoad = false;
                  
              var url = "http://rdmedia.bbc.co.uk/dash/ondemand/testcard/1/client_manifest-events.mpd";
                  player.initialize(document.querySelector("video"), url, true);
                  var controlbar = new ControlBar(player); //Player is instance of Dash.js MediaPlayer;
  controlbar.initialize();
  }
  
  document.addEventListener("DOMContentLoaded", function () {
                  init();
              });
   
  
              function load()
          {
              // Add your own URL in here if you wish to epxlore events with other content.
              var url2=document.getElementById('streamUrl').value;
              if (!firstLoad)
              {
                  player.attachSource(url2);
              }
          }
  
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
            <video preload="auto" id="videoPlayer"   autoplay="" > </video>
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
							<!-- <p>ghghg</p> -->
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

	
		<div class="inputForm">
      <div>
          <label>Stream Url: </label>
          <input class="code" type="text" id="streamUrl" value="https://livesim.dashif.org/livesim/testpic_2s/Manifest.mpd">  <input type="button" value="Load" onclick="load()">
      </div>
      
<div> example:
       <p>https://cdn.streamlytv.com/streamly/ngrp:AETVP.stream_all/manifest.mpd</p>
       <p>http://playready.directtaps.net/smoothstreaming/SSWSS720H264/SuperSpeedway_720.ism/Manifest</p>
       <p>http://videos.electroteque.org/playready/big_buck_bunny.ism/Manifest</p>
       <p>https://vm2.dashif.org/livesim/testpic_2s/Manifest.mpd</p>
		</div>
  </div>
	</section>
	
@endsection