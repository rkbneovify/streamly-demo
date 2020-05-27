sub init()
   
   
   m.deviceinfo = getDeviceInfo()
   ?"deviceinfo: ";m.deviceinfo.getDisplaySize()
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
   
   m.Searchkeyboard = m.top.createChild("MiniKeyboard")
   m.Searchkeyboard.id = "Searchkeyboard"
   m.Searchkeyboard.translation = "[100,200]"
   m.Searchkeyboard.observeField("text","channelSearchText")
   m.Searchkeyboard.setFocus(true)   

   m.labelList = m.top.createChild("LabelList")   
   m.labelList.id = "labelList"
   m.labelList.translation = "[800,200]"
   
'   m.labelContent = createObject("roSGNode", "ContentNode")
'   m.labelContent.createChild("ContentNode").title="a"
'   m.labelContent.createChild("ContentNode").title="e"
'   m.labelContent.createChild("ContentNode").title="c"
'   m.labelContent.createChild("ContentNode").title="b"
'   
'   m.labelList.content = m.labelContent
   
   'spinner
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
    
    LabelNav = m.top.CreateChild("LAbel")
    LabelNav.text ="SEARCH"
    LabelNav.translation = [100, 80]
    
    
'    leftLayout = layoutGroup.createChild("LayoutGroup")
'    leftLayout.horizAlignment = "left"
'    leftLayout.vertAlignment = "center"
'    leftLayout.layoutDirection ="horiz"
'    leftLayout.itemSpacings =10
    
    
    m.spinner = m.top.CreateChild("BusySpinner")
    m.spinner.visible = false
    m.spinner.translation = [820, 460]
    m.spinner.uri = "pkg:/images/busyspinner_hd.png"
   
   
end sub

sub channelSearchText(event as object)
?"channelSearchText; ";event.getData()
  
  if m.ChannelSearchTask<> invalid then m.ChannelSearchTask.control = "Stop"
   
   if Len(event.getData())>1
   m.spinner.visible = true
     m.ChannelSearchTask = CreateObject("roSGNode", "ChannelSearchTask") 'Create XML Parsing task node
     
'     ApiCallFor(callto,"GET")
'     m.ChannelSearchTask.callto = callto
     m.ChannelSearchTask.callType = "POST"
     m.ChannelSearchTask.parameters ={"search":event.getData()} 
     m.ChannelSearchTask.observeField("response","responseAPI")
     m.ChannelSearchTask.control = "RUN"
   end if
     
     
     

 
end sub

sub responseAPI(event as object)
?"SearchScreen::responseAPI: ";event.getData()

  dataJson = event.getData()
  
  if dataJson.success=true
    ?"dataJson:dataJson:  ";dataJson.message.data[0]
    m.labelContent = createObject("roSGNode", "ContentNode")
    for each channel in dataJson.message.data
     ?"Search::chanel details :";channel
     channelDetail=m.labelContent.createChild("ContentNode")
     if channel.name<> invalid then channelDetail.title = channel.name
      if channel.url<> invalid then channelDetail.url = channel.url
       if channel.id<> invalid then  channelDetail.id = channel.id
       
  
    end for
  end if     
      m.labelList.content = m.labelContent
'  m.labelContent.createChild("ContentNode").title=event.getData()
  m.spinner.visible = false
end sub


Function onKeyEvent(key,press)


   if press
     ?"SearchScreen: ";key
      if  key="right"
         m.Searchkeyboard.setFocus(false)
         m.labelList.setFocus(true)
      else if key="left"
         m.labelList.setFocus(false)
         m.Searchkeyboard.setFocus(true)   
      
      else if key="back"
        m.top.exitView =true
      end if
   end if
 
end function