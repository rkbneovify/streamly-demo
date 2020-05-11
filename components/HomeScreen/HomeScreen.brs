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
    
    
    m.deviceinfo = createObject("roDeviceInfo")
    if m.deviceinfo.getDisplaySize().w = 1920
'         m.PosterBackground.uri = "pkg:/images/background_FHD.jpg"     
         m.channelBackgroundposter.uri="pkg:/images/homeScreenBckgrnd.png"  
          
    else if m.deviceinfo.getDisplaySize().w = 1280
      
         m.channelBackgroundposter.uri="pkg:/images/home/homeScreenBckgrndhd.png"  
     
    else 
         m.channelBackgroundposter.uri="pkg:/images/home/homeScreenBckgrndhd.png"  
     
    end if
    
    
    
    
    m.ChannelCategoryMarkuGrid = m.top.findNode("ChannelCategoryMarkuGrid")
    
    UsernameFont  = CreateObject("roSGNode", "Font") 
    UsernameFont.uri = "pkg:/fonts/HelveticaNeueMedium.ttf"
    UsernameFont.size = 28
    
    m.channelGuide.font = UsernameFont
    m.pressbackLabel.font = UsernameFont
    m.Settings.font = UsernameFont
    
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
'    m.video.setFocus(true)

end sub
sub setFocus()
?"HomeScreen:setFocus: "
    if m.top.getScene().visible = true
      m.video.setFocus(true)
'       playVideo("https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8")
      
    end if  
end sub

function playVideo(url as String) as void
?"HomeScreen:playVideo: "
    vidContent = createObject("RoSGNode", "ContentNode")
    vidContent.url = url
    vidContent.streamformat = "HLS"

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
 
 
end sub
sub programSelected(event as object)
 
 timeGrid = event.GetRoSGNode()
 data=timeGrid.content.getChild(timeGrid.channelSelected).getChild( timeGrid.programSelected)

 ?":programSelecte: data: ";data
 
 url=data.url

 m.ChannelsGroup.visible = false
 m.video.visible=true
 m.video.setFocus(true) 
playVideo(url)
end sub

sub VideoControl(event as object) 
  
  video = event.getRoSGNode()
  ?"VideoControl: ";video
  ?"control: ";video.control

end sub


function onKeyEvent(key as String, press as Boolean) as Boolean
    ?"key: ";key
    ?"press: ";press
    result = false
    
    if press
        
        if m.timeGride.hasFocus() and key="up"
      
          m.timeGride.setFocus(false)
          m.ChannelCategoryMarkuGrid.setFocus(true)
          
          result = true
          
        else if m.ChannelCategoryMarkuGrid.hasFocus() and key="down"
           
          m.ChannelCategoryMarkuGrid.setFocus(false)
          m.timeGride.setFocus(true)
          result = true
        
        else if m.ChannelsGroup.visible=true and key="back"
          
          m.ChannelsGroup.visible = false
          m.video.visible=true
          m.video.setFocus(true)
         
          result = true        
        else if m.video.visible=true and key="options" and m.video.state<> "buffering" and m.video.state<> "stopped"
           ?"HomeSceen:m.video.control:";m.video.state
          m.video.visible=false 
          m.ChannelsGroup.visible = true
           
          m.ChannelCategoryMarkuGrid.setFocus(false)
          m.timeGride.setFocus(true)
         
          result = true 
        else if m.video.visible=true and key="play" or key="OK"
          
          
          playVideo("https://bitmovin-a.akamaihd.net/content/MI201109210084_1/m3u8s/f08e80da-bf1d-4e3d-8899-f0f6155f6efa.m3u8") 
'          m.video.visible=false 
'          m.ChannelsGroup.visible = true
'           
'          m.ChannelCategoryMarkuGrid.setFocus(false)
'          m.timeGride.setFocus(true)
         
          result = true   
        
        end if   
    end if
        
    return result 
end function


















