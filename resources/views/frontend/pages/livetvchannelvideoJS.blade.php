	@extends('frontend/partials/master')

	@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}
		video {
				width: 100%;
		}


	/* Player parent DIV  */
	.play-parent {
		width:100%;  /* width depends on your layout and needs  */
		position:relative;
		overflow:hidden;
	}
	/* Semi-transparent DIV element to cover entire player */
	.div-over {
		/* background:#27507E; */
		/* background:rgba(0,0,0,0.5); */
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
		z-index:2147483647;
		display:none;
		overflow:hidden;
	}
	/* Centered DIV element for our banner ad */
	.overlay {
		width: auto;
		position:absolute;
		top:50%;
		left:50%;
		transform: translate(-50%, -50%);
		display:inline-block;
	}
	/* Close button */
	.over-close {
		width: 28px;
		height: 28px;
		position: absolute;
		top:10px;
		right:10px;
		background:#fff;
		cursor:pointer;
		border-radius:50%;
		-webkit-border-radius:50%;
		-moz-border-radius:50%;
	}
	.over-close:after {
		content: '';
		height: 16px;
		border-left: 2px solid #222;
		position: absolute;
		transform: rotate(45deg);
		left: 13px;
		top:6px;
	}

	.over-close:before {
		content: '';
		height: 16px;
		border-left: 2px solid #222;
		position: absolute;
		transform: rotate(-45deg);
		left: 13px;
		top:6px;
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
{{-- <script src="https://cdn.jsdelivr.net/npm/can-autoplay@3.0.2/build/can-autoplay.min.js"></script> --}}
	<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
	{{-- <script src="http://localhost/buydrm/forqualityswitch/dist/videojs-http-source-selector.min.js"></script> --}}
	<script src="{{ asset('frontend/videojs/videojs-http-source-selector.min.js') }}" ></script>

	<script src="{{ asset('frontend/dueljs/duel.min.js') }}" ></script>


		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
				// alert(JSON.stringify(data.popupmsg));
				// outsidelocationplay();
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
	/**
	canAutoplay.video().then(({result}) => {
  if (result === true) {
    // Can autoplay
	console.log("can play");
  } else {
    // Can not autoplay
	console.log("cant play");
	
  }
});
*/
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
						player.eme({
									// If you are using proxy, please, remove 'emeHeaders' block. Only 'player.eme();' should stay to register eme plugin.
									emeHeaders: {
											'customdata': '{{$encoded_auth_xml}}'
									}
							});

						player.on('ready', function() {
console.log("playr ready");
							var show_on_start = true;  <!--// Set true to show overlay banner before video start-->

	<!--// Find overlay elements-->
	var parent = player.el().parentNode;
	var closeBtn = parent.querySelector('.over-close');
	var overlay = parent.querySelector('.div-over');
	<!--// Close overlay and play media file on close button click-->
			closeBtn.addEventListener('click',function(e) {
				overlay.style.display='none';
				player.play();
			}, false);

			<!--// Show overlay on Pause event-->
			this.on("pause", function(){
				if (!this.seeking() && this.paused()) {
					overlay.style.display='block';
				}
			});

			<!--// Hide overlay on Play event-->
			this.on('play', function(e) {
				overlay.style.display='none';
			});
			if(show_on_start) {
				player.pause(); overlay.style.display='block';
			} else {
				overlay.style.display='none';
			}

								// Setup dash + drms
								var dashdrmplay = {
										// src: 'https://bitdash-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8',
										src: '{{$url[0]->url??"no url found"}}',
										type: 'application/dash+xml',
									keySystems: {
                        'com.widevine.alpha': 'https://wv-keyos.licensekeyserver.com/',
                        'com.microsoft.playready': 'https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx'
                    }
								};

								// Set the source to the player to init/start the playback.
								player.src(dashdrmplay);
								player.on('play', function() {
	console.log("it's playing bro");
	console.log(dashdrmplay);
		tabplayer.broadcast('stop');
		document.title = 'play...';

							gtag('event', 'video_play', {'call_sign': CallSignValueURL,'inside_geofence':localStorage.getItem("geofence")});
							setInterval(function(){ 
								// console.log("15 secs");
								gtag('event', 'video_livestream_duration', {'livestream_seconds_watched': 15,'call_sign': CallSignValueURL,'inside_geofence':localStorage.getItem("geofence")});
								},15000);
								Addrecent();//save to recents
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
				var today = new Date();
	var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
	var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	//var dateTime2 = date+' '+time;
	//console.log('dateTime',dateTime);
	//$('#gracenote_data').select2('open');
	//var dateTime=new Date().toISOString().slice(0, 19).replace('T', ' ');
	$("#gracenote_data").select2({
		placeholder: 'gracenote_data...',
		// width: '350px',
		allowClear: true,
		closeOnSelect: false,
		ajax: {
			url: '/epgdata',
			dataType: 'json',
			delay: 250,
			data: function(params) {
				return {
					term: params.term || '',
					page: params.page || 1,
					starttime:time,
					callsign: window.location.pathname.split('/')[2],
					hoursoffset: '-07:00',
				}
			},
			cache: true
		}, escapeMarkup: function (markup) { return markup; },
		templateResult: function (data) {
		// console.log('templateResult',data);
			return '<span>'+data.StartDateTimewithTimezon+ ' '+ data.text + '</span>';
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
			#overlay {
	position: absolute; 
	/* top: 100px;  */
		left: 5px;
	top: 5px;
	/* color: #FFF;  */
	text-align: center;
	font-size: 20px;
	/* background-color: #000; */
	width: 80%;
		/* width: 530px; */
		background: #063056 0% 0% no-repeat padding-box;
	border-radius: 4px;
	opacity: 0.55;
	backdrop-filter: blur(50px);
	-webkit-backdrop-filter: blur(50px);
		/* width: fit-content;  */
		/* background-size: 50%; */
		
		height: fit-content;
	/* padding: 10px 0; */
	z-index: 2;
		/* height: 100%; */
		
	}

			details > summary {
		list-style-type: none;
	background-color: #fff;
	}

	details > summary::-webkit-details-marker {
		display: none;
	}

	details > summary::before {
		content: '\002630';
	}
	details[open] > summary::before {
		content: '\2715';
	}
	details[open] {
		background: rgba( 39, 80, 126, 0.30 );
	box-shadow: 0 8px 32px 0 #063056;
	backdrop-filter: blur( 2.5px );
	-webkit-backdrop-filter: blur( 2.5px );
	border-radius: 10px;
	border: 1px solid rgba( 255, 255, 255, 0.18 );
	}

	@media only screen and (min-width: 600px) {
		details[open] {
			width: 28vw;
		}
}

		</style>
	@endsection

	@section('content')

	{{-- https://www.onlineedge.co.in/demo/videojsnup/examples/overlay.html --}}
		<section class="page-section">
	{{-- {{$channelData}} --}}
			<div class="full-width">
				<div class="container">
					<div class="row">
					
						<div class="col-md-8 col-centered" >
							
							<video id="my-player" class="video-js vjs-default-skin vjs-fluid" controls muted autoplay></video>

						<div class="div-over">
							<div class="overlay" >
											
	<details>
	<summary>Menu</summary><ul style="
	list-style: none;
	max-height: 300px;
	padding: 25px;	
	/* background-color: #27507E; */
	overflow-x: hidden;
	overflow-y: scroll;">
			@foreach ($allchannel as $channel)
				<a href ='/livetv/{{$channel->CallSign}}/{{$channel->genrenae}}'
				{{-- /livetv/'+data.CallSign+'/'+data.genrename --}}
				><li >
					<div style="
				display: flex;
				justify-content: center;
				align-items: center;
				margin-bottom: 15px;
				width: 100%;
					height: 150px;
					border: 2px solid #FFFFFF;
					
				border-radius: 10px;   "><img style=' height: 80%;width: 80%; object-fit: contain'  class="lazy" data-src="/uploads/channellogo/{{  $channel->image}}" loading="lazy"  alt="{{  $channel->StationName}}"></div>
					{{-- {{$channel->StationName}} --}}
				</li></a>
		@endforeach
	</ul>
	</details> 
								
							</div>
							<div class="over-close">
						</div></div>
							
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
			
			{{$Channel[0]->stationName??$Channel[0]->callSign}}
			<select id='gracenote_data' style="width: 100%; display:block;">
			<option value='0'>- The schedule -</option>
		</select>
				<h4 class="section-heading">Similar Channels</h4>
				<div class="row">
					<div class="live-news-carousel arrow-custom-car owl-carousel">
						@foreach ($relatedchannel as $channeldet)

							<a href="{{url('livetv/'.$channeldet->CallSign.'/'.$channeldet->genrenae)}}">
							<div class="live-news-item"><div class="channelogosame"><img style='background-color: #4D5659; height: 100%; width: 100%; object-fit: contain; padding: 0.5vw;'  class="lazy" data-src="/uploads/channellogo/{{  $channeldet->image}}" loading="lazy"  alt="{{  $channeldet->StationName}}"></div>
		
											<p  style=" max-width: auto;
											padding: 0px 10px;
							overflow: hidden;
							white-space: nowrap;
							text-overflow: ellipsis;">{{  $channeldet->StationName}}</p>
						</div> </a>
						@endforeach
						</div>
						
					</div>
				</div>
			</div>
		</section>
		
	@endsection