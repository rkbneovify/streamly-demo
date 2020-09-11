@extends('master')
@section('stylesheets')
<style>video {
  width: 100%;
}

.dash-video-player {
  position: relative;  /* This position relative is needed to position the menus */
  width: 640px;
  margin: 0 auto;
}</style>
<link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">

<link href="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video-js.min.css" rel="stylesheet">
  
<script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs/7.5.4/video.min.js"></script>
<script src="https://s3.amazonaws.com/cdn.keyos.com/html5/videojs-contrib-eme/3.5.4/videojs-contrib-eme.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
{{-- <script src="http://localhost/buydrm/forqualityswitch/dist/videojs-http-source-selector.min.js"></script> --}}
<script src="{{ asset('frontend/videojs/videojs-http-source-selector.min.js') }}" ></script>
    @stop
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Channel</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Apple Channel</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <a class="btn btn-xs btn-primary" href="{{  url('admin/channelstatus/create') }} ">
                                        Add new Channel
                                    </a>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Channel</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-0">
          <table id="data-table" class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                      name
                      </th>
                      <th style="width: 20%">
                      url
                      </th>

                      <th style="width: 20%">
                      genres
                      </th>
                      <th style="width: 20%">
                      status
                      </th>
                      
                      <th style="width: 20%">
                        image
                        </th>


                      <th style="width: 20%">
                        dash url
                        </th>
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="
    width: 602px;">
      <div class="modal-header">
        <h4 class="modal-title">Video Player</h4>
      </div>
      <div class="modal-body">
        <div class="username">url:<span></span></div>
        <div >
          
						<video id="my-player" class="video-js" controls></video>

          <div class="channel-detail">
            <p>{{$channelData[0]->callSign??'channel detail'}}</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
  </div>
  
    <!-- Modal HTML -->
    {{-- <div id="myModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">YouTube Video</h4>
              </div>
              <div class="modal-body">
                  
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
								<p>{{$channelData[0]->callSign??'channel detail'}}</p>
						</div>
					</div>
              </div>
          </div>
      </div>
  </div> --}}
  
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('scripts')

<script type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>   
<script>
$('.select2').select2();
</script>
<script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script>

function videoJsPlayer(url) {
					// Init player.
					var player = videojs('my-player');
					player.httpSourceSelector();
					player.eme();
					player.on('ready', function() {
							// Helper functions.
							var base64DecodeUint8Array = function (a) { var b = window.atob(a), c = b.length, d = new Uint8Array(new ArrayBuffer(c)); for (i = 0; i < c; i++)d[i] = b.charCodeAt(i); return d };
							var base64EncodeUint8Array = function (a) { for (var d, e, f, g, h, i, j, b = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", c = "", k = 0; k < a.length;)d = a[k++], e = k < a.length ? a[k++] : Number.NaN, f = k < a.length ? a[k++] : Number.NaN, g = d >> 2, h = (3 & d) << 4 | e >> 4, i = (15 & e) << 2 | f >> 6, j = 63 & f, isNaN(e) ? i = j = 64 : isNaN(f) && (j = 64), c += b.charAt(g) + b.charAt(h) + b.charAt(i) + b.charAt(j); return c };
							var arrayToString = function (a) { var b = new Uint16Array(a.buffer); return String.fromCharCode.apply(null, b) };

							// Setup hls + fp
							var fairplayHlsSrc = {
									src: url,
									
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
					});
			};
 
$(function() {
 
    $('#data-table').DataTable({
      pageLength: 100,
        processing: true,
        serverSide: true,
        ajax: '{!! url('admin/channelstatus/channeldata') !!}',
    
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name',orderable: true },
            { data: 'url', name: 'url' },
            { data: 'fullName', name: 'fullName' ,searchable: true},
            
            { data: 'status', name: 'status' ,orderable:false},
            { data: 'photo', name: 'photo',orderable: false, searchable: false },

            {data: 'dash_url', name: 'dash_url', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
        {       
            "render": function(data, type, row) {
                if (row.status==1) {
                  return '<span class="dotgreen"></span> Active';
                }
                else if (row.status==0) {
                  return '<span class="dotred"></span> Inactive';
                }
                else {
                    return '<span class="label label-default">unknown</span>';
                }
             }, "targets": [4]
        },{       
            "render": function(data, type, row) {
             return row.dash_url;
             }, "targets": [6]
        },
        {
    "targets": 2,
    "data": "url",
    "render": function ( data, type, row, meta ) {
      return type === 'display' && data.length > 15 ?
        '<span title="'+data+'">'+data.substr( 0, 12 )+'...</span>' :
        data;
    }
  } 
        
        ]
    });
    $('#data-table').on('click', 'tr', function () {
    
        var name = $('td', this).eq(1).text();
        var mpd = $('td', this).eq(6).text();
        var url = $('td', this).eq(2).find("span").prop("title");
        
        console.log('name',name);
        console.log('url',url);
        $(".modal-body div span").text("");
        $(".username span").text(url);

              //  var url1 = "http://videos.electroteque.org/playready/big_buck_bunny.ism/Manifest";
        // init(mpd);
      //  var m3u8 ='https://bitdash-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8';
       videoJsPlayer(url);
        $('#myModal').modal("show");
        // $("#videoPlayer").attr('src', url);
    });
    /* Assign empty url value to the iframe src attribute when
    modal hide, which stop the video playing */
    $("#myModal").on('hide.bs.modal', function(){
      
        // $("#videoPlayer").attr('src', '');
    });
});

</script>
@stop
