sub init()

 m.ProfileCategory = m.top.findNode("ProfileCategory")
 m.Dialog = m.top.findNode("Dialog")
 m.dialogbackgndUri = m.top.findNode("dialogbackgndUri")
 m.logoutView = m.top.findNode("logoutView")
 m.profileView = m.top.findNode("profileView")
 m.NoButton = m.top.findNode("NoButton")
 m.YesButton = m.top.findNode("YesButton")
 
 m.YesButton.observeField("buttonSelected","logoutApiCall")
 
 
   'loader
    m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
    m.LoaderBackground = m.top.findNode("LoaderBackground")    
    m.LoginBusySpinner.observeField("visible", "showspinner")
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    m.LoginBusySpinner.visible = false
 
 
 m.ok = m.top.findNode("ok")
 m.ok.observeField("buttonSelected","closeDialog")


 m.ProfileCategory.observeField("itemSelected","initDialogMrkupWithData")
 
 m.dialogOptions = m.top.findNode("dialogOptions")
 
 initMrkupWithData()
 
 m.top.observeField("visible","setfocus")
 
end sub

sub logoutApiCall()
?"logoutApiCall"
 ApiCallFor("Account","POST")
end sub

sub closeDialog()

    m.ok.setFocus(false)
    m.ProfileCategory.setFocus(true)      
    showDialog("",false)
        
end sub

sub initMrkupWithData()
?"m.ChannelCategoryMarkuGrid:init()"
 
 marupContent = createObject("roSGNode", "ContentNode")
 
 data = marupContent.createChild("ContentNode")
 data.text = "Account"
 data.SDPosterUrl = "pkg:/images/profile/profileicon.png"

 data = marupContent.createChild("ContentNode")
 data.text = "Registered Devices"
 data.SDPosterUrl = "pkg:/images/profile/devicesicon.png"
  
 data = marupContent.createChild("ContentNode")
 data.text = "Support"
 data.SDPosterUrl = "pkg:/images/profile/supporticon.png"
 
 data = marupContent.createChild("ContentNode")
 data.text = "Privacy & Policy"
 data.SDPosterUrl = "pkg:/images/profile/privicypolicyicon.png"
 
 data = marupContent.createChild("ContentNode")
 data.text = "Sign out"
 data.SDPosterUrl = "pkg:/images/profile/logouticon.png"
 
 m.ProfileCategory.content = marupContent
 
 end sub
 
sub initDialogMrkupWithData(event as object)
?"m.ChannelCategoryMarkuGrid:init(): event pos: ";event.getData()
  
    m.ProfileTask = CreateObject("roSGNode", "ProfileTask") 'Create XML Parsing task node
    
 if  event.getData() = 0
     
     ApiCallFor("Account","GET")
     
' m.ProfileTask.control = "RUN" 'Run the task node    
 
 else if event.getData() = 1
     
'     m.ProfileTask.setField("callType","GET")
'     m.ProfileTask.setField("callFor","Registered Devices")
 
 else if event.getData() = 2
    
'    m.ProfileTask.setField("callType","GET")
'    m.ProfileTask.setField("callFor","Support")
    
 else if event.getData() = 3
    
'    m.ProfileTask.setField("callType","GET")
'    m.ProfileTask.setField("callFor","Privacy & Policy")
    
 else if event.getData() = 4 'Logout api call 
     m.YesButton.setFocus(true)
     showDialog("Sign out",true)
 end if
   

 
end sub

sub ApiCallFor(callto as String, callType as String)
 ?"ApiCallFor: " 
    m.LoginBusySpinner.visible=true   
'     ApiCallFor(callto,"GET")
     m.ProfileTask.callto = callto
     m.ProfileTask.callType = callType
      
     m.ProfileTask.observeField("response","responseAPI")
     
      m.ProfileTask.observeField("response","responseAPI")
     m.ProfileTask.control = "RUN" 'Run the task node 
end sub 
sub responseAPI(event as object)
  responseData = event.getData().data
  ?"ProfileScreen::responseAPI: event: ";responseData.user
  
  m.LoginBusySpinner.visible=false
  m.ProfileTask.control = "STOP"
  
 if responseData.success = true
    
    if m.ProfileTask.callto = "Account" 
   
      m.ok.setFocus(true) 
      marupContent = createObject("roSGNode", "ContentNode")
   
      userData = responseData.user
      for each data  in userData.Keys()
        ?"data: "data
        ?"userData[data]: "userData[data]
        if userData[data] <> invalid        
           assoArray = createObject("roAssociativeArray")    
           assoArray[data] =   userData[data]          
           marupContent.createChild("ContentNode").addFields(assoArray)
        end if
      end for
   
      m.dialogOptions.content = marupContent
      showDialog("Account",true) 
    else if m.ProfileTask.callto = "Sign out"
            
    end if
 else
   ?"error dialog to show the error:";responseData
 end if
 
   
 
end sub

sub showDialog(dialogView as String , show as boolean ) 

 m.logoutView.visible = false
 m.profileView.visible = false
 

  if  show
     if dialogView = "Sign out"
        
        m.dialogbackgndUri.width = 1223
        m.dialogbackgndUri.height = 488
        m.logoutView.visible = show
        m.Dialog.visible = show
        
     else if dialogView = "Account"
        
        m.dialogbackgndUri.width = 1223
        m.dialogbackgndUri.height = 724
        
        m.profileView.visible = show
        m.Dialog.visible = show
     
     else if dialogView = ""
     
     end if
  else
    m.Dialog.visible = show
  end if
 
end sub
 
sub setfocus()
  ?"ProfileScreen: ";m.top.visible
  if m.top.visible  = true
    m.ProfileCategory.setFocus(true)
  end if 
end sub 
sub showspinner(event as object)
?"showspinner:event : ";event


      if(event.getData() = true)
        
        centerx = (1920 - m.LoginBusySpinner.poster.bitmapWidth) / 2
        centery = (1080 - m.LoginBusySpinner.poster.bitmapHeight) / 2
        m.LoginBusySpinner.translation = [ centerx, centery ]
        
        m.LoaderBackground.width = 1920
        m.LoaderBackground.height = 1080         
        m.LoaderBackground.visible=true
        
        m.LoginBusySpinner.control = "start"
        
      else  if(event.getData() = false)
        
        m.LoaderBackground.visible=false
        m.LoginBusySpinner.control = "stop"
      
      end if
      
end sub
 
function onKeyEvent(key as String, press as Boolean) as Boolean
    ?"key: ";key
    ?"press: ";press
    result = false    
    if press
      if m.YesButton.hasFocus() and key = "left"
        m.YesButton.setFocus(false)   
        m.NoButton.setFocus(true)
         
     else if m.NoButton.hasFocus() and key = "right"
        m.NoButton.setFocus(false)   
        m.YesButton.setFocus(true)    
     else if (m.NoButton.hasFocus() or m.YesButton.hasFocus() or m.ok.hasFocus() ) and key = "back" 
        
        m.ok.setFocus(false)
        m.NoButton.setFocus(false)
        m.YesButton.setFocus(false)
       
        m.ProfileCategory.setFocus(true)
        
        showDialog("",false)
        return true
     else if m.ProfileCategory.hasFocus() and key = "back" 
      m.top.visible = false
      return true
     end if 
    end if    
end function     