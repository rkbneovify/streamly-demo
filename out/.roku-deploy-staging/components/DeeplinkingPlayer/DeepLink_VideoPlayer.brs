' 1st function that runs for the scene component on channel startup
sub init()
    'To see print statements/debug info, telnet on port 8089
    m.Video = m.top.findNode("Video")
    ' SetupVideoPlayer()
    ' VideoliveLoader()
    setContent()
  end sub

  
  'Set your information here
  sub setContent()
  
    'Change the image
    'm.Image.uri="pkg:/images/DanGilbert.jpg"
    'ContentNode = CreateObject("roSGNode", "ContentNode")
    'ContentNode.streamFormat = "mp4"
    'ContentNode.url = "http://video.ted.com/talks/podcast/DanGilbert_2004_480.mp4"
    'ContentNode.ShortDescriptionLine1 = "Dan Gilbert asks, Why are we happy?"
    'ContentNode.Description = "Harvard psychologist Dan Gilbert says our beliefs about what will make us happy are often wrong -- a premise he supports with intriguing research, and explains in his accessible and unexpectedly funny book, Stumbling on Happiness."
    'ContentNode.StarRating = 80
    'ContentNode.Length = 1280
    'ContentNode.Title = "Dan Gilbert asks, Why are we happy?"

    ContentNode = CreateObject("roSGNode", "ContentNode")
    
  
    'm.Image.uri="pkg:/images/BigBuckBunny.jpg"
    'ContentNode = CreateObject("roSGNode", "ContentNode")
    'ContentNode.streamFormat = "mp4"
    'ContentNode.url = "http://video.ted.com/talks/podcast/CraigVenter_2008_480.mp4"
    'ContentNode.ShortDescriptionLine1 = "Big Buck Bunny"
    'ContentNode.Description = "Big Buck Bunny is being served using a Wowza server running on Amazon EC2 cloud services. The video is transported via HLS HTTP Live Streaming. A team of small artists from the Blender community produced this open source content..."
    'ContentNode.StarRating = 80
    'ContentNode.Length = 600
    'ContentNode.Title = "Big Buck Bunny"

    drmParams = {
      keySystem: "Playready"
      licenseServerURL: "https://streamlytv.com/api/generic?drm-type=playready"
      ' licenseServerURL:"https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=widevine"
      '  "https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=widevine"
  }

  ' vidContent.streamformat = "smooth"

    m.Video.enableUI = false

    ContentNode.streamformat = "smooth"
    ContentNode.drmParams = drmParams
    ContentNode.url = "https://channelstore3.streamlytv.com/FNC.isml/manifest"
    ContentNode.live = true

    m.Video.content = ContentNode
    m.Video.setFocus(true)

    ' m.Video.control = "prebuffer"
    ?"Video set"

    m.Video.control = "play"

  
  end sub
  
  ' Called when a key on the remote is pressed
  function onKeyEvent(key as String, press as Boolean) as Boolean
    print "in SimpleVideoScene.xml onKeyEvent ";key;" "; press
    if press then
      if key = "back"
        if m.Video.visible
          m.Video.control = "stop"
          m.top.ExitView = true
          return true
        else
          return false
        end if
        return false
      end if
    end if
    return false
  end function

'   sub SetupVideoPlayer()

'     ' m.Video = m.top.findNode("VideoPlayer")
'     m.video.observeField("state", "onStateChange")
'     m.video.observeField("position", "onPositionChange")
'     m.video.observeField("bufferingStatus", "percentage")
'     m.video.observeField("visible", "onVisibleChange")
'     m.video.observeField("content", "onContentChange")
'     m.video.observeField("streamInfo", "onStreamInfoChange")
'     m.video.notificationInterval = 1

'     'map of events that should be handled on state change
'     m.statestToHandle = {
'         "stopped": ""
'         "playing": ""
'         "paused": ""
'         "error": ""
'         "finished": ""
'     }
'     m.position = 0
' end sub

' sub percentage(event as object)

'     if (event <> invalid and event.getData() <> invalid)
'         ?" buffering ";event.getData().percentage
'     else

'     end if
' end sub

' sub onStateChange()
'     if m.Video <> invalid

'         ? "videoState"; m.video.state
'         ? "completedStreamInfo"; m.video.completedStreamInfo

'         if m.Video.state = "buffering"
'             ' m.video.visible = true
'             centerx = (1920 - m.playerLoader.poster.bitmapWidth) / 2
'             centery = (1080 - m.playerLoader.poster.bitmapHeight) / 2
'             m.playerLoader.translation = [800, 500]

'             m.playerLoader.visible = true
'             m.playerLoader.control = "start"
'         else

'             m.playerLoader.visible = false
'             m.playerLoader.control = "stop"
'         end if

'         if m.Video.state = "playing"
'             if m.selectedPlayingChannel <> invalid
'                 ?"m.selectedPlayingChannel";m.selectedPlayingChannel
'                 DisplaySize = getDeviceInfo().GetDisplaySize()
'                 m.analyticsChannelClicks = CreateObject("roSGNode", "ApiCall")
'                 m.analyticsChannelClicks.bodyData = { "callsign": m.selectedPlayingChannel, sr: DisplaySize.w.toStr() + "x" + DisplaySize.h.toStr(), inside_geofence: m.inside_geofence }
'                 m.analyticsChannelClicks.callType = "POST"
'                 m.analyticsChannelClicks.url = ga()
'                 m.analyticsChannelClicks.observeField("apiResponseData", "apiResponse")
'                 ' m.analyticsChannelClicks.observeField("errorMessage", "errorDialogShow")
'                 m.analyticsChannelClicks.control = "RUN"
'                 if m.GlobalPopupmsgtoggle = true
'                     m.LiveChannelError.setFocus(true)
'                 end if

'             end if
'         end if

'         if (m.video.state <> invalid and ((m.video.state = "error") or (m.video.state = "finished") or (m.video.state = "stopped")))

'             print "m.video.errorCode: "; m.video.errorCode
'             print "m.video.errorMsg: "; m.video.errorMsg
'             print "m.vdeo.errorStr: "; m.video.errorStr
'             print "m.vdeo.errorInfo: "; m.video.errorInfo
'             print "m.vdeo.completedStreamInfo: "; m.video.completedStreamInfo

'             checkforVideoApiCall = 0



'             DisplaySize = getDeviceInfo().GetDisplaySize()
'             CrashLogCall = CreateObject("roSGNode", "ApiCall")
'             CrashLogCall.bodyData = {
'                 errcode: m.video.errorCode,
'                 errmsg: m.video.errorMsg,
'                 callsign: m.selectedPlayingChannel + "ROKU",
'                 sr: DisplaySize.w.toStr() + "x" + DisplaySize.h.toStr(),
'                 inside_geofence: m.inside_geofence
'             }
'             CrashLogCall.callType = "POST"
'             CrashLogCall.url = playererror()
'             CrashLogCall.observeField("apiResponseData", "apiResponse")
'             CrashLogCall.control = "RUN"
'             m.top.signalBeacon("VODStartComplete")
'             closePlayer()
'             ' m.top.signalBeacon("VODStartComplete")
'         end if

'     end if

' end sub

' sub onPositionChange()
'     if m.video.duration > 0 then
'         ' ?"onPositionChange:";m.video.duration

'         if m.checkforVideoApiCall = 15

'             ?"m.checkforVideoApiCall";m.checkforVideoApiCall
'             ?"m.selectedPlayingChannel";m.selectedPlayingChannel
'             m.checkforVideoApiCall = 0

'             if m.selectedPlayingChannel <> invalid

'                 ?"m.selectedPlayingChannel";m.selectedPlayingChannel
'                 DisplaySize = getDeviceInfo().GetDisplaySize()
'                 m.analyticsChannelClicks = CreateObject("roSGNode", "ApiCall")
'                 m.analyticsChannelClicks.bodyData = { "callsign": m.selectedPlayingChannel + "ROKU", sr: DisplaySize.w.toStr() + "x" + DisplaySize.h.toStr(), inside_geofence: m.inside_geofence }
'                 m.analyticsChannelClicks.callType = "POST"
'                 m.analyticsChannelClicks.url = GADUration()
'                 m.analyticsChannelClicks.observeField("apiResponseData", "apiResponse")
'                 ' m.analyticsChannelClicks.observeField("errorMessage", "errorDialogShow")
'                 m.analyticsChannelClicks.control = "RUN"
'             end if
'         else
'             ' m.checkforVideoApiCall += 1
'         end if

'     end if


' end sub
' sub closePlayer()
'   ?"close player:";m.video.control
'   if m.LiveChannelError <> invalid
'       closeErrorDialogShow(m.top)
'   end if

'   ' if m.video.control = "buffering" or  m.video.control = "playing" or  m.video.control = "finished" or  m.video.control = "prebuffer"
'   if m.streaminfoTimer.control <> invalid and m.streaminfoTimer.control = "start"
'       m.streaminfoTimer.control = "stop"
'   end if

'   if m.programUpdateTimer.control <> invalid and m.programUpdateTimer.control = "start"
'       m.programUpdateTimer.control = "stop"
'   end if





'   ' closeRelatedchnnelView()
'   ' m.streamlyview.visible = false
'   ' m.PosterBackground.visible = false
'   ' m.navtoRelatedCHannels.visible = false

'   ' m.ChannelsGroup.visible = true
'   m.video.visible = false
'   ' m.video.setFocus(false)
'   ' m.timeGride.setFocus(true)

'   ' end if

' end sub

' sub VideoLiveLoader()
'   m.playerLoader = m.top.createChild("BusySpinner")
'   m.playerLoader.visible = false
'   m.playerLoader.clockwise = false
'   m.playerLoader.poster.uri = "pkg:/images/videoplayer/loader96.png"
'   ' ?"m.playerLoader.poster:";m.playerLoader.poster
'   ' m.playerLoader.translation = [(1920 -m.playerLoader.poster.bitmapWidth)/2 , 1080 -  (m.playerLoader.poster.bitmapHeight)/2]
' end sub

