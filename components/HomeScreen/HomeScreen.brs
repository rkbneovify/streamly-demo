sub init()
?"Home init()"
    
    m.ChannelsGroup = m.top.findNode("ChannelsGroup")
    m.timeGride = m.top.findNode("timeGrid")
'    m.video = m.top.findNode("video")
    m.video = m.top.findNode("myVideo")
    m.video.enableUI = false
    m.video.thumbnailImage = "pkg:/images/home/testingThumbnail.jpg"
    m.channelGuide = m.top.findNode("channelGuide")
    m.pressbackLabel = m.top.findNode("pressbackLabel")
    m.Settings = m.top.findNode("Settings")
    m.channelBackgroundposter = m.top.findNode("channelBackgroundposter")
    
    m.searchPoster = m.top.createChild("Poster")    
    m.searchPoster.translation= [60,50]
    m.searchPoster.uri = "pkg:/images/home/searchicon_100.png"
    m.searchPoster.height = 50
    m.searchPoster.width = 50
    
    m.focusPoster = m.searchPoster.createChild("Poster")
    m.focusPoster.id="focusPoster"
    m.focusPoster.translation= [-10,-10]
    m.focusPoster.uri = ""
    m.focusPoster.height = 60
    m.focusPoster.width = 75
    
    m.searchPoster.visible =false
          
    m.deviceinfo = createObject("roDeviceInfo")
  
    if m.deviceinfo.getDisplaySize().w = 1920
'        m.PosterBackground.uri = "pkg:/images/background_FHD.jpg"     
         m.channelBackgroundposter.uri="pkg:/images/homeScreenBckgrnd.png"  
          
    else if m.deviceinfo.getDisplaySize().w = 1280
      
         m.channelBackgroundposter.uri="pkg:/images/home/homeScreenBckgrndhd.png"  
     
    else 
         m.channelBackgroundposter.uri="pkg:/images/home/homeScreenBckgrndhd.png"  
     
    end if
      
    m.ChannelCategoryMarkuGrid = m.top.findNode("ChannelCategoryMarkuGrid")
   
   'channel ,episode api call '   
    reqApiCAll({"page":1})   
    
    font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.channelGuide.font = font 
    m.pressbackLabel.font = font
    m.Settings.font = font
    
'    initGrigData()
  
    currentTime =  CreateObject("roDateTime")
    t = currentTime.AsSeconds()
    t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
    m.timeGride.contentStartTime = t
    m.timeGride.leftEdgeTargetTime = currentTime.AsSeconds() 
      
    m.timeGride.focusBitmapUri="pkg:/images/Login/buttonFoucs.png"
    m.timeGride.channelInfoComponentName="ChannelItemComponent"
  
    m.timeGride.observeField("programSelected","programSelected")
    m.timeGride.observeField("channelFocused","channelFocused")
                
    m.timeGride.programHorizMargin = 24
'    m.timeGride.programBackgroundBitmapUri = "pkg:/images/programUnfocusBOrder.png"
    m.timeGride.programBackgroundBitmapUri = "pkg:/images/home/borderwhite1.png"
    m.timeGride.overlayBitmapUri=""
    m.timeGride.channelInfoBackgroundBitmapUri =""
    m.timeGride.timeBarBitmapUri = "pkg:/images/transparentimg.png"
    m.timeGride.programTitleFocusedColor ="#ffffff"
    m.timeGride.showPastTimeScreen=true
        
    m.timeGride.channelNoDataText = "Loading..."
    m.timeGride.loadingDataText = "Loading..."
    m.timeGride.automaticLoadingDataFeedback = false

    m.timeGride.numRows = 9
    m.timeGride.fillProgramGaps = false
    m.timeGride.translation="[0.0, 350.0]"
    m.timeGride.vertFocusAnimationStyle ="fixedFocus"
    
    m.top.observeField("visible","setFocus")
    m.video.setFocus(true)
    
    'prgrams Api call timer
    m.lazyLoadingTimer = CreateObject("roSGNode", "Timer")
    m.lazyLoadingTimer.repeat = false
    m.lazyLoadingTimer.duration = 3
    m.lazyLoadingTimer.observeField("fire", "StartContentLoading")
'    m.lazyLoadingTimer.control = "start"
'    m.ChannelData = createObject("roArray")
    
    'prgramView Update Timer
    
    m.programUpdateTimer = CreateObject("roSGNode", "Timer")
    m.programUpdateTimer.repeat = false
   
    m.programUpdateTimer.observeField("fire", "StartProgramUpdating")    
    
   PosterBackground= m.top.createChild("Poster")
   PosterBackground.translation = [1411,716]
   PosterBackground.height = 236
   PosterBackground.width = 469
   PosterBackground.uri="pkg:/images/home/channelinfobackgrnd.png"
   PosterBackground.opacity=.8
   PosterBackground.visible =false
'   LayoutGroup = PosterBackground.createChild("LayoutGroup")
'   LayoutGroup.layoutDirection = "horiz"
'   LayoutGroup.itemSpacings ="10"
  
   
   PosterchannleIcon = PosterBackground.createChild("Poster")
   PosterchannleIcon.translation=[26,98]
   PosterchannleIcon.height = 94
   PosterchannleIcon.width = 138
   PosterchannleIcon.uri="pkg:/images/mtv.png"
  
   PosterchannleIcon = PosterBackground.createChild("Poster")
   PosterchannleIcon.translation=[189,22]
   PosterchannleIcon.height = 20
   PosterchannleIcon.width = 20
   PosterchannleIcon.uri="pkg:/images/home/liveIcon.png"
   PosterchannleIconLabel = PosterchannleIcon.createChild("Label")
   
   PosterchannleIconLabel.text = "Live"
   PosterchannleIconLabel.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
   PosterchannleIconLabel.translation=[30,0]
   
   ConsertTiming = PosterBackground.createChild("Rectangle")
   ConsertTiming.translation=[189,55]
   ConsertTiming.height = 30
   ConsertTiming.width = 163
   ConsertTiming.color = "#004E92"
   ConsertTimingTExt = ConsertTiming.createChild("Label")
   ConsertTimingTExt.text ="04:00 - 05:00PM"
   ConsertTimingTExt.translation = [8,4]
   ConsertTimingTExt.font = getFont(18, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
   
   
   ConcertChannel = PosterBackground.createChild("Label")
   ConcertChannel.translation=[189,103]
   ConcertChannel.height = 41
   ConcertChannel.width = 0
'   ConcertChannel.wrap =true
   ConcertChannel.text ="MTV-USA"
   ConcertChannel.font = getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")
   
   ConcertName = PosterBackground.createChild("Label")
   ConcertName.translation=[189,162]
   ConcertName.height = 30
   ConcertName.width = 255
   ConcertName.text="Justin Beiber concert"
   ConcertName.font = getFont(26, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
'   LayutRght.layoutDirection = "vert"
   
'   LayutRght.itemSpacings ="10"
'   LayutRght.createChild("Label")
'   LayutRght.translation=[217,21]
   m.AllChannelContent = createObject("roSGNode", "ContentNode")
   
end sub


sub StartProgramUpdating()
?"StartProgramUpdating"

'  m.programIndex = 0
 currentProgram = m.currentProgramsNode.getChild(m.programIndex)
 ?"currentProgram title ;";currentProgram.title
 ?"currentProgram start ;";currentProgram.PLAYSTART
 ?"currentProgram end ;";currentProgram["PLAYDURATION"] ;" sec"
 
 m.programDuration = currentProgram["PLAYDURATION"]
 
 m.programUpdateTimer.control = "stop" 
 m.programUpdateTimer.duration = m.programDuration
 m.programUpdateTimer.control = "start"
 m.programIndex+=1
 
end sub
Sub RestartLazyLoadingTimer()
?"RestartLazyLoadingTimer"
    m.lazyLoadingTimer.control = "stop"
    m.lazyLoadingTimer.control = "start"
End Sub

sub channelFocused(event as object)
?"channelFocused:";event.getData()

 if  event.getRoSGNode().content <> invalid and event.getRoSGNode().content.getChild( event.getData()+6 ) = invalid and m.channelListData < m.ChannelData[m.channelNameString].Count()
    
    if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"         
    
    channelnode = event.getRoSGNode().content
    getChannelDAta(channelnode,m.ChannelData[m.channelNameString][m.channelListData])
    m.channelListData +=1
  end if 
   
end sub

sub StartContentLoading()
?"StartContentLoading; "
 if  m.channelListData < m.ChannelData[m.channelNameString].Count() and m.timeGride.content<> invalid
     m.channelListData +=1
     getChannelDAta(m.timeGride.content,m.ChannelData[m.channelNameString][m.channelListData])           
 else if  m.timeGride.content = invalid
      ?"first time start"
      content = CreateObject("roSGNode", "ContentNode")     
      m.timeGride.content = content
      getChannelDAta(m.timeGride.content,m.ChannelData[m.channelNameString][m.channelListData]) 
 else if  m.channelListData = 0 then
        if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"       
 end if  
end sub
  

sub getChannelDAta(channelnode as object,channelData as object)
?"getChannelDAta;"
    nodedata = channelnode.createChild("ContentNode")
  if channelData <> invalid
   if channelData.id <> invalid then nodedata.id = channelData.id
   if channelData.callSign <> invalid then nodedata.TITLE = channelData.callSign
   if channelData.logo <> invalid then nodedata.HDSMALLICONURL = channelData.logo
   if channelData.url <> invalid then nodedata.url = channelData.url
    
    m.TestingTAsk = createObject("roSGNode","TestingTAsk")
    m.TestingTAsk.id = channelnode.getChildCount()-1
    m.TestingTAsk.content = m.timeGride.content 
    m.TestingTAsk.parameters ={"search":channelData.callSign}
    m.TestingTAsk.observeField("programData","showResponse")
    m.TestingTAsk.control = "RUN"
  else 
    ?"getChannelDAta:: channelData is not complete : ;";channelData
  end if        
end sub
 
sub showResponse(event as object)
?"testing ::showResponse; ";

 if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "stop" then m.lazyLoadingTimer.control = "start"
  event.getRoSGNode().control="stop"    
         
end sub
  
sub reqApiCAll(page)

    m.ChannelEpisodeTask = CreateObject("roSGNode","ChannelEpisodeTask")
    m.ChannelEpisodeTask.observeField("ChannelData","ChannelResponseData")    
    m.ChannelEpisodeTask.control = "RUN"

end sub
sub ChannelResponseData()
'?"ChannelResponseData; "m.ChannelEpisodeTask.ChannelData
   m.ChannelData = m.ChannelEpisodeTask.ChannelData
   initGrigData(m.ChannelData)
   m.channelListData = 0
   m.channelNameString ="ALL"
   m.lazyLoadingTimer.control = "start"
      
end sub
  
function playVideo(url as String) as void
?"HomeScreen:playVideo: "
    vidContent = createObject("RoSGNode", "ContentNode")
    vidContent.url = url
    vidContent.streamformat = "HLS"
    m.searchPoster.visible =false
    m.video.enableUI = false
    m.video.content = vidContent
    m.video.setFocus(true)
    
    m.video.control = "play"
    m.video.thumbnailImage=""
end function

sub videoState()
 
 Print "videoState"; m.video.state
 Print "completedStreamInfo"; m.video.completedStreamInfo
 Print "videoFormat"; m.video.completedStreamInfo
  
'  if m.video.state="playing"
'     m.video.control="pause"
'      m.video.mute = false
' else if  m.video.state="error"
'      Print "m.video.errorCode: "; m.video.errorCode
'      Print "m.video.errorMsg: "; m.video.errorMsg
'      Print "m.video.errorStr: "; m.video.errorStr
     
'  end if
  
end sub


sub initGrigData(channels as object)
?"m.ChannelCategoryMarkuGrid:init()"
 
 marupContent = createObject("roSGNode", "ContentNode")
 
 for each channel in channels.Keys()
 
   marupContent.createChild("ContentNode").TITLE =channel
 end for
' marupContent.createChild("ContentNode").TITLE = "ALL"
' marupContent.createChild("ContentNode").TITLE = "RECENT"
' marupContent.createChild("ContentNode").TITLE = "MY CHANNELS"
' marupContent.createChild("ContentNode").TITLE = "SPORTS"
' marupContent.createChild("ContentNode").TITLE = "NEWS"
' marupContent.createChild("ContentNode").TITLE = "MOVIES"
' marupContent.createChild("ContentNode").TITLE = "KIDS"

 m.ChannelCategoryMarkuGrid.content = marupContent
 m.ChannelCategoryMarkuGrid.observeField("itemSelected","categorySelcted")
 m.ChannelCategoryMarkuGrid.setFocus(false)
 
end sub
sub categorySelcted(event as object)
?"categorySelcted: ";event.getRoSGNode().content.getChild(event.getData())

  if m.lazyLoadingTimer <> invalid and m.lazyLoadingTimer.control = "start" then m.lazyLoadingTimer.control = "stop"
   m.timeGride.content  = createObject("roSGNode", "ContentNode")
   m.channelNameString = event.getRoSGNode().content.getChild(event.getData()).title
   m.channelListData = 0    
   m.ChannelCategoryMarkuGrid.setFocus(false)
   m.timeGride.setFocus(true)
  
end sub
 
Function programSelected(event as object) as object
 
 
 timeGrid = event.GetRoSGNode()
 channelNode = timeGrid.content.getChild(timeGrid.channelSelected)
 m.currentProgramsNode =  channelNode
 
 m.programIndex = 0
   
 currentProgram = m.currentProgramsNode.getChild(m.programIndex)
 ?"currentProgram title ;";currentProgram.title
 ?"currentProgram start ;";currentProgram.PLAYSTART
 ?"currentProgram end ;";currentProgram.PLAYDURATION ;" sec"
 
    currentTime =  CreateObject("roDateTime")
    t = currentTime.AsSeconds()
    if  t > (currentProgram.PLAYSTART/1000)
       m.programDuration = (t- (currentProgram.PLAYSTART/1000))   
    else
       m.programDuration = ((currentProgram.PLAYSTART/1000) -t)
    end if
 
 ?"m.programDuration exact ;";m.programDuration
' m.programDuration = currentProgram.PLAYDURATION
 
 if m.programUpdateTimer <> invalid and m.programUpdateTimer.control = "start" then m.programUpdateTimer.control = "stop"
 
 
 m.programUpdateTimer.duration = m.programDuration
 m.programUpdateTimer.control = "start"
 m.programIndex+=1

 data=timeGrid.content.getChild(timeGrid.channelSelected)
 
 ?":programSelecte: data: ";data.url
  
 url=data.url

 m.ChannelsGroup.visible = false
 m.video.visible=true
' m.video.setFocus(true) 
playVideo(url)

return true
end Function

sub VideoControl(event as object) 
 
  video = event.getRoSGNode()
  ?"VideoControl: ";video
  ?"control: ";video.control

end sub

sub onPofileFinished()
?"onPofileFinished";

end sub

sub closeSearchScreen(event as object)
?"closeSearchScreen; "
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
?"closeProfileScreen; "
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
?"showLoginPage"
  searchScreen = event.getRoSGNode()    
  m.top.removeChild(searchScreen)   
  m.top.ExitView =true    
end sub

sub playSearchChannelUrl(event as object)
?"playSearchChannelUrl:";event.getData() 

  searchScreen = event.getRoSGNode()
  m.top.removeChild(searchScreen)    
  
  m.ChannelsGroup.visible = false
  m.video.visible=true
  playVideo(searchScreen.playChannel) 
    
end sub
    
 
 
function onKeyEvent(key as String, press as Boolean) as Boolean
    ?"HomeScreen::key: ";key
    ?"HomeScreen::press: ";press
    ?" m.video.hasFocus() "; m.video.hasFocus()
   
    result = false
    
    if press
      if m.searchPoster.hasFocus() and key="down"
         m.searchPoster.setFocus(false)
         m.focusPoster.uri=""
         m.timeGride.setFocus(true)
      else if m.searchPoster.hasFocus() and key="OK"
        m.searchPoster.setFocus(false)
        m.focusPoster.uri=""
        SearchScreen = m.top.createChild("SearchScreen")
        SearchScreen.id ="SearchScreen"
        SearchScreen.observeField("exitView","closeSearchScreen")
        SearchScreen.observeField("playChannel","playSearchChannelUrl")
       else if  m.ChannelCategoryMarkuGrid.hasFocus() and key="left"
         m.searchPoster.setFocus(true)
         m.ChannelCategoryMarkuGrid.setFocus(false)
         m.focusPoster.uri = "pkg:/images/home/categiryNavFocusIcon.png"
         
       else if  m.searchPoster.hasFocus() and key="right"
         m.ChannelCategoryMarkuGrid.setFocus(true)
         m.searchPoster.setFocus(false)  
         m.focusPoster.uri=""
       else if m.timeGride.hasFocus() and key="up"
      
          m.timeGride.setFocus(false)
          m.ChannelCategoryMarkuGrid.setFocus(true)
          m.video.setFocus(false) 
          result = true
          
        else if m.ChannelCategoryMarkuGrid.hasFocus() and key="down"
           
          m.ChannelCategoryMarkuGrid.setFocus(false)
          m.video.setFocus(false) 
          m.timeGride.setFocus(true)
          result = true
        
        else if m.ChannelsGroup.visible=true and key="back"
          
          m.ChannelsGroup.visible = false
          m.timeGride.setFocus(false)
          m.ChannelCategoryMarkuGrid.setFocus(false)
          
          m.video.visible=true
          m.video.setFocus(true)
          m.searchPoster.visible =false
          result = true        
        else if m.video.visible=true and key="options" and  m.video.hasFocus() and m.video.state<> "buffering" and m.video.state<> "stopped"
  
          ?"HomeSceen:open channel view";
          m.searchPoster.visible =true
          m.video.visible=false 
          m.ChannelsGroup.visible = true
          m.video.setFocus(false) 
          m.ChannelCategoryMarkuGrid.setFocus(false)         
          m.timeGride.setFocus(true)
         
          result = true 
        else if m.video.hasFocus()  and (key="play" or key="OK")
          
           ?"ok pressed fr play video"
          
'           playVideo("https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8") 
 
          result = true 
         else if (m.timeGride.hasFocus() = true or m.searchPoster.hasFocus() or m.ChannelCategoryMarkuGrid.hasFocus()) and key="options" 
           ?"HomeSceen:open profile setting view";
      
          m.searchPoster.setFocus(false)
          m.ChannelCategoryMarkuGrid.setFocus(false)
          m.focusPoster.uri=""
          m.timeGride.setFocus(false)
          
          ProfileSettingScreen = m.top.createChild("ProfileSettingScreen")
          ProfileSettingScreen.id ="ProfileSettingScreen"
          ProfileSettingScreen.observeField("exitView","closeProfileScreen")
          ProfileSettingScreen.observeField("logout","showLoginPage")
          
          result = true    
        
        end if  
        return result  
    end if
        
    return result 
end function