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
{{-- <script src="{{ asset('frontend/dashjs/dash.mss.min.js') }}" ></script> --}}
<script src="{{ asset('frontend/controlbar/ControlBar.js') }}" ></script>
<script src="{{ asset('frontend/dueljs/duel.min.js') }}" ></script>


<script>
	(function () {
function getLocation2() {
if (navigator.geolocation) {
	// console.log('inside the function');
    var location_timeout = setTimeout("geolocFail(1)", 10000);

    navigator.geolocation.getCurrentPosition(function(position) {
        clearTimeout(location_timeout);

        let lat = position.coords.latitude;
        let lng = position.coords.longitude;

        SetGeocodeLatLng(lat, lng);
    }, function(error) {
			// console.log('inside the function error occured');
        clearTimeout(location_timeout);
        geolocFail(2);
    });
} else {
    // Fallback for no geolocation
    geolocFail(3);
}}
getLocation2();
function geolocFail(pa) {
	// console.log('geolocFailfail ocuured',pa);
var astrourl='https://api.astroip.co/{{getUserIpAddr()}}/?api_key={{ env("ASTRO_IP") }}';
console.log("astrourl",astrourl);
		 var myHeaders = new Headers();
// myHeaders.append("Cookie", "ARRAffinity=069e115ca7c25625ebd8581b6dc49c782dc6ddd6cee7dc472cc3b69eeb2a234a; ARRAffinitySameSite=069e115ca7c25625ebd8581b6dc49c782dc6ddd6cee7dc472cc3b69eeb2a234a");

var requestOptions = {
  method: 'GET',
  headers: myHeaders,
  redirect: 'follow'
};

// fetch("https://api.astroip.co/43.231.58.253/?api_key=e4c059f7-72fd-47f8-8e4f-9481d379d737", requestOptions)
fetch(astrourl, requestOptions)

  .then(response => response.json())
	.then(function(data) {
		// console.log(data.status_code);
		console.log("lonng",data.geo.longitude);
		console.log("latitude",data.geo.latitude);
		let lat = data.geo.latitude;
        let lng = data.geo.longitude;

        SetGeocodeLatLng(lat, lng);
    
  
  })
  .catch(error => console.log('error', error));

}
function SetGeocodeLatLng(lat,lng){
	localStorage.setItem("LAT", lat); 
	localStorage.setItem("lng", lng); 
	console.log("LAT",lat);
	console.log("lng",lng);
	geofence();
}
function getGeocodeLat(){
return	localStorage.getItem("LAT");
}
function getGeocodeLong(){
return	localStorage.getItem("lng");
}

function geofence(){
		var base_url = window.location.origin;
	let url=base_url+'/api/auth/geofence';
		

	var myHeaders = new Headers();
myHeaders.append("Accept", "application/json");
myHeaders.append("Authorization", "Bearer {{session()->get('jwttoken')}}");

var formdata = new FormData();
formdata.append("latitude", getGeocodeLat());
formdata.append("longitude", getGeocodeLong());
formdata.append("deviceid", MediaDeviceInfo.deviceId||"not defined");

var requestOptions = {
  method: 'POST',
  headers: myHeaders,
  body: formdata,
  redirect: 'follow'
};
for (var pair of formdata.entries()) {
    console.log(pair[0]+ ', ' + pair[1]); 
}
console.log("deviceid",MediaDeviceInfo.deviceId);
fetch(url, requestOptions)
  .then(response => response.json())
  // .then(result => console.log(result))
	.then(function(data) {
    var popupmsgtoggle = data.popupmsgtoggle;
		localStorage.setItem("geofence", data.success); 
    // console.log(popupmsgtoggle);
		if(popupmsgtoggle){
			alert(JSON.stringify(data.popupmsg));
			 outsidelocationplay();
		}
  
  })
  .catch(error => console.log('error', error));}
	// geofence();

function outsidelocationplay(){
		var base_url = window.location.origin;
	let url=base_url+'/api/auth/outsidelocationplay';
		

	var myHeaders = new Headers();
myHeaders.append("Accept", "application/json");
myHeaders.append("Authorization", "Bearer {{session()->get('jwttoken')}}");

var formdata = new FormData();
formdata.append("latitude", getGeocodeLat());
formdata.append("longitude", getGeocodeLong());
formdata.append("deviceid", MediaDeviceInfo.deviceId||"not defined");

var requestOptions = {
  method: 'POST',
  headers: myHeaders,
  body: formdata,
  redirect: 'follow'
};

fetch(url, requestOptions)
  .then(response => response.text())
  // .then(result => console.log(result))
  .catch(error => console.log('error', error));}



})();
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

function Addrecent(){
		var base_url = window.location.origin;
	let url=base_url+'/api/auth/addtorecent';
		

	var myHeaders = new Headers();
myHeaders.append("Accept", "application/json");
myHeaders.append("Authorization", "Bearer {{session()->get('jwttoken')}}");

var formdata = new FormData();
formdata.append("channelids", '{{$channelId[0]->id}}');

var requestOptions = {
  method: 'POST',
  headers: myHeaders,
  body: formdata,
  redirect: 'follow'
};

fetch(url, requestOptions)
  .then(response => response.text())
  // .then(result => console.log(result))
  .catch(error => console.log('error', error));	}
	var player,firstLoad = true;
 
  function init() {
		var CallSignValueURL = window.location.pathname.split('/')[2];
					
		  /*** open a channel named player 
    */
    var tabplayer = duel.channel('tabplayer');

    /**
     * Register a new player channel event
     */
     tabplayer.on('stop', function () {
     

        document.title = 'Stopped';
        player.pause();
    });
      player = dashjs.MediaPlayer().create();
      // player.updateSettings({ 'debug': { 'logLevel': dashjs.Debug.LOG_LEVEL_NONE }});
      // player.updateSettings({
      //     // 'debug': {'logLevel': dashjs.Debug.LOG_LEVEL_DEBUG},
      //     'streaming': {
			// 			'liveDelay': 10,
			// 			'fastSwitchEnabled': true  ,
      //         //Set retryIntervals of MPD (file with description of the resources forming a streaming service, that a DASH client uses to
      //         //determine which assets to request in order to perfom adaptive streaming of the content.
      //         //In other word, make 3 attemps to load files, each attemps with an interval of 5 seconds (5000 milliseconds).
      //         'retryIntervals': {MPD: 5000},
      //         'retryAttempts': {MPD: 3}
      //     },'abr': { 'autoSwitchBitrate': { 'video': false } }
      // });
			// player.updateSettings({
      //     // 'debug': {'logLevel': dashjs.Debug.LOG_LEVEL_DEBUG},
      //     'streaming': {
			// 			'liveDelay': 2,
      //                   'liveCatchUpMinDrift': 0.05,
      //                   'liveCatchUpPlaybackRate': 0.5,
      //                   'stableBufferTime': 2,
      //                   'bufferTimeAtTopQuality': 2,
      //                   'bufferTimeAtTopQualityLongForm': 2,
      //                   'bufferToKeep': 2,
      //                   'bufferAheadToKeep': 2,
      //                   'lowLatencyEnabled': true,
      //                   'fastSwitchEnabled': true,
      //     },'abr': { 'autoSwitchBitrate': { 'video': false } }
      // });
			player.updateSettings({
         // 'debug': {'logLevel': dashjs.Debug.LOG_LEVEL_DEBUG},
         'streaming': {
          //  'liveDelay': 1,
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
                  
							// var url = "https://livesim.dashif.org/livesim/testpic_2s/Manifest.mpd";
							var url = "{{$url[0]->url??'no url found'}}";
              // var url = "http://videos.electroteque.org/playready/big_buck_bunny.ism/Manifest";
                  player.initialize(document.querySelector("video"), url, false);
                  var controlbar = new ControlBar(player); //Player is instance of Dash.js MediaPlayer;
  controlbar.initialize();
	player.on(dashjs.MediaPlayer.events["PLAYBACK_PLAYING"], function () {
						console.log("bro it is playing");
						//hit geo fence api check if inside or not if not than pop will come up to log the info
            tabplayer.broadcast('stop');
            document.title = 'play...';

						gtag('event', 'video_play', {'call_sign': CallSignValueURL,'inside_geofence':localStorage.getItem("geofence")});
						setInterval(function(){ 
							// console.log("15 secs");
							gtag('event', 'video_livestream_duration', {'livestream_seconds_watched': 15,'call_sign': CallSignValueURL,'inside_geofence':localStorage.getItem("geofence")});
							},15000);
							Addrecent();//save to recents

        });
        player.on(dashjs.MediaPlayer.events["PLAYBACK_PAUSED"], function () {
            console.log("bro it is paused");
            
        });   
				// player.on(dashjs.MediaPlayer.events["PLAYBACK_PROGRESS"], function () {
        //     console.log("PLAYBACK_PROGRESS");
				// 		// PLAYBACK_TIME_UPDATED
				// gtag('event', 'player_error', {'error_code': 15,'error_msg': 'errrootmsg',
				// 'call_sign': CallSignValueURL,'inside_geofence':localStorage.getItem("geofence")});

            
        // });	
		/*	player.on(dashjs.MediaPlayer.events["PLAYBACK_TIME_UPDATED"], function (e) {
            // console.log("PLAYBACK_TIME_UPDATED");
						for (var name in e)
            {

                if (typeof e[name] != 'object') {
                    console.log("    " + name + ": " + e[name]);
                }
            }
						// PLAYBACK_TIME_UPDATED
            
        }); */
  }
  
  document.addEventListener("DOMContentLoaded", function () {
                  init();
              });	
		
	</script>
	<style>

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}

		/* .owl-carousel .owl-item img {
		    display: block;
		    width: auto;
		    margin: 0 auto;
		    height: 1.5rem;
		} */
		.channelogosame{

display: block;
margin: 0 auto;
height: 100px;
width: 100px;
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

		<div class="full-width">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-centered">
						<video  autoplay="" preload="auto" id="videoPlayer"   > </video>
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

						{{-- <div class="channel-detail">
							<p>{{$channelData[0]->callSign??'channel detail'}}</p>
						</div> --}}
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

						<a href="{{url('livetv/'.$channeldet->CallSign.'/'.$channeldet->genrenae)}}">
						<div class="live-news-item"><div class="channelogosame"><img style='background-color: #4D5659; height: 100%; width: 100%; object-fit: contain'  class="lazy" data-src="{{  $channeldet->logo}}" loading="lazy"  alt="{{  $channeldet->StationName}}"></div>
	
										<p>{{  $channeldet->StationName}}</p>
					</div> </a>
					@endforeach
					</div>
					
				</div>
			</div>
		
	</section>
	
@endsection