sub init()
    ? "Home init()"
    'show loading
    m.processPoster = m.top.createChild("Poster")
    '    deviceinfo = getDeviceInfo()
    '    ?"deviceinfo:"; deviceinfo.getDisplaySize().w
    m.processPoster.height = 1080
    m.processPoster.width = 1920
    m.processPoster.uri = "pkg:/images/SplashHD.png"

    'background image for screen
    currentResolution = m.top.getScene().currentDesignResolution
    m.channelBackgroundposter = m.top.findNode("channelBackgroundposter")
    ' ?"m.top.getScene().currentDesignResolution";m.top.getScene().currentDesignResolution
    ' if m.deviceinfo.getDisplaySize().w = 1920
    '        m.m.PosterBackground.uri = "pkg:/images/background_FHD.jpg"
    m.channelBackgroundposter.height = currentResolution.height
    m.channelBackgroundposter.width = currentResolution.width
    m.channelBackgroundposter.uri = "pkg:/images/homeScreenBckgrnd.png"
    ' else if m.deviceinfo.getDisplaySize().w = 1280
    ' m.channelBackgroundposter.uri = "pkg:/images/home/homeScreenBckgrndhd.png"
    ' else
    ' m.channelBackgroundposter.uri = "pkg:/images/home/homeScreenBckgrndhd.png"
    ' end if

    m.ChannelsGroup = m.top.findNode("ChannelsGroup")
    m.timeGride = m.top.findNode("timeGrid")

    m.timeGride.numRows = 0
    '    m.video = m.top.findNode("video")
    m.video = m.top.findNode("livePlayer")
    m.streamlyview = m.top.findNode("streamlyview")
    ' m.streaminfo   = m.top.findNode("streaminfo")
    ' m.video.observeField("state", "videoState")

    m.video.enableUI = false
    m.video.enableTrickPlay = false
    m.video.visible = false
    m.ChannelsGroup.visible = true
    m.video.thumbnailImage = "pkg:/images/home/testingThumbnail.jpg"
    '    m.channelGuide = m.top.findNode("channelGuide")
    '    m.pressbackLabel = m.top.findNode("pressbackLabel")
    m.Settings = m.top.findNode("Settings")


    m.searchPoster = m.top.findNode("searchPoster")
    m.searchPoster.translation = [60, 50]
    m.searchPoster.uri = "pkg:/images/home/searchicon_100.png"
    m.searchPoster.height = 50
    m.searchPoster.width = 50
    m.searchPoster.visible = true
    m.focusPoster = m.searchPoster.createChild("Poster")
    m.focusPoster.id = "focusPoster"
    m.focusPoster.translation = [-10, -10]
    m.focusPoster.uri = ""
    m.focusPoster.height = 60
    m.focusPoster.width = 75

    '    m.searchPoster.visible =false



    m.ChannelCategoryMarkuGrid = m.top.findNode("ChannelCategoryMarkuGrid")
    ' m.channelIcon = m.top.findNode("channelIcon")

    ' channel ,episode api call '

    reqApiCAll({ "page": 1 })

    font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
    '    m.channelGuide.font = font
    '    m.pressbackLabel.font = font
    m.Settings.font = font

    currentTime = CreateObject("roDateTime")
    currentTime.Mark()
    currentTime.ToLocalTime()

    ? "currentTime1.ToISOString(): "currentTime.ToISOString()
    ? "LocalTIme : currentTime1.ToISOString(): "currentTime.ToISOString()

    t = currentTime.AsSeconds()
    t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
    m.timeGride.contentStartTime = t
    m.timeGride.leftEdgeTargetTime = currentTime.AsSeconds()

    m.timeGride.focusBitmapUri = "pkg:/images/home/buttonFoucs1.9.png"
    m.timeGride.channelInfoComponentName = "ChannelItemComponent"

    m.timeGride.observeField("programSelected", "programSelected")
    m.timeGride.observeField("channelFocused", "channelFocused")

    m.timeGride.programHorizMargin = 32
    'm.timeGride.programBackgroundBitmapUri = "pkg:/images/programUnfocusBOrder.png"
    m.timeGride.programBackgroundBitmapUri = "pkg:/images/home/borderwhite3.9.png"
    ' m.timeGride.programBackgroundBitmapUri = "pkg:/images/home/borderwhite4.png"

    m.timeGride.overlayBitmapUri = ""
    m.timeGride.channelInfoBackgroundBitmapUri = ""
    m.timeGride.timeBarBitmapUri = "pkg:/images/transparentimg.png"
    m.timeGride.programTitleFocusedColor = "#ffffff"
    m.timeGride.showPastTimeScreen = true

    m.timeGride.itemSize = [1920, 75]
    m.timeGride.programTitleFont = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.timeGride.channelNoDataText = "Loading..."
    m.timeGride.loadingDataText = "Loading..."
    m.timeGride.automaticLoadingDataFeedback = true

    m.timeGride.numRows = 0
    m.timeGride.fillProgramGaps = true
    m.timeGride.translation = [0.0, 350.0]
    ' m.timeGride.nowBackgroundBitmapUri = "pkg:/images/profile/profile_dialog.png"
    m.timeGride.vertFocusAnimationStyle = "fixedFocus"

    m.top.observeField("visible", "setFocus")

    m.timeGride.visible = true

    ' prgrams Api call timer
    m.lazyLoadingTimer = CreateObject("roSGNode", "Timer")
    m.lazyLoadingTimer.repeat = false
    m.lazyLoadingTimer.duration = .3
    m.lazyLoadingTimer.observeField("fire", "StartContentLoading")

    m.streaminfoTimer = CreateObject("roSGNode", "Timer")
    m.streaminfoTimer.repeat = false
    m.streaminfoTimer.duration = 4
    m.streaminfoTimer.observeField("fire", "closeStreamInfo")
    '    m.lazyLoadingTimer.control = "start"
    '    m.ChannelData = createObject("roArray")
    ' prgramView Update Timer

    m.programUpdateTimer = CreateObject("roSGNode", "Timer")
    m.programUpdateTimer.repeat = false

    m.programUpdateTimer.observeField("fire", "StartProgramUpdating")

    m.PosterBackground = m.top.createChild("Poster")

    m.PosterBackground.translation = [60, 800]

    m.PosterBackground.height = 236
    m.PosterBackground.width = 469
    m.PosterBackground.uri = "pkg:/images/home/channelinfobackgrnd.png"
    m.PosterBackground.opacity = .8
    m.PosterBackground.visible = false
    '   LayoutGroup = m.PosterBackground.createChild("LayoutGroup")
    '   LayoutGroup.layoutDirection = "horiz"
    '   LayoutGroup.itemSpacings ="10"

    m.PosterchannleIcon = m.PosterBackground.createChild("Poster")
    m.PosterchannleIcon.translation = [26, 98]
    m.PosterchannleIcon.height = 94
    m.PosterchannleIcon.width = 138
    m.PosterchannleIcon.uri = "pkg:/images/mtv.png"

    PosterchannleliveIcon = m.PosterBackground.createChild("Poster")
    PosterchannleliveIcon.translation = [189, 22]
    PosterchannleliveIcon.height = 20
    PosterchannleliveIcon.width = 20
    PosterchannleliveIcon.uri = "pkg:/images/home/liveIcon.png"
    m.PosterchannleIconLabel = PosterchannleliveIcon.createChild("Label")

    m.PosterchannleIconLabel.text = "Live"
    m.PosterchannleIconLabel.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.PosterchannleIconLabel.translation = [30, 0]

    ConsertTiming = m.PosterBackground.createChild("Rectangle")
    ConsertTiming.translation = [189, 55]
    ConsertTiming.height = 30
    ConsertTiming.width = 163
    ConsertTiming.color = "#004E92"
    m.ConsertTimingTExt = ConsertTiming.createChild("Label")
    m.ConsertTimingTExt.text = "04:00 - 05:00PM"
    m.ConsertTimingTExt.translation = [8, 4]
    m.ConsertTimingTExt.font = getFont(18, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")

    m.ConcertChannel = m.PosterBackground.createChild("Label")
    m.ConcertChannel.translation = [189, 103]
    m.ConcertChannel.height = 41
    m.ConcertChannel.width = 0
    '   m.ConcertChannel.wrap =true
    m.ConcertChannel.text = "MTV-USA"
    m.ConcertChannel.font = getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.ConcertName = m.PosterBackground.createChild("Label")
    m.ConcertName.translation = [189, 162]
    m.ConcertName.height = 30
    m.ConcertName.width = 255
    m.ConcertName.text = "Justin Beiber concert"
    m.ConcertName.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
    m.channelListData = 0
    '   LayutRght.layoutDirection = "vert"

    '   LayutRght.itemSpacings ="10"
    '   LayutRght.createChild("Label")
    '   LayutRght.translation=[217,21]
    m.AllChannelContent = CreateObject("roSGNode", "ContentNode")

    ' mybutton.horiz

    m.global.RSG_analytics.trackEvent = {
        google: {
            ec: "EPG_Screen",
            ea: "EPG_Launch"
        }
    }
    SetupVideoPlayer()
    m.programIndex = 0

end sub
sub closeStreamInfo()
    ?"closeStreamInfo"
    m.streaminfoTimer.control = "stop"
    m.streamlyview.visible = false
    ' m.channelIcon.visible = false
    m.PosterBackground.visible = false

    ' m.streaminfo.visible = false


end sub

sub SetupVideoPlayer()

    ' m.Video = m.top.findNode("VideoPlayer")
    m.video.observeField("state", "onStateChange")
    m.video.observeField("position", "onPositionChange")
    m.video.observeField("bufferingStatus", "percentage")
    m.video.observeField("visible", "onVisibleChange")
    m.video.observeField("content", "onContentChange")
    m.video.observeField("streamInfo", "onStreamInfoChange")
    m.video.notificationInterval = 1

    'map of events that should be handled on state change
    m.statestToHandle = {
        "stopped": ""
        "playing": ""
        "paused": ""
        "error": ""
        "finished": ""
    }
    m.position = 0
end sub
' sub percentage(event as object)

'     if  event.getData()<>invalid

'         ?"percentage buffering ";event.getData().percentage
'     else

'     end if
' end sub
sub onStateChange()
    ?"state:"m.Video.state

    ? "videoState"; m.video.state
    ? "completedStreamInfo"; m.video.completedStreamInfo

    if m.video.state = "error"
        print "m.video.errorCode: "; m.video.errorCode
        print "m.video.errorMsg: "; m.video.errorMsg
        print "m.vdeo.errorStr: "; m.video.errorStr
        print "m.vdeo.errorInfo: "; m.video.errorInfo
        print "m.vdeo.completedStreamInfo: "; m.video.completedStreamInfo

        if m.programUpdateTimer.control <> invalid and m.programUpdateTimer.control = "start"
            m.programUpdateTimer.control = "stop"
        end if

        m.streamlyview.visible = false
        m.PosterBackground.visible = false
        ' m.streaminfo.visible = false
        m.video.control = "stop"
        m.ChannelsGroup.visible = true
        m.video.visible = false
        m.video.setFocus(false)
        m.timeGride.setFocus(true)


    end if

    if m.Video.content <> invalid and m.statestToHandle[m.Video.state] <> invalid
        'we have a state that should be fired
        'store these states in map so you won't receive unhandled states or have string case sensitive issues
        state = m.Video.state

        'Convert video node state to proper GA event name
        GoogleEventAction = invalid
        if state = "playing" and m.peviousestate = "paused"
            GoogleEventAction = "resume"
        else if state = "playing"
            GoogleEventAction = "progress"
        else if state = "finished"
            GoogleEventAction = "complete"
        else if state = "paused"
            GoogleEventAction = "pause"
        else if state = "stopped"
            GoogleEventAction = "complete"
        end if

        'Convert video node state to proper Omniture event name
        'you can have more than one parameter here

        GoogleParams = invalid
        if GoogleEventAction <> invalid
            ni = invalid
            if GoogleEventAction = "complete" or GoogleEventAction = "progress"
                ni = "1"
            end if
            GoogleParams = {
                t: "event"
                ec: "video"
                ea: GoogleEventAction
                el: m.video.content.title
                ev: int(m.video.position).tostr()
                ni: ni
            }
        end if

        m.global.RSG_analytics.trackEvent = {
            Google: GoogleParams
        }

        m.peviousestate = state
        if state = "error" or state = "finished"
            m.timer = CreateObject("roSgnode", "Timer")
            m.timer.observeField("fire", "CloseVideoPlayer")
            m.timer.duration = 0.3
            m.timer.control = "start"
        end if
    end if

end sub

sub onPositionChange()
    if m.video.duration > 0 then
        EventAction = "progress"
        'this is logic for seek events, this should be done here not in onkeyevent as it should only be send once per seek
        if m.video.content <> invalid and m.userHasSeeked = true
            m.userHasSeeked = false
            ?"send fast seek event"
            if int(m.video.position) < m.position
                EventAction = "rewind"
                ev = "event28,event72"
            else
                EventAction = "fast forward"
                ev = "event28,event73"
            end if
            m.global.RSG_analytics.trackEvent = {
                Google: {
                    t: "event"
                    ec: "video"
                    ea: EventAction
                    el: m.video.content.title
                    ev: int(m.video.position).tostr()
                }

            }
        end if

        'calculate if it's quartal event
        quarts = m.video.duration / 4
        event = invalid
        if int(m.video.Position) = Int(quarts) then
            event = "event26,event72"
        else if int(m.video.Position) = Int(quarts * 2) then
            event = "event27,event72"
        else if int(m.video.Position) = Int(quarts * 3) then
            event = "event28,event72"
        end if
        if event <> invalid
            ?"CustomVideo: quarts ["quarts"] event="event
            m.global.RSG_analytics.trackEvent = {
                Google: {
                    t: "event"
                    ec: "video"
                    ea: EventAction
                    el: m.video.content.title
                    ev: int(m.video.position).tostr()
                    ni: "1"
                }
            }
        end if
    end if
    'store previous position so you can now if user really skipped video
    m.position = int(m.video.position)
end sub

sub StartProgramUpdating()

    m.programUpdateTimer.control = "stop"

    conncertUpdate(m.timeGrid)


end sub
sub RestartLazyLoadingTimer()
    ? "RestartLazyLoadingTimer"

    m.lazyLoadingTimer.control = "stop"
    m.lazyLoadingTimer.control = "start"

end sub

sub channelFocused(event as object)
    'm.lazyLoadingTimer.control = "start"
    if m.timeGride.content <> invalid
        ? "m.timeGride.content:";m.timeGride.content.getChild(m.timeGride.channelFocused)
        m.PosterchannleIcon.uri = m.timeGride.content.getChild(m.timeGride.channelFocused).HDSMALLICONURL
        ' m.ConsertTimingTExt =
        ' ?"programSelected";m.timeGride.content.getChild(m.timeGride.channelFocused).getChild(m.timeGride.programSelected)

        m.timeGride.content.getChild(m.timeGride.channelFocused).channelfocus = true
    end if
    if m.timeGride.channelFocused <> -1
       
    end if
    if m.channelListData < 8 and event.getRoSGNode().content <> invalid and event.getRoSGNode().content.getChild(event.getData() + 6) = invalid and m.channelListData < m.ChannelData[m.channelNameString].Count()
        if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"
        channelnode = event.getRoSGNode().content
        getChannelDAta(channelnode, m.ChannelData[m.channelNameString][m.channelListData])

        if m.channelListData < 10
            m.timeGride.numRows = m.channelListData + 1
        end if
        m.channelListData += 1
    end if
end sub

sub StartContentLoading()
    ' ? "StartContentLoading; "

    if m.channelListData < m.ChannelData[m.channelNameString].Count() and m.timeGride.content <> invalid


        getChannelDAta(m.timeGride.content, m.ChannelData[m.channelNameString][m.channelListData])
        if m.channelListData < 10
            m.timeGride.numRows = m.channelListData + 1
        end if
        m.channelListData += 1
    else if m.timeGride.content = invalid
        ? "first time start"
        content = CreateObject("roSGNode", "ContentNode")
        m.timeGride.content = content
        getChannelDAta(m.timeGride.content, m.ChannelData[m.channelNameString][m.channelListData])
        m.channelListData += 1
    else if m.channelListData = 0 then
        if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"
    end if
    m.processPoster.visible = false
end sub

sub getChannelDAta(channelnode as object, channelData as object)
    ' ? "getChannelDAta;"
    nodedata = channelnode.createChild("ContentNode")

    if channelData <> invalid
        if channelData.id <> invalid then nodedata.id = channelData.id
        if channelData.callSign <> invalid then nodedata.TITLE = channelData.callSign
        if channelData.logo <> invalid then nodedata.HDSMALLICONURL = channelData.logo
        if channelData.url <> invalid then nodedata.url = channelData.url
        m.TestingTAsk = CreateObject("roSGNode", "TestingTAsk")
        m.TestingTAsk.id = channelnode.getChildCount() - 1
        m.TestingTAsk.content = m.timeGride.content
        m.TestingTAsk.parameters = { "search": channelData.callSign }
        m.TestingTAsk.observeField("programData", "showResponse")
        m.TestingTAsk.control = "RUN"
    else
        ? "getChannelDAta:: channelData is not complete : ;"; channelData
    end if
end sub

sub showResponse(event as object)
    ' ? "testing ::showResponse; ";
    if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "stop" then m.lazyLoadingTimer.control = "start"
    event.getRoSGNode().control = "stop"
end sub

sub reqApiCAll(page)
    m.ChannelEpisodeTask = CreateObject("roSGNode", "ChannelEpisodeTask")
    m.ChannelEpisodeTask.observeField("ChannelData", "ChannelResponseData")
    m.ChannelEpisodeTask.observeField("errorMessage", "errorDialogShow")
    m.ChannelEpisodeTask.control = "RUN"
end sub

function errorDialogShow(event as object)

    if event.getData().responseCode = 401

        m.DialogButton = popupDialog(m.top, "Error ", event.getData().Failure)
        m.DialogButton.observeField("buttonSelected", "loginScreen")
        m.DialogButton.setFocus(true)

    else
        m.DialogButton = popupDialog(m.top, "Error ", event.getData().Failure)
        m.DialogButton.observeField("buttonSelected", "closeScreen1")
        m.DialogButton.setFocus(true)
    end if

end function

function loginScreen()

    ' ShowScreen(CreateObject("roSgNode","LoginScreen"))
    m.top.ExitView = true

end function

function closeScreen1()
    ?"closeScreen"
    m.top.getScene().exitApp = "exitApp"
    closeErrorDialogShow(m.top)

end function

sub closeDialog(event as object)
    ?"closeCustomDialog"
    m.top.removeChild(event.getRosgNode())
    m.top.setFocus(true)

end sub

sub ChannelResponseData()
    ' ?"ChannelResponseData; "m.ChannelEpisodeTask.ChannelData

    m.top.signalBeacon("AppLaunchComplete")
    m.timeGride.setFocus(true)
    m.ChannelData = m.ChannelEpisodeTask.ChannelData



    initGrigData(m.ChannelData)

    m.channelListData = 0
    m.channelNameString = "ALL"
    m.lazyLoadingTimer.control = "start"

    

    m.global.RSG_analytics.trackEvent = {
        google: {
            ec: "EPG_Screen",
            ea: "EPG_launch_complete"
        }
    }
end sub

function playVideo(liveStreamData) as void
    ? "HomeScreen:playVideo: url:";liveStreamData
    m.video.thumbnailImage = ""
    if liveStreamData.channelid <> invalid
        m.ApiCall = CreateObject("roSGNode", "ApiCall")
        m.ApiCall.bodyData = { "channelids": liveStreamData.channelid }
        m.ApiCall.callType = "POST"
        m.ApiCall.url = addRecentUrl()
        m.ApiCall.observeField("apiResponse", "apiResponse")
        m.ApiCall.observeField("errorMessage", "errorDialogShow")
        m.ApiCall.control = "RUN"
    end if

    vidContent = CreateObject("RoSGNode", "ContentNode")
    ' licenseServerURL:"https://widevine-proxy.appspot.com/proxy"
    ' licenseServerURL:"https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=Playready"

    drmParams = {
        keySystem: "Playready"
        licenseServerURL: "https://streamlytv.com/api/generic?drm-type=Playready"
        ' licenseServerURL:"https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=Playready"

    }

    vidContent.streamformat = "smooth"
    vidContent.drmParams = drmParams
    ' vidContent.HttpHeaders=["customdata", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTA5LTAzIDA3OjUxOjIyLjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMDktMDMgMDg6MDY6MjIuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+MTIzM2Q0NmRlZTU0NzlhMzU4NDQzZjIwN2Q5NDIzNGE8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+YUxocTlsQkZxMmYyOW9ncUlhM3VZbFF5RnR5TGRid0R0eXFyN2dvY09jWFl6cFdMYXJqM3FKZm9ZdUluRFR1VGZUYmhkdXhhNlN0bXBpb0l4QWttT00xSzBudUZ1cFBTa1ZONjhiOVVacXlnYUswK3FiMzJnOXV1RmdLVWFoN3lLU1JIeGYxbHNqaU5UNTJDQmNWampFdHZ1aXBXT3FGOVF4MVJKWTRMRjhZK1AyQS9lZDNyblF0TFhDUmJuM1N6My9TK0VNWTJNU2lSMmd6Wm9ONWUzRXdQVjhreHRzUlQ4d3dJQ3dYc3pxMDJweGRpSmp5ZEtCTmlNcXZMcjBqcGlkY3JZU2VxVmVKL2l1N3hadlhLMFhaYnZuUWJXVXVDaWNtNDBSdFpoaXN5bDhla0loN3hmcVdQU252Mm4wNWlEQUs4SFhqQWZhdnB5Q0RuTU9VaGFnPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="]
    ' vidContent.url = "https://cdn.streamlytv.com/streamly/ngrp:AETVP.stream_all/manifest.mpd"
    ?"liveStreamData.url";liveStreamData.url
    vidContent.url = liveStreamData.url
    ' vidContent.url = "https://cdn.streamlytv.com/streamly/ngrp:AETVP.stream_all/Manifest"
    ' vidContent.url = "https://test.playready.microsoft.com/smoothstreaming/SSWSS720H264/SuperSpeedway_720.ism/manifest"


    ' httpAgent = CreateObject("roHttpAgent")
    ' httpAgent.SetCertificatesFile("common:/certs/ca-bundle.crt")
    ' httpAgent.InitClientCertificates()
    ' httpAgent.EnableCookies()
    ' httpAgent.AddHeader("X-Roku-Reserved-Dev-Id", "")
    ' httpAgent.AddHeader("Accept", "text/xml")
    ' ' headers = {

    ' '     "customdata": drmKey
    ' '     ' "x-keyos-authorization": drmKey
    ' '     ' "customdata":"PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTA5LTA5IDExOjU5OjE1LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMDktMDkgMTI6MTQ6MTUuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+YjNmOGU3NTBiYzIwY2VmOGEyOTJkNmE4NDE4ZDMwYTE8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+Y0lyaXhMMUk5NW51UWQ4V2NwUWZmVWFNNGVBWEJQajFIS3I4WENtNkh2bDM0eWNIL0M5Y1RXSy9DaEVKQlJ4Um5XOUJhREsrZGgxZ0VGN1hMYVZRSERETmp0NklKYVJvNjduUzJjN244QjVINmlwUWRpd3E4dS8wM2tCVjRydG9EQlJWTndXUXdQbm91Wm95a1JETzdiK0hjU0tRaUhwZkQySFlKWHo0alBRb0pEb1J4VUFQNm9FYVJDUW0yNzJZUzlNaFZkYURDSGgyaW81enMwY3RsdEhkb0xqTlBqZEZvMVIyV0t1R0lmNktuK0FSVzZXeHgrbTNySzJiMlFPOCtRSG1Xb0FzTFMxcU9yMDRTZjVKdXFIMERIaUgwSlFKbkpaOTBlTUptYU9QZ052RDBRcDdDMWh1MFlqTU5mTVgrbzRyMUFyeHVCMmhOWVVyQ0tQb1hnPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="
    ' ' }

    ' ' httpAgent.setheaders(headers)
    ' ' m.video.setHttpAgent(httpAgent)
    ' m.video.drmHttpAgent=httpAgent



    '   m.video.Live=true
    m.searchPoster.visible = true
    m.video.enableUI = false

    ' ?"drminfo:";getDeviceInfo(). GetDrmInfoEx()
    ' ?"drminfo:";getDeviceInfo(). GetDrmInfoEx().Widevine
    '     m.video.errorMsg: The connection timed out.
    ' m.video.errorStr: reader pick stream error:timed out:Transfer error: Timeout was reached, response code:(0):extra:clip_id:0:source:?

    ' vidContent.encodingType = "PlayReadyLicenseAcquisitionAndChallenge"
    ' vidContent.licenseServerURL = "https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx"
    ' vidContent.streamFormat = "smooth"
    ' vidContent.KeySystem="playready"

    vidContent.live = true
    ' vidContent.appData ="PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTA5LTAzIDA3OjUxOjIyLjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMDktMDMgMDg6MDY6MjIuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+MTIzM2Q0NmRlZTU0NzlhMzU4NDQzZjIwN2Q5NDIzNGE8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+YUxocTlsQkZxMmYyOW9ncUlhM3VZbFF5RnR5TGRid0R0eXFyN2dvY09jWFl6cFdMYXJqM3FKZm9ZdUluRFR1VGZUYmhkdXhhNlN0bXBpb0l4QWttT00xSzBudUZ1cFBTa1ZONjhiOVVacXlnYUswK3FiMzJnOXV1RmdLVWFoN3lLU1JIeGYxbHNqaU5UNTJDQmNWampFdHZ1aXBXT3FGOVF4MVJKWTRMRjhZK1AyQS9lZDNyblF0TFhDUmJuM1N6My9TK0VNWTJNU2lSMmd6Wm9ONWUzRXdQVjhreHRzUlQ4d3dJQ3dYc3pxMDJweGRpSmp5ZEtCTmlNcXZMcjBqcGlkY3JZU2VxVmVKL2l1N3hadlhLMFhaYnZuUWJXVXVDaWNtNDBSdFpoaXN5bDhla0loN3hmcVdQU252Mm4wNWlEQUs4SFhqQWZhdnB5Q0RuTU9VaGFnPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="

    ' vidContent.url = "https://channelstore.streamlytv.com/streamly/AETVP.stream_240p/Manifest"
    m.video.content = vidContent
    m.video.setFocus(true)

    m.video.control = "play"
end function


' sub videoState()
'     ? "videoState"; m.video.state
'     ? "completedStreamInfo"; m.video.completedStreamInfo


'     '  if m.video.state="playing"
'     '     m.video.control="pause"
'     '      m.video.mute = false
'     if m.video.state = "error"
'         print "m.video.errorCode: "; m.video.errorCode
'         print "m.video.errorMsg: "; m.video.errorMsg
'         print "m.vdeo.errorStr: "; m.video.errorStr

'         m.video.control = "stop"
'         m.ChannelsGroup.visible = true
'         m.video.visible = true
'         m.video.setFocus(false)
'         m.timeGride.setFocus(true)


'     end if
' end sub

sub initGrigData(channels as object)
    ? "m.ChannelCategoryMarkuGrid:init()"

    marupContent = CreateObject("roSGNode", "ContentNode")
    marupContent.createChild("ContentNode").TITLE = "ALL"
    marupContent.createChild("ContentNode").TITLE = "RECENT"
    for each channel in channels.Keys()
        if channel <> "ALL" and channel <> "RECENT"
            marupContent.createChild("ContentNode").TITLE = channel
        end if
    end for
    ' marupContent.createChild("ContentNode").TITLE = "ALL"
    ' marupContent.createChild("ContentNode").TITLE = "RECENT"
    ' marupContent.createChild("ContentNode").TITLE = "MY CHANNELS"
    ' marupContent.createChild("ContentNode").TITLE = "SPORTS"
    ' marupContent.createChild("ContentNode").TITLE = "NEWS"
    ' marupContent.createChild("ContentNode").TITLE = "MOVIES"
    ' marupContent.createChild("ContentNode").TITLE = "KIDS"

    m.ChannelCategoryMarkuGrid.content = marupContent
    m.ChannelCategoryMarkuGrid.observeField("itemSelected", "categorySelcted")
    m.ChannelCategoryMarkuGrid.setFocus(false)
end sub
sub categorySelcted(event as object)
    ? "categorySelcted: "; event.getRoSGNode().content.getChild(event.getData())

    '    if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"


    ?" m.ChannelData[event.getRoSGNode().content.getChild(event.getData()).title]:"; m.ChannelData[event.getRoSGNode().content.getChild(event.getData()).title]
    if m.ChannelData[event.getRoSGNode().content.getChild(event.getData()).title] <> invalid

        m.channelNameString = event.getRoSGNode().content.getChild(event.getData()).title
        m.timeGride.content = CreateObject("roSGNode", "ContentNode")
        m.channelListData = 0
        m.ChannelCategoryMarkuGrid.setFocus(false)
        m.timeGride.setFocus(true)
        m.lazyLoadingTimer.control = "start"
    end if

end sub

function programSelected(event as object) as object
    timeGrid = event.GetRoSGNode()

    playVideo(timeGrid.content.getChild(timeGrid.channelSelected))

    conncertUpdate(timeGrid)

    m.ChannelsGroup.visible = false
    m.video.visible = true
    m.video.setFocus(true)

    ' m.drmKeyApiCall = CreateObject("roSGNode", "ApiCall")
    ' m.drmKeyApiCall.bodyData = {}
    ' m.drmKeyApiCall.callType = "GET"
    ' m.drmKeyApiCall.url = drmkeyUrl()
    ' m.drmKeyApiCall.observeField("apiResponse", "apiResponse")
    ' m.drmKeyApiCall.observeField("errorMessage", "errorDialogShow")
    ' m.drmKeyApiCall.control = "RUN"

    ' m.ApiCall = CreateObject("roSGNode", "ApiCall")
    ' m.ApiCall.bodyData = { "channelids": liveStreamData.channelid }
    ' m.ApiCall.url = addRecentUrl()
    ' m.ApiCall.observeField("apiResponse", "apiResponse")
    ' m.ApiCall.control = "RUN"

    return true
end function
sub conncertUpdate(timeGrid as object)
    m.programIndex += timeGrid.programSelected

    if m.programUpdateTimer.control <> invalid and m.programUpdateTimer.control = "start"
        m.programUpdateTimer.control = "stop"
    end if

    m.currentProgramsNode = timeGrid.content.getChild(timeGrid.channelSelected)
    programnode = m.currentProgramsNode.getChild(m.programIndex)
    programdate = CreateObject("roDateTime")
    programdate.FromSeconds(programnode.PLAYSTART)
    durationTimetext = programdate.toISOString().Mid(11, 5) + " - "
    programdate.FromSeconds(programnode.PLAYSTART + programnode.PLAYDURATION)
    durationTimetext += programdate.toISOString().Mid(11, 5)
    m.ConsertTimingTExt.text = durationTimetext
    m.ConcertChannel.text = m.currentProgramsNode.TITLE
    m.ConcertName.text = programnode.TITLE

    m.programUpdateTimer.duration = programnode.PLAYDURATION
    m.programUpdateTimer.control = "start"

end sub
' sub apiResponse(event as object)
'     ? "m.ApiCall.apiResponse;"; event.getData()
'     ?"event.getDagetRoSGNode()ta().url ";event.getRoSGNode().url
'     if (event.getData() <> invalid and event.getRoSGNode().url = addRecentUrl() and event.getData().data <> invalid)
'         m.ChannelData["RECENT"] = event.getData().data

'     else if (event.getData() <> invalid and event.getRoSGNode().url = drmkeyUrl() and event.getData().data <> invalid)

'         playVideo(m.liveStreamData, event.getData().data)
'     end if

'     ' m.ApiCall.RECENTResponse
'     event.getRoSGNode().control = "stop"
' end sub

sub VideoControl(event as object)
    video = event.getRoSGNode()
    ? "VideoControl: "; video
    ? "control: "; video.control
end sub

sub onPofileFinished()
    ? "onPofileFinished";
end sub

sub closeSearchScreen(event as object)
    ? "closeSearchScreen; "
    searchScreen = event.getRoSGNode()
    exitView = searchScreen.exitView

    if exitView
        m.top.removeChild(searchScreen)
        m.searchPoster.setFocus(true)
        m.ChannelCategoryMarkuGrid.setFocus(false)
        m.focusPoster.uri = "pkg:/images/home/categiryNavFocusIcon.png"
    end if
end sub
sub closeProfileScreen(event as object)
    ? "closeProfileScreen; "
    searchScreen = event.getRoSGNode()
    exitView = searchScreen.exitView

    if exitView
        m.top.removeChild(searchScreen)
        m.searchPoster.setFocus(false)
        m.ChannelCategoryMarkuGrid.setFocus(false)
        m.timeGride.setFocus(true)
        m.focusPoster.uri = ""
    end if
end sub
sub showLoginPage(event as object)
    ? "showLoginPage"
    searchScreen = event.getRoSGNode()
    m.top.removeChild(searchScreen)
    m.top.ExitView = true
end sub

sub playSearchChannelUrl(event as object)
    ? "playSearchChannelUrl:"; event.getData()

    searchScreen = event.getRoSGNode()
    m.top.removeChild(searchScreen)

    m.ChannelsGroup.visible = false
    m.video.visible = true
    '    playVideo(searchScreen.playChannel,channelid)
end sub

function onKeyEvent(key as string, press as boolean) as boolean
    ? "HomeScreen::key: "; key
    ? "HomeScreen::press: "; press
    ? " m.video.hasFocus() "; m.video.hasFocus()

    result = false

    if press
        if m.searchPoster.hasFocus() and key = "down"
            m.searchPoster.setFocus(false)
            m.focusPoster.uri = ""
            m.timeGride.setFocus(true)
        else if m.searchPoster.hasFocus() and key = "OK"
            m.searchPoster.setFocus(false)
            m.focusPoster.uri = ""
            SearchScreen = m.top.createChild("SearchScreen")
            SearchScreen.id = "SearchScreen"
            SearchScreen.observeField("exitView", "closeSearchScreen")
            SearchScreen.observeField("playChannel", "playSearchChannelUrl")
            SearchScreen.setFocus(true)
        else if m.ChannelCategoryMarkuGrid.hasFocus() and key = "left"
            m.searchPoster.setFocus(true)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.focusPoster.uri = "pkg:/images/home/categiryNavFocusIcon.png"
        else if m.searchPoster.hasFocus() and key = "right"
            m.ChannelCategoryMarkuGrid.setFocus(true)
            m.searchPoster.setFocus(false)
            m.focusPoster.uri = ""
        else if m.timeGride.hasFocus() and key = "up"
            m.timeGride.setFocus(false)
            m.ChannelCategoryMarkuGrid.setFocus(true)
            m.video.setFocus(false)
            result = true
        else if m.ChannelCategoryMarkuGrid.hasFocus() and key = "down"
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.video.setFocus(false)
            m.timeGride.setFocus(true)
            result = true
        else if (m.video.hasFocus() and key = "back")

            if m.programUpdateTimer.control <> invalid and m.programUpdateTimer.control = "start"
                m.programUpdateTimer.control = "stop"
            end if

            m.streamlyview.visible = false
            m.PosterBackground.visible = false
            ' m.streaminfo.visible = false
            m.video.control = "stop"
            m.ChannelsGroup.visible = true
            m.video.visible = false
            m.video.setFocus(false)
            m.timeGride.setFocus(true)
            return true

        else if m.video.hasFocus() and key = "OK"

            ?"ok pressed fr play video"

            if m.streamlyview.visible = false
                m.streamlyview.visible = true
                m.PosterBackground.visible = true
                ' m.streaminfo.visible = true
                m.streaminfoTimer.control = "start"
            end if

            '   playVideo("https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8")

            result = true
        else if (m.timeGride.hasFocus() = true or m.searchPoster.hasFocus() or m.ChannelCategoryMarkuGrid.hasFocus()) and key = "options"

            ? "HomeSceen:open profile setting view";
            m.searchPoster.setFocus(false)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.focusPoster.uri = ""
            m.timeGride.setFocus(false)

            ProfileSettingScreen = m.top.createChild("ProfileSettingScreen")
            ProfileSettingScreen.id = "ProfileSettingScreen"
            ProfileSettingScreen.observeField("exitView", "closeProfileScreen")
            ProfileSettingScreen.observeField("logout", "showLoginPage")

            result = true
        end if
        return result
    end if

    return result
end function