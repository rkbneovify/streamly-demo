@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}
  /* CUSTOM BUTTONS */
  [class^="icon-"]:before,
  [class*=" icon-"]:before {
    font-family: FontAwesome;
    font-weight: normal;
    font-style: normal;
    display: inline-block;
    text-decoration: inherit;
  }
  .icon-angle-left:before {
      content: "\f104";
  }
  .icon-angle-right:before {
      content: "\f105";
  }
  
  .video-js .icon-angle-right, .video-js .icon-angle-left {
      cursor: pointer;
      -webkit-box-flex: none;
      -moz-box-flex: none;
      -webkit-flex: none;
      -ms-flex: none;
      flex: none;
  }
	</style>

<link href="https://cdn.jsdelivr.net/npm/videojs-playlist-ui@3.8.0/dist/videojs-playlist-ui.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/video.js@7.10.1/dist/video-js.min.css">
{{-- <link href="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video-js.min.css" rel="stylesheet"> --}}
<script src="https://cdn.jsdelivr.net/npm/video.js@7.10.1/dist/video.min.js"></script>
{{-- <script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video.min.js"></script> --}}
<script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs-contrib-eme/3.5.4/videojs-contrib-eme.min.js"></script>
<script src="{{ asset('frontend/videojs/videojs-http-source-selector.min.js') }}" ></script>

<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/videojs-playlist@4.3.1/dist/videojs-playlist.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-playlist-ui@3.8.0/dist/videojs-playlist-ui.min.js"></script>
	

<script>
	$('document').ready(function(){
		var types = {mp4:'video/mp4',webm:'video/webm', m3u: 'application/x-mpegURL'};
		var videoList = [{
  sources: [{
    src: 'http://media.w3.org/2010/05/sintel/trailer.mp4',
    type: types.mp4
  }],
  poster: 'https://www.rt.com/static/img/og-logo-rt.png',name : 'Video 1',
}, {
  sources: [{
    src: 'https://content.jwplatform.com/manifests/yp34SRmf.m3u8',
    type: types.m3u
  }],
  poster: 'http://media.w3.org/2010/05/bunny/poster.png',name : 'Video 2',
}, {
  sources: [{
    src: 'https://vjs.zencdn.net/v/oceans.mp4',
    type: types.mp4
  }],
  poster: 'https://vjs.zencdn.net/v/oceans.png',name : 'Video 3',
}, {
  sources: [{
    src: 'http://media.w3.org/2010/05/bunny/movie.mp4',
    type: types.mp4
  }],
  poster: 'http://media.w3.org/2010/05/bunny/poster.png',name : 'Video 4',
}, {
  sources: [{
    src: 'http://media.w3.org/2010/05/video/movie_300.mp4',
    type: types.mp4
  }],
  poster: 'http://media.w3.org/2010/05/video/poster.png',name : 'Video 5',
}];
		var player = videojs('player', {
          preload: true,
          autoplay: true,
          controls: true,
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

					// Init eme plugin to work with DRM. Set header common for all license related requests: https://github.com/videojs/videojs-contrib-eme/tree/v3.5.0#emeheaders
					player.eme({
							// If you are using proxy, please, remove 'emeHeaders' block. Only 'player.eme();' should stay to register eme plugin.
							emeHeaders: {
									'customdata': '{{$encoded_auth_xml}}'
							}
					});

					player.on('ready', function() {
						var myPlayer = this;
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
							// player.src(wvprDashSrc);
							myPlayer.playlist(videoList)
          myPlayer.playlistUi()
					
								$("#add").on('click', function () {
                 var currentplaylist= myPlayer.playlist();
                 let position=myPlayer.playlist.currentItem();
                 let lenh=videoList.length + 1;
                 console.log('len',lenh);
                   var new_url = $("#myInput").val();
                  videoList.push( {
  sources: [{
    src: new_url?new_url:'https://content.jwplatform.com/manifests/yp34SRmf.m3u8',
    type: 'application/x-mpegURL'
  }],
  poster: 'http://media.w3.org/2010/05/bunny/poster.png',name : 'Video '+lenh,
});

myPlayer.playlist(videoList,position);
 console.table(videoList);
 var updatedplaylist= myPlayer.playlist();
                  console.log('updatedplaylist',updatedplaylist);
								});
								
						
								var button = videojs.getComponent('Button')
          //Close button
          var closeButton = videojs.extend(button, {
            constructor: function () {
              button.apply(this, arguments)
              this.controlText('Exit Course')
              this.addClass('vjs-icon-cancel')
            },
            handleClick: function () {
              this.player().dispose()
            },
          })
          videojs.registerComponent('closeButton', closeButton)
          myPlayer.getChild('controlBar').addChild('closeButton', {})

          // Extend default
          var PrevButton = videojs.extend(button, {
            //constructor: function(player, options) {
            constructor: function () {
              button.apply(this, arguments)
              this.addClass('icon-angle-left')
              this.controlText('Previous')
            },

            handleClick: function () {
              console.log('clickedPrevious')
              myPlayer.playlist.previous()
            },
          })

          videojs.registerComponent('PrevButton', PrevButton)

          myPlayer.getChild('controlBar').addChild('PrevButton', {}, 0)

          /* Next BUTTON */
          //var Button = videojs.getComponent('Button');

          // Extend default
          var NextButton = videojs.extend(button, {
            constructor: function () {
              button.apply(this, arguments)
              this.addClass('icon-angle-right')
              this.controlText('Next')
            },

            handleClick: function () {
              console.log('clickednext')
              myPlayer.playlist.next()
            },
          })

          videojs.registerComponent('NextButton', NextButton)

          myPlayer.getChild('controlBar').addChild('NextButton', {}, 2)		
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
		.vjs-playlist {
			position: absolute;
		    top: 10%;
		    right: -10%;
        width: 200px;
        height: 240px;
        
      }

/* 
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
		} */

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
							<source src="" type="application/dash+xml"/>
					</video>
					<div class="vjs-playlist"></div>
						
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

	
        
		<input type="text" placeholder="paste your link here" id="myInput" size="50">
		<button id="add">Add video(m3u8 url only)</button>

{{-- <div> example:
	<p>https://cdn.streamlytv.com/streamly/ngrp:AETVP.stream_all/manifest.mpd</p>
	<p>http://playready.directtaps.net/smoothstreaming/SSWSS720H264/SuperSpeedway_720.ism/Manifest</p>
	<p>http://videos.electroteque.org/playready/big_buck_bunny.ism/Manifest</p>
	<p>https://vm2.dashif.org/livesim/testpic_2s/Manifest.mpd</p>
	"https://d2rghg6apqy7xc.cloudfront.net/dvp/test_dash/005/unencrypted/montereypop_16_min_2265099.mpd
</div> --}}
		</div>
	</section>
	
@endsection