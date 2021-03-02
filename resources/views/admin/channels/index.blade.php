@extends('master')
@section('stylesheets')
<style>video {
  width: 100%;
}

.dash-video-player {
  position: relative;  /* This position relative is needed to position the menus */
  width: 640px;
  margin: 0 auto;
}
.hide_column {
    display : none;
}</style>
<link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('frontend/controlbar/controlbar.css') }}">
<script defer src="{{ asset('frontend/dashjs/dash.all.min.js') }}" ></script>
<script defer src="{{ asset('frontend/dashjs/dash.mss.min.js') }}" ></script>
<script defer src="{{ asset('frontend/controlbar/ControlBar.js') }}" ></script>
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
              <li class="breadcrumb-item active">Channel</li>
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
          <div class="form-group">
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
              <input type="checkbox" class=" custom-control-input" name="toggleswitch" id="customSwitch3">
              <label class="custom-control-label" for="customSwitch3">Turn this on to test smooth links</label>
            </div>
          </div>
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
                      <th style="width: 20%">
                      Callsign
                      </th>
                      <th style="width: 20%" style="display:none">
                      ism
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
        <h4 class="modal-title">Video player</h4>
      </div>
      <div class="modal-body">
        <div class="username">url:<span></span></div>
        <div >
          <video   autoplay="" preload="auto" id="videoPlayer"  autoplay > </video>
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

<script defer type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>   
<script>
$('.select2').select2();
</script>
<script defer type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>   
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script>

 
 function init(url) {
     player = dashjs.MediaPlayer().create();
    //  player.getProtectionController().setRobustnessLevel('SW_SECURE_CRYPTO');
     // player.updateSettings({ 'debug': { 'logLevel': dashjs.Debug.LOG_LEVEL_NONE }});
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
                 
            
              // var url1 = "http://videos.electroteque.org/playready/big_buck_bunny.ism/Manifest";
                 player.initialize(document.querySelector("video"), url, true);
                 var controlbar = new ControlBar(player); //Player is instance of Dash.js MediaPlayer;
 controlbar.initialize();
 }
 
$(function() {
 
    $('#data-table').DataTable({
      pageLength: 100,
        processing: true,
        serverSide: true,
        ajax: '{!! url('admin/channelstatus/channeldata') !!}',
    
        columns: [
            { data: 'id', name: 'id' },
            { data: 'StationName', name: 'name',orderable: true },
            { data: 'url', name: 'url' },
            { data: 'genrename', name: 'genrename' ,searchable: true},
            
            { data: 'status', name: 'status' ,orderable:false},
            { data: 'logo', name: 'logo',orderable: false, searchable: false },

            {data: 'dash_url', name: 'dash_url', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            { data: 'callSign', name: 'callSign',orderable: true },

            {data: 'smooth_url', name: 'smooth_url', orderable: false, searchable: false ,},
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
        },{       
            "render": function(data, type, row) {
             return '<a href="../uploads/channellogo/'+row.image+'" target="_blank"><img src="../uploads/channellogo/'+row.image+'"" width="50px" height="50px"></a>';
             }, "targets": [5]
        },
        {
    "targets": 2,
    "data": "url",
    "render": function ( data, type, row, meta ) {
      return type === 'display' && data.length > 15 ?
        '<span title="'+data+'">'+data.substr( 0, 12 )+'...</span>' :
        data;
    }
  } ,  { 'targets': [ 9 ],
      'className': "hide_column"
    }
        
        ]
    });
    $('#data-table').on('click', 'tr', function () {
    
        var name = $('td', this).eq(1).text();
        var mpd = $('td', this).eq(6).text();
        var ism = $('td', this).eq(9).text();
        var url = $('td', this).eq(2).find("span").prop("title");
        var checkISMlink=$('#customSwitch3').is(":checked");
        // console.log('name',name);
        // console.log('url',url);
        // console.log('ism',ism);
        // console.log('checkISMlink',checkISMlink);
        
        $(".modal-body div span").text("");
        if(checkISMlink){$(".username span").text(ism);
        init(ism);
        }else{
        $(".username span").text(mpd);

              //  var url1 = "http://videos.electroteque.org/playready/big_buck_bunny.ism/Manifest";
        init(mpd);}
        $('#myModal').modal("show");
        // $("#videoPlayer").attr('src', url);
    });
    /* Assign empty url value to the iframe src attribute when
    modal hide, which stop the video playing */
    $("#myModal").on('hide.bs.modal', function(){
      player.reset()
        // $("#videoPlayer").attr('src', '');
    });
});

</script>
@stop
