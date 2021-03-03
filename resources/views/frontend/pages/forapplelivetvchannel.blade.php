@extends('frontend/partials/master')

@section('stylesheets-bottom')
<style>
	nav#mainNav {
		background-color: #004E92;
	}
	video {
			width: 100%;
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/video.js@7.10.1/dist/video-js.min.css">
{{-- <link href="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video-js.min.css" rel="stylesheet"> --}}
<script src="https://cdn.jsdelivr.net/npm/video.js@7.10.1/dist/video.min.js"></script>
{{-- <script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video.min.js"></script> --}}
<script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs-contrib-eme/3.5.4/videojs-contrib-eme.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
{{-- <script src="http://localhost/buydrm/forqualityswitch/dist/videojs-http-source-selector.min.js"></script> --}}
<script src="{{ asset('frontend/videojs/videojs-http-source-selector.min.js') }}" ></script>

<script src="{{ asset('frontend/dueljs/duel.min.js') }}" ></script>


<script>
			(function() {
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
				var player;

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
					// Init player.
				 player = videojs('my-player', {
          preload: true,
          autoplay: true,
          controls: true,
					responsive: true,
          controlBar: {
              'liveDisplay': true,
              'pictureInPictureToggle': true,
		// 					'volumeMenuButton': true,
    // 'currentTimeDisplay': true,
    // 'timeDivider': true,
    // 'durationDisplay': true,
    // 'remainingTimeDisplay': false,
    // 'progressControl': true,
    // 'subtitlesButton': true,
    'fullscreenToggle': true,
          }
      });
					player.httpSourceSelector();
					player.eme();
					player.on('ready', function() {
							// Helper functions.
							var base64DecodeUint8Array = function (a) { var b = window.atob(a), c = b.length, d = new Uint8Array(new ArrayBuffer(c)); for (i = 0; i < c; i++)d[i] = b.charCodeAt(i); return d };
							var base64EncodeUint8Array = function (a) { for (var d, e, f, g, h, i, j, b = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", c = "", k = 0; k < a.length;)d = a[k++], e = k < a.length ? a[k++] : Number.NaN, f = k < a.length ? a[k++] : Number.NaN, g = d >> 2, h = (3 & d) << 4 | e >> 4, i = (15 & e) << 2 | f >> 6, j = 63 & f, isNaN(e) ? i = j = 64 : isNaN(f) && (j = 64), c += b.charAt(g) + b.charAt(h) + b.charAt(i) + b.charAt(j); return c };
							var arrayToString = function (a) { var b = new Uint16Array(a.buffer); return String.fromCharCode.apply(null, b) };

							// Setup hls + fp
							var fairplayHlsSrc = {
									// src: 'https://bitdash-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8',
									src: '{{$url[0]->url??"no url found"}}',
									type: 'application/x-mpegURL',
									keySystems: {
											'com.apple.fps.1_0': {
													certificateUri: 'https://fp-keyos.licensekeyserver.com/cert/9754e0cfddc682c21216de3eb4d11885.der',

													getContentId: function (emeOptions, initData) {
															var contentId = arrayToString(initData);

															if (contentId.indexOf("skd://") > -1)
																	return contentId.split("skd://")[1].substr(0, 32);

															throw 'Invalid Content ID format. The format of the Content ID must be the following: skd://xxx where xxx is the Key ID in hex format.';
													},

													getLicense: function (emeOptions, contentId, keyMessage, callback) {
															var data = 'spc=' + base64EncodeUint8Array(keyMessage) + '&assetId=' + contentId;
															$.ajax({
																	url: 'https://fp-keyos.licensekeyserver.com/getkey',
																	type: 'POST',
																	data: data,
																	// If you are using proxy, please, remove 'headers' object.
																	headers: {
																			'customdata': '{{$encoded_auth_xml}}'
																	},
																	contentType: 'application/x-www-form-urlencoded',
																	success: function (result) {
																			callback(null, base64DecodeUint8Array(result));
																	},
																	error: function (err) {
																			callback(err);
																	}
															});
													}
											}
									}
							};

							// Set the source to the player to init/start the playback.
							player.src(fairplayHlsSrc);
							player.on('play', function() {
  console.log("it's playing bro");
	tabplayer.broadcast('stop');
	 document.title = 'play...';

						gtag('event', 'video_play', {'call_sign': CallSignValueURL,'inside_geofence':localStorage.getItem("geofence")});
						setInterval(function(){ 
							// console.log("15 secs");
							gtag('event', 'video_livestream_duration', {'livestream_seconds_watched': 15,'call_sign': CallSignValueURL,'inside_geofence':localStorage.getItem("geofence")});
							},15000);
});					
player.on('pause', function() {
  console.log("it's paused bro");
});
					});
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
{{-- {{$channelData}} --}}
		<div class="full-width">
			<div class="container">
				<div class="row">
				
					<div class="col-md-8 col-centered">
						
						<video id="my-player" class="video-js vjs-default-skin vjs-fluid" controls></video>

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
		</div>
	</section>
	
@endsection