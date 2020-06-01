sub init()
   
   
   m.deviceinfo = getDeviceInfo()
   ?"deviceinfo: ";m.deviceinfo.getDisplaySize()
  
   setScreenBackGrndimage()
   
   searchKeyboard()
 
   resultentList()
  
   setHeader()  
     
    'spinner
    m.spinner = m.top.CreateChild("BusySpinner")
    m.spinner.visible = false
    m.spinner.translation = [820, 460]
    m.spinner.uri = "pkg:/images/busyspinner_hd.png"
      
end sub
sub resultentList()
?"resultentList"   
   m.labelList = m.top.createChild("LabelList")   
   m.labelList.id = "labelList"
   m.labelList.translation = "[800,200]" 
   m.labelList.observeField("itemSelected","setChannelUrl")
 'show response text 
   m.errorResultLabel = m.top.createChild("Label")
   m.errorResultLabel.text ="Please type some text for search the channels :" 
   m.errorResultLabel.translation = [850, 480]
   m.errorResultLabel.visible = true
end sub

sub setChannelUrl()
?"setChannelUrl"   
 m.top.playChannel = "http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8"
end sub
   
sub setHeader()
?"setHeader"
    Buttonleft = m.top.CreateChild("Button")
    Buttonleft.translation = [1500, 80]
    Buttonleft.iconUri = ""
    butonPoster = Buttonleft.createChild("Poster")
    butonPoster.uri="pkg:/images/home/backNavIcon.png"
    butonPoster.height = 56
    butonPoster.width =  56
    butonLabel = Buttonleft.createChild("Label")
    butonLabel.text ="Go back"
    butonLabel.translation =[80,15]
    
    LabelNav = m.top.CreateChild("Label")
    LabelNav.text ="SEARCH CHANNELS"
    LabelNav.translation = [100, 80]      
end sub
   
sub searchKeyboard()
?"searchKeyboard"  
 m.Searchkeyboard = m.top.createChild("MiniKeyboard")
   m.Searchkeyboard.id = "Searchkeyboard"
   m.Searchkeyboard.translation = "[100,200]"
   m.Searchkeyboard.observeField("text","channelSearchText")
   m.Searchkeyboard.setFocus(true)  
end sub
 
sub setScreenBackGrndimage()
?"setScreenBackGrndimage"
 Poster = m.top.createChild("Poster")
   Poster.id ="Poster"
   Poster.translation ="[0,0]"
   Poster.height=1080
   Poster.width =1920
    
    if m.deviceinfo.getDisplaySize().w = 1920    
          Poster.uri="pkg:/images/homeScreenBckgrnd.png"           
    else if m.deviceinfo.getDisplaySize().w = 1280
          Poster.uri = "pkg:/images/home/homeScreenBckgrndhd.png"      
    else 
          Poster.uri = "pkg:/images/home/homeScreenBckgrndhd.png"
    end if 
    
end sub
sub channelSearchText(event as object)
?"channelSearchText; ";event.getData()
  m.inputText = event.getData()
  if m.ChannelSearchTask<> invalid then m.ChannelSearchTask.control = "Stop"
   
   if Len(m.inputText)>1
     
     m.errorResultLabel.visible = false
   
     m.spinner.visible = true
     m.ChannelSearchTask = CreateObject("roSGNode", "ChannelSearchTask") 'Create XML Parsing task node  
     m.ChannelSearchTask.callType = "POST"
     m.ChannelSearchTask.parameters ={"search":m.inputText} 
     m.ChannelSearchTask.observeField("response","responseAPI")
     m.ChannelSearchTask.control = "RUN"
   else 
     m.errorResultLabel.text ="Please type some text for search the channels :" 
     m.errorResultLabel.visible = true
   end if
   
end sub

sub responseAPI(event as object)
?"SearchScreen::responseAPI: ";event.getData()
  m.errorResultLabel.visible = false
  dataJson = event.getData()
  
  if dataJson.success=true
    ?"dataJson:dataJson:  ";dataJson.message.data[0]
'    ?"dataJson:dataJson:  ";dataJson.message.data[0]
   if  dataJson.message.data[0] <> invalid
     m.labelContent = createObject("roSGNode", "ContentNode")
     for each channel in dataJson.message.data
       ?"Search::chanel details :";channel
       channelDetail=m.labelContent.createChild("ContentNode")
       if channel.name<> invalid then channelDetail.title = channel.name
       if channel.url<> invalid then channelDetail.url = channel.url
       if channel.id<> invalid then  channelDetail.id = channel.id
     end for
    else
       m.errorResultLabel.text ="No Channels for "+"' "+m.inputText+" '" 
       m.errorResultLabel.visible = true
    end if
  end if     
      m.labelList.content = m.labelContent
'  m.labelContent.createChild("ContentNode").title=event.getData()
  m.spinner.visible = false
end sub

Function onKeyEvent(key,press) as object
result =false
   if press
     ?"SearchScreen: ";key
     ?"m.Searchkeyboard.hasFocus(); ";m.Searchkeyboard.hasFocus()
      if key="right"  then
         m.Searchkeyboard.setFocus(false)
         m.labelList.setFocus(true)
         result = true 
      else if key="left" and m.labelList.hasFocus() then
         m.labelList.setFocus(false)
         m.Searchkeyboard.setFocus(true)   
        result = true 
      else if key="back" then
        m.top.exitView =true
        result = true 
      end if
     result = true  
   end if
 return result
end function