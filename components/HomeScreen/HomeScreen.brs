sub init()
    ? "Home init()"
     
    
    'show loading
    m.processPoster = m.top.createChild("Poster")
'    deviceinfo = getDeviceInfo()
'    ?"deviceinfo:"; deviceinfo.getDisplaySize().w 
    m.processPoster.height = 1080
    m.processPoster.width = 1920
    m.processPoster.uri = "pkg:/images/SplashHD.png"

    m.ChannelsGroup = m.top.findNode("ChannelsGroup")
    m.timeGride = m.top.findNode("timeGrid")
    
     
    m.timeGride.numRows = 0
'    m.video = m.top.findNode("video")
    m.video = m.top.findNode("myVideo")
    m.video.observeField("state","videoState")
    
    m.video.enableUI = false
    m.video.visible = false
    m.ChannelsGroup.visible = true
    m.video.thumbnailImage = "pkg:/images/home/testingThumbnail.jpg"
'    m.channelGuide = m.top.findNode("channelGuide")
'    m.pressbackLabel = m.top.findNode("pressbackLabel")
    m.Settings = m.top.findNode("Settings")
    m.channelBackgroundposter = m.top.findNode("channelBackgroundposter")

    m.searchPoster = m.top.findNode("searchPoster")
    m.searchPoster.translation = [60, 50]
    m.searchPoster.uri = "pkg:/images/home/searchicon_100.png"
    m.searchPoster.height = 50
    m.searchPoster.width = 50
    m.searchPoster.visible = true
    m.focusPoster = m.searchPoster.createChild("Poster")
    m.focusPoster.id = "focusPoster"
    m.focusPoster.translation = [- 10, - 10]
    m.focusPoster.uri = ""
    m.focusPoster.height = 60
    m.focusPoster.width = 75

'    m.searchPoster.visible =false

    m.deviceinfo = CreateObject("roDeviceInfo")

    if m.deviceinfo.getDisplaySize().w = 1920
'        m.PosterBackground.uri = "pkg:/images/background_FHD.jpg"     
        m.channelBackgroundposter.uri = "pkg:/images/homeScreenBckgrnd.png"
    else if m.deviceinfo.getDisplaySize().w = 1280
        m.channelBackgroundposter.uri = "pkg:/images/home/homeScreenBckgrndhd.png"
    else
        m.channelBackgroundposter.uri = "pkg:/images/home/homeScreenBckgrndhd.png"
    end if

    m.ChannelCategoryMarkuGrid = m.top.findNode("ChannelCategoryMarkuGrid")

    ' channel ,episode api call ' 
      
    reqApiCAll({ "page": 1 })

    font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
'    m.channelGuide.font = font 
'    m.pressbackLabel.font = font
    m.Settings.font = font

    currentTime =  CreateObject("roDateTime")
    currentTime.Mark()
    currentTime.ToLocalTime()

    ? "currentTime1.ToISOString(): "currentTime.ToISOString()
    ? "LocalTIme : currentTime1.ToISOString(): "currentTime.ToISOString()

    t = currentTime.AsSeconds()
    t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
    m.timeGride.contentStartTime = t
    m.timeGride.leftEdgeTargetTime = currentTime.AsSeconds()

    m.timeGride.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.timeGride.channelInfoComponentName = "ChannelItemComponent"

    m.timeGride.observeField("programSelected", "programSelected")
    m.timeGride.observeField("channelFocused", "channelFocused")

    m.timeGride.programHorizMargin = 24
'    m.timeGride.programBackgroundBitmapUri = "pkg:/images/programUnfocusBOrder.png"
    m.timeGride.programBackgroundBitmapUri = "pkg:/images/home/borderwhite1.png"
    m.timeGride.overlayBitmapUri = ""
    m.timeGride.channelInfoBackgroundBitmapUri = ""
    m.timeGride.timeBarBitmapUri = "pkg:/images/transparentimg.png"
    m.timeGride.programTitleFocusedColor = "#ffffff"
    m.timeGride.showPastTimeScreen = true

    m.timeGride.font=getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.timeGride.channelNoDataText = "Loading..."
    m.timeGride.loadingDataText = "Loading..."
    m.timeGride.automaticLoadingDataFeedback = true

    m.timeGride.numRows = 0
    m.timeGride.fillProgramGaps = true
    m.timeGride.translation = [0.0, 350.0]
    m.timeGride.vertFocusAnimationStyle = "fixedFocus"

    m.top.observeField("visible", "setFocus")

    m.timeGride.visible = true

    ' prgrams Api call timer
    m.lazyLoadingTimer = CreateObject("roSGNode", "Timer")
    m.lazyLoadingTimer.repeat = false
    m.lazyLoadingTimer.duration = .3
    m.lazyLoadingTimer.observeField("fire", "StartContentLoading")
'    m.lazyLoadingTimer.control = "start"
'    m.ChannelData = createObject("roArray")

    ' prgramView Update Timer

'    m.programUpdateTimer = CreateObject("roSGNode", "Timer")
'    m.programUpdateTimer.repeat = false

'    m.programUpdateTimer.observeField("fire", "StartProgramUpdating")    

    PosterBackground = m.top.createChild("Poster")
    PosterBackground.translation = [1411, 716]

    PosterBackground.height = 236
    PosterBackground.width = 469
    PosterBackground.uri = "pkg:/images/home/channelinfobackgrnd.png"
    PosterBackground.opacity = .8
    PosterBackground.visible = false
'   LayoutGroup = PosterBackground.createChild("LayoutGroup")
'   LayoutGroup.layoutDirection = "horiz"
'   LayoutGroup.itemSpacings ="10" 

    PosterchannleIcon = PosterBackground.createChild("Poster")
    PosterchannleIcon.translation = [26, 98]
    PosterchannleIcon.height = 94
    PosterchannleIcon.width = 138
    PosterchannleIcon.uri = "pkg:/images/mtv.png"

    PosterchannleIcon = PosterBackground.createChild("Poster")
    PosterchannleIcon.translation = [189, 22]
    PosterchannleIcon.height = 20
    PosterchannleIcon.width = 20
    PosterchannleIcon.uri = "pkg:/images/home/liveIcon.png"
    PosterchannleIconLabel = PosterchannleIcon.createChild("Label")

    PosterchannleIconLabel.text = "Live"
    PosterchannleIconLabel.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
    PosterchannleIconLabel.translation = [30, 0]

    ConsertTiming = PosterBackground.createChild("Rectangle")
    ConsertTiming.translation = [189, 55]
    ConsertTiming.height = 30
    ConsertTiming.width = 163
    ConsertTiming.color = "#004E92"
    ConsertTimingTExt = ConsertTiming.createChild("Label")
    ConsertTimingTExt.text = "04:00 - 05:00PM"
    ConsertTimingTExt.translation = [8, 4]
    ConsertTimingTExt.font = getFont(18, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")

    ConcertChannel = PosterBackground.createChild("Label")
    ConcertChannel.translation = [189, 103]
    ConcertChannel.height = 41
    ConcertChannel.width = 0
'   ConcertChannel.wrap =true
    ConcertChannel.text = "MTV-USA"
    ConcertChannel.font = getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")

    ConcertName = PosterBackground.createChild("Label")
    ConcertName.translation = [189, 162]
    ConcertName.height = 30
    ConcertName.width = 255
    ConcertName.text = "Justin Beiber concert"
    ConcertName.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
    m.channelListData =0
'   LayutRght.layoutDirection = "vert"

'   LayutRght.itemSpacings ="10"
'   LayutRght.createChild("Label")
'   LayutRght.translation=[217,21]
    m.AllChannelContent = CreateObject("roSGNode", "ContentNode")
    
    ' mybutton.horiz
end sub

' sub StartProgramUpdating()
' ?"StartProgramUpdating"

'  m.programIndex = 0
' currentProgram = m.currentProgramsNode.getChild(m.programIndex)
' ?"currentProgram title ;";currentProgram.title
' ?"currentProgram start ;";currentProgram.PLAYSTART
' ?"currentProgram end ;";currentProgram["PLAYDURATION"] ;" sec"

' m.programDuration = currentProgram["PLAYDURATION"]

' m.programUpdateTimer.control = "stop" 
' m.programUpdateTimer.duration = m.programDuration
' m.programUpdateTimer.control = "start"
' m.programIndex+=1

' end sub
sub RestartLazyLoadingTimer()
    ? "RestartLazyLoadingTimer"
    m.lazyLoadingTimer.control = "stop"
    m.lazyLoadingTimer.control = "start"
end sub

sub channelFocused(event as Object)
    ? "channelFocused:"; event.getData()
'm.lazyLoadingTimer.control = "start"
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
    ? "StartContentLoading; "
    
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
        m.channelListData +=1
    else if m.channelListData = 0 then
        if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"
    end if
    m.processPoster.visible = false
end sub

sub getChannelDAta(channelnode as Object, channelData as Object)
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

sub showResponse(event as Object)
    ? "testing ::showResponse; ";
    if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "stop" then m.lazyLoadingTimer.control = "start"
    event.getRoSGNode().control = "stop"
end sub

sub reqApiCAll(page)
    m.ChannelEpisodeTask = CreateObject("roSGNode", "ChannelEpisodeTask")
    m.ChannelEpisodeTask.observeField("ChannelData", "ChannelResponseData")
    m.ChannelEpisodeTask.control = "RUN" 
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
    
'    this = {}
'    this.epgContent
'    this.channelIndexUpdate
'    this.categorySring
end sub

function playVideo(url as String, channelid as String) as Void
    ? "HomeScreen:playVideo: "

    if channelid <> invalid
        m.recentTask = CreateObject("roSGNode", "recentTask")
        m.recentTask.data = { "channelids": channelid }
        m.recentTask.observeField("recentResponse", "recentResponseUpdate")
        m.recentTask.control = "RUN"
    end if
    vidContent = CreateObject("RoSGNode", "ContentNode")
' contentNode = createObject("roSGNode", "contentNode")
' vidContent.streamFormat = "smooth"
' ' contentNode.url = "wwww.myvideo.com/content.ism"
' vidContent.encodingType = "encode:base64"
' vidContent.encodingKey = "https://pr-keyos.licensekeyserver.com/core/rightsmanager.asmx" + "%%%" + "PEtleU9TQXV0aGVudGljYXRpb25YTUw+CjxEYXRhPgogIDxHZW5lcmF0aW9uVGltZT4yMDIwLTA4LTE0IDA3OjEzOjIwLjE2MzwvR2VuZXJhdGlvblRpbWU+CiAgPEV4cGlyYXRpb25UaW1lPjIwMjAtMDktMTMgMDc6MTM6MjAuMTY0PC9FeHBpcmF0aW9uVGltZT4KICA8VW5pcXVlSWQ+ODUwN2RmMzQyNDZjMzY2M2I4NTA5YWZmY2RkNzlkYzk8L1VuaXF1ZUlkPgogIDxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+CiAgPFdpZGV2aW5lUG9saWN5IGZsX0NhblBsYXk9InRydWUiIGZsX0NhblBlcnNpc3Q9ImZhbHNlIiAvPgogIDxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPgogICAgPFNlY3VyaXR5TGV2ZWw+MTwvU2VjdXJpdHlMZXZlbD4KICA8L1dpZGV2aW5lQ29udGVudEtleVNwZWM+CiAgPEZhaXJQbGF5UG9saWN5IHBlcnNpc3RlbnQ9ImZhbHNlIiAvPgogIDxMaWNlbnNlIHR5cGU9InNpbXBsZSIgLz4KPC9EYXRhPgo8U2lnbmF0dXJlPm96dU5MOEtBV0d6eE5hRjBGVXhKMkN0WVBadk5WWTR1V2MyYkxxejUxc3dpTlQwcG52TjFTb0ZNcTJudWVuSnhZbXBzbnl2L1VBVHlrUzFkenU2RDVwZFNWR1RKRml5YmhucEE3bjdVMmhoMUpvTnM2b0FaQWFnazNJbzlxdS96VmNIWXFrdFp5Y2hidGxNdGo4Zm5OWEJWRktWNndZOVdPVWMyRnU3RG9sU1crbXBXd0xNWDBlT1VRYkY1aHEzTkQ2anh0V29xQmgwTWJYbmxydWo5b0ptWk1SUEdseTl4RHNQZjdmTGdvTjMydkVBUXN4NUlueXgzdGk1ZTA2aFlXM0NsYSs0NFM1aTY5VTZRM3JYV1djNHNvZHpXeERwN1NJNkc0K0FaMkV3ZGtVaW5ZM25zMTdrYkpLWmhWb0d5OC9LWGxpcjB2T0syaUprNWVTN0p1dz09PC9TaWduYXR1cmU+CjwvS2V5T1NBdXRoZW50aWNhdGlvblhNTD4=>"

    drmParams = {
        keySystem: "Widevine"
        licenseServerURL: "https://wv-keyos.licensekeyserver.com/" 
    }
    vidContent.url = "https://channelstore.streamlytv.com/streamly/AETVP.stream/manifest.mpd"
    vidContent.StreamFormat = "dash"
    vidContent.drmParams = drmParams

    httpAgent = CreateObject("roHttpAgent")  
    httpAgent.SetCertificatesFile("common:/certs/ca-bundle.crt")
    httpAgent.InitClientCertificates()
    httpAgent.EnableCookies()
    ' httpAgent.AddHeader("X-Roku-Reserved-Dev-Id", "")
    httpAgent.AddHeader("customdata", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTA4LTE3IDE0OjE0OjI5LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMDgtMTcgMTQ6MTY6MjkuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+MzNjY2M1Y2E3YzJhODJlZmNhNDUzMDQxODk4OWM1Yzg8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+WElkR0dySm1uc0lKU2x1UHZ5WEliOW5XTlphWnRHaE1Nc0xRc2lOWkY5dHZBeXB5K044dWpMUHhuUFpMUUlGZng5OCs0Ynl3L21SUEw4dlZsYWNtSmtDT2o4S2dualozNlZ3L3BIeGUyOUVEaitRS1NLRGdnSXAxSkNKbXZ2cyt0WC9ucWM4dDc1bmNwaVFsTlU3eTdZc3lRMXQreXRVaFNjdmNBaDA0Y3NEM0RlSk16dFd2QkxvZDlTMkY1ZGkrRDlCWWR1OUhmeE1IUnVCb0ppTnhVWmowVVNVc0pBdkFYOFl1SnM5cHJDNzN3ejVVUndYQjFBeEt3UnpkQkhUVkt5bm0reGlVUUZTSVorbHVQKzVBeVVhM0kxdkhjMEdIL0EvTWU0VDdpUWtUeG9CanlybnlZbFVDSFZUbks0clpwVXhjbmRkZGJDVytGOVNHVVI0SXFBPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg==")
    m.video.setHttpAgent(httpAgent)
    ' m.video.drmHttpAgent=httpAgent
    ' m.video.Live=true    
    ' m.video.content = vidContent
    m.searchPoster.visible =false
    m.video.enableUI = false
    m.video.content = vidContent
    m.video.setFocus(true)

    m.video.control = "play"
    m.video.thumbnailImage = ""
    
end function

sub videoState()
    ? "videoState"; m.video.state
    ? "completedStreamInfo"; m.video.completedStreamInfo
    ? "videoFormat"; m.video.completedStreamInfo

'  if m.video.state="playing"
'     m.video.control="pause"
'      m.video.mute = false
 if  m.video.state="error"
     Print "m.video.errorCode: "; m.video.errorCode
     Print "m.video.errorMsg: "; m.video.errorMsg
     Print "m.video.errorStr: "; m.video.errorStr

 end if
end sub

sub initGrigData(channels as Object)
    ? "m.ChannelCategoryMarkuGrid:init()"

    marupContent = CreateObject("roSGNode", "ContentNode")
    marupContent.createChild("ContentNode").TITLE = "ALL"
    marupContent.createChild("ContentNode").TITLE = "Recent"
    for each channel in channels.Keys()
        if channel <> "ALL" and channel <> "Recent"
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
sub categorySelcted(event as Object)
    ? "categorySelcted: "; event.getRoSGNode().content.getChild(event.getData())
    
'    if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"
    
   
    ?" m.ChannelData[event.getRoSGNode().content.getChild(event.getData()).title]:"; m.ChannelData[event.getRoSGNode().content.getChild(event.getData()).title]
    if m.ChannelData[event.getRoSGNode().content.getChild(event.getData()).title] <>invalid

        m.channelNameString = event.getRoSGNode().content.getChild(event.getData()).title
        m.timeGride.content = CreateObject("roSGNode", "ContentNode")
        m.channelListData = 0
        m.ChannelCategoryMarkuGrid.setFocus(false)
        m.timeGride.setFocus(true)
        m.lazyLoadingTimer.control = "start"    
    end if
    
end sub

function programSelected(event as Object) as Object
    timeGrid = event.GetRoSGNode()

' channelNode = timeGrid.content.getChild(timeGrid.channelSelected)
' m.currentProgramsNode =  channelNode

' m.programIndex = 0

' currentProgram = m.currentProgramsNode.getChild(m.programIndex)

' currentTime1 =  CreateObject("roDateTime")
'    t = currentTime1.AsSeconds()
' ?"currentProgram t ;";currentTime1.ToISOString() ;" sec"

' currentTime1.FromSeconds(currentProgram.PLAYSTART)
' ?"currentProgram PLAYSTART ;";currentTime1.ToISOString()

' ?"currentProgram title ;";currentProgram.title
' ?"currentProgram end ;";currentProgram.PLAYDURATION ;" sec"

' currentEpisodeDuration =  (   (currentProgram.PLAYSTART) +currentProgram.PLAYDURATION* 1000 - ( t  )  ) 

'    if  t > (currentProgram.PLAYSTART/1000)
'       m.programDuration = (t- (currentProgram.PLAYSTART/1000))   
'    else
'       m.programDuration = ((currentProgram.PLAYSTART/1000) -t)
'    end if
'  m.programDuration = (currentEpisodeDuration/1000)

' ?"m.programDuration exact ;";m.programDuration
' m.programDuration = currentProgram.PLAYDURATION

' if m.programUpdateTimer <> invalid and m.programUpdateTimer.control = "start" then m.programUpdateTimer.control = "stop"

' m.programUpdateTimer.duration = m.programDuration
' m.programUpdateTimer.control = "start"
' m.programIndex+=1

    data = timeGrid.content.getChild(timeGrid.channelSelected)

    ? ":programSelecte: data: "; data

    url = data.url

    m.ChannelsGroup.visible = false
    m.video.visible = true
    m.video.setFocus(true)
    playVideo(url, data.id)

    return true
end function

sub recentResponseUpdate()
    ? "m.recentTask.recentResponse;"; m.recentTask.recentResponse

    if (m.recentTask.recentResponse <> invalid and m.recentTask.recentResponse.data <> invalid)
        m.ChannelData["Recent"] = m.recentTask.recentResponse.data
    end if

' m.recentTask.recentResponse
    m.recentTask.control = "stop"
end sub

sub VideoControl(event as Object)
    video = event.getRoSGNode()
    ? "VideoControl: "; video
    ? "control: "; video.control
end sub

sub onPofileFinished()
    ? "onPofileFinished";
end sub

sub closeSearchScreen(event as Object)
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
sub closeProfileScreen(event as Object)
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
sub showLoginPage(event as Object)
    ? "showLoginPage"
    searchScreen = event.getRoSGNode()
    m.top.removeChild(searchScreen)
    m.top.ExitView = true
end sub

sub playSearchChannelUrl(event as Object)
    ? "playSearchChannelUrl:"; event.getData()

    searchScreen = event.getRoSGNode()
    m.top.removeChild(searchScreen)

    m.ChannelsGroup.visible = false
    m.video.visible = true
'    playVideo(searchScreen.playChannel,channelid)
end sub

function onKeyEvent(key as String, press as Boolean) as Boolean
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
            m.video.control = "stop"
            m.ChannelsGroup.visible = true
            m.video.visible = true
            m.video.setFocus(false)
            m.timeGride.setFocus(true)
            return true

'        else if m.ChannelsGroup.visible=true and key="back"

'          m.ChannelsGroup.visible = false
'          m.timeGride.setFocus(false)
'          m.ChannelCategoryMarkuGrid.setFocus(false)

'          m.video.visible=true
'          m.video.setFocus(true)
'          m.searchPoster.visible =false
'          result = true        
'        else if m.video.visible=true and key="options" and  m.video.hasFocus() and m.video.state<> "buffering" and m.video.state<> "stopped"

'          ?"HomeSceen:open channel view";
'          m.searchPoster.visible = true
'          m.video.visible=false 
'          m.ChannelsGroup.visible = true
'          m.video.setFocus(false) 
'          m.ChannelCategoryMarkuGrid.setFocus(false)         
'          m.timeGride.setFocus(true)

'          result = true 
'        else if m.video.hasFocus()  and (key="play" or key="OK")

'           ?"ok pressed fr play video"

'           playVideo("https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8") 

'          result = true 
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