//package com.adaptivemedia.streamlytv.streamly.playervideo
//
//import android.annotation.SuppressLint
//import android.app.ProgressDialog
//import android.content.DialogInterface
//import android.content.Intent
//import android.net.Uri
//import android.os.Bundle
//import android.os.Handler
//import android.provider.Settings
//import android.util.DisplayMetrics
//import android.util.Log
//import android.view.KeyEvent
//import android.view.View
//import android.widget.ImageView
//import android.widget.LinearLayout
//import android.widget.PopupMenu
//import android.widget.ProgressBar
//import androidx.fragment.app.FragmentActivity
//import androidx.lifecycle.ViewModelProvider
//import androidx.recyclerview.widget.LinearLayoutManager
//import androidx.recyclerview.widget.RecyclerView
//import com.adaptivemedia.streamlytv.R
//import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog
//import com.adaptivemedia.streamlytv.streamly.epg.ChannellistModel
//import com.adaptivemedia.streamlytv.streamly.epg.Datum
//import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage
//import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity
//import com.adaptivemedia.streamlytv.streamly.playervideo.PlayerErrorDialog.PlayerDialogInterface
//import com.adaptivemedia.streamlytv.streamly.playervideo.allchannel.AllChannelAdapter
//import com.adaptivemedia.streamlytv.streamly.playervideo.drm.DrmKeyModel
//import com.adaptivemedia.streamlytv.streamly.playervideo.drm.DrmViewModel
//import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient
//import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface
//import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility
//import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility
//import com.android.volley.*
//import com.android.volley.toolbox.StringRequest
//import com.android.volley.toolbox.Volley
//import com.google.android.exoplayer2.*
//import com.google.android.exoplayer2.drm.*
//import com.google.android.exoplayer2.source.MediaSource
//import com.google.android.exoplayer2.source.TrackGroupArray
//import com.google.android.exoplayer2.source.dash.DashMediaSource
//import com.google.android.exoplayer2.trackselection.*
//import com.google.android.exoplayer2.ui.StyledPlayerView
//import com.google.android.exoplayer2.upstream.*
//import com.google.android.exoplayer2.util.Util
//import com.google.firebase.analytics.FirebaseAnalytics
//import kotlinx.android.synthetic.main.activity_player.*
//import kotlinx.android.synthetic.main.custom_exo_controls.*
//import kotlinx.android.synthetic.main.custom_exo_controls.exo_quality
//
//import org.json.JSONObject
//import retrofit2.Call
//import retrofit2.Callback
//import java.util.*
//
//class playersActivity() : FragmentActivity(),Player.Listener {
//    private var dialog: ProgressDialog? = null
//    private var playerErrorDialog: PlayerErrorDialog? = null
//    var authToken: String? = null
//    var uniqueId: String? = null
//    var userId: String? = null
//    var player: ExoPlayer? = null
//    private var qualityPopUp: PopupMenu?=null
//    private var playbackPosition = 0L
//    private var playWhenReady = true
//    private var trackSelector:DefaultTrackSelector?=null
//    var qualityList = ArrayList<Pair<String, TrackSelectionOverrides.Builder>>()
//    var playerView: StyledPlayerView? = null
//    var exoNext: ImageView? = null
//    var exoPre: ImageView? = null
//    var exoPlay: ImageView? = null
//    var checkStreamingPlying = false
//    private var mFirebaseAnalytics: FirebaseAnalytics? = null
//    var params: Bundle? = null
//    var handler = Handler()
//    val delay = 10000 //millisecondsaddto
//    var runnable: Runnable? = null
//    var allChannelRecyclerView: RecyclerView? = null
//    var channelList: List<Datum>? = null
//    var allChannelAdapter: AllChannelAdapter? = null
//    var logoImage: ImageView? = null
//    var exo_qualitys: ImageView? = null
//    var menuImage: ImageView? = null
//    var drawerCloseImage: ImageView? = null
//    var drawerLayout: LinearLayout? = null
//    var channel_id = ""
//    var item: String? = "0"
//    var rvItemClick = "0"
//    var channelId: String? = null
//    var height: String? = null
//    var width: String? = null
//    var callsign: String? = null
//    var finalPosition = 0
//    var loader: ProgressBar? = null
//    var url: String? = null
////    override fun onStart() {
////        super.onStart()
////    }
//
////    private val defaultDataSourceFactory: HttpDataSource.Factory
//    val defaultDataSourceFactory = DefaultHttpDataSource.Factory()
//
////        private get() = DefaultHttpDataSourceFactory(Util.getUserAgent(this, "sample-player"))
//
//    private fun createMediaDrmCallback(
//        licenseUrl: String,
//        keyRequestPropertiesArray: Array<String>?
//    ): HttpMediaDrmCallback {
//        val licenseDataSourceFactory = defaultDataSourceFactory
//        val drmCallback = HttpMediaDrmCallback(licenseUrl, licenseDataSourceFactory)
//        if (keyRequestPropertiesArray != null) {
//            var i = 0
//            while (i < keyRequestPropertiesArray.size - 1) {
//                drmCallback.setKeyRequestProperty(
//                    keyRequestPropertiesArray[i],
//                    keyRequestPropertiesArray[i + 1]
//                )
//                i += 2
//            }
//        }
//        return drmCallback
//    }
//
//    @SuppressLint("MissingPermission", "MissingInflatedId")
//    override fun onCreate(savedInstanceState: Bundle?) {
//        super.onCreate(savedInstanceState)
//        setContentView(R.layout.activity_player)
//        // Create player
//        player = ExoPlayer.Builder(this).build()
//        playerView = findViewById(R.id.playerView)
//        logoImage = findViewById(R.id.logo)
//        exo_qualitys = findViewById(R.id.exo_quality)
//        menuImage = findViewById(R.id.menu_icon)
//        exoNext = findViewById(R.id.exo_next)
//        exoPre = findViewById(R.id.exo_prev)
//        exoPlay = findViewById(R.id.exo_play)
//        drawerCloseImage = findViewById(R.id.drawer_close)
//        drawerLayout = findViewById(R.id.player_channel_frame)
//        allChannelRecyclerView = findViewById(R.id.player_channel_list)
//        authToken =
//            TokenStorage.readSharedToken(this@playersActivity, ConstantUtility.AUTH_TOKEN, null)
//        userId = TokenStorage.readSharedToken(this@playersActivity, ConstantUtility.USER_ID, null)
//        uniqueId = Settings.Secure.getString(contentResolver, Settings.Secure.ANDROID_ID)
//        uniqueId = Settings.Secure.getString(contentResolver, Settings.Secure.ANDROID_ID)
//        dialog = CommonUtility.custom_loader(this)
//        loader = findViewById(R.id.load)
//        val displayMetrics = DisplayMetrics()
//        windowManager.defaultDisplay.getMetrics(displayMetrics)
//        height = displayMetrics.heightPixels.toString()
//        width = displayMetrics.widthPixels.toString()
//        Log.e(
//            TAG,
//            "height::>>$height$width"
//        )
//        exo_qualitys!!.setVisibility(View.VISIBLE)
//
//        exo_qualitys!!.setOnClickListener(View.OnClickListener {
//            qualityPopUp?.show()
//        })
////        playerView.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
////            @Override
////            public void onPrepared(MediaPlayer mediaPlayer) {
////                mediaPlayer.setScreenOnWhilePlaying(false);
////            }
////        });
//        loader!!.setVisibility(View.VISIBLE)
//        playerView!!.setShowBuffering(StyledPlayerView.SHOW_BUFFERING_ALWAYS)
//        // Obtain the FirebaseAnalytics instance.
//        mFirebaseAnalytics = FirebaseAnalytics.getInstance(this)
//        params = Bundle()
//        channelId = intent.getStringExtra("id")
//        callsign = intent.getStringExtra("callsign")
//        println("Channel Name : $channelId")
//        if (intent.getStringExtra("item") != null) {
//            item = intent.getStringExtra("item")
//        }
//        params!!.putString("call_sign", channelId + "_Android")
//        params!!.putString("inside_geofence", "false")
//        mFirebaseAnalytics!!.setUserId(userId)
//        Log.e("Player", "Token : $authToken")
//
//        //getIpAddress2();
//        url = intent.getStringExtra("from")
//        drmKey
//        menuImage!!.setOnClickListener(View.OnClickListener { view: View? ->
//            menuImage!!.setVisibility(View.GONE)
//            drawerLayout!!.setVisibility(View.VISIBLE)
//        })
//        drawerCloseImage!!.setOnClickListener(View.OnClickListener { view: View? ->
//            menuImage!!.setVisibility(View.VISIBLE)
//            drawerLayout!!.setVisibility(View.GONE)
//        })
//    }
//
//    fun playNext(channelN: String, videoUrl: String, channelId: String, position: Int) {
//        url = videoUrl
//        playerView!!.setShowBuffering(StyledPlayerView.SHOW_BUFFERING_ALWAYS)
//        Log.e(
//            TAG,
//            "playNext: 1 $channelId"
//        )
//        Log.e(
//            TAG,
//            "playNext: 2 $videoUrl"
//        )
//        Log.e(
//            TAG,
//            "playNext: 3 $channelN"
//        )
//        Log.e(
//            TAG,
//            "playNext: 4 $position"
//        )
//        rvItemClick = "1"
//        finalPosition = position
//        checkStreamingPlying = true
//        //        Intent i = new Intent(this , playersActivity.class);
////        i.putExtra("from", videoUrl);
////        i.putExtra("channel_name", channelN);
////        i.putExtra("channel_id", channelId);
////        i.putExtra("item", "1");
////        startActivity(i);
////        finish();
////
////        handler.removeCallbacks(runnable);
////        player.stop();
////        Log.e(TAG,"back_press_called");
////        finish();
//        player!!.stop()
//        getDrmKey2(videoUrl, channelId, channelN)
//        menuImage!!.visibility = View.VISIBLE
//        drawerLayout!!.visibility = View.GONE
//
//
////        initializePlayer2("",videoUrl,channelN,channelId);
//    }
//
//    //call all channel api....
//    val drmKey: Unit
//        get() {
//            val viewModel = ViewModelProvider(this).get(DrmViewModel::class.java)
//            viewModel.getData(this@playersActivity).observe(
//                this
//            ) { apiResponse ->
//                Log.i(
//                    TAG,
//                    "onChanged: $apiResponse"
//                )
//                val drmKeyModel = apiResponse.apiResponse as DrmKeyModel?
//                when (apiResponse.responseCode) {
//                    200 -> {
//                        val drmKey = drmKeyModel!!.data
//                        val wvUrl = drmKeyModel.widevinelicense
//                        Log.e(
//                            TAG,
//                            "onChanged: getDrmKey $wvUrl"
//                        )
//                        Log.e(
//                            TAG,
//                            "onChanged: getDrmKey $drmKey"
//                        )
//                        //call all channel api....
//                        getChannelList(drmKey, wvUrl)
//                    }
//                    401 -> {
//                        val sessionExpiredDialog = SessionExpiredDialog(this@playersActivity)
//                        sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
//                        sessionExpiredDialog.setCancelable(false)
//                        sessionExpiredDialog.show()
//                    }
//                    404 -> {
//                        TokenStorage.clearSharedToken(this@playersActivity)
//                        CommonUtility.showToast(
//                            this@playersActivity,
//                            ConstantUtility.SESSION_EXPIRED
//                        )
//                        startActivity(Intent(this@playersActivity, LogInActivity::class.java))
//                        finish()
//                    }
//                    else -> CommonUtility.showToast(
//                        this@playersActivity,
//                        ConstantUtility.SERVER_ISSUE
//                    )
//                }
//            }
//        }
//
//    private fun getDrmKey2(videoUrl: String, channelId: String, channelN: String) {
//        val viewModel = ViewModelProvider(this).get(DrmViewModel::class.java)
//        viewModel.getData(this@playersActivity).observe(
//            this
//        ) { apiResponse ->
//            val drmKeyModel = apiResponse.apiResponse as DrmKeyModel?
//            when (apiResponse.responseCode) {
//                200 -> {
//                    val drmKey = drmKeyModel!!.data
//                    val wvUrl = drmKeyModel.widevinelicense
//                    //call all channel api....
//                    //                        getChannelList(drmKey,wvUrl);
//                    Log.e(
//                        TAG,
//                        "onChanged: getDrmKey2 $wvUrl"
//                    )
//                    Log.e(
//                        TAG,
//                        "onChanged: getDrmKey2 $drmKey"
//                    )
//                    if (checkStreamingPlying) {
//                        checkStreamingPlying = false
//                        initializePlayer2(drmKey, wvUrl, channelN, channelId, videoUrl)
//                    }
//                }
//                401 -> {
//                    val sessionExpiredDialog = SessionExpiredDialog(this@playersActivity)
//                    sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
//                    sessionExpiredDialog.setCancelable(false)
//                    sessionExpiredDialog.show()
//                }
//                404 -> {
//                    TokenStorage.clearSharedToken(this@playersActivity)
//                    CommonUtility.showToast(this@playersActivity, ConstantUtility.SESSION_EXPIRED)
//                    startActivity(Intent(this@playersActivity, LogInActivity::class.java))
//                    finish()
//                }
//                else -> CommonUtility.showToast(this@playersActivity, ConstantUtility.SERVER_ISSUE)
//            }
//        }
//    }
//
//    private fun initializePlayer2(
//        drmKey: String,
//        wvUrl: String,
//        channelN: String,
//        channelId: String,
//        videoUrl: String
//    ) {
//        playerView!!.setShowBuffering(StyledPlayerView.SHOW_BUFFERING_ALWAYS)
//        Log.e(
//            TAG,
//            "initializePlayer2: $drmKey"
//        )
//        Log.e(
//            TAG,
//            "initializePlayer2: $wvUrl"
//        )
//        Log.e(
//            TAG,
//            "initializePlayer2: $channelN"
//        )
//        Log.e(
//            TAG,
//            "initializePlayer2: $channelId"
//        )
//        val drmkey = drmKey
//        val wvurl = wvUrl
//        val streamUrl = videoUrl
//        val channelIdIntent: String = channelId
//        if (channelIdIntent != null) {
//            channel_id = channelIdIntent
//        }
//        player?.playWhenReady = true
//        playerView!!.player = player
//        // URL of the KeyOS MultiKey License API for Widevine
////        String wvUrl = "https://wv-keyos.licensekeyserver.com/";
////        String wvURL1="http://127.0.0.1:8000/api/generic?drm-type=widevine";
////        String wvURL1="https://streamlytv.com/api/generic?drm-type=widevine";
////        String wvUrl1="https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=widevine";
////        String streamUrl2="https://channelstore.streamlytv.com/streamly/AETVP.stream/manifest.mpd";
////        Uri dashUri = Uri.parse("https://path/to/your/stream.mpd");
//        val dashUri = Uri.parse(streamUrl.trim { it <= ' ' })
//        //        Uri dashUri = Uri.parse("https://cdn.streamlytv.com/espn/index.m3u8");
////        Uri dashUri = Uri.parse("https://dash.akamaized.net/envivio/EnvivioDash3/manifest.mpd");
//        Log.e(
//            TAG,
//            "1) license_server: " + wvUrl + "\n"
//                    + "2) DRM_key: " + drmKey + "\n"
//                    + "3) Stream_URL: " + streamUrl + "\n"
//                    + "4) dash_uri: " + dashUri
//                    + "5) channel_id: " + channelIdIntent
//        )
//
//        // Identifies the Widevine DRM
//        val widevineUuid = UUID.fromString("edef8ba9-79d6-4ace-a3c8-27dcd51d21ed")
//
//        // Security token required to acquire a license. Base64 encoded Authentication XML
//        val keyRequestProperties = arrayOf("x-keyos-authorization", drmKey)
//        //        String[] keyRequestProperties = new String[] { "customdata", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTEwLTE5IDE0OjUxOjU1LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMTAtMTkgMTU6MDY6NTUuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZjEzNGMxNjBlYjU1ZTE5ZTJjMjUxODM1YjZlYjdjZDY8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+dS9YQVgxNnNxU2FvUU43UW9FKzY3TlNuTzY0UXFhbUVBOUFsYnNVSlozOWdUT1l3NEpDTW9md3NaYkFQWWVJaS9DKy9SMkJweHJwVEpuWS9ZRGx2dERoSFluYlNwYy9wMXNKcUxOdmREaE4yejEwS2wyT0hKekE1OTdxU0Urcnd2d3BHd09FSm1iVEFidm8zK3FTQVdsTnQ0dWJoMTg0M1VQU3FYMDVZNFEzdlJLWXdzSzR5SUxRdFJuTStENDJsWXcvcTlpc0pUR0pGVWRqY1hJVnh3QWdPRGpDNEdWQVIycXM1VXhHY1cyT0c0THd6ZWpkeUkwMENrK1FYZHpNcTVab3pUZ243a2ZJaGpPS0JETUx0L2VrNnhNYTZ2VjRTQlZEdWxkYnZCR1BjZW9CVzNYWDc1WFY4VjZxdUxJM29GM3FTM3FZQzUvNkJiRmNuQ0k2YjFRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="};
//
//        // Create the DRM session manager and set required request parameters.
//        val mediaDrmCallback: MediaDrmCallback = createMediaDrmCallback(wvUrl, keyRequestProperties)
//        val drmSessionManager: DrmSessionManager = DefaultDrmSessionManager.Builder()
//            .setUuidAndExoMediaDrmProvider(widevineUuid, FrameworkMediaDrm.DEFAULT_PROVIDER)
//            .build(mediaDrmCallback)
//        val dataSourceFactory: DataSource.Factory = DefaultDataSourceFactory(
//            this@playersActivity,
//            Util.getUserAgent(this, "StreamlyTV"), null
//        )
//        //todo HLs for the testing purpose (m3u8)
////        HlsMediaSource mediaSource =new HlsMediaSource.Factory(dataSourceFactory)
////                .setDrmSessionManager(drmSessionManager)
////                .createMediaSource(dashUri);
//
//        //todo DASH for the testing purpose (mpd)
//        val mediaSource: MediaSource = DashMediaSource.Factory(dataSourceFactory)
//            .setDrmSessionManager(drmSessionManager)
//            .createMediaSource(dashUri)
//
////        DashMediaSource mediaSource = new DashMediaSource(dashUri, dataSourceFactory,
////                new DefaultDashChunkSource.Factory(dataSourceFactory),
////                null, null);
//        val bandwidthMeter: BandwidthMeter = DefaultBandwidthMeter()
////        val trackSelector: TrackSelector = DefaultTrackSelector(AdaptiveTrackSelection.Factory())
////        val loadControl =
////            DefaultLoadControl.Builder().setBufferDurationsMs(32 * 1024, 64 * 1024, 1024, 1024)
////                .createDefaultLoadControl()
//
//       val trackSelector = DefaultTrackSelector(/* context= */this, AdaptiveTrackSelection.Factory())
//        player = ExoPlayer.Builder(this)
//            .setTrackSelector(trackSelector!!)
//            .build()
////        player = ExoPlayerFactory.newSimpleInstance(this, trackSelector, loadControl)
//
//        player?.setMediaSource(mediaSource)
//        player?.seekTo(playbackPosition)
//        player?.playWhenReady = playWhenReady
//        player?.addListener(this)
//        player?.prepare()
//////////////////////////////////////////////////////////////////////////////////////////////////////
//        //todo for the DRM protected streaming
//        // Create a data source factory.
////        DataSource.Factory dataSourceFactory = getDefaultDataSourceFactory();
//        // Create a DASH media source pointing to a DASH manifest uri.
////        MediaSource mediaSource = new DashMediaSource.Factory(dataSourceFactory)
////                .setDrmSessionManager(drmSessionManager)
////                .createMediaSource(dashUri);
///////////////////////////////////////////////////////////////////////////////////////////////////////
//        // Attach it to the view
////        playerViews.player = player
////        playerView!!.useController = true
////        player?.addListener(this)
//
//        // Preapre the player with content
////        player!!.prepare(mediaSource)
////        player!!.addListener(object : Player.EventListener {
////            override fun onTimelineChanged(timeline: Timeline, reason: Int) {}
////            fun onTimelineChanged(timeline: Timeline?, manifest: Any?, reason: Int) {}
////            override fun onTracksChanged(
////                trackGroups: TrackGroupArray,
////                trackSelections: TrackSelectionArray
////            ) {
////            }
////
////            override fun onLoadingChanged(isLoading: Boolean) {
////                /*if (isLoading){
////                    mFirebaseAnalytics.logEvent("video_play", params);
////                }*/
////            }
////
////            override fun onPlayerStateChanged(playWhenReady: Boolean, playbackState: Int) {
////                when (playbackState) {
////                    ExoPlayer.STATE_IDLE -> {}
////                    ExoPlayer.STATE_READY -> {}
////                    ExoPlayer.STATE_BUFFERING -> {}
////                    ExoPlayer.STATE_ENDED -> {}
////                }
////            }
////
////            override fun onPlaybackSuppressionReasonChanged(playbackSuppressionReason: Int) {}
////            override fun onIsPlayingChanged(isPlaying: Boolean) {
////                if (isPlaying) {
////
////
//////                    logoImage.setVisibility(View.VISIBLE);
//////                    menuImage.setVisibility(View.GONE);
////
//////
////                    Log.e(TAG, "yes")
////                    mFirebaseAnalytics!!.logEvent("video_play", params)
////                    //
////                    runnable = object : Runnable {
////                        override fun run() {
////                            //do something
////                            params!!.putString("livestream_seconds_watched", "15")
////                            mFirebaseAnalytics!!.logEvent("video_livestream_duration", params)
////                            handler.postDelayed(this, delay.toLong())
////                        }
////                    }
////                    handler.postDelayed(runnable as Runnable, delay.toLong())
////                } else {
////                    Log.e(TAG, "no")
////                    //                    logoImage.setVisibility(View.GONE);
//////                    menuImage.setVisibility(View.VISIBLE);
////                }
////            }
////
////            override fun onRepeatModeChanged(repeatMode: Int) {}
////            override fun onShuffleModeEnabledChanged(shuffleModeEnabled: Boolean) {}
////            fun onPlayerError(error: ExoPlaybackException) {
////                playererror(
////                    callsign, error.toString(), "false", error.sourceException.message,
////                    "$height*$width"
////                )
////                playerErrorDialog =
////                    PlayerErrorDialog(this@playersActivity, object : PlayerDialogInterface {
////                        override fun okButton() {
////                            stopPlayerAndBack()
////                        }
////
////                        override fun tryAgainButton() {
////                            playNext(
////                                channelList!![finalPosition].id.toString(),
////                                channelList!![finalPosition].url,
////                                channelList!![finalPosition].id.toString(),
////                                finalPosition
////                            )
////                        }
////                    })
////                playerErrorDialog!!.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
////                playerErrorDialog!!.show()
////                playerErrorDialog!!.setOnCancelListener { stopPlayerAndBack() }
////                /*playerErrorDialog.setOnKeyListener(new DialogInterface.OnKeyListener() {
////           @Override
////           public boolean onKey(DialogInterface dialogInterface, int i, KeyEvent keyEvent) {
////               Log.e(TAG,"player_error_dialog_key " + KeyEvent.keyCodeToString(i));
////               goBackToHome(dialogInterface,i,keyEvent);
////               return true;
////           }
////       });*/
////            }
////
////            override fun onPositionDiscontinuity(reason: Int) {}
////            override fun onPlaybackParametersChanged(playbackParameters: PlaybackParameters) {}
////            override fun onSeekProcessed() {}
////        })
////        player!!.playWhenReady = true
//        //        player.setRepeatMode(Player.REPEAT_MODE_ALL);
//        postRecentViewChannel(channel_id)
//    }
//
//
//
//    private fun setUpQualityList() {
//        qualityPopUp = PopupMenu(this, exo_quality)
//        qualityList.let {
//            for ((i, videoQuality) in it.withIndex()) {
//                qualityPopUp?.menu?.add(0, i, 0, videoQuality.first)
//            }
//        }
//        qualityPopUp?.setOnMenuItemClickListener { menuItem ->
//            qualityList[menuItem.itemId].let {
//                trackSelector!!.setParameters(
//                    trackSelector!!.getParameters()
//                        .buildUpon()
//                        .setTrackSelectionOverrides(it.second.build())
//                        .setTunnelingEnabled(true)
//                        .build())
//            }
//            true
//        }
//    }
//    override fun onTracksInfoChanged(tracksInfo: TracksInfo) {
//        println("TRACK CHANGED")
//        println(tracksInfo.trackGroupInfos)
//    }
//
//    override fun onPlaybackStateChanged(playbackState: Int) {
//        if (playbackState==Player.STATE_READY){
//            trackSelector?.generateQualityList()?.let {
//                qualityList = it
//                setUpQualityList()
//            }
//        }
//    }
//
//
//    private fun releasePlayer() {
//        player?.let {
//            playbackPosition = it.currentPosition
//            playWhenReady = it.playWhenReady
//            it.release()
//            player = null
//        }
//    }
//
//
//    override fun onStart() {
//        super.onStart()
//        if (Util.SDK_INT >= 24) {
////            initPlayer()
//        }
//    }
//
//    override fun onStop() {
//        super.onStop()
//        if (Util.SDK_INT >= 24) {
//            releasePlayer()
//        }
//    }
//
//    override fun onResume() {
//        super.onResume()
//        if (Util.SDK_INT < 24) {
////            initPlayer()
//        }
//    }
//    override fun onPause() {
//        super.onPause()
//        if (Util.SDK_INT < 24) {
//            releasePlayer()
//        }
//    }
//
//
//    override fun onDestroy() {
//        super.onDestroy()
//        releasePlayer()
//    }
//
//
//
//
//
//
//    private fun initializePlayer(drmKey: String, wvUrl: String) {
////        dialog.show();
//        playerView!!.setShowBuffering(StyledPlayerView.SHOW_BUFFERING_ALWAYS)
//        val drmkey = drmKey
//        val wvurl = wvUrl
//        Log.i(
//            TAG,
//            "initializePlayer: $drmKey"
//        )
//        Log.i(
//            TAG,
//            "initializePlayer: $wvUrl"
//        )
//        Log.e(
//            TAG, "Open live player " + intent.getStringExtra("from")
//        )
//        val streamUrl = intent.getStringExtra("from")
//
//        // final String streamUrl = "https://channelstore1.streamlytv.com/DJCHP/index.mpd";
//        val channelIdIntent = intent.getStringExtra("channel_id")
//        if (channelIdIntent != null) {
//            channel_id = channelIdIntent
//        }
//
//        // URL of the KeyOS MultiKey License API for Widevine
////        String wvUrl = "https://wv-keyos.licensekeyserver.com/";
////        String wvURL1="http://127.0.0.1:8000/api/generic?drm-type=widevine";
////        String wvURL1="https://streamlytv.com/api/generic?drm-type=widevine";
////        String wvUrl1="https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=widevine";
////        String streamUrl2="https://channelstore.streamlytv.com/streamly/AETVP.stream/manifest.mpd";
////        Uri dashUri = Uri.parse("https://path/to/your/stream.mpd");
//        val dashUri = Uri.parse(streamUrl!!.trim { it <= ' ' })
//        //        Uri dashUri = Uri.parse("https://cdn.streamlytv.com/espn/index.m3u8");
////        Uri dashUri = Uri.parse("https://5db313b643fd8.streamlock.net:443/ZerounoTVEventi/ZerounoTVEventi/playlist.m3u8");
////        Uri dashUri = Uri.parse("https://dash.akamaized.net/envivio/EnvivioDash3/manifest.mpd");
//        Log.e(
//            TAG, ("1) license_server: " + wvUrl + "\n"
//                    + "2) DRM_key: " + drmKey + "\n"
//                    + "3) Stream_URL: " + streamUrl + "\n"
//                    + "4) dash_uri: " + dashUri
//                    + "5) channel_id: " + channelIdIntent)
//        )
//
//        // Identifies the Widevine DRM
//        val widevineUuid = UUID.fromString("edef8ba9-79d6-4ace-a3c8-27dcd51d21ed")
//
//        // Security token required to acquire a license. Base64 encoded Authentication XML
//        val keyRequestProperties = arrayOf("x-keyos-authorization", drmKey)
//        //        String[] keyRequestProperties = new String[] { "customdata", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTEwLTE5IDE0OjUxOjU1LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMTAtMTkgMTU6MDY6NTUuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZjEzNGMxNjBlYjU1ZTE5ZTJjMjUxODM1YjZlYjdjZDY8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+dS9YQVgxNnNxU2FvUU43UW9FKzY3TlNuTzY0UXFhbUVBOUFsYnNVSlozOWdUT1l3NEpDTW9md3NaYkFQWWVJaS9DKy9SMkJweHJwVEpuWS9ZRGx2dERoSFluYlNwYy9wMXNKcUxOdmREaE4yejEwS2wyT0hKekE1OTdxU0Urcnd2d3BHd09FSm1iVEFidm8zK3FTQVdsTnQ0dWJoMTg0M1VQU3FYMDVZNFEzdlJLWXdzSzR5SUxRdFJuTStENDJsWXcvcTlpc0pUR0pGVWRqY1hJVnh3QWdPRGpDNEdWQVIycXM1VXhHY1cyT0c0THd6ZWpkeUkwMENrK1FYZHpNcTVab3pUZ243a2ZJaGpPS0JETUx0L2VrNnhNYTZ2VjRTQlZEdWxkYnZCR1BjZW9CVzNYWDc1WFY4VjZxdUxJM29GM3FTM3FZQzUvNkJiRmNuQ0k2YjFRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="};
//
//        // Create the DRM session manager and set required request parameters.
//        val mediaDrmCallback: MediaDrmCallback = createMediaDrmCallback(wvUrl, keyRequestProperties)
//        val drmSessionManager: DrmSessionManager = DefaultDrmSessionManager.Builder()
//            .setUuidAndExoMediaDrmProvider(widevineUuid, FrameworkMediaDrm.DEFAULT_PROVIDER)
//            .build(mediaDrmCallback)
//        val dataSourceFactory: DataSource.Factory = DefaultDataSourceFactory(
//            this@playersActivity,
//            Util.getUserAgent(this, "StreamlyTV"), null
//        )
//        //todo HLs for the testing purpose (m3u8)
////        HlsMediaSource mediaSource =new HlsMediaSource.Factory(dataSourceFactory)
////                .setDrmSessionManager(drmSessionManager)
////                .createMediaSource(dashUri);
//
//        //todo DASH for the testing purpose (mpd)
//        val mediaSource: MediaSource = DashMediaSource.Factory(dataSourceFactory)
//            .setDrmSessionManager(drmSessionManager)
//            .createMediaSource(dashUri)
//
////        DashMediaSource mediaSource = new DashMediaSource(dashUri, dataSourceFactory,
////                new DefaultDashChunkSource.Factory(dataSourceFactory),
////                null, null);
////        val bandwidthMeter: BandwidthMeter = DefaultBandwidthMeter()
////        val trackSelector: TrackSelector = DefaultTrackSelector(AdaptiveTrackSelection.Factory())
////        //        DefaultLoadControl loadControl = new DefaultLoadControl.Builder().setBufferDurationsMs(32*1024, 64*1024, 1024, 1024).createDefaultLoadControl();
//////        DefaultLoadControl loadControl = new DefaultLoadControl.Builder().setBufferDurationsMs(99*1024, 120*1024, 1024, 1024).createDefaultLoadControl();
////        player = ExoPlayerFactory.newSimpleInstance(this, trackSelector)
//
//        val trackSelector = DefaultTrackSelector(/* context= */this, AdaptiveTrackSelection.Factory())
//        player = ExoPlayer.Builder(this)
//            .setTrackSelector(trackSelector!!)
//            .build()
//////////////////////////////////////////////////////////////////////////////////////////////////////
//        //todo for the DRM protected streaming
//        // Create a data source factory.
////        DataSource.Factory dataSourceFactory = getDefaultDataSourceFactory();
//        // Create a DASH media source pointing to a DASH manifest uri.
////        MediaSource mediaSource = new DashMediaSource.Factory(dataSourceFactory)
////                .setDrmSessionManager(drmSessionManager)
////                .createMediaSource(dashUri);
///////////////////////////////////////////////////////////////////////////////////////////////////////
//        // Attach it to the view
//        playerView!!.player = player
//        playerView!!.useController = true
//
//
//        // Preapre the player with content
//        player!!.prepare(mediaSource)
//        player!!.addListener(object : Player.EventListener {
//            override fun onTimelineChanged(timeline: Timeline, reason: Int) {
//                Log.i(
//                    TAG,
//                    "onTimelineChanged: 1 $timeline"
//                )
//                Log.i(
//                    TAG,
//                    "onTimelineChanged: 2 $reason"
//                )
//            }
//
//            fun onTimelineChanged(timeline: Timeline, manifest: Any?, reason: Int) {
//                Log.i(
//                    TAG,
//                    "onTimelineChanged: 3 $timeline"
//                )
//                Log.i(
//                    TAG,
//                    "onTimelineChanged: 4 $reason"
//                )
//            }
//
//            override fun onTracksChanged(
//                trackGroups: TrackGroupArray,
//                trackSelections: TrackSelectionArray
//            ) {
//                Log.i(
//                    TAG,
//                    "onTracksChanged: $trackGroups"
//                )
//                Log.i(
//                    TAG,
//                    "onTracksChanged: $trackSelections"
//                )
//            }
//
//            override fun onLoadingChanged(isLoading: Boolean) {
//                Log.i(
//                    TAG,
//                    "onLoadingChanged: $isLoading"
//                )
//                if (isLoading) {
//                    mFirebaseAnalytics!!.logEvent("video_play", params)
//                }
//            }
//
//            override fun onPlayerStateChanged(playWhenReady: Boolean, playbackState: Int) {
//                when (playbackState) {
//                    ExoPlayer.STATE_IDLE -> Log.i(TAG, "onPlayerStateChanged: idle ")
//                    ExoPlayer.STATE_READY -> Log.i(TAG, "onPlayerStateChanged: ready ")
//                    ExoPlayer.STATE_BUFFERING -> Log.i(TAG, "onPlayerStateChanged: buffering ")
//                    ExoPlayer.STATE_ENDED -> Log.i(TAG, "onPlayerStateChanged: STATE_ENDED  ")
//                }
//            }
//
//            override fun onPlaybackSuppressionReasonChanged(playbackSuppressionReason: Int) {
//                Log.i(TAG, "onPlaybackSuppressionReasonChanged: ")
//            }
//
//            override fun onIsPlayingChanged(isPlaying: Boolean) {
//                if (isPlaying) {
//                    Log.e(TAG, "yes")
//                    mFirebaseAnalytics!!.logEvent("video_play", params)
//                    runnable = object : Runnable {
//                        override fun run() {
//                            //do something
//                            params!!.putString("livestream_seconds_watched", "15")
//                            mFirebaseAnalytics!!.logEvent("video_livestream_duration", params)
//                            handler.postDelayed(this, delay.toLong())
//                        }
//                    }
//                    handler.postDelayed(runnable as Runnable, delay.toLong())
//                } else {
//                    Log.i(TAG, "no ")
//                    //                    logoImage.setVisibility(View.GONE);
////                    menuImage.setVisibility(View.VISIBLE);
//                }
//            }
//
//            override fun onRepeatModeChanged(repeatMode: Int) {
//                Log.i(TAG, "onRepeatModeChanged: else ")
//            }
//
//            override fun onShuffleModeEnabledChanged(shuffleModeEnabled: Boolean) {
//                Log.i(TAG, "onShuffleModeEnabledChanged: ")
//            }
//
//            fun onPlayerError(error: ExoPlaybackException) {
//                playererror(
//                    callsign, error.toString(), "false", error.sourceException.message,
//                    "$height*$width"
//                )
//                playerErrorDialog =
//                    PlayerErrorDialog(this@playersActivity, object : PlayerDialogInterface {
//                        override fun okButton() {
//                            stopPlayerAndBack()
//                        }
//
//                        override fun tryAgainButton() {
//                            playNext(
//                                channelList!![finalPosition].id.toString(),
//                                channelList!![finalPosition].url,
//                                channelList!![finalPosition].id.toString(),
//                                finalPosition
//                            )
//                        }
//                    })
//                playerErrorDialog!!.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
//                playerErrorDialog!!.show()
//                playerErrorDialog!!.setOnCancelListener { stopPlayerAndBack() }
//                /*playerErrorDialog.setOnKeyListener(new DialogInterface.OnKeyListener() {
//           @Override
//           public boolean onKey(DialogInterface dialogInterface, int i, KeyEvent keyEvent) {
//               Log.e(TAG,"player_error_dialog_key " + KeyEvent.keyCodeToString(i));
//               goBackToHome(dialogInterface,i,keyEvent);
//               return true;
//           }
//       });*/
//            }
//
//            override fun onPositionDiscontinuity(reason: Int) {
//                Log.i(TAG, "onPositionDiscontinuity: ")
//            }
//
//            override fun onPlaybackParametersChanged(playbackParameters: PlaybackParameters) {
//                Log.i(TAG, "onPlaybackParametersChanged: ")
//            }
//
//            override fun onSeekProcessed() {
//                Log.i(TAG, "onSeekProcessed: ")
//            }
//        })
//        exoNext!!.setOnClickListener {
//            val nextPosition = finalPosition + 1
//            if (nextPosition < channelList!!.size) {
//                playNext(
//                    channelList!![nextPosition].id.toString(),
//                    channelList!![nextPosition].url,
//                    channelList!![nextPosition].id.toString(),
//                    nextPosition
//                )
//            }
//        }
//        exoPre!!.setOnClickListener {
//            val prePosition = finalPosition - 1
//            Log.e(
//                TAG,
//                "Position : $prePosition"
//            )
//            if (prePosition >= 0) {
//                playNext(
//                    channelList!![prePosition].id.toString(),
//                    channelList!![prePosition].url,
//                    channelList!![prePosition].id.toString(),
//                    prePosition
//                )
//            }
//        }
//        player!!.playWhenReady = true
//        postRecentViewChannel(channel_id)
//    }
//
//    private fun goBackToHome(dialogInterface: DialogInterface, i: Int, keyEvent: KeyEvent) {
//        if (i == KeyEvent.KEYCODE_BACK && keyEvent.action == KeyEvent.ACTION_UP) {
//            stopPlayerAndBack()
//        }
//    }
//
//    fun stopPlayerAndBack() {
//        handler.removeCallbacks((runnable)!!)
//        player!!.stop()
//        finish()
//    }
//
//    override fun onBackPressed() {
//        super.onBackPressed()
//        Log.e(TAG, "Back")
//        stopPlayerAndBack()
//    }
//
////    override fun onStop() {
////        super.onStop()
////        player!!.playWhenReady = false
////        player!!.playbackState
////    }
//
//    private fun playererror(
//        call_sign: String?,
//        errcode: String,
//        inside_geofence: String,
//        errmsg: String?,
//        sr: String
//    ) {
//
////        ShowProgressDialog(activity, getString(R.string.please_wait));
//        val postRequest: StringRequest = object : StringRequest(
//            Method.POST, ConstantUtility.PLAYER_ERROR,
//            object : Response.Listener<String> {
//                var error_msg: String? = null
//                override fun onResponse(response: String) {
//                    Log.e("!_@@ playererror Resp:>", "playererror===>>>>$response")
//                    //                        hideProgressDialog();
//                    try {
//                        val `object` = JSONObject(response)
//                        val message: String
//                        val success: String
//                        success = `object`.getString("success")
//                        message = `object`.getString("message")
//                        val response_code = `object`.getInt("response_code")
//                        //                            if (response_code == 403) {
////                                ApplicationHelper.INSTANCE.showToast(activity, Constants.SERVER_ISSUE);
////                            } else if (response_code == 500) {
////                                ApplicationHelper.INSTANCE.showToast(activity, Constants.SERVER_ISSUE);
////                            } else {
////
////                            }
////                            if (success.equals("true")) {
////
////                            } else
////                                Utils.Companion.showAlert(PlayerNewActivity.this, getString(R.string.app_name), message);
//                    } catch (e: Exception) {
//                        e.printStackTrace()
//                        Log.e("error..", ": $e")
//                    }
//                }
//            },
//            Response.ErrorListener { error ->
//                Log.e("Errorlist", error.toString())
//                //                hideProgressDialog();
//                //                Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
//                Log.e(TAG, "error fail>>: " + error.message)
//            }) {
//            public override fun getParams(): Map<String, String>? {
//                val params: MutableMap<String, String> = HashMap()
//                params["callsign"] = (call_sign)!!
//                params["errcode"] = errcode
//                params["inside_geofence"] = inside_geofence
//                params["errmsg"] = (errmsg)!!
//                params["sr"] = sr
//                Log.e("!_@@ Params :", "" + params)
//                return params
//            }
//
//            @Throws(AuthFailureError::class)
//            override fun getHeaders(): Map<String, String> {
//                val headers: MutableMap<String, String> = HashMap()
//                headers["User-Agent"] = System.getProperty("http.agent")
//                headers["Accept"] = "application/json"
//                headers["Authorization"] = "Bearer $authToken"
//                Log.e("!_@@ headers :", "" + headers)
//                return headers
//            }
//        }
//        val queue = Volley.newRequestQueue(this)
//        queue.cache.clear()
//        postRequest.retryPolicy =
//            DefaultRetryPolicy(
//                60000,
//                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
//                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT
//            )
//        queue.add(postRequest)
//    }
//
//    private fun getChannelList(drmKey: String, wvUrl: String) {
//        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
//        val call = apiInterface.getChannelList("Bearer $authToken")
//        call.enqueue(object : Callback<ChannellistModel?> {
//            override fun onResponse(
//                call: Call<ChannellistModel?>,
//                response: retrofit2.Response<ChannellistModel?>
//            ) {
//                try {
//                    if (response.isSuccessful && (response.body() != null) && (response.code() != 401)) {
//                        channelList = response.body()!!.data
//                        Log.e("channelList-------", channelList!!.get(0).url)
//                        Log.e("channelList-------", channelList!!.get(0).id.toString())
//                        Log.e("channelList-------", wvUrl)
//                        //                        check for current channel name and store the position of the current channle and auto scroll
////                        that position.
//                        var position = 0
//                        for (i in channelList!!.indices) {
//                            if ((url == channelList!!.get(i).url)) {
//                                channel_id = channelList!!.get(i).id.toString()
//                                Log.e(
//                                    TAG,
//                                    "onResponse: channel list id " + channelList!!.get(i).id.toString()
//                                )
//                            }
//                            if ((channelList!!.get(i).id.toString() == channelId)) {
//                                position = i
//                            } else if ((channelList!!.get(i).id.toString() == channel_id)) {
//                                position = i
//                            }
//                        }
//                        if ((rvItemClick == "1")) {
//                            allChannelRecyclerView!!.layoutManager = LinearLayoutManager(
//                                this@playersActivity,
//                                LinearLayoutManager.VERTICAL,
//                                false
//                            )
//                            allChannelAdapter =
//                                AllChannelAdapter(this@playersActivity, channelList, finalPosition)
//                            allChannelRecyclerView!!.adapter = allChannelAdapter
//                        } else {
//                            allChannelRecyclerView!!.layoutManager = LinearLayoutManager(
//                                this@playersActivity,
//                                LinearLayoutManager.VERTICAL,
//                                false
//                            )
//                            allChannelAdapter =
//                                AllChannelAdapter(this@playersActivity, channelList, position)
//                            allChannelRecyclerView!!.adapter = allChannelAdapter
//                            Log.e(
//                                TAG,
//                                "onResponse: channel position $position"
//                            )
//                            finalPosition = position
//                        }
//                        allChannelRecyclerView!!.post {
//                            allChannelRecyclerView!!.isSelected = true
//                            allChannelRecyclerView!!.isFocusable = true
//                            allChannelRecyclerView!!.scrollToPosition(finalPosition)
//                        }
//                        menuImage!!.visibility = View.VISIBLE
//                        if ((rvItemClick == "1")) {
//                            rvItemClick = "0"
//                        } else {
//                            loader!!.visibility = View.GONE
//                            initializePlayer(drmKey, wvUrl)
//                        }
//                    } else if (response.code() == 401) {
//                        if (response.errorBody() != null) {
//                            val sessionExpiredDialog = SessionExpiredDialog(this@playersActivity)
//                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
//                            sessionExpiredDialog.setCancelable(false)
//                            sessionExpiredDialog.show()
//                        }
//                    } else if (response.code() == 404) {
//                        TokenStorage.clearSharedToken(this@playersActivity)
//                        CommonUtility.showToast(
//                            this@playersActivity,
//                            ConstantUtility.SESSION_EXPIRED
//                        )
//                    } else if (response.code() == 500) {
//                        CommonUtility.showToast(this@playersActivity, ConstantUtility.SERVER_ISSUE)
//                    }
//                } catch (ex: Exception) {
//                    ex.printStackTrace()
//                    Log.e(
//                        TAG,
//                        "Exception $ex"
//                    )
//                }
//            }
//
//            override fun onFailure(call: Call<ChannellistModel?>, t: Throwable) {
//                Log.e(TAG, "failure: " + call + " " + t.localizedMessage)
//                /*AlertDialog.Builder dialog = new AlertDialog.Builder(playersActivity.this, R.style.AlertDialogCustom);
//                dialog.setTitle("StreamlyTV");
//                dialog.setMessage("Due to some technical reason, channel gets interrupted. Kindly try again later");
//
//                // Specifying a listener allows you to take an action before dismissing the dialog.
//                // The dialog is automatically dismissed when a dialog button is clicked.
//                dialog.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
//                    public void onClick(DialogInterface dialog, int which) {
//                        // Continue with delete operation
//                        dialog.dismiss();
//                        handler.removeCallbacks(runnable);
//                        player.stop();
//                        finish();
//
//
//                    }
//                });
//                dialog.show();
//                dialog.setCancelable(true);*/
//                val errorMsg =
//                    "Due to some technical reason, channel gets interrupted. Kindly try again later"
//                CommonUtility.showToast(this@playersActivity, errorMsg)
//                handler.removeCallbacks((runnable)!!)
//                player!!.stop()
//                finish()
//            }
//        })
//    }
//
//    private fun postRecentViewChannel(channelId: String) {
//        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
//        val call = apiInterface.postRecentList("Bearer $authToken", channelId)
//        call.enqueue(object : Callback<ChannellistModel?> {
//            override fun onResponse(
//                call: Call<ChannellistModel?>,
//                response: retrofit2.Response<ChannellistModel?>
//            ) {
//                try {
//                    if (response.isSuccessful && response.body() != null) {
////                        List<Datum> datumList = response.body().getData();
////                        recentList = response.body().getData();
//                        Log.e(
//                            TAG, "onResponse: recent list " + response.body()!!
//                                .data
//                        )
//                    } else if (response.code() == 401) {
//                        CommonUtility.showToast(this@playersActivity, response.body()!!.message)
//                    } else if (response.code() == 404) {
//                        TokenStorage.clearSharedToken(this@playersActivity)
//                        CommonUtility.showToast(
//                            this@playersActivity,
//                            ConstantUtility.SESSION_EXPIRED
//                        )
//                    } else if (response.code() == 500) {
//                        CommonUtility.showToast(this@playersActivity, ConstantUtility.SERVER_ISSUE)
//                    }
//                } catch (ex: Exception) {
//                    ex.printStackTrace()
//                    Log.e(
//                        TAG,
//                        "Exception $ex"
//                    )
//                }
//            }
//
//            override fun onFailure(call: Call<ChannellistModel?>, t: Throwable) {
//                Log.e(TAG, "failure: " + call + " " + t.localizedMessage)
//                CommonUtility.showToast(this@playersActivity, ConstantUtility.SERVER_ISSUE)
//            }
//        })
//    }
//
//    @SuppressLint("RestrictedApi")
//    override fun dispatchKeyEvent(event: KeyEvent): Boolean {
//        playerView!!.dispatchKeyEvent(event)
//        return super.dispatchKeyEvent(event)
//    }
//
////    override fun onPause() {
////        super.onPause()
////        Log.e(TAG, "on Pause")
////        player!!.playWhenReady = false
////        player!!.playbackState
////    }
////
////    override fun onResume() {
////        super.onResume()
////        Log.e(TAG, "on Resume")
////        player!!.playWhenReady = true
////        player!!.playbackState
////    }
////
////    override fun onDestroy() {
////        super.onDestroy()
////        handler.removeCallbacks((runnable)!!)
////        player!!.stop()
////    }
//
//    companion object {
//        private val TAG = playersActivity::class.java.simpleName
//    }
//}