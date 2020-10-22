@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}
	</style>

<link href="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video-js.min.css" rel="stylesheet">
  
<script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video.min.js"></script>
<script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs-contrib-eme/3.5.4/videojs-contrib-eme.min.js"></script>
<script src="{{ asset('frontend/videojs/videojs-http-source-selector.min.js') }}" ></script>

<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
	

<script>
	$('document').ready(function(){
		var player = videojs('player', {autoplay: true});
	
		player.httpSourceSelector();

					// Init eme plugin to work with DRM. Set header common for all license related requests: https://github.com/videojs/videojs-contrib-eme/tree/v3.5.0#emeheaders
					player.eme({
							// If you are using proxy, please, remove 'emeHeaders' block. Only 'player.eme();' should stay to register eme plugin.
							emeHeaders: {
									'customdata': '{{$encoded_auth_xml}}'
							}
					});

					player.on('ready', function() {
							// Setup dash + pr/wv
							var wvprDashSrc = {
									src: 'https://cdn.streamlytv.com/streamly/ngrp:AETVP.stream_all/manifest.mpd',
									type: 'application/dash+xml',
									keySystems: {
                        'com.widevine.alpha': 'https://wv-keyos.licensekeyserver.com/',
                        'com.microsoft.playready': 'https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx'
                    }
							};

							// Set the source to the player to init/start the playback.
							player.src(wvprDashSrc);

							$("#change").on('click', function () {
								var new_url = $("#myInput").val();
								var changeIn = {
									src: new_url,
									type: 'application/dash+xml',
									keySystems: {
                        'com.widevine.alpha': 'https://wv-keyos.licensekeyserver.com/',
                        'com.microsoft.playready': 'https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx'
                    }
							};
								
								player.src(changeIn);
                });
					});
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
							<video id="player" width="600" height="300" class="video-js vjs-default-skin" controls></video>
							<!-- <source src="http://sample.vodobox.net/skate_phantom_flex_4k/skate_phantom_flex_4k.m3u8" type="application/x-mpegURL"/> -->
							<source src="" type="application/x-mpegURL"/>
					</video>

						<div class="channel-detail">
							<p>ghghg</p>
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

	
		<input type="text" placeholder="paste your link here" id="myInput">
        
		<button id="change">change video</button>
		</div>
	</section>
	
@endsection