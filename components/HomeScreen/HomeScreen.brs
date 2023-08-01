sub init()
    m.conditionCount = 0
    ? "Home init()"
    m.duration = 0
    m.time = 0
    m.channels = []
    m.channelsId = []
    di = CreateObject("roDeviceInfo")

    ' Delete all Channel selected value
    delete_channelSelectedValue()

    m.button_focus = m.top.findNode("button_focus")
    m.deviceText = m.top.findNode("deviceText")
    m.deviceView = m.top.findNode("deviceView")
    m.Close = m.top.findNode("Close")
    m.Tryagain = m.top.findNode("Tryagain")
    m.Tryagain.observeField("buttonSelected", "Try_again")
    m.Close.observeField("buttonSelected", "closeTryAgain")

    'background image for screen
    currentResolution = m.top.getScene().currentDesignResolution
    m.channelBackgroundposter = m.top.findNode("channelBackgroundposter")
    m.channelBackgroundposter.height = currentResolution.height
    m.channelBackgroundposter.width = currentResolution.width
    m.channelBackgroundposter.uri = "pkg:/images/homeScreenBckgrnd.png"

    m.episode_name = m.top.findNode("episode_name")
    m.broadcst_time = m.top.findNode("broadcst_time")
    m.episode_detail = m.top.findNode("episode_detail")
    m.thumbnail_poster = m.top.findNode("thumbnail_poster")

    m.episode_detail.font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.episode_detail.numLines = 5
    m.episode_detail.width = 1300
    m.episode_detail.height = 0
    m.episode_detail.wrap = true
    m.episode_detail.lineSpacing = 2

    m.broadcst_time.font = getFont(27, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.episode_detail.font = getFont(27, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.ChannelsGroup = m.top.findNode("ChannelsGroup")
    m.LoaderBackground = m.top.createChild("Rectangle")
    m.LoaderBackground.color = "0x000000FF"
    m.LoaderBackground.opacity = "0.65"
    m.LoaderBackground.width = 1920
    m.LoaderBackground.height = 1080
    m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
    m.LoginBusySpinner = m.LoaderBackground.createChild("BusySpinner")
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    centerx = (1920 - m.LoginBusySpinner.poster.bitmapWidth) / 2
    centery = (1000 - m.LoginBusySpinner.poster.bitmapHeight) / 2
    m.LoginBusySpinner.translation = [900, centery]

    m.video = m.top.findNode("livePlayer")
    m.video.mute = true
    m.streamlyview = m.top.findNode("streamlyview")
    m.video.enableUI = false
    m.video.enableTrickPlay = false
    m.video.visible = false
    m.ChannelsGroup.visible = true

    m.RelatedChannlesView = m.top.findNode("RelatedChannlesView")
    m.RelatedChannlesView.visible = false

    m.navtoRelatedCHannels = m.top.findNode("navtoRelatedCHannels")
    m.navtoRelatedCHannels1 = m.top.findNode("navtoRelatedCHannels1")
    m.navtoRelatedCHannels.visible = false
    m.navtoRelatedCHannels1.visible = false
    m.seekFocus = m.top.findNode("seekFocus")
    m.controllers = m.top.findNode("controllers")
    m.controllers.translation = [0, di.GetDisplaySize().h - 185]
    m.rewine = m.top.findNode("rewine")
    m.play = m.top.findNode("play")
    m.forward = m.top.findNode("forward")
    m.seekTimeStart = m.top.findNode("seekTime")
    m.bookmark = m.top.findNode("bookmark")
    m.seekTotalTime = m.top.findNode("seekTotalTime")
    m.seekUnfocus = m.top.findNode("seekUnfocus")
    m.published = m.top.findNode("published")
    m.circle = m.top.findNode("circle")
    m.next = m.top.findNode("next")
    m.previous = m.top.findNode("previous")

    m.top.signalBeacon("VODStartInitiate")

    m.relatedChannelText = m.top.findNode("relatedChannelText")
    m.relatedChannelText.visible = false
    m.relatedChannelText.font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.relatedChannelMarkupList = m.top.findNode("relatedChannelMarkupList")
    m.relatedChannelMarkupList.visible = false
    m.relatedChannelMarkupList.itemSize = [490, 135]
    m.relatedChannelMarkupList.numRows = 7
    m.relatedChannelMarkupList.itemSpacing = [0, 0]
    m.relatedChannelMarkupList.itemComponentName = "RelatedChannelsItemComponent"
    m.relatedChannelMarkupList.drawFocusFeedback = false
    m.relatedChannelMarkupList.wrapDividerBitmapUri = "pkg:/images/transparentimg.png"
    m.relatedChannelMarkupList.observeField("ItemSelected", "selectedChannelplay")

    channelItems()

    m.Settings = m.top.findNode("Settings")

    m.searchPoster = m.top.findNode("searchPoster")
    m.searchPoster.translation = [48, 50]
    m.searchPoster.uri = "pkg:/images/home/searchicon_100.png"
    m.searchPoster.height = 50
    m.searchPoster.width = 50
    m.searchPoster.visible = true
    m.focusPoster = m.searchPoster.createChild("Poster")
    m.focusPoster.id = "focusPoster"
    m.focusPoster.translation = [-8, -10]
    m.focusPoster.uri = ""
    m.focusPoster.height = 70
    m.focusPoster.width = 70

    m.ChannelCategoryMarkuGrid = m.top.findNode("ChannelCategoryMarkuGrid")

    font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.Settings.font = font
    VideoliveLoader()

    ' Go Live
    m.PosterliveIcon = m.searchPoster.createChild("Poster")
    m.PosterliveIcon.translation = [80, 122]
    m.PosterliveIcon.height = 40
    m.PosterliveIcon.width = 120
    m.PosterIconLabel = m.PosterliveIcon.createChild("Label")
    m.PosterIconLabel.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")

    ' if getVide_val() <> ""
    '     if getVide_val() = "video"
    '         videoId = getVideoid().toInt()
    '         setVideoscreen("home_refresh")
    '         if videoId < 96
    '             m.relatedChannelMarkupList.observeField("ItemSelected", "selectedChannelplay")
    '             m.LoaderBackground.visible = false
    '             m.PosterliveIcon.setFocus(false)
    '             m.timeGride = m.top.findNode("timeGrid")
    '             m.timeGride.observeField("channelFocused", "channelFocused")
    '             m.timeGride.setFocus(false)
    '             for i = 0 to LoadVideo_id().count() - 1
    '                 if LoadVideo_id()[i] = videoId then
    '                     setVideourl(LoadVideo_back()[i].URL)
    '                     playVideo(LoadVideo_back()[i])
    '                     exit for
    '                 end if
    '             end for

    '             m.ChannelsGroup.visible = false
    '             m.video.visible = true
    '             m.video.setFocus(true)
    '             m.top.signalBeacon("VODStartComplete")
    '         else
    '             ' Loading Timegrid data if video was not played before exit app
    '             load_TimegridData()

    '         end if
    '     end if
    ' else
    ' Loading Home screen data
    Home_screenTimegridData()
    ' end if

end sub

' Time grid refresh function according to time
sub onTimer()
    currentDateTime = CreateObject("roDateTime")
    currentTimestamp = currentDateTime.GetSeconds()

    hour = currentDateTime.GetHours()
    minute = currentDateTime.GetMinutes()

    ?"hour "hour
    ' Check if it's after 12 AM (midnight)
    if hour = 0 then
        m.screen = showHomeScreen()
        m.screen.observeField("ExitView", "openLoginView")

    else
        currentTime = CreateObject("roDateTime")
        currentTime.Mark()
        currentTime.ToLocalTime()
        t = currentTime.AsSeconds() - (36 * 60 * 60)

        t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark

        m.timeGride.leftEdgeTargetTime = t
        m.timeGride.maxDays = 5
        m.timeGride.setFields({
            showPastTimeScreen: true
            channelInfoComponentName: "ChannelItemComponent"
        })
        m.PosterliveIcon.setFocus(false)
        m.timeGride.setFocus(true)

    end if
end sub


sub channelItems()
    ContentNodeChannel = CreateObject("roSgNode", "ContentNode")

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    channelitem = ContentNodeChannel.createChild("ContentNode")
    itemarry = {}
    itemarry.chnnelIconUrl = "pkg:/images/mtvicon/mtv.png"
    itemarry.streamurl = ""
    channelitem.addFields(itemarry)

    m.relatedChannelMarkupList.content = ContentNodeChannel
end sub

sub Home_screenTimegridData()
    ' ?"videoId = getVideoid().toInt() "getVideoid().toInt()
    videoIdInt = getVideoid().toInt()
    m.LoaderBackground.visible = true
    m.timeGride = m.top.findNode("timeGrid")
    m.timeGride.numRows = 0
    currentTime = CreateObject("roDateTime")
    currentTime.Mark()
    currentTime.ToLocalTime()

    if (currentTime.GetHours() >= 13)
        m.timeGride.maxDays = 5
    else
        m.timeGride.maxDays = 6
    end if

    t = currentTime.AsSeconds() - (36 * 60 * 60)

    m.timer = CreateObject("roSgnode", "Timer")
    m.timer.Interval = 60000 ' Set the interval to check the time every 1 minute (adjust as needed)

    m.timer.ObserveField("fire", "onTimer")
    m.timer.control = "start"


    t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
    m.timeGride.contentStartTime = t
    m.timeGride.leftEdgeTargetTime = currentTime.AsSeconds()

    m.stringArray = createObject("roArray", 3, true)
    m.stringTitle = createObject("roArray", 3, true)
    m.stringDescription = createObject("roArray", 3, true)
    m.stringArray1 = createObject("roArray", 3, true)

    m.timeGride.focusBitmapUri = "pkg:/images/home/buttonFoucs1.9.png"
    m.timeGride.channelInfoComponentName = "ChannelItemComponent"
    m.timeGride.jumpToChannel = videoIdInt - 1
    ' m.timeGride.animateToChannel = getVideoid().toInt()

    m.timeGride.observeField("programSelected", "programSelected")
    m.timeGride.observeField("channelFocused", "channelFocused")
    m.timeGride.observeField("programFocusedDetails", "programFocusedDetails")

    m.uniqueArray = []
    timestamp = m.timeGride.contentStartTime
    dateTime = CreateObject("roDateTime")
    dateTime.FromSeconds(timestamp)
    hour = dateTime.GetHours()
    m.currentPosition = []

    m.prevIndexss = -1
    m.timeGride.observeField("programUnfocused", "programUnfocused")
    m.timeGride.programHorizMargin = 32

    m.timeGride.programBackgroundBitmapUri = "pkg:/images/home/borderwhite1.png"

    m.timeGride.overlayBitmapUri = ""
    m.timeGride.channelInfoBackgroundBitmapUri = ""
    m.timeGride.timeBarBitmapUri = "pkg:/images/transparentimg.png"
    m.timeGride.programTitleFocusedColor = "#ffffff"
    m.timeGride.showPastTimeScreen = true

    m.timeGride.itemSize = [1920, 140]
    m.timeGride.programTitleFont = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.timeGride.channelNoDataText = "Loading..."

    m.timeGride.loadingDataText = "Loading..."

    m.timeGride.automaticLoadingDataFeedback = true

    m.timeGride.numRows = m.channelListData

    m.timeGride.fillProgramGaps = true
    m.timeGride.translation = [30.0, 350.0]

    m.timeGride.vertFocusAnimationStyle = "fixedFocus"

    m.top.observeField("visible", "setFocus")

    m.timeGride.visible = false
    m.timeGride.setFocus(true)

    m.ChannelCategoryMarkuGrid.setFocus(false)

    m.streaminfoTimer = CreateObject("roSGNode", "Timer")
    m.streaminfoTimer.repeat = false
    m.streaminfoTimer.duration = 4
    m.streaminfoTimer.observeField("fire", "closeStreamInfo")
    m.fire = false

    m.categoryList = []'createObject("roArray")
    m.recentChannelList = []'createObject("roArray")
    m.myChannelList = []'createObject("roArray")
    m.channelList = []'createObject("roArray")

    m.programUpdateTimer = CreateObject("roSGNode", "Timer")
    m.programUpdateTimer.repeat = false

    m.programUpdateTimer.observeField("fire", "StartProgramUpdating")

    m.PosterBackground = m.top.createChild("Poster")

    m.PosterBackground.translation = [60, 800]

    m.PosterBackground.height = 230
    m.PosterBackground.width = 430

    m.PosterBackground.uri = "pkg:/images/search/search_result_background.png"

    m.PosterBackground.visible = false

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
    m.ConcertChannel.width = 230

    m.ConcertChannel.text = "MTV-USA"
    m.ConcertChannel.font = getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.ConcertName = m.PosterBackground.createChild("Label")
    m.ConcertName.translation = [189, 162]
    m.ConcertName.height = 30
    m.ConcertName.width = 230
    m.ConcertName.text = "Justin Beiber concert"
    m.ConcertName.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")

    SetupVideoPlayer()
    m.programIndex = 0
    di = getDeviceInfo()

    m.checkforVideoApiCall = 0

    m.GlobalPopupmsgtoggle = false

    initSupportDialog()

    reqApiCAll()

    m.RelatedChannlesView.visible = false
    m.relatedChannelMarkupList.visible = false
    m.relatedChannelText.visible = false
end sub

sub load_TimegridData()
    m.LoaderBackground.visible = true
    m.timeGride = m.top.findNode("timeGrid")
    m.timeGride.numRows = 0
    currentTime = CreateObject("roDateTime")
    currentTime.Mark()
    currentTime.ToLocalTime()

    if (currentTime.GetHours() >= 13)
        m.timeGride.maxDays = 5
    else
        m.timeGride.maxDays = 6
    end if

    t = currentTime.AsSeconds() - (36 * 60 * 60)
    ' m.timer.ObserveField("fire", "onTimer")
    ' m.timer.Start()

    t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
    m.timeGride.contentStartTime = t
    m.timeGride.leftEdgeTargetTime = currentTime.AsSeconds()

    m.stringArray = createObject("roArray", 3, true)
    m.stringTitle = createObject("roArray", 3, true)
    m.stringDescription = createObject("roArray", 3, true)
    m.stringArray1 = createObject("roArray", 3, true)

    m.timeGride.focusBitmapUri = "pkg:/images/home/buttonFoucs1.9.png"
    m.timeGride.channelInfoComponentName = "ChannelItemComponent"

    m.timeGride.observeField("programSelected", "programSelected")
    m.timeGride.observeField("channelFocused", "channelFocused")
    m.timeGride.observeField("programFocusedDetails", "programFocusedDetails")

    m.timeGride.programHorizMargin = 32
    m.timeGride.programBackgroundBitmapUri = "pkg:/images/home/borderwhite1.png"

    m.timeGride.overlayBitmapUri = ""
    m.timeGride.channelInfoBackgroundBitmapUri = ""
    m.timeGride.timeBarBitmapUri = "pkg:/images/transparentimg.png"
    m.timeGride.programTitleFocusedColor = "#ffffff"
    m.timeGride.showPastTimeScreen = true

    m.timeGride.itemSize = [1920, 140]
    m.timeGride.programTitleFont = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.timeGride.channelNoDataText = "Loading..."

    m.timeGride.loadingDataText = "Loading..."

    m.timeGride.automaticLoadingDataFeedback = true

    m.timeGride.numRows = m.channelListData

    m.timeGride.fillProgramGaps = true
    m.timeGride.translation = [30.0, 350.0]

    m.timeGride.vertFocusAnimationStyle = "fixedFocus"

    m.top.observeField("visible", "setFocus")

    m.timeGride.visible = false
    m.timeGride.setFocus(true)

    m.ChannelCategoryMarkuGrid.setFocus(false)

    m.streaminfoTimer = CreateObject("roSGNode", "Timer")
    m.streaminfoTimer.repeat = false
    m.streaminfoTimer.duration = 4
    m.streaminfoTimer.observeField("fire", "closeStreamInfo")
    m.fire = false

    m.categoryList = []'createObject("roArray")
    m.recentChannelList = []'createObject("roArray")
    m.myChannelList = []'createObject("roArray")
    m.channelList = []'createObject("roArray")

    m.programUpdateTimer = CreateObject("roSGNode", "Timer")
    m.programUpdateTimer.repeat = false

    m.programUpdateTimer.observeField("fire", "StartProgramUpdating")

    m.PosterBackground = m.top.createChild("Poster")

    m.PosterBackground.translation = [60, 800]

    m.PosterBackground.height = 230
    m.PosterBackground.width = 430

    m.PosterBackground.uri = "pkg:/images/search/search_result_background.png"

    m.PosterBackground.visible = false

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
    m.ConcertChannel.width = 230

    m.ConcertChannel.text = "MTV-USA"
    m.ConcertChannel.font = getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.ConcertName = m.PosterBackground.createChild("Label")
    m.ConcertName.translation = [189, 162]
    m.ConcertName.height = 30
    m.ConcertName.width = 230
    m.ConcertName.text = "Justin Beiber concert"
    m.ConcertName.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")

    SetupVideoPlayer()
    m.programIndex = 0
    di = getDeviceInfo()

    m.checkforVideoApiCall = 0

    m.GlobalPopupmsgtoggle = false

    initSupportDialog()

    reqApiCAll()

    m.RelatedChannlesView.visible = false
    m.relatedChannelMarkupList.visible = false
    m.relatedChannelText.visible = false
end sub

sub delete_channelSelectedValue()
    if RegRead("Recent")
        RegDelete("Recent")
    else if RegRead("Sports")
        RegDelete("Sports")
    else if RegRead("Others")
        RegDelete("Others")
    else if RegRead("Movies")
        RegDelete("Movies")
    else if RegRead("Family")
        RegDelete("Family")
    else if RegRead("Kids")
        RegDelete("Kids")
    else if RegRead("News")
        RegDelete("News")
    else if RegRead("Comedy")
        RegDelete("Comedy")
    else if RegRead("All")
        RegDelete("All")
    end if
end sub

function width() as integer
    di = CreateObject("roDeviceInfo")
    mode = di.GetCaptionsMode()
    w = di.GetDisplaySize().w

    if w = 1280
        w = w + 640
    end if
    return w
end function


'calculate the screen height
function height() as integer
    di = CreateObject("roDeviceInfo")
    mode = di.GetCaptionsMode()
    h = di.GetDisplaySize().h

    if h = 720
        h = h + 360
    end if

    return h
end function

sub closeTryAgain()
    m.navtoRelatedCHannels1.visible = false
    m.controllers.visible = false
    deleteVide_val()
    videoIdInt = getVideoid().toInt()
    ' ?"videoId = getVideoid().toInt() "getVideoid().toInt()
    if RegRead("search_video")
        RegDelete("search_video")
        m.Close.setFocus(false)
        showDialogDevice("", false, true, "")
        m.video.control = "stop"
        m.video.visible = false
        SearchScreen = m.top.createChild("SearchScreen")
        SearchScreen.id = "SearchScreen"
        SearchScreen.observeField("exitView", "closeSearchScreen")
        SearchScreen.observeField("playChannel", "playSearchChannelUrl")
        m.controllers.visible = false
        SearchScreen.setFocus(true)
    else
        m.Close.setFocus(false)
        showDialogDevice("", false, true, "")
        closeRelatedchnnelView()
        m.video.control = "stop"
        m.playerLoader.visible = false
        m.streamlyview.visible = false
        m.navtoRelatedCHannels.visible = false
        m.navtoRelatedCHannels1.visible = false
        m.ChannelsGroup.visible = true
        m.video.visible = false
        currentTime = CreateObject("roDateTime") ' roDateTime is initialized
        m.PosterliveIcon.uri = "pkg:/images/red1.png"
        m.PosterIconLabel.text = "Live"
        m.PosterIconLabel.translation = [35, 2]

        ' to the current time
        t = currentTime.AsSeconds()
        t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
        ' m.timeGride.contentStartTime = t
        datetime = CreateObject("roDateTime")
        datetime.FromSeconds(m.timeGride.contentStartTime)
        currentdate = (datetime.GetDayOfMonth() + 1).ToStr()

        if currentTime.GetHours() >= 12 and currentTime.GetHours() <= 23 and currentTime.GetMinutes() <= 59
            if currentTime.GetDayOfMonth().ToStr() = currentdate
                m.timeGride.leftEdgeTargetTime = t
                m.timeGride.contentStartTime = t
                m.timeGride.maxDays = 5
                m.timeGride.setFields({
                    showPastTimeScreen: true
                    channelInfoComponentName: "ChannelItemComponent"
                })
                m.PosterliveIcon.setFocus(false)
                m.timeGride.setFocus(true)
                if getVideoscreen() = "home_refresh"
                    m.screen = showHomeScreen()
                    m.screen.observeField("ExitView", "openLoginView")
                    m.timeGride.jumpToChannel = videoIdInt
                    ' deleteVideoscreen()
                end if
            else
                m.timeGride.leftEdgeTargetTime = t
                m.timeGride.contentStartTime = t
                m.timeGride.maxDays = 5
                m.timeGride.setFields({
                    showPastTimeScreen: true
                    channelInfoComponentName: "ChannelItemComponent"
                })
                m.PosterliveIcon.setFocus(false)
                m.timeGride.setFocus(true)
                if getVideoscreen() = "home_refresh"
                    m.screen = showHomeScreen()
                    m.screen.observeField("ExitView", "openLoginView")
                    m.timeGride.jumpToChannel = videoIdInt
                    ' deleteVideoscreen()
                end if
            end if
        else

            if currentTime.GetDayOfMonth().ToStr() = currentdate
                m.timeGride.leftEdgeTargetTime = t
                m.timeGride.maxDays = 5
                m.timeGride.contentStartTime = t
                m.timeGride.setFields({
                    showPastTimeScreen: true
                    channelInfoComponentName: "ChannelItemComponent"
                })
                m.PosterliveIcon.setFocus(false)
                m.timeGride.setFocus(true)
                if getVideoscreen() = "home_refresh"
                    m.screen = showHomeScreen()
                    m.screen.observeField("ExitView", "openLoginView")
                    m.timeGride.jumpToChannel = videoIdInt
                    ' deleteVideoscreen()
                end if
            else
                ' m.screen = showHomeScreen()
                ' m.screen.observeField("ExitView", "openLoginView")
                m.timeGride.leftEdgeTargetTime = t
                m.timeGride.maxDays = 5
                m.timeGride.contentStartTime = t
                m.timeGride.setFields({
                    showPastTimeScreen: true
                    channelInfoComponentName: "ChannelItemComponent"
                })
                m.PosterliveIcon.setFocus(false)
                m.timeGride.setFocus(true)
                if getVideoscreen() = "home_refresh"
                    m.screen = showHomeScreen()
                    m.screen.observeField("ExitView", "openLoginView")
                    m.timeGride.jumpToChannel = videoIdInt
                    ' deleteVideoscreen()
                end if
            end if
        end if


        m.controllers.visible = false
        m.timeGride.setFocus(true)
        if m.SupportDialog <> invalid
            m.SupportDialog.visible = false
            m.PosterBackground.visible = false
        end if
    end if
end sub

sub Try_again()
    ?"try again"
    ' ?"m.channelsId "m.channelsId
    videoId = getVideoid().toInt()
    videoIds = LoadVideo_id()
    m.Tryagain.setFocus(false)
    showDialogDevice("", false, true, "")
    for each i in videoIds
        if videoIds[i] = videoId then
            playVideo(m.channels[0][i])
            exit for
        end if
    end for
    m.ChannelsGroup.visible = false
    m.video.visible = true
    m.video.setFocus(true)
end sub

function repeat(arr as object)
    res = []
    table = {}
    for i = 1 to arr.Count() - 1
        j = i + 1
        if arr[i] = arr[j]
            res.push(arr[j])
        end if
    end for
    return res
end function

function contains(arr as object, value as string) as boolean
    for each entry in arr
        if entry = value
            return true
        end if
    end for
    return false
end function

sub initSupportDialog()
    m.dialogHeight = 400
    m.dialogWidth = 1223

    m.SupportDialog = m.top.createChild("Poster")
    m.SupportDialog.visible = false
    m.SupportDialog.width = m.dialogWidth
    m.SupportDialog.height = m.dialogHeight
    m.SupportDialog.uri = "pkg:/images/profile/profile_dialog.png"
    m.SupportDialog.translation = [(1920 - m.dialogWidth) / 2, (1080 - m.dialogHeight) / 2]

    m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [(m.SupportDialog.width) / 2, 50]

    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [20]
    m.LayoutGroup.horizAlignment = "center"
    m.TextLabel = m.LayoutGroup.createChild("Label")
    m.TextLabel.width = m.dialogWidth - 10

    m.TextLabel.font = getFont(40, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")

    m.TextLabel.maxLines = 3
    m.TextLabel.horizAlign = "Center"
    m.TextLabel.vertAlign = "Center"

    m.contactNumber = m.LayoutGroup.createChild("Label")

    m.contactNumber.wrap = true
    m.contactNumber.width = m.dialogWidth
    m.contactNumber.height = m.dialogHeight * (35 / 100)

    m.contactNumber.horizAlign = "Center"
    m.contactNumber.vertAlign = "Center"
    m.contactNumber.font = getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.HelpButton = m.LayoutGroup.createChild("Button")
    m.HelpButton.observeField("buttonSelected", "closeDialogNext")

    m.HelpButton.minWidth = "280"
    m.HelpButton.maxWidth = "280"
    m.HelpButton.focusedTextColor = "#FFFFFF"
    m.HelpButton.height = "84"
    m.HelpButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.HelpButton.showFocusFootprint = "true"
    m.HelpButton.iconUri = ""
    m.HelpButton.focusedIconUri = ""
    m.ButtonLabelText = m.HelpButton.createChild("Label")
    m.ButtonLabelText.horizAlign = "center"
    m.ButtonLabelText.height = "84"
    m.ButtonLabelText.width = "280"
    m.ButtonLabelText.vertAlign = "center"
    m.ButtonLabelText.text = "OK"
    m.ButtonLabelText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.HelpButton.translation = [(m.dialogWidth - m.HelpButton.boundingRect().width) / 2, m.dialogHeight * (65 / 100)]

    m.timegridMessage = m.top.findNode("timegridMessage")

    m.epg_detail_info = m.top.findNode("epg_detail_info")
    m.epg_des_layout_group = m.top.findNode("epg_des_layout_group")

end sub


sub selectedChannelplay(event as object)
    ' ?"getVideoid().toInt()"getVideoid().toInt()
    ?"event.getData()"event.getData()
    ?event.getRoSGNode().content.getChild(event.getData())
    if m.controllers.visible = false
        showDialogDevice("", false, false, "")
        RegWrite("selectedchan", "selectedchan")
        m.timeGride.jumpToChannel = event.getData()
        playfromchnnelguide(event.getRoSGNode().content.getChild(event.getData()))
    end if
end sub
sub playfromchnnelguide(streamNode as object)
    closeRelatedchnnelView()
    setVideourl(streamNode.URL)
    playVideo(streamNode)
    m.navtoRelatedCHannels1.visible = false
    m.ChannelsGroup.visible = false
    m.video.visible = true
    m.video.setFocus(true)
    m.top.signalBeacon("VODStartComplete")
end sub

sub LocationInfo()
    location = CreateObject("roSgNode", "Location")
    location.callType = "GET"
    location.callTO = "checkLocation"
    location.observeField("apiResponse", "locationresponse")
    location.control = "RUN"
end sub

sub locationresponse(event as object)
    event.getRoSGNode().control = "Stop"
    m.inside_geofence = true
    if event.getData() <> invalid and event.getRoSGNode().callTO = "checkLocation"
        m.inside_geofence = event.getData().popupmsgtoggle

        if(event.getData() <> invalid and event.getData().response_code = 403)
            if event.getData().popupmsgtoggle
                m.needHelpText = "location"
                showDialog("Not within zipcode boundary", " You are away from your home zip code: click here to continue viewing ", true, "", "")
            end if
        end if
    end if
end sub

sub showDialogDevice(dialogView as string, show as boolean, focus as boolean, message as string)
    m.navtoRelatedCHannels1.visible = false
    m.navtoRelatedCHannels.visible = false
    m.deviceView.visible = false
    if show
        m.deviceText.text = tr(message)
        m.deviceView.width = 1620
        m.deviceView.height = 488
        m.deviceView.visible = show
        m.deviceView.translation = [(1920 - m.deviceView.boundingRect().width) / 2, (1080 - m.deviceView.boundingRect().height) / 2]
    end if

end sub


sub showDialog(title as string, message as string, optionsDialog as boolean, buttons as object, onserveField as object)
    if optionsDialog
        m.HelpButton.setFocus(true)
        m.contactNumber.text = message
        m.TextLabel.text = title
        m.SupportDialog.visible = true
        m.ButtonLabelText.text = "OK"
    end if
end sub

sub showspinner(event as object)
    m.LoaderBackground.visible = event
    if event
        m.LoginBusySpinner.control = "stop"
    else
        m.LoginBusySpinner.control = "start"
    end if
end sub

sub closeDialogNext()
    if m.needHelpText = "location"
        m.SupportDialog.visible = false
        m.timeGride.setFocus(true)
    else if m.needHelpText = "Error"
        if RegRead("search_video")
            m.SupportDialog.visible = false
            closeRelatedchnnelView()

            m.streamlyview.visible = false
            m.PosterBackground.visible = false
            m.navtoRelatedCHannels.visible = false
            m.navtoRelatedCHannels1.visible = false
            m.ChannelsGroup.visible = true
            m.video.visible = false

            m.timeGride.setFocus(true)
            RegDelete("search_video")
            SearchScreen = m.top.createChild("SearchScreen")
            SearchScreen.id = "SearchScreen"
            SearchScreen.observeField("exitView", "closeSearchScreen")
            SearchScreen.observeField("playChannel", "playSearchChannelUrl")
            SearchScreen.setFocus(true)
        else
            m.SupportDialog.visible = false
            closeRelatedchnnelView()
            m.streamlyview.visible = false
            m.PosterBackground.visible = false
            m.navtoRelatedCHannels.visible = false
            m.navtoRelatedCHannels1.visible = false
            m.ChannelsGroup.visible = true
            m.video.visible = false
            m.timeGride.setFocus(true)
        end if
    else
        m.HelpButton.setFocus(false)
        m.SupportDialog.visible = false
    end if
    m.needHelpText = ""
end sub

sub VideoLiveLoader()
    m.playerLoader = m.top.createChild("BusySpinner")
    m.playerLoader.visible = false
    m.playerLoader.clockwise = false
    m.playerLoader.poster.uri = "pkg:/images/videoplayer/loader96.png"
end sub

sub programUnfocused(params as object)
    channelNode = m.timeGride.programUnfocused
end sub


function elementExists(arr as object, element as integer) as boolean
    for i = 0 to arr.Count() - 1
        if arr[i] = element then
            return true
        end if
    end for
    return false
end function



sub programFocusedDetails(params as object)

    channelNode = params.getRosgNode().content.getChild(params.getData().focusChannelIndex)

    if channelNode <> invalid
        episode = channelNode.getChild(params.getData().focusIndex)

        if episode <> invalid

            m.episode_name.text = episode.TITLE

            startTime = getDurationIn12HrFormate(episode.PLAYSTART)

            endTime = getDurationIn12HrFormate(episode.PLAYSTART + episode.PLAYDURATION)
            m.broadcst_time.text = "Starts At " + startTime + "  -   Ends At " + endTime
            m.episode_detail.text = episode.DESCRIPTION
            m.thumbnail_poster.uri = channelNode.HDSMALLICONURL

            ' Visibility of Live button
            dateTime = CreateObject("roDateTime")
            timeString = dateTime.asSeconds()

            if timeString >= episode.PLAYSTART and timeString <= episode.PLAYSTART + episode.PLAYDURATION

                m.PosterliveIcon.uri = "pkg:/images/red1.png"
                m.PosterIconLabel.text = "Live"
                m.PosterIconLabel.translation = [35, 2]
            else
                m.PosterIconLabel.text = ""
                m.PosterliveIcon.uri = ""
                m.PosterliveIcon.uri = "pkg:/images/grey.png"
                m.PosterIconLabel.text = "Go Live"
                m.PosterIconLabel.translation = [10, 2]
            end if


        end if
    end if

end sub


sub closeStreamInfo()
    m.streaminfoTimer.control = "stop"
    m.streamlyview.visible = false
    m.PosterBackground.visible = false
    m.navtoRelatedCHannels.visible = false
    m.navtoRelatedCHannels1.visible = false
    m.fire = true
end sub

sub SetupVideoPlayer()
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

sub percentage(event as object)
    if (event <> invalid and event.getData() <> invalid)
        ?" buffering ";event.getData().percentage
    end if
end sub

sub onStateChange()
    retryCount = 0
    if m.Video <> invalid and m.video.state <> "error"
        if m.Video.state = "buffering"
            ?"m.video.control"
            centerx = (1920 - m.playerLoader.poster.bitmapWidth) / 2
            centery = (1080 - m.playerLoader.poster.bitmapHeight) / 2
            m.playerLoader.translation = [centerx, centery]
            m.playerLoader.visible = true
            m.playerLoader.control = "start"
        else
            m.playerLoader.visible = false
            m.playerLoader.control = "stop"
        end if

        if m.Video.state = "playing"
            m.video.mute = false
            if getControlMode() = "pause"
                m.video.control = "pause"
            end if
            m.duration = m.Video.duration
            horesAndMinute()
            forSeekBar()
            timer1()
            if m.selectedPlayingChannel <> invalid

                DisplaySize = getDeviceInfo().GetDisplaySize()
                m.analyticsChannelClicks = CreateObject("roSGNode", "ApiCall")
                m.analyticsChannelClicks.bodyData = { "callsign": m.selectedPlayingChannel, sr: DisplaySize.w.toStr() + "x" + DisplaySize.h.toStr(), inside_geofence: m.inside_geofence }
                m.analyticsChannelClicks.callType = "POST"
                m.analyticsChannelClicks.url = ga()
                m.analyticsChannelClicks.observeField("apiResponseData", "apiResponse")
                m.analyticsChannelClicks.control = "RUN"
                if m.GlobalPopupmsgtoggle = true
                    m.LiveChannelError.setFocus(true)
                end if

            end if
        end if

        if (m.video.state <> invalid and ((m.video.state = "error") or (m.video.state = "finished") or (m.video.state = "stopped")))
            ?"m.video.state"m.video.state
            print "m.video.errorCode: "; m.video.errorCode
            print "m.video.errorMsg: "; m.video.errorMsg
            print "m.vdeo.errorStr: "; m.video.errorStr
            print "m.vdeo.errorInfo: "; m.video.errorInfo
            print "m.vdeo.completedStreamInfo: "; m.video.completedStreamInfo

            if m.video.errorCode <> 0
                while retryCount < 3
                    ' Increment the retry count
                    retryCount = retryCount + 1
                    Try_again()
                end while

                if retryCount = 3
                    if m.video.state = "finished"
                        m.video.control = "stop"
                        m.playerLoader.visible = false
                        m.playerLoader.control = "stop"

                        m.Close.setFocus(true)
                        m.controllers.visible = false
                        RegWrite("errorcode", "errorcode")
                        showDialogDevice("StreamlyTV", true, false, "Oops! There is a technical issue!  We are on it!  Please try again later!")
                    else
                        closeTryAgain()
                    end if
                end if
            else
                checkforVideoApiCall = 0

                DisplaySize = getDeviceInfo().GetDisplaySize()
                CrashLogCall = CreateObject("roSGNode", "ApiCall")
                CrashLogCall.bodyData = {
                    errcode: m.video.errorCode,
                    errmsg: m.video.errorMsg,
                    sr: DisplaySize.w.toStr() + "x" + DisplaySize.h.toStr(),
                    inside_geofence: m.inside_geofence
                }
                CrashLogCall.callType = "POST"
                CrashLogCall.url = playererror()
                CrashLogCall.observeField("apiResponseData", "apiResponse")
                CrashLogCall.control = "RUN"
                m.top.signalBeacon("VODStartComplete")
                closePlayer()

            end if
        end if
    end if
end sub

sub forReplaySeekbar()
    if m.data.playingPosition <> 0
        ps = (m.data.playingPosition / m.duration) * 100
        ss = m.seekUnfocus.width * (ps / 100)
        m.seekFocus.width = ss
        m.circle.translation = [ss, -4]
        m.video.initial_time = m.data.playingPosition
        horesAndMinute1()
    end if
end sub

sub forSeekBar()
    if m.Video.position <> 0 and m.duration <> 0
        ps = (m.Video.position / m.duration) * 100
        w = m.seekUnfocus.width - 33
        ss = w * (ps / 100)
        m.seekFocus.width = ss
        m.circle.translation = [ss, -4]
        horesAndMinute1()
    end if
end sub

sub forRewine()
    m.Video.seek = m.Video.position - 10.0
    onStateChange()
end sub

sub forPrevious()
    videoIdInt = getVideoid().toInt()
    videoIds = LoadVideo_id()
    if videoIdInt > 1
        m.video.control = "stop"
        m.controllers.visible = false
        horesAndMinute()
        forSeekBar()
        timer1()
        id = 0

        for i = 0 to videoIds.count() - 1
            if videoIds[i] = videoIdInt then
                playVideo(m.channels[0][i - 1])
                exit for
            end if
        end for
        m.ChannelsGroup.visible = false
        m.video.visible = true
        m.video.setFocus(true)
    end if
    m.top.signalBeacon("VODStartComplete")
end sub

sub forNext()
    videoIdInt = getVideoid().toInt()
    videoIds = LoadVideo_id()
    ' if videoIdInt < 132
        m.video.control = "stop"
        m.controllers.visible = false
        horesAndMinute()
        forSeekBar()
        timer1()
        id = 0
        for i = 0 to videoIds.count() - 1
            if videoIds[i] = videoIdInt then
                if m.channels.Count() <> 0
                    playVideo(m.channels[0][i + 1])
                end if

                exit for
            end if
        end for
        m.ChannelsGroup.visible = false
        m.video.visible = true
        m.video.setFocus(true)
    ' end if
    m.top.signalBeacon("VODStartComplete")
end sub

sub forForward()
    m.Video.seek = m.Video.position + 10.0
    onStateChange()
end sub

sub focusColor()
    m.play.blendColor = "#FFFFFF"
    m.rewine.blendColor = "#FFFFFF"
    m.forward.blendColor = "#FFFFFF"
    if m.play.hasFocus()
        m.play.blendColor = "#FFD23F"
    else if m.rewine.hasFocus()
        m.rewine.blendColor = "#FFD23F"
    else if m.forward.hasFocus()
        m.forward.blendColor = "#FFD23F"
    end if
end sub


sub horesAndMinute()
    min = (m.duration / 60) mod 60
    hours = (m.duration / 60) / 60
    seconds = m.duration mod 60
    n = Int(hours)
    m.seekTotalTime.text = getNumber(n) + ":" + getNumber(min) + ":" + getNumber(seconds)
end sub

sub horesAndMinute1()
    d = m.Video.position
    min = (d / 60) mod 60
    hours = (d / 60) / 60
    seconds = d mod 60
    n = Int(hours)
    m.seekTimeStart.text = getNumber(n) + ":" + getNumber(min) + ":" + getNumber(seconds)
end sub

function getNumber(h as integer) as string
    if h = 0
        return "00"
    else if h < 10
        return "0" + StrI(h).Trim()
    else
        return StrI(h).Trim()
    end if
end function

sub hideAndShow(isV as boolean)
    m.time = 0
    if m.video.state = "playing"
        onStateChange()
    end if
end sub

sub timer1()
    m.timer = CreateObject("roSgnode", "Timer")
    m.timer.observeField("fire", "seekbarUpdate")
    m.timer.duration = 1
    m.timer.repeat = true
    m.timer.control = "start"
end sub

sub seekbarUpdate()
    m.time = m.time + 1
    if m.time = 5
        hideAndShow(true)
    else if m.time = 15
        m.controllers.visible = false
    end if
    forSeekBar()
end sub

function videoerror()
    closeErrorDialogShow(m.top)
    closeRelatedchnnelView()
    m.DialogButton.visible = false
    m.streamlyview.visible = false
    m.PosterBackground.visible = false
    m.navtoRelatedCHannels.visible = false
    m.navtoRelatedCHannels1.visible = false
    m.ChannelsGroup.visible = true
    m.video.visible = false
    m.timeGride.setFocus(true)
end function

sub onPositionChange()
    if m.video.duration > 0 then

        if m.checkforVideoApiCall = 15

            m.checkforVideoApiCall = 0

            if m.selectedPlayingChannel <> invalid

                DisplaySize = getDeviceInfo().GetDisplaySize()
                m.analyticsChannelClicks = CreateObject("roSGNode", "ApiCall")
                m.analyticsChannelClicks.bodyData = { "callsign": m.selectedPlayingChannel + "ROKU", sr: DisplaySize.w.toStr() + "x" + DisplaySize.h.toStr(), inside_geofence: m.inside_geofence }
                m.analyticsChannelClicks.callType = "POST"
                m.analyticsChannelClicks.url = GADUration()
                m.analyticsChannelClicks.observeField("apiResponseData", "apiResponse")
                m.analyticsChannelClicks.control = "RUN"
            end if
        else
            m.checkforVideoApiCall += 1
        end if
    end if
end sub

sub StartProgramUpdating()
    m.programUpdateTimer.control = "stop"
end sub

sub RestartLazyLoadingTimer()
    m.lazyLoadingTimer.control = "stop"
    m.lazyLoadingTimer.control = "start"
end sub

sub channelFocused(event as object)
    videoIdInt = getVideoid().toInt()
    videoIds = LoadVideo_id()
    if m.timeGride.content <> invalid
        if m.timeGride.content.getChild(m.timeGride.channelFocused) <> invalid
            if getVideoscreen() = "home_refresh"
                for i = 0 to videoIds().count() - 1
                    if videoIds[i] = videoIdInt then
                        if m.channels.Count() <> 0
                            m.timeGride.jumpToChannel = m.channels[0][i + 1]
                            deleteVideoscreen()
                        end if
                        exit for
                    end if
                end for
            else
                m.PosterchannleIcon.uri = m.timeGride.content.getChild(m.timeGride.channelFocused).HDSMALLICONURL
                m.ConcertChannel.text = m.timeGride.content.getChild(m.timeGride.channelFocused).TITLE

            end if

        end if
    else
        if m.channelNameString <> invalid
            getChannelDAta(m.channelNameString)
        end if
    end if
end sub

' This methods return channel list with programme list which is include on specific channel object.
sub getChannelDAta(genere as string)
    m.TestingTAsk = CreateObject("roSGNode", "TestingTAsk")
    currentDate = CreateObject("roDateTime")
    param = CreateObject("roAssociativeArray")

    if genere <> "All" and genere <> "Recent" and genere <> "MyChannel"
        param["genre"] = genere
    end if
    dt = CreateObject ("roDateTime")
    utcHours = dt.GetHours ()
    utcMinutes = dt.GetMinutes()
    dt.ToLocalTime ()
    localHours = dt.GetHours ()
    localMinutes = dt.GetMinutes()
    utcHourOffset = localHours - utcHours
    utcMinutesOffset = localHours - utcHours

    dt1 = CreateObject("roDateTime")
    dt2 = dt1.GetTimeZoneOffset() * 60

    if (0 > dt2)
        dt2 = dt2 * (-1)
        timezone = GetDurationStringStandard(dt2)
        setCurrentTimezone("+" + timezone)
    else if (0 = dt2)
        timezone = GetDurationStringStandard(dt2)
        setCurrentTimezone("+" + timezone)
    else
        timezone = GetDurationStringStandard(dt2)
        setCurrentTimezone("-" + timezone)
    end if

    param["hoursoffset"] = timezone
    param["&days"] = 2
    param["typeurl"] = "manifest"

    m.TestingTAsk.parameters = param
    m.TestingTAsk.observeField("ChannelList", "showResponse")
    m.TestingTAsk.observeField("errorResponse", "errorDialogShow")
    m.TestingTAsk.control = "RUN"
end sub

' Call API for recent and favourite channel List.
sub getUsersRecentAndFavouriteData()
    m.commonAPITask = CreateObject("roSGNode", "CommonAPI")
    if m.channelNameString = "Recent"
        ' m.timeGride.visible = false
        m.commonAPITask.observeField("RecentChannelData", "recentChannelAPIResponse")
    else
        m.commonAPITask.observeField("MyChannelData", "myChannelAPIResponse")
    end if

    m.commonAPITask.observeField("errorResponse", "errorDialogShow")
    m.commonAPITask.control = "RUN"
end sub

' Get Response from Recent Channel API.
sub recentChannelAPIResponse(event as object)
    m.timeGride.visible = false
    m.timegridMessage.visible = false
    m.epg_des_layout_group.visible = true
    m.epg_detail_info.visible = true
    ' m.timeGride.visible = true
    m.recentChannelList = event.getData()
    m.channelListData = m.recentChannelList.Count()
    if m.recentChannelList.Count() = 1
        m.timeGride.numRows = 3
    ?"recent data"

    else if m.recentChannelList.Count() = 0
        m.timegridMessage.visible = true
        m.timegridMessage.text = "Oops! It looks like technical issues have taken our channels offline! We are working on it, and will have the channels backup as soon as possible!"
        m.timegridMessage.font = getFont(38, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.timegridMessage.translation = [300, 500.0]
        m.timegridMessage.width = 1300
        m.timegridMessage.numRows = 2
        m.timegridMessage.wrap = true
        m.epg_detail_info.visible = false
        m.epg_des_layout_group.visible = false
        m.timeGride.visible = false

    else
        m.timeGride.numRows = m.channelListData
        m.timeGride.visible = false
    end if
    m.top.signalBeacon("EPGLaunchComplete")
    event.getRoSGNode().control = "stop"
    m.streamlyview.visible = false
    m.PosterBackground.visible = false
    m.navtoRelatedCHannels.visible = false
    m.navtoRelatedCHannels1.visible = false
    m.ChannelsGroup.visible = true
    m.video.visible = false
    m.timeGride.setFocus(false)
    m.ChannelCategoryMarkuGrid.setFocus(true)
end sub

' Get My Channel list from API.
sub myChannelAPIResponse(event as object)
    m.myChannelList = event.getData()
    m.channelListData = m.myChannelList.Count()
    if m.myChannelList.Count() = 0
        m.timegridMessage.visible = true
        m.timegridMessage.text = "Oops! It looks like technical issues have taken our channels offline! We are working on it, and will have the channels backup as soon as possible!"
        m.timegridMessage.font = getFont(38, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.timegridMessage.translation = [300, 500.0]
        m.timegridMessage.width = 1300
        m.timegridMessage.numRows = 2
        m.timegridMessage.wrap = true
        m.epg_detail_info.visible = false
        m.epg_des_layout_group.visible = false
        m.timeGride.visible = false
    else if m.myChannelList.Count() = 1
        m.timeGride.numRows = 3
    else
        m.timeGride.numRows = m.channelListData
    end if
    m.top.signalBeacon("EPGLaunchComplete")
    event.getRoSGNode().control = "stop"
    m.streamlyview.visible = false
    m.PosterBackground.visible = false
    m.navtoRelatedCHannels.visible = false
    m.navtoRelatedCHannels1.visible = false
    m.ChannelsGroup.visible = true
    m.video.visible = false
    m.timeGride.setFocus(false)
    m.ChannelCategoryMarkuGrid.setFocus(true)
end sub

sub makeListOFRecentChannel()
    ' m.timeGride.visible = false
    for each channel in m.channelList
        for each recentChannel in m.recentChannelList
            if recentChannel.id = channel.id
                nodedata = m.timeGride.content.createChild("ContentNode")
                nodedata.id = channel.id
                nodedata.TITLE = channel.id.toStr()
                nodedata.HDSMALLICONURL = channel.logo
                nodedata.url = channel.url
                for each programme in channel.programs
                    singleProgramNode = nodedata.createChild("ContentNode")
                    singleProgramNode.TITLE = programme.title
                    singleProgramNode.PLAYSTART = programme.start
                    singleProgramNode.Description = programme.description
                    singleProgramNode.PLAYDURATION = programme["end"] - programme["start"]
                end for
            end if
        end for
    end for
end sub

sub makeListMyChannel()
    for each channel in m.channelList
        for each myChannel in m.myChannelList
            if myChannel.id = channel.id
                nodedata = m.timeGride.content.createChild("ContentNode")
                nodedata.id = channel.id
                nodedata.TITLE = channel.id.toStr()
                nodedata.HDSMALLICONURL = channel.logo
                nodedata.url = channel.url
                for each programme in channel.programs
                    singleProgramNode = nodedata.createChild("ContentNode")
                    singleProgramNode.TITLE = programme.title
                    singleProgramNode.PLAYSTART = programme.start
                    singleProgramNode.Description = programme.description
                    singleProgramNode.PLAYDURATION = programme["end"] - programme["start"]
                end for
            end if
        end for
    end for
end sub

sub CategoriesChannelList()
    m.timegridMessage.visible = false
    m.epg_des_layout_group.visible = true
    m.epg_detail_info.visible = true
    m.timeGride.visible = true
    if m.channelList.Count() = 0
        m.timegridMessage.visible = true
        m.timegridMessage.text = "Oops! It looks like technical issues have taken our channels offline! We are working on it, and will have the channels backup as soon as possible!"
        m.timegridMessage.font = getFont(38, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.timegridMessage.translation = [300, 450.0]
        m.timegridMessage.width = 1300
        m.timegridMessage.numRows = 2
        m.timegridMessage.wrap = true
    end if

    uniqueChannels = CreateObject("roAssociativeArray")
    for each channel in m.channelList

        channelId = channel.id.toStr()

        ' Check if the channel has already been encountered1
        if uniqueChannels[channelId] = invalid then
            ' Add the channel to the unique channels collection
            uniqueChannels[channelId] = channel

            nodedata = m.timeGride.content.createChild("ContentNode")
            nodedata.id = channel.id
            nodedata.TITLE = channel.id.toStr()
            nodedata.HDSMALLICONURL = channel.logo
            nodedata.url = channel.url
            if channel.id = 11
                for each programme in channel.programs
                    ?"programme "programme
                    singleProgramNode = nodedata.createChild("ContentNode")
                    singleProgramNode.TITLE = "Horse Racing"
                    singleProgramNode.PLAYSTART = programme.start
                    singleProgramNode.Description = programme.description
                    singleProgramNode.PLAYDURATION = programme["end"] - programme["start"]
                end for
            else
                for each programme in channel.programs
                    singleProgramNode = nodedata.createChild("ContentNode")
                    singleProgramNode.TITLE = programme.title
                    singleProgramNode.PLAYSTART = programme.start
                    singleProgramNode.Description = programme.description
                    singleProgramNode.PLAYDURATION = programme["end"] - programme["start"]
                end for
            end if
        end if
    end for

end sub

' This function call when channeScheduledpaginate api call and get success response
sub showResponse(event as object)
    m.LoginBusySpinner.visible = false
    m.LoaderBackground.visible = false
    m.timeGride.visible = true
    m.channelList = event.getData()
    m.channelListData = m.channelList.Count()
    SaveVideo_back(m.channelList)

    m.chan_id = []
    for i = 0 to m.channelList.count() - 1
        m.chan_id.push(m.channelList[i].id)
    end for
    SaveVideo_id(m.chan_id)

    m.timeGride.numRows = m.channelListData

    if m.channelNameString = "Recent"
        makeListOFRecentChannel()
    else if m.channelNameString = "MyChannel"
        makeListMyChannel()
    else
        CategoriesChannelList()
    end if
    m.top.signalBeacon("EPGLaunchComplete")
    event.getRoSGNode().control = "stop"
    m.streamlyview.visible = false
    m.PosterBackground.visible = false
    m.navtoRelatedCHannels.visible = false
    m.navtoRelatedCHannels1.visible = false
    m.ChannelsGroup.visible = true
    m.video.visible = false
end sub

' Get Categories list from this api.
sub reqApiCAll()
    m.ChannelEpisodeTask = CreateObject("roSGNode", "ChannelEpisodeTask")
    m.ChannelEpisodeTask.observeField("CategoriesList", "CategoryResponseData")
    m.ChannelEpisodeTask.observeField("errorMessage", "errorDialogShow")
    m.ChannelEpisodeTask.control = "RUN"
end sub

sub playVideoFromDeepLinking (node as dynamic)
    closeRelatedchnnelView()
    playVideo(node)
    m.ChannelsGroup.visible = false
    m.video.visible = true
    m.video.setFocus(true)
end sub

' This function use for any common api alert.
function errorDialogShow(event as object)
    ?"event.getData().responseCode "event.getData()
    m.controllers.visible = false
    if event.getData().responseCode = 429
        ' or event.getData().responseCode = 404
        ' event.getData().responseCode = 401 or
        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "showLogin1Screen")
        setLogin("login")
        m.DialogButton.setFocus(true)
        setSession("error")
    else if event.getData().responseCode = 500

        ' Session message
        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "showLogin1Screen")
        setLogin("login")

        m.timeGride.setFocus(false)
        m.DialogButton.setFocus(true)
    else if event.getData().responseCode = 402
        ' Session message
        m.DialogButton = popupDialog(m.top, "Error ", "User account is deactivated")
        m.DialogButton.observeField("buttonSelected", "showLogin1Screen")
        setLogin("login")
        m.DialogButton.setFocus(true)
    end if

end function

function errorDialogSession(event as object)
    ?"errorDialogShow: ";event.getData()

    if event.getData().responseCode = 401
        ' Session message
        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "showLogin1Screen")
        m.video.control = "stop"
        m.controllers.visible = false
        m.DialogButton.setFocus(true)
        setLogin("login")
    else
        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "showLogin1Screen")
        m.DialogButton.setFocus(true)
        setLogin("login")
    end if

end function

sub reqApiSession()
    m.ChannelEpisodeTask = CreateObject("roSGNode", "ChannelEpisodeTask")
    m.ChannelEpisodeTask.observeField("errorMessage", "errorDialogSession")
    m.ChannelEpisodeTask.control = "RUN"
end sub

' For show login page when error comes on any api.
sub showLoginPage(event as object)
    ? "showLoginPage"
    searchScreen = event.getRoSGNode()
    m.top.removeChild(searchScreen)
    m.top.ExitView = true
end sub

function showLogin1Screen() as object
    ? "showLoginScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    setProfilescreen("login_screen")
    LoginScreen1 = m.top.createChild("LoginScreen")
    LoginScreen1.observeField("dialog", "showDialog")
    LoginScreen1.observeField("ExitView", "openHomeView")
    LoginScreen1.observeField("showPersonalDeviceName", "showPersonalDeviceNameScreen")
    LoginScreen1.setFocus(true)
    return LoginScreen1
end function

function loginScreen()
    ApiCallFor("Sign out", "POST")

    setLogin("login")
end function

sub ApiCallFor(callto as string, callType as string)
    ?"ProfileSetting::ApiCallFor: "
    m.ProfileTask = CreateObject("roSGNode", "ProfileTask") 'Create XML Parsing task node
    m.LoginBusySpinner.visible = true
    '     ApiCallFor(callto,"GET")
    m.ProfileTask.callto = callto
    m.ProfileTask.callType = callType

    m.ProfileTask.observeField("response", "responseAPI")
    m.ProfileTask.observeField("errorMessage", "errorDialogShow")

    '     m.ProfileTask.observeField("response","responseAPI")
    m.ProfileTask.control = "RUN" 'Run the task node
    ' m.top.ExitView = true

end sub

sub responseAPI(event as object)
    if m.ProfileTask.callto = "Sign out"
        ' ?"Profile::responseData.message:";m.responseData.message
        setAccountToken("")
        setThePersonalDeviceName("")
        setPersonaldeviceid("")
        m.logout = true
        m.top.logout = true
        ' m.ProfileCategory.setFocus(false)
        loginScreen()
        ' showLogin1Screen()
    end if
end sub

function closeScreen1()
    ?"closeScreen"
    m.top.getScene().exitApp = "exitApp"
    closeErrorDialogShow(m.top)
end function

' This function excute when any dialog box click on button like for e.g: cancel, okay, logout
sub closeDialog(event as object)
    ?"closeCustomDialog"; event.getRosgNode()
    m.top.removeChild(event.getRosgNode())
    closeErrorDialogShow(m.top)
    location = CreateObject("roSgNode", "Location")
    location.callType = "POST"
    location.callTO = "locationAllow"
    location.bodyData = {
        latitude: getLatitude(),
        longitude: getLongitude(),
        deviceid: getDeviceInfo().GetChannelClientId()
    }
    location.observeField("apiResponse", "locationresponse")
    location.control = "RUN"
    m.timeGride.visible = true
end sub

' This function excute when response come from category api.
' Todo: Correction : Here we need to pass the evetn as object for getting the categories list data
sub CategoryResponseData(event as object)
    ?"CategoryResponseData; "event.getData()
    m.top.signalBeacon("AppLaunchComplete")
    m.timeGride.setFocus(true)
    m.categoryList = event.getData()
    m.top.signalBeacon("EPGLaunchInitiate")
    initGrigData()
    m.channelListData = 0
    m.channelNameString = "All"
    content = CreateObject("roSGNode", "ContentNode")
    m.timeGride.content = content
    getChannelDAta(m.channelNameString)
end sub

' For use play video of any channel
function playVideo(liveStreamData) as void
    ?"getSession() "getSession()
    if getSession() <> "session"

        m.allChannel = CreateObject("roSGNode", "AllChannleTask")
        m.allChannel.callType = "GET"
        m.allChannel.observeField("response", "allChannelApiResponse")
        m.allChannel.control = "RUN"

        setVide_val("video")

        m.play.uri = "pkg:/images/pause.png"
        m.controllers.visible = true

        m.previous.width = "38"
        m.previous.height = "35"

        m.rewine.width = "35"
        m.rewine.height = "32"

        m.next.width = "38"
        m.next.height = "35"

        m.forward.width = "35"
        m.forward.height = "32"

        m.button_focus.translation = [810, -30]
        m.play.width = "42"
        m.play.height = "48"
        if getControlMode() = "pause"
            deleteControlMode()
        end if
        horesAndMinute()
        forSeekBar()
        timer1()
        reqApiSession()

        centerx = (1920 - m.playerLoader.poster.bitmapWidth) / 2
        centery = (1080 - m.playerLoader.poster.bitmapHeight) / 2
        m.playerLoader.translation = [centerx, centery]
        m.playerLoader.visible = true
        m.top.signalBeacon("VODStartInitiate")

        if liveStreamData <> invalid
            if liveStreamData.id <> invalid
                m.ApiCall = CreateObject("roSGNode", "ApiCall")
                m.ApiCall.bodyData = { "channelids": liveStreamData.id }
                m.ApiCall.callType = "POST"
                m.ApiCall.url = addRecentUrl()
                m.ApiCall.observeField("apiResponseData", "apiResponseforRecent")
                m.ApiCall.observeField("errorMessage", "errorDialogShow")
                m.ApiCall.control = "RUN"
            end if

            vidContent = CreateObject("RoSGNode", "ContentNode")
            ' drmParams = {
            '     keySystem: "Widevine"
            '     licenseServerURL: "https://streamlytv.com/api/generic?drm-type=widevine"
            ' }
            drmParams = {
                keySystem: "Playready"
                licenseServerURL: "https://streamlytv.com/api/generic?drm-type=playready"
            }

            vidContent.streamformat = "smooth"
            vidContent.drmParams = drmParams
            m.backitem = LoadVideo_back()

            ' originalUrlLength = Len(liveStreamData.url)
            ' trimLength = originalUrlLength - Len(".isml/manifest")
            ' trimmedUrl = Left(liveStreamData.url, trimLength)
            ' newUrl = trimmedUrl + "/index.mpd"

            setVideourl(liveStreamData.url)
            setVideoid(liveStreamData.id.toStr())
            vidContent.url = liveStreamData.url

            m.searchPoster.visible = true
            m.video.enableUI = false

            vidContent.live = true
            m.video.content = vidContent
            m.video.setFocus(true)
            m.streamlyview.visible = true
            m.navtoRelatedCHannels.visible = true
            m.video.control = "play"

            ' Bitmovin player

            ' m.bitmovinPlayerSDK = CreateObject("roSGNode", "ComponentLibrary")
            ' m.bitmovinPlayerSDK.id = "BitmovinPlayerSDK"
            ' m.bitmovinPlayerSDK.uri = "https://cdn.bitmovin.com/player/roku/1/bitmovinplayer.zip"
            ' m.bitmovinPlayerSDK.observeField("loadStatus", "onLoadStatusChanged")

        end if
    else
        deleteSession()
        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "loginScreen")

        m.DialogButton.setFocus(true)
    end if

end function

sub onLoadStatusChanged()
    if m.bitmovinPlayerSDK.loadStatus = "ready"
        ' Once the player library is loaded and ready, we can use it to reference the BitmovinPlayer component
        m.bitmovinPlayer = CreateObject("roSGNode", "BitmovinPlayerSDK:BitmovinPlayer")

        m.top.appendChild(m.bitmovinPlayer)
        m.playerConfig = {
            playback: {
                autoplay: true,
                muted: false
            },

            key: "84e0eb02-57d1-40a6-931e-106cbe60e5a6" ' The license key is only required1 here if it wasn't added in the manifest
        }



        m.sourceConfig = {

            dash: "https://channelstore3.streamlytv.com/FNC/index.mpd",
            drm: {
                widevine: {
                    LA_URL: "https://streamlytv.com/api/generic?drm-type=widevine"
                }
            },
            title: "Streamly"
        }
        m.bitmovinPlayer.callFunc("setup", m.playerConfig)
        m.bitmovinPlayer.visible = true
        m.bitmovinPlayer.callFunc("load", m.sourceConfig)
        m.bitmovinPlayer.setFocus(true)
        m.bitmovinPlayer.control = "play"
        ' m.bitmovinPlayer.play()
        m.BitmovinFunctions = m.bitmovinPlayer.BitmovinFunctions
        m.BitmovinFields = m.bitmovinPlayer.BitmovinFields
        m.BitmovinPlayerState = m.bitmovinPlayer.BitmovinPlayerState
        m.bitmovinPlayer.ObserveField(m.BitmovinFields.PLAYER_STATE, "onStateChange1")
        m.bitmovinPlayer.ObserveField(m.BitmovinFields.ERROR, "onVideoError")
        m.bitmovinPlayer.ObserveField(m.BitmovinFields.SEEK, "onSeek")
        m.bitmovinPlayer.ObserveField(m.BitmovinFields.SEEKED, "onSeeked")
    end if
end sub

sub onStateChange1()
    if m.bitmovinPlayer.playerState = m.BitmovinPlayerState.FINISHED
        print "Video has finished playing"
    end if
end sub

sub onSeek()
    print "SEEKING"
end sub

sub onSeeked()
    print "SEEKED: "; m.bitmovinPlayer.seeked
end sub

sub onVideoError()
    print "ERROR: "; m.bitmovinPlayer.error.code.toStr() + ": " + m.bitmovinPlayer.error.message
end sub

function RegRead(key, section = invalid)
    if section = invalid section = "Default"
    sec = CreateObject("roRegistrySection", section)
    if sec.Exists(key)
        return true
    end if
    return false
end function

function RegWrite(key, val, section = invalid)
    if section = invalid section = "Default"
    sec = CreateObject("roRegistrySection", section)
    sec.Write(key, val)
end function

function RegDelete(key, section = invalid)
    if section = invalid section = "Default"
    sec = CreateObject("roRegistrySection", section)
    sec.Delete(key)
    sec.Flush()
end function

' Call when Recent api's success response.
sub apiResponseforRecent(event as object)
    ?"apiResponseforRecent";event.getData()
    if event.getData() <> invalid
        if event.getData().response_code = 200
            m.ChannelData = event.getData().data
        else
            ?"Call when Recent api's success response"
            m.needHelpText = "Error"
            ' showDialog("StreamlyTV ", "Oops! There is a technical issue!  We are on it!  Please try again later!", true, "", "")
        end if
    end if

end sub

' this function is for the all category on the top Markup grid like all, recent, sports, News and etc ...
sub initGrigData()
    ? "m.ChannelCategoryMarkuGrid:init()"
    marupContent = CreateObject("roSGNode", "ContentNode")
    for each item in m.CategoryList
        marupContent.createChild("ContentNode").TITLE = item
    end for
    m.ChannelCategoryMarkuGrid.content = marupContent
    m.ChannelCategoryMarkuGrid.observeField("itemSelected", "categorySelcted")
    m.ChannelCategoryMarkuGrid.setFocus(false)
end sub

' When select any category from list
sub categorySelcted(event as object)
    currentTime = CreateObject("roDateTime") ' roDateTime is initialized
    m.PosterliveIcon.uri = "pkg:/images/red1.png"
    m.PosterIconLabel.text = "Live"
    m.PosterIconLabel.translation = [35, 2]

    ' t = currentTime.AsSeconds()
    ' t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark

    ' datetime = CreateObject("roDateTime")
    ' datetime.FromSeconds(m.timeGride.contentStartTime)

    ' currentdate = (datetime.GetDayOfMonth() + 1).ToStr()

    ' if datetime.GetHours() > 12 and datetime.GetHours() < 23 and datetime.GetMinutes() < 59
    '     if currentTime.GetDayOfMonth().ToStr() = currentdate

    '         m.timeGride.leftEdgeTargetTime = t
    '         m.timeGride.maxDays = 2

    '         m.timeGride.setFields({
    '             showPastTimeScreen: true
    '             channelInfoComponentName: "ChannelItemComponent"
    '         })
    '         m.PosterliveIcon.setFocus(false)
    '         m.timeGride.setFocus(true)
    '     else
    '         m.timeGride.leftEdgeTargetTime = t
    '         m.timeGride.maxDays = 2
    '         m.timeGride.setFields({
    '             showPastTimeScreen: true
    '             channelInfoComponentName: "ChannelItemComponent"
    '         })
    '         m.PosterliveIcon.setFocus(false)
    '         m.timeGride.setFocus(true)
    '     end if
    ' else

    '     if currentTime.GetDayOfMonth().ToStr() = currentdate
    '         m.timeGride.leftEdgeTargetTime = t
    '         m.timeGride.maxDays = 2

    '         m.timeGride.setFields({
    '             showPastTimeScreen: true
    '             channelInfoComponentName: "ChannelItemComponent"
    '         })
    '         m.PosterliveIcon.setFocus(false)
    '         m.timeGride.setFocus(true)
    '     else
    '         m.screen = showHomeScreen()
    '         m.screen.observeField("ExitView", "openLoginView")
    '     end if
    ' end if
    RegWrite("selected", "selected")

    m.highlight = event.getRoSGNode().content.getChild(event.getData())
    m.relatedChannelText.text = event.getRoSGNode().content.getChild(event.getData()).TITLE
    m.channelNameString = event.getRoSGNode().content.getChild(event.getData()).TITLE

    if m.channelNameString = "All"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true
        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        end if

        RegWrite("All", "All")
        m.focusHeighlightAll = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightAllLabel = m.focusHeighlightAll.createChild("Label")
        m.focusHeighlightAll.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightAll.height = 60
        m.focusHeighlightAll.width = 110

        m.PosterHeighlightAllLabel.text = m.channelNameString
        m.PosterHeighlightAllLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightAll.translation = [45, 0]
        m.PosterHeighlightAllLabel.translation = [40, 10]
    else if m.channelNameString = "Recent"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true
        if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        end if

        RegWrite("Recent", "Recent")
        m.focusHeighlightRecent = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightRecentLabel = m.focusHeighlightRecent.createChild("Label")
        m.focusHeighlightRecent.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightRecent.height = 60
        m.focusHeighlightRecent.width = 170
        m.PosterHeighlightRecentLabel = m.focusHeighlightRecent.createChild("Label")
        m.PosterHeighlightRecentLabel.text = m.channelNameString
        m.PosterHeighlightRecentLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightRecent.translation = [185, 0]
        m.PosterHeighlightRecentLabel.translation = [40, 10]
    else if m.channelNameString = "Others"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true
        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        end if

        RegWrite("Others", "Others")
        m.focusHeighlightOthers = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightOthersLabel = m.focusHeighlightOthers.createChild("Label")
        m.focusHeighlightOthers.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightOthers.height = 60
        m.focusHeighlightOthers.width = 170
        m.PosterHeighlightOthersLabel = m.focusHeighlightOthers.createChild("Label")
        m.PosterHeighlightOthersLabel.text = m.channelNameString
        m.PosterHeighlightOthersLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightOthers.translation = [360, 0]
        m.PosterHeighlightOthersLabel.translation = [40, 10]
    else if m.channelNameString = "Sports"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true
        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        end if

        RegWrite("Sports", "Sports")
        m.focusHeighlightSports = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightSportsLabel = m.focusHeighlightSports.createChild("Label")
        m.focusHeighlightSports.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightSports.height = 60
        m.focusHeighlightSports.width = 170
        m.PosterHeighlightSportsLabel = m.focusHeighlightSports.createChild("Label")
        m.PosterHeighlightSportsLabel.text = m.channelNameString
        m.PosterHeighlightSportsLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightSports.translation = [545, 0]
        m.PosterHeighlightSportsLabel.translation = [40, 10]
    else if m.channelNameString = "Movies"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true
        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        end if
        RegWrite("Movies", "Movies")
        m.focusHeighlightMovies = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightMoviesLabel = m.focusHeighlightMovies.createChild("Label")
        m.focusHeighlightMovies.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightMovies.height = 60
        m.focusHeighlightMovies.width = 170
        m.PosterHeighlightMoviesLabel = m.focusHeighlightMovies.createChild("Label")
        m.PosterHeighlightMoviesLabel.text = m.channelNameString
        m.PosterHeighlightMoviesLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightMovies.translation = [720, 0]
        m.PosterHeighlightMoviesLabel.translation = [40, 10]
    else if m.channelNameString = "Family"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true

        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        end if
        RegWrite("Family", "Family")
        m.focusHeighlightFamily = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightFamilyLabel = m.focusHeighlightFamily.createChild("Label")
        m.focusHeighlightFamily.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightFamily.height = 60
        m.focusHeighlightFamily.width = 170
        m.PosterHeighlightFamilyLabel = m.focusHeighlightFamily.createChild("Label")
        m.PosterHeighlightFamilyLabel.text = m.channelNameString
        m.PosterHeighlightFamilyLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightFamily.translation = [895, 0]
        m.PosterHeighlightFamilyLabel.translation = [40, 10]
    else if m.channelNameString = "Kids"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true

        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        end if
        RegWrite("Kids", "Kids")
        m.focusHeighlightKids = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightKidsLabel = m.focusHeighlightKids.createChild("Label")
        m.focusHeighlightKids.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightKids.height = 60
        m.focusHeighlightKids.width = 140
        m.PosterHeighlightKidsLabel = m.focusHeighlightKids.createChild("Label")
        m.PosterHeighlightKidsLabel.text = m.channelNameString
        m.PosterHeighlightKidsLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightKids.translation = [1100, 0]
        m.PosterHeighlightKidsLabel.translation = [40, 10]
    else if m.channelNameString = "News"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true

        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        end if
        RegWrite("News", "News")
        m.focusHeighlightNews = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightNewsLabel = m.focusHeighlightNews.createChild("Label")
        m.focusHeighlightNews.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightNews.height = 60
        m.focusHeighlightNews.width = 140
        m.PosterHeighlightNewsLabel = m.focusHeighlightNews.createChild("Label")
        m.PosterHeighlightNewsLabel.text = m.channelNameString
        m.PosterHeighlightNewsLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightNews.translation = [1275, 0]
        m.PosterHeighlightNewsLabel.translation = [40, 10]
    else if m.channelNameString = "Comedy"
        m.timeGride.visible = false
        m.LoginBusySpinner.visible = true
        m.LoaderBackground.visible = true
        if RegRead("Recent")
            m.focusHeighlightRecent.uri = ""
            m.PosterHeighlightRecentLabel.text = ""
            RegDelete("Recent")
        else if RegRead("Sports")
            m.focusHeighlightSports.uri = ""
            m.PosterHeighlightSportsLabel.text = ""
            RegDelete("Sports")
        else if RegRead("Others")
            m.focusHeighlightOthers.uri = ""
            m.PosterHeighlightOthersLabel.text = ""
            RegDelete("Others")
        else if RegRead("Movies")
            m.focusHeighlightMovies.uri = ""
            m.PosterHeighlightMoviesLabel.text = ""
            RegDelete("Movies")
        else if RegRead("Family")
            m.focusHeighlightFamily.uri = ""
            m.PosterHeighlightFamilyLabel.text = ""
            RegDelete("Family")
        else if RegRead("Kids")
            m.focusHeighlightKids.uri = ""
            m.PosterHeighlightKidsLabel.text = ""
            RegDelete("Kids")
        else if RegRead("News")
            m.focusHeighlightNews.uri = ""
            m.PosterHeighlightNewsLabel.text = ""
            RegDelete("News")
        else if RegRead("All")
            m.focusHeighlightAll.uri = ""
            m.PosterHeighlightAllLabel.text = ""
            RegDelete("All")
        else if RegRead("Comedy")
            m.focusHeighlightComedy.uri = ""
            m.PosterHeighlightComedyLabel.text = ""
            RegDelete("Comedy")
        end if

        RegWrite("Comedy", "Comedy")
        m.focusHeighlightComedy = m.ChannelCategoryMarkuGrid.createChild("Poster")
        m.PosterHeighlightComedyLabel = m.focusHeighlightComedy.createChild("Label")
        m.focusHeighlightComedy.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focusHeighlightComedy.height = 60
        m.focusHeighlightComedy.width = 170
        m.PosterHeighlightComedyLabel = m.focusHeighlightComedy.createChild("Label")
        m.PosterHeighlightComedyLabel.text = m.channelNameString
        m.PosterHeighlightComedyLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
        m.focusHeighlightComedy.translation = [1435, 0]
        m.PosterHeighlightComedyLabel.translation = [40, 10]
    end if

    m.timeGride.setFocus(false)

    m.ChannelCategoryMarkuGrid.setFocus(true)
    content = CreateObject("roSGNode", "ContentNode")
    m.timeGride.content = content

    getChannelDAta(m.channelNameString)
    if m.channelNameString = "MyChannel" or m.channelNameString = "Recent"
        getUsersRecentAndFavouriteData()
    end if
end sub

' For selected program action.
function programSelected(event as object) as object
    timeGrid = event.GetRoSGNode()
    ?"timegrid:"timeGrid
    m.allChannel = CreateObject("roSGNode", "AllChannleTask")
    m.allChannel.callType = "GET"
    m.allChannel.observeField("response", "allChannelApiResponse")
    m.allChannel.control = "RUN"
    streamNode = timeGrid.content.getChild(timeGrid.channelSelected)
    stream = { key: streamNode }
    setVideourl(streamNode.URL)
    playVideo(streamNode)
    m.ChannelsGroup.visible = false
    m.video.visible = true
    m.video.setFocus(true)
    return true
end function

function getDifferences(numbers as object) as object
    differences = []
    indexes = []
    num_diff = []
    num_diff1 = []
    for i = 1 to numbers.count() - 1
        difference = numbers[i] - numbers[i - 1]
        differences.push(difference)

        if (numbers[i] - numbers[i - 1]) > 1
            indexes.push(numbers[i])
            num_diff.push((numbers[i - 1]))
            num_diff1.push((numbers[i] - numbers[i - 1]))
        end if
    end for
    return [num_diff, indexes, num_diff1]
end function

function unique(arr as object)
    res = []
    table = {}
    for each v in arr
        k = v.toStr()
        if not table.doesExist(k)
            res.push(v)
            table[k] = true
        end if
    end for
    return res
end function

sub channel_drawyer()
    ?"LoadVideo_id()[i] channel"i
    videoIdDrawer = LoadVideo_id_drawyer()

    ' Convert the video ID to an integer
    videoIdInt = getVideoid().toInt()

    for i = 0 to  videoIdDrawer.count() - 1

        if videoIdDrawer[i] = videoIdInt then
            ?"LoadVideo_id()[i] channel"i
            m.relatedChannelMarkupList.jumpToItem = i
            ' m.relatedChannelMarkupList.animateToItem = i - 3
            exit for
        end if
    end for
end sub

sub allChannelApiResponse(event as object)
    if(event.getData().success = true and event.getData().response_code = 200)
        ?"event.getData() ",event.getData()
        m.AllChannelApiResponse = event.getData()
        m.labelContent = createObject("roSGNode", "ContentNode")
        m.channels.push(event.getData().data)
        m.chan_id1 = []
        for each channel in event.getData().data


            m.chan_id1.push(channel.id)
            ' SaveVideo_id_drawyer(m.chan_id1)


            channelDetail = m.labelContent.createChild("ContentNode")
            if channel.callSign <> invalid
                channelDetail.title = channel.stationName
            else
                channelDetail.title = "other"
            end if
            if channel.url <> invalid then channelDetail.url = channel.url
            if channel.id <> invalid then channelDetail.id = channel.id
            if channel.logo <> invalid then channelDetail.HDSMALLICONURL = channel.logo
            if channel.callSign <> invalid then channelDetail.title = channel.stationName
        end for
        ?"channel.id" channel.id
        m.relatedChannelMarkupList.content = m.labelContent
        ' Function for Related channels drawyer
        channel_drawyer()
    end if
end sub

sub conncertUpdate(timeGrid as object)
    m.programIndex += timeGrid.programSelected

    if m.programUpdateTimer.control <> invalid and m.programUpdateTimer.control = "start"
        m.programUpdateTimer.control = "stop"
    end if

    m.currentProgramsNode = timeGrid.content.getChild(timeGrid.channelSelected)
    programnode = m.currentProgramsNode.getChild(m.programIndex)

    if programnode <> invalid
        programdate = CreateObject("roDateTime")
        startTime = programnode.PLAYSTART
        programdate.FromSeconds(startTime)
        durationTimetext = programdate.toISOString().Mid(11, 5) + " - "
        duration = programnode.PLAYSTART + programnode.PLAYDURATION
        programdate.FromSeconds(duration)
        durationTimetext += programdate.toISOString().Mid(11, 5)
        m.ConsertTimingTExt.text = durationTimetext
        m.ConcertChannel.text = m.currentProgramsNode.TITLE
        m.ConcertName.text = programnode.TITLE
        m.programUpdateTimer.duration = CInt(programnode.PLAYDURATION)
        m.programUpdateTimer.control = "start"
    end if
end sub

sub VideoControl(event as object)
    video = event.getRoSGNode()
end sub

sub closeSearchScreen(event as object)
    ? "closeSearchScreen; "
    searchScreen = event.getRoSGNode()
    exitView = searchScreen.exitView

    if exitView
        if RegRead("errorcode") = true
            RegDelete("errorcode")
            if m.screen <> invalid then m.top.removeChild(m.screen)
            HomeScreen = m.top.createChild("HomeScreen")
            HomeScreen.setFocus(true)
        else
            m.top.removeChild(searchScreen)
            m.searchPoster.setFocus(true)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.video.setFocus(false)
            m.focusPoster.uri = "pkg:/images/home/channelborderfocus.png"
        end if
    end if
end sub


sub closeProfileScreen(event as object)
    if getProfilescreen() = "login_screen"
        setLogin("login")
    else
        searchScreen = event.getRoSGNode()
        exitView = searchScreen.exitView
        if exitView
            m.top.removeChild(searchScreen)
            m.searchPoster.setFocus(false)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.timeGride.setFocus(true)
            m.focusPoster.uri = ""
        end if
    end if
end sub


sub playSearchChannelUrl(event as object)
    ? "playSearchChannelUrl:"; event.getData()
    showDialogDevice("", false, false, "")
    closeDialogNext()
    m.allChannel = CreateObject("roSGNode", "AllChannleTask")
    m.allChannel.callType = "GET"
    m.allChannel.observeField("response", "allChannelApiResponse")
    m.allChannel.control = "RUN"
    RegWrite("search_video", "search_video")
    searchScreen = event.getRoSGNode()
    m.top.removeChild(searchScreen)
    m.ChannelsGroup.visible = false
    m.video.visible = true
    playVideo(event.getData())
end sub

sub closeRelatedchnnelView()
    ?"closeRelatedchnnelView"
    if m.fire = false
        m.navtoRelatedCHannels.visible = true
        m.navtoRelatedCHannels.setfocus(false)
    else
        m.video.setfocus(true)
    end if
    m.relatedChannelMarkupList.setFocus(false)
    m.RelatedChannlesView.visible = false
    m.relatedChannelMarkupList.visible = false
    m.relatedChannelText.visible = false
    m.navtoRelatedCHannels.visible = true
    m.navtoRelatedCHannels1.visible = true
    m.navtoRelatedCHannels.setFocus(true)
end sub

function showHomeScreen() as object
    ? "showHomeScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    HomeScreen = m.top.createChild("HomeScreen")
    HomeScreen.setFocus(true)
    return HomeScreen
end function

' Handle remote key press and manage actions.
function onKeyEvent(key as string, press as boolean) as boolean
    ? "HomeScreen::key: "; key
    ?"(m.controllers.visible = true) and (m.play.width).ToStr()"m.controllers.visible
    ?"(m.controllers.visible = true) and (m.play.width).ToStr()"m.play.width.ToStr()
    ?m.timeGride.hasFocus()

    result = false

    if press
        if (key = "OK" and m.navtoRelatedCHannels.hasFocus())
            if m.controllers.visible = false
                m.relatedChannelMarkupList.setfocus(true)
                m.navtoRelatedCHannels.visible = false
                m.navtoRelatedCHannels1.visible = false
                m.RelatedChannlesView.visible = true
                m.relatedChannelMarkupList.visible = true
                m.relatedChannelText.visible = true
                m.controllers.visible = false
            else if (key = "left" and m.controllers.visible = true and (m.forward.width).ToStr() = "45")
                m.forward.setFocus(false)
                m.play.setFocus(false)
                m.forward.width = "35"
                m.forward.height = "32"
                m.button_focus.translation = [810, -30]
                m.play.width = "42"
                m.play.height = "48"
            else if (key = "left" and m.controllers.visible = true and (m.next.width).ToStr() = "48")
                m.forward.setFocus(false)
                m.play.setFocus(false)
                m.forward.width = "45"
                m.forward.height = "42"
                m.button_focus.translation = [1010, -30]
                m.next.width = "38"
                m.next.height = "35"
            else if (key = "left" and m.controllers.visible = true and (m.play.width).ToStr() = "42")
                m.forward.setFocus(false)
                m.play.setFocus(false)
                m.rewine.width = "45"
                m.rewine.height = "42"
                m.button_focus.translation = [610, -30]
                m.play.width = "32"
                m.play.height = "38"
            else if (key = "left" and m.controllers.visible = true and (m.rewine.width).ToStr() = "45")
                m.forward.setFocus(false)
                m.play.setFocus(false)
                m.rewine.width = "35"
                m.rewine.height = "32"
                m.button_focus.translation = [410, -30]
                m.previous.width = "48"
                m.previous.height = "45"
            else if (key = "OK" and m.controllers.visible = true and (m.rewine.width).ToStr() = "45")
                m.rewine.setFocus(false)
                m.play.setFocus(false)
                forRewine()
                result = true
            else if (key = "OK" and m.controllers.visible = true and (m.forward.width).ToStr() = "45")
                m.rewine.setFocus(false)
                m.play.setFocus(false)
                forForward()
                result = true
            else if (key = "OK" and m.controllers.visible = true and (m.next.width).ToStr() = "48")
                m.rewine.setFocus(false)
                m.play.setFocus(false)
                forNext()
                result = true
            else if (key = "OK" and m.controllers.visible = true and (m.previous.width).ToStr() = "48")
                m.rewine.setFocus(false)
                m.play.setFocus(false)
                forPrevious()
                result = true
            else if (key = "OK" and (m.controllers.visible = true) and (m.play.width).ToStr() = "42")
                ?"controller ok "
            if RegRead("selectedchan") = false
                ?"controller ok "
                if m.video.state = "playing"
                    setControlMode("pause")
                    m.play.uri = "pkg:/images/play.png"
                    m.video.control = "pause"
                else if m.video.state = "paused"
                    setControlMode("play")
                    m.video.control = "resume"
                    onStateChange()
                    m.play.uri = "pkg:/images/pause.png"
                end if
            else
                RegDelete("selectedchan")
            end if
            result = true
            end if
            result = true
        else if (key = "up" and m.controllers.visible = true)
            m.navtoRelatedCHannels.setFocus(true)
            m.navtoRelatedCHannels1.visible = true
            m.controllers.visible = false
            result = true
        else if (key = "down" and m.navtoRelatedCHannels.hasFocus())
            ' m.navtoRelatedCHannels.setFocus(false)
            m.navtoRelatedCHannels1.visible = false
            m.controllers.visible = true
            result = true
        else if (key = "OK" and (m.controllers.visible = true) and (m.play.width).ToStr() = "42")
                ?"controller ok "
            if RegRead("selectedchan") = false
                ?"controller ok "
                if m.video.state = "playing"
                    setControlMode("pause")
                    m.play.uri = "pkg:/images/play.png"
                    m.video.control = "pause"
                else if m.video.state = "paused"
                    setControlMode("play")
                    m.video.control = "resume"
                    onStateChange()
                    m.play.uri = "pkg:/images/pause.png"
                end if
            else
                RegDelete("selectedchan")
            end if
            result = true
        else if (key = "right" and m.controllers.visible = true and (m.play.width).ToStr() = "42")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.button_focus.translation = [1010, -30]
            m.forward.width = "45"
            m.forward.height = "42"
            m.play.width = "32"
            m.play.height = "38"
            result = true
        else if (key = "left" and m.controllers.visible = true and (m.forward.width).ToStr() = "45")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.forward.width = "35"
            m.forward.height = "32"
            m.button_focus.translation = [810, -30]
            m.play.width = "42"
            m.play.height = "48"
            result = true
        else if (key = "right" and m.controllers.visible = true and (m.forward.width).ToStr() = "45")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.forward.width = "35"
            m.forward.height = "32"
            m.button_focus.translation = [1210, -30]
            m.next.width = "48"
            m.next.height = "45"
            result = true
        else if (key = "left" and m.controllers.visible = true and (m.next.width).ToStr() = "48")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.forward.width = "45"
            m.forward.height = "42"
            m.button_focus.translation = [1010, -30]
            m.next.width = "38"
            m.next.height = "35"
            result = true

        else if (key = "left" and m.controllers.visible = true and (m.play.width).ToStr() = "42")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.rewine.width = "45"
            m.rewine.height = "42"
            m.button_focus.translation = [610, -30]
            m.play.width = "32"
            m.play.height = "38"
            result = true
        else if (key = "right" and m.controllers.visible = true and (m.rewine.width).ToStr() = "45")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.rewine.width = "35"
            m.rewine.height = "32"
            m.button_focus.translation = [810, -30]
            m.play.width = "42"
            m.play.height = "48"
            result = true
        else if (key = "left" and m.controllers.visible = true and (m.rewine.width).ToStr() = "45")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.rewine.width = "35"
            m.rewine.height = "32"
            m.button_focus.translation = [410, -30]
            m.previous.width = "48"
            m.previous.height = "45"
            result = true
        else if (key = "right" and m.controllers.visible = true and (m.previous.width).ToStr() = "48")
            m.forward.setFocus(false)
            m.play.setFocus(false)
            m.rewine.width = "45"
            m.rewine.height = "42"
            m.button_focus.translation = [610, -30]
            m.previous.width = "38"
            m.previous.height = "35"
            result = true
        else if (key = "OK" and m.controllers.visible = true and (m.rewine.width).ToStr() = "45")
            m.rewine.setFocus(false)
            m.play.setFocus(false)
            forRewine()
            result = true
        else if (key = "OK" and m.controllers.visible = true and (m.forward.width).ToStr() = "45")
            m.rewine.setFocus(false)
            m.play.setFocus(false)
            forForward()
            result = true
        else if (key = "OK" and m.controllers.visible = true and (m.next.width).ToStr() = "48")
            m.rewine.setFocus(false)
            m.play.setFocus(false)
            forNext()
            result = true
        else if (key = "OK" and m.controllers.visible = true and (m.previous.width).ToStr() = "48")
            m.rewine.setFocus(false)
            m.play.setFocus(false)
            forPrevious()
            result = true
        else if (key = "right" and m.relatedChannelMarkupList.hasFocus())
            closeRelatedchnnelView()
            result = true
        else if m.searchPoster.hasFocus() and key = "down"
            m.searchPoster.setFocus(false)
            m.focusPoster.uri = ""
            m.timeGride.setFocus(true)
            result = true
        else if m.searchPoster.hasFocus() and key = "OK"
            m.searchPoster.setFocus(false)
            m.focusPoster.uri = ""
            if m.top.findNode("SearchScreen") = invalid
                SearchScreen = m.top.createChild("SearchScreen")
                SearchScreen.id = "SearchScreen"
                SearchScreen.observeField("exitView", "closeSearchScreen")
                SearchScreen.observeField("playChannel", "playSearchChannelUrl")
            else
                ' SearchScreen already exists, retrieve the reference
                SearchScreen = m.top.findNode("SearchScreen")
            end if

            SearchScreen.setFocus(true)
            result = true
        else if m.ChannelCategoryMarkuGrid.hasFocus() and key = "left"
            m.searchPoster.setFocus(true)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.focusPoster.uri = "pkg:/images/home/channelborderfocus.png"
            result = true
        else if m.Tryagain.hasFocus() and key = "left"
            m.Tryagain.setFocus(false)
            m.Close.setFocus(true)
            return true
        else if m.Close.hasFocus() and key = "right"
            m.Close.setFocus(false)
            m.Tryagain.setFocus(true)
            return true
        else if (m.deviceView.visible and key = "back")

            closeTryAgain()
            return true
        else if m.searchPoster.hasFocus() and key = "right"
            m.ChannelCategoryMarkuGrid.setFocus(true)
            m.searchPoster.setFocus(false)
            m.focusPoster.uri = ""
            result = true
        else if m.timeGride.hasFocus() and key = "up"
            if m.PosterIconLabel.text = "Go Live"
                m.PosterIconLabel.translation = [10, 4]
                m.PosterliveIcon.height = 50
                m.PosterliveIcon.width = 130
                m.PosterIconLabel.font = getFont(28, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                ' m.PosterIconLabel.translation = [35, 4]
            else
                ' m.PosterIconLabel.translation = [10, 4]
                m.PosterliveIcon.height = 50
                m.PosterliveIcon.width = 130
                m.PosterIconLabel.font = getFont(28, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                m.PosterIconLabel.translation = [35, 4]
            end if
            m.PosterliveIcon.uri = "pkg:/images/red1.png"
            m.PosterliveIcon.setFocus(true)
            m.timeGride.setFocus(false)
            m.video.setFocus(false)
            result = true
        else if m.PosterliveIcon.hasFocus() and key = "up"
            if m.PosterIconLabel.text <> "Live"
                m.PosterliveIcon.uri = "pkg:/images/grey.png"
            end if

            if m.PosterIconLabel.text = "Go Live"
                m.PosterIconLabel.translation = [10, 2]
                m.PosterliveIcon.height = 40
                m.PosterliveIcon.width = 120
                m.PosterIconLabel.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                ' m.PosterIconLabel.translation = [35, 2]
            else
                ' m.PosterIconLabel.translation = [10, 4]
                m.PosterliveIcon.height = 40
                m.PosterliveIcon.width = 120
                m.PosterIconLabel.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                m.PosterIconLabel.translation = [35, 2]
            end if
            
            m.PosterliveIcon.setFocus(false)
            m.ChannelCategoryMarkuGrid.setFocus(true)
            m.video.setFocus(false)
            result = true
        else if m.ChannelCategoryMarkuGrid.hasFocus() and key = "down"
            if m.PosterIconLabel.text = "Go Live"
                m.PosterIconLabel.translation = [10, 4]
                m.PosterliveIcon.height = 50
                m.PosterliveIcon.width = 130
                m.PosterIconLabel.font = getFont(28, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                ' m.PosterIconLabel.translation = [35, 2]
            else
                ' m.PosterIconLabel.translation = [10, 4]
                m.PosterliveIcon.height = 50
                m.PosterliveIcon.width = 130
                m.PosterIconLabel.font = getFont(28, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                m.PosterIconLabel.translation = [35, 4]
            end if
            m.PosterliveIcon.uri = "pkg:/images/red1.png"
            m.PosterliveIcon.setFocus(true)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.video.setFocus(false)
            m.timeGride.setFocus(false)
            result = true
        else if m.ChannelCategoryMarkuGrid.hasFocus() and key = "right"
            ?"working"
            result = true
        else if m.PosterliveIcon.hasFocus() and key = "down"
            if m.PosterIconLabel.text <> "Live"
                m.PosterliveIcon.uri = "pkg:/images/grey.png"
            end if
            if m.PosterIconLabel.text = "Go Live"
                m.PosterIconLabel.translation = [10, 2]
                m.PosterliveIcon.height = 40
                m.PosterliveIcon.width = 120
                m.PosterIconLabel.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                ' m.PosterIconLabel.translation = [35, 2]
            else
                ' m.PosterIconLabel.translation = [10, 4]
                m.PosterliveIcon.height = 40
                m.PosterliveIcon.width = 120
                m.PosterIconLabel.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
                m.PosterIconLabel.translation = [35, 2]
            end if
            
            m.PosterliveIcon.setFocus(false)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.video.setFocus(false)
            m.timeGride.setFocus(true)
            result = true
        else if (m.relatedChannelMarkupList.hasFocus() and key = "back")
            closeRelatedchnnelView()
            result = true
        else if (((m.navtoRelatedCHannels.hasFocus()) or m.controllers.visible = true or m.video.visible) and key = "back")
            deleteVide_val()
            m.navtoRelatedCHannels.visible = false
            m.navtoRelatedCHannels1.visible = false
            if RegRead("search_video")
                RegDelete("search_video")
                m.video.control = "stop"
                m.controllers.visible = false
                SearchScreen = m.top.createChild("SearchScreen")
                SearchScreen.id = "SearchScreen"
                SearchScreen.observeField("exitView", "closeSearchScreen")
                SearchScreen.observeField("playChannel", "playSearchChannelUrl")
                SearchScreen.setFocus(true)
            else


                if getVide_val() = "video"
                    m.screen = showHomeScreen()
                    m.screen.observeField("ExitView", "openLoginView")
                else
                    closeTryAgain()

                end if
            end if
            return true
        else if m.LiveChannelError <> invalid and m.LiveChannelError.hasFocus() and key = "back"
            closeErrorDialogShow(m.top)
            m.LiveChannelError.visible = false
            return true
        else if m.PosterliveIcon.hasFocus() and key = "OK"
            currentTime = CreateObject("roDateTime") ' roDateTime is initialized
            m.PosterliveIcon.uri = "pkg:/images/red1.png"
            m.PosterIconLabel.text = "Live"
            m.PosterIconLabel.translation = [35, 2]

            ' to the current time
            t = currentTime.AsSeconds()
            t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
            ' m.timeGride.contentStartTime = t
            datetime = CreateObject("roDateTime")
            datetime.FromSeconds(m.timeGride.contentStartTime)
            currentdate = (datetime.GetDayOfMonth() + 1).ToStr()

            if currentTime.GetHours() >= 12 and currentTime.GetHours() <= 23 and currentTime.GetMinutes() <= 59
                if currentTime.GetDayOfMonth().ToStr() = currentdate
                    m.timeGride.leftEdgeTargetTime = t
                    m.timeGride.contentStartTime = t
                    m.timeGride.maxDays = 5
                    m.timeGride.setFields({
                        showPastTimeScreen: true
                        channelInfoComponentName: "ChannelItemComponent"
                    })
                    m.PosterliveIcon.setFocus(false)
                    m.timeGride.setFocus(true)
                else
                    m.timeGride.leftEdgeTargetTime = t
                    m.timeGride.contentStartTime = t
                    m.timeGride.maxDays = 5
                    m.timeGride.setFields({
                        showPastTimeScreen: true
                        channelInfoComponentName: "ChannelItemComponent"
                    })
                    m.PosterliveIcon.setFocus(false)
                    m.timeGride.setFocus(true)
                end if
            else

                if currentTime.GetDayOfMonth().ToStr() = currentdate
                    m.timeGride.leftEdgeTargetTime = t
                    m.timeGride.maxDays = 5
                    m.timeGride.contentStartTime = t
                    m.timeGride.setFields({
                        showPastTimeScreen: true
                        channelInfoComponentName: "ChannelItemComponent"
                    })
                    m.PosterliveIcon.setFocus(false)
                    m.timeGride.setFocus(true)
                else
                    ' m.screen = showHomeScreen()
                    ' m.screen.observeField("ExitView", "openLoginView")
                    m.timeGride.leftEdgeTargetTime = t
                    m.timeGride.maxDays = 5
                    ' m.timeGride.contentStartTime = t
                    m.timeGride.setFields({
                        showPastTimeScreen: true
                        channelInfoComponentName: "ChannelItemComponent"
                    })
                    m.PosterliveIcon.setFocus(false)
                    m.timeGride.setFocus(true)
                end if
            end if

        else if m.video.hasFocus() and key = "OK"
            ?"ok pressed fr play video"

            if m.streamlyview.visible = false
                m.streamlyview.visible = true
                m.navtoRelatedCHannels.visible = true
                m.navtoRelatedCHannels.setFocus(true)
                m.fire = false
                m.streaminfoTimer.control = "start"
                m.controllers.visible = true
            end if
            result = true
        else if (m.timeGride.hasFocus() = true or m.searchPoster.hasFocus() or m.ChannelCategoryMarkuGrid.hasFocus()) and key = "options"

            ? "HomeSceen:open profile setting view";
            m.searchPoster.setFocus(false)
            m.ChannelCategoryMarkuGrid.setFocus(false)
            m.focusPoster.uri = ""
            m.timeGride.setFocus(false)

            ProfileSettingScreen = m.top.createChild("ProfileSettingScreen")
            setSettingMode("setting")
            ProfileSettingScreen.id = "ProfileSettingScreen"
            ProfileSettingScreen.observeField("exitView", "closeProfileScreen")
            ProfileSettingScreen.observeField("logout", "showLoginPage")

            result = true
        end if
        return result
    else
    end if
    return result
end function

sub closePlayer()
    m.controllers.visible = false
    ?"close player:";m.video.control
    if m.LiveChannelError <> invalid
        closeErrorDialogShow(m.top)
    end if

    if m.streaminfoTimer.control <> invalid and m.streaminfoTimer.control = "start"
        m.streaminfoTimer.control = "stop"
    end if

    if m.programUpdateTimer.control <> invalid and m.programUpdateTimer.control = "start"
        m.programUpdateTimer.control = "stop"
    end if

    closeRelatedchnnelView()
    m.streamlyview.visible = false
    m.PosterBackground.visible = false
    m.navtoRelatedCHannels.visible = false
    m.navtoRelatedCHannels1.visible = false
    m.ChannelsGroup.visible = true
    m.video.visible = false
    m.timeGride.setFocus(true)
end sub

function GetDurationStringStandard(TotalSeconds = 0 as integer) as string
    datetime = CreateObject("roDateTime")
    datetime.FromSeconds(TotalSeconds)
    hours = datetime.GetHours().ToStr()
    minutes = datetime.GetMinutes().ToStr()
    seconds = datetime.GetSeconds().ToStr()
    if Len(hours) = 1 then
        hours = "0" + hours
    end if
    if Len(minutes) = 1 then
        minutes = "0" + minutes
    end if
    if Len(seconds) = 1 then
        seconds = "0" + seconds
    end if

    if hours <> "00" then
        return hours + ":" + minutes
    else
        return minutes + ":" + seconds
    end if
end function


function getDurationIn12HrFormate(TotalSeconds = 0 as integer) as string
    datetime = CreateObject("roDateTime")
    datetime.FromSeconds(TotalSeconds)
    datetime.ToLocalTime()

    hours = datetime.GetHours().ToStr()
    minutes = datetime.GetMinutes().ToStr()
    seconds = datetime.GetSeconds().ToStr()
    AmPm = "AM"
    if(datetime.GetHours() > 12)
        AmPm = "PM"
        hours = (datetime.GetHours() - 12).toStr()
    else if (datetime.GetHours() = 12)
        AmPm = "PM"
    end if

    if Len(hours) = 1 then
        hours = "0" + hours
    end if
    if Len(minutes) = 1 then
        minutes = "0" + minutes
    end if
    return hours + ":" + minutes + " " + AmPm
end function