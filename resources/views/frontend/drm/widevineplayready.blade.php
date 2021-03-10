@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}

/* Player parent DIV  */
.play-parent {
	width:100%;  /* width depends on your layout and needs  */
	position:relative;
	overflow:hidden;
}
/* Semi-transparent DIV element to cover entire player */
.div-over {
	background:red;
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
  
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/video.js@7.10.1/dist/video-js.min.css">
{{-- <link href="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video-js.min.css" rel="stylesheet"> --}}
<script src="https://cdn.jsdelivr.net/npm/video.js@7.10.1/dist/video.min.js"></script>
{{-- <script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video.min.js"></script> --}}

	<script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs-contrib-eme/3.5.4/videojs-contrib-eme.min.js"></script>
	<script src="{{ asset('frontend/videojs/videojs-http-source-selector.min.js') }}" ></script>

	<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
 
		<script>
		$('document').ready(function(){
			
			var player = videojs('player');
		
			player.httpSourceSelector();
 
            // Init eme plugin to work with DRM. Set header common for all license related requests: https://github.com/videojs/videojs-contrib-eme/tree/v3.5.0#emeheaders
						player.eme({
                // If you are using proxy, please, remove 'emeHeaders' block. Only 'player.eme();' should stay to register eme plugin.
                emeHeaders: {
                    'customdata': '{{$encoded_auth_xml}}'
                }
            });
 
						player.on('ready', function() {
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
                // Setup dash + pr/wv
								var wvprDashSrc = {
                    src: 'https://cph-p2p-msl.akamaized.net/hls/live/2000341/test/master.m3u8',
                    type: 'application/dash+xml',
                    // keySystems: {
                    //     'com.widevine.alpha': "https://streamlytv.com/api/generic?drm-type=widevine",
                    //     'com.microsoft.playready': "{{ url('/api/generic?drm-type=playready') }}"
                    // }
										keySystems: {
                        'com.widevine.alpha': 'https://wv-keyos.licensekeyserver.com/',
                        'com.microsoft.playready': 'https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx'
                    }
                };
 
                // Set the source to the player to init/start the playback.
                player.src(wvprDashSrc);
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
				
					<div class="col-md-8 col-centered ">
							<video id="player" width="600" height="300" class="video-js vjs-default-skin play-parent" controls></video>
							<!-- <source src="http://sample.vodobox.net/skate_phantom_flex_4k/skate_phantom_flex_4k.m3u8" type="application/x-mpegURL"/> -->
							<source src="" type="application/x-mpegURL"/>
					</video>
					<div class="div-over">
						<div class="overlay" style="background-color: yellow;">
							<details>
								<summary>Epcot Center</summary>
								<p>Epcot is a theme park at Walt Disney World Resort featuring exciting attractions, international pavilions, award-winning fireworks and seasonal special events.</p>
							</details>
							
						</div>
						<div class="over-close">
					</div></div>
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

	
		</div>
	</section>
	
@endsection