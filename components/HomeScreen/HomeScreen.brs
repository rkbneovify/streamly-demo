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
    
'    reqApiCAll({"page":1})   
    font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.channelGuide.font = font 
    m.pressbackLabel.font = font
    m.Settings.font = font
    
   
    initGrigData()
  
    currentTime =  CreateObject("roDateTime")
    t = currentTime.AsSeconds()
    t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
    m.timeGride.contentStartTime = t
    m.timeGride.leftEdgeTargetTime = currentTime.AsSeconds()



    'content
    content = CreateObject("roSGNode", "ContentNode")
    
    channleNode = content.createChild("ContentNode")
    channleNode.TITLE = "MTV-USA"
    channleNode.HDSMALLICONURL="pkg:/images/mtv.png"
    
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Justin Beiber concert"
    programNode.PLAYSTART = t  
    programNode.PLAYDURATION = 3000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
   
    
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "International football tournament"
    programNode.PLAYSTART = t+3000  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "c"
    programNode.PLAYSTART = t+5000  
    programNode.PLAYDURATION = 2000 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"     
    'second channel
    
    channleNode = content.createChild("ContentNode")
    channleNode.HDSMALLICONURL="pkg:/images/espn.png"
    channleNode.TITLE = "ESPN"
    
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Cricket test"
    programNode.PLAYSTART = t 
    programNode.PLAYDURATION = 2100
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "ICC World cup 2011"
    programNode.PLAYSTART = t+2100  
    programNode.PLAYDURATION = 2000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"  
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Highlghts of crickets"
    programNode.PLAYSTART = t+4100  
    programNode.PLAYDURATION = 2000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    
    channleNode = content.createChild("ContentNode")
    channleNode.HDSMALLICONURL="pkg:/images/cnicon.png"
    channleNode.TITLE = "Discovery"
    
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Oggy show"
    programNode.PLAYSTART = t 
    programNode.PLAYDURATION = 2100
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
   
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Tom & Jerry"
    programNode.PLAYSTART = t+2100  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"  
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Tom & Jerry"
    programNode.PLAYSTART = t+4100  
    programNode.PLAYDURATION = 2000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"

    channleNode = content.createChild("ContentNode")
    channleNode.TITLE = "MTV-USA"
    channleNode.HDSMALLICONURL="pkg:/images/mtv.png"
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Justin Beiber concert"
    programNode.PLAYSTART = t  
    programNode.PLAYDURATION = 3000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "International football tournament"
    programNode.PLAYSTART = t+3000  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "c"
    programNode.PLAYSTART = t+5000  
    programNode.PLAYDURATION = 2000 
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"     
    'second channel
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    channleNode = content.createChild("ContentNode")
    channleNode.HDSMALLICONURL="pkg:/images/espn.png"
    channleNode.TITLE = "ESPN"
    
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Cricket test"
    programNode.PLAYSTART = t 
    programNode.PLAYDURATION = 2100
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "ICC World cup 2011"
    programNode.PLAYSTART = t+2100  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"  
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Highlghts of crickets"
    programNode.PLAYSTART = t+4100  
    programNode.PLAYDURATION = 2000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    
     channleNode = content.createChild("ContentNode")
    channleNode.HDSMALLICONURL="pkg:/images/cnicon.png"
    channleNode.TITLE = "Discovery"
    
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Oggy show"
    programNode.PLAYSTART = t 
    programNode.PLAYDURATION = 2100
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Tom & Jerry"
    programNode.PLAYSTART = t+2100  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"  
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Tom & Jerry"
    programNode.PLAYSTART = t+4100  
    programNode.PLAYDURATION = 2000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
   
    channleNode = content.createChild("ContentNode")
    channleNode.TITLE = "MTV-USA"
    channleNode.HDSMALLICONURL="pkg:/images/mtv.png"
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Justin Beiber concert"
    programNode.PLAYSTART = t  
    programNode.PLAYDURATION = 3000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "International football tournament"
    programNode.PLAYSTART = t+3000  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "c"
    programNode.PLAYSTART = t+5000  
    programNode.PLAYDURATION = 2000 
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"     
    'second channel
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    channleNode = content.createChild("ContentNode")
    channleNode.HDSMALLICONURL="pkg:/images/espn.png"
    channleNode.TITLE = "ESPN"
    
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Cricket test"
    programNode.PLAYSTART = t 
    programNode.PLAYDURATION = 2100
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "ICC World cup 2011"
    programNode.PLAYSTART = t+2100  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"  
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Highlghts of crickets"
    programNode.PLAYSTART = t+4100  
    programNode.PLAYDURATION = 2000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    
     channleNode = content.createChild("ContentNode")
    channleNode.HDSMALLICONURL="pkg:/images/cnicon.png"
    channleNode.TITLE = "Discovery"
    
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Oggy show"
    programNode.PLAYSTART = t 
    programNode.PLAYDURATION = 2100
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png" 
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Tom & Jerry"
    programNode.PLAYSTART = t+2100  
    programNode.PLAYDURATION = 2000
'    programNode.HDSMALLICONURL = "pkg:/images/Login/borderon.png"  
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
    
    programNode = channleNode.createChild("ContentNode")
    programNode.TITLE  = "Tom & Jerry"
    programNode.PLAYSTART = t+4100  
    programNode.PLAYDURATION = 2000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"
 
    m.timeGride.content = content  
      
    m.timeGride.focusBitmapUri="pkg:/images/Login/buttonFoucs.png"
    m.timeGride.channelInfoComponentName="ChannelItemComponent"
  
    m.timeGride.observeField("programSelected","programSelected")
    m.timeGride.observeField("programFocused","programFocus")
                
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

    m.timeGride.numRows = 7
    m.timeGride.fillProgramGaps = false
    m.timeGride.translation="[0.0, 350.0]"
    m.timeGride.vertFocusAnimationStyle ="fixedFocus"
    
    m.top.observeField("visible","setFocus")
    m.video.setFocus(true)
    m.totalcount = 8
end sub

sub programFocus(event as object)
?"programFocus:";event.getRoSGNode().content.getChildCount()
'?"event.getRoSGNode().content.getChild( m.totalcount).id.ToInt() :";event.getRoSGNode().content.getChild( m.totalcount)
'?"event.getRoSGNode().content.getChild( m.totalcount) ::node :";event.getRoSGNode().content.getChild( m.totalcount)
?"event.getRoSGNode().content.getChildCount()-1 :";event.getRoSGNode().content.getChildCount()
?"m.totalcount; ";m.totalcount
'
' if event.getRoSGNode().content.getChild( event.getRoSGNode().content.getChildCount() ) = invalid
'    
'    ?"m.totalcount; ";event.getRoSGNode().content.getChildCount()    
'    TestingTAsk = createObject("roSGNode", "TestingTAsk")
'    
'    channleNode = event.getRoSGNode().content.createChild("ContentNode")
'    channleNode.id = event.getRoSGNode().content.getChildCount()-1
'    channleNode.TITLE = "Abba chaba dabba"
'    channleNode.HDSMALLICONURL="pkg:/images/mtv.png"
'    m.totalcount=  event.getRoSGNode().content.getChildCount()
'    
'    TestingTAsk.id = event.getRoSGNode().content.getChildCount()-1 
'    TestingTAsk.observeField("data","showResponse")
'    
'    
'    TestingTAsk.control ="RUN"
'    end if 
   
end sub
 
sub showResponse(event as object)
?"testing ::showResponse; ";event.getData()

event.getRoSGNode().control="stop"    

   content = m.timeGride.content
   channleNode = content.getChild(event.getData().ToInt())
   currentTime =  CreateObject("roDateTime")
    t = currentTime.AsSeconds()
    t = t - (t mod 1800)
    programNode = channleNode.createChild("ContentNode")  
    programNode.TITLE  = "Justin Beiber concert"
    programNode.PLAYSTART = t  
    programNode.PLAYDURATION = 3000
    programNode.url = "https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8"   
end sub
  
sub reqApiCAll(page)

    m.ChannelEpisodeTask = CreateObject("roSGNode","ChannelEpisodeTask")
    m.ChannelEpisodeTask.parameters = page
    m.ChannelEpisodeTask.observeField("ChannelData","ChannelResponseData")
    
    m.ChannelEpisodeTask.control = "RUN"

end sub
sub ChannelResponseData()
?"ChannelResponseData; "m.ChannelEpisodeTask.ChannelData
 m.timeGride.content = m.ChannelEpisodeTask.ChannelData
    
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


sub initGrigData()
?"m.ChannelCategoryMarkuGrid:init()"
 
 marupContent = createObject("roSGNode", "ContentNode")
 
 marupContent.createChild("ContentNode").TITLE = "ALL"
 marupContent.createChild("ContentNode").TITLE = "RECENT"
 marupContent.createChild("ContentNode").TITLE = "MY CHANNELS"
 marupContent.createChild("ContentNode").TITLE = "SPORTS"
 marupContent.createChild("ContentNode").TITLE = "NEWS"
 marupContent.createChild("ContentNode").TITLE = "MOVIES"
 marupContent.createChild("ContentNode").TITLE = "KIDS"

 m.ChannelCategoryMarkuGrid.content = marupContent
 m.ChannelCategoryMarkuGrid.observeField("itemSelected","categorySelcted")
 m.ChannelCategoryMarkuGrid.setFocus(false)
 
end sub
sub categorySelcted(event as object)
?"categorySelcted: ";event.getData()
    
end sub
 
sub programSelected(event as object)
 
 timeGrid = event.GetRoSGNode()
 data=timeGrid.content.getChild(timeGrid.channelSelected).getChild( timeGrid.programSelected)

 ?":programSelecte: data: ";data
 
 url=data.url

 m.ChannelsGroup.visible = false
 m.video.visible=true
' m.video.setFocus(true) 
playVideo(url)
end sub

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
          
          playVideo("http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8") 
'          playVideo("https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8") 
'          m.video.visible=false 
'          m.ChannelsGroup.visible = true
'           
'          m.ChannelCategoryMarkuGrid.setFocus(false)
'          m.timeGride.setFocus(true)
         
          result = true 
         else if (m.timeGride.hasFocus() = true or m.searchPoster.hasFocus() or m.ChannelCategoryMarkuGrid.hasFocus()) and key="options" 
           ?"HomeSceen:open profile setting view";
           
'           m.top.profileScreen = true
'          m.ChannelsGroup.visible = true
           
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


















