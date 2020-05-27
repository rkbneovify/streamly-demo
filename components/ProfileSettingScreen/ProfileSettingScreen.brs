sub init()

 m.ProfileCategory = m.top.findNode("ProfileCategory")
 m.Dialog = m.top.findNode("Dialog")
' m.ProfileSettingScreen = CreateObject("roSGNode", "ProfileSettingScreen")
 
 'dialog
 m.dialogbackgndUri = m.top.findNode("dialogbackgndUri")
 m.logoutView = m.top.findNode("logoutView")
 m.profileView = m.top.findNode("profileView")
 m.NoButton = m.top.findNode("NoButton")
 m.YesButton = m.top.findNode("YesButton")
 m.logoutText = m.top.findNode("logoutText")
 m.Lbaelok = m.top.findNode("Lbaelok")
 m.labelYes = m.top.findNode("labelYes")
 m.labelNo = m.top.findNode("labelNo")


  font = getFont(30, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
  m.Lbaelok.font = font 
  m.labelYes.font = font
  m.labelNo.font = font
  m.logoutText.font = getFont(32, "pkg:/fonts/HelveticaNeueMedium.ttf")
  textTranslate()

'  m.ProfileTask = CreateObject("roSGNode", "TrackerTask")
'  m.ProfileTask.control="init"
   m.logout = false

'  dialog error
   m.ErrorDialogView = m.top.findNode("ErrorDialogView")
   m.buttonClick = m.top.findNode("buttonClick")
   m.messageBody = m.top.findNode("messageBody")
  

    m.YesButton.observeField("buttonSelected","logoutApiCall")
    m.NoButton.observeField("buttonSelected","closeDialog")
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
 
' m.top.observeField("visible","setfocus")
 m.top.observeField("focusedChild","OnFocusChildChange")
 m.ProfileCategory.setFocus(true)   
end sub

sub textTranslate()
  m.logoutText.text = tr("Are you sure you want to logout from this device?")
end sub
  

sub OnFocusChildChange()
  ?"Pr0file focus"
'    if m.top.isInFocusChain() and not m.ProfileCategory.hasFocus() then
'        ?"Pr0file focus true"
       m.ProfileCategory.setFocus(true)
'    end if
    
end sub


sub logoutApiCall()
?"ProfileSetting::logoutApiCall"
 m.YesButton.setFocus(false)
 showDialog("",false,false)
 ApiCallFor("Sign out","POST")
end sub


sub closeScreen()
?"ProfileScreen::closeScreen";
'm.buttonClick.observeField("buttonSelected","closeScreen")
m.buttonClick.unobserveField("buttonSelected")
   if m.buttonClick.hasFocus() and m.logout = true
       
       ?"ProfileSetting::if:close screen::" 
       m.logout = false
       m.buttonClick.setFocus(false)
       m.ErrorDialogView.visible = false
       m.top.logout = true
    
    end if
end sub
sub closeDialog()
 ?"ProfileSetting:: close dialog"   
    
  
     ?"ProfileSetting::else close dialog"
       m.ok.setFocus(false)
       m.NoButton.setFocus(false)
            
       showDialog("",false,true)
        
       m.buttonClick.setFocus(false)
       m.ErrorDialogView.visible =false    
    
         
end sub





sub initMrkupWithData()
?"ProfileSetting:MarkuGrid:init()"
 
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
?"ProfileSetting::MarkuGrid:init(): event pos: ";event.getData()
  
    m.ProfileCategory.setFocus(false)
    
'
    
 if  event.getData() = 0
     
     ApiCallFor("Account","GET")
     
' m.ProfileTask.control = "RUN" 'Run the task node    
 
 else if event.getData() = 1
     
'     m.ProfileTask.setField("callType","GET")
'     m.ProfileTask.setField("callFor","Registered Devices")
      ApiCallFor("Registered Devices","GET")
 else if event.getData() = 2
    
'    m.ProfileTask.setField("callType","GET")
'    m.ProfileTask.setField("callFor","Support")
    
 else if event.getData() = 3
    
'    m.ProfileTask.setField("callType","GET")
'    m.ProfileTask.setField("callFor","Privacy & Policy")
    
 else if event.getData() = 4 'Logout api call 
     m.YesButton.setFocus(true)
     showDialog("Sign out",true,false)
 end if

 
end sub

sub ApiCallFor(callto as String, callType as String)
 ?"ProfileSetting::ApiCallFor: " 
     m.ProfileTask = CreateObject("roSGNode", "ProfileTask") 'Create XML Parsing task node
    m.LoginBusySpinner.visible=true   
'     ApiCallFor(callto,"GET")
     m.ProfileTask.callto = callto
     m.ProfileTask.callType = callType
      
     m.ProfileTask.observeField("response","responseAPI")
     
     m.ProfileTask.observeField("response","responseAPI")
     m.ProfileTask.control = "RUN" 'Run the task node 
     
end sub 
sub responseAPI(event as object)
  responseData = event.getData()
  ?"ProfileScreen::responseAPI: event: ";responseData
  
  m.LoginBusySpinner.visible=false
  m.ProfileTask.control = "STOP"
  m.ProfileCategory.setFocus(true)
 if responseData.response_code = true
    
    if m.ProfileTask.callto = "Account" 
   
      m.ok.setFocus(true) 
      marupContent = createObject("roSGNode", "ContentNode")
   
      userData = responseData.data
      for each data  in userData.Keys()
        ?"data: "data
        ?"userData[data]: "userData[data]
        if userData[data] <> invalid        
           assoArray = createObject("roAssociativeArray")    
           assoArray[data+":"] =   userData[data]          
           marupContent.createChild("ContentNode").addFields(assoArray)
        end if
      end for
   
      m.dialogOptions.content = marupContent
      showDialog("Account",true,false) 
    else if m.ProfileTask.callto = "Sign out" 
       ?"Profile::responseData.message:";responseData.message
        setAccountToken("")
         m.logout = true
        showDialogError(true , responseData.message, "Ok") 
    else if m.ProfileTask.callto = "Registered Devices" 
       ConnectedDevices = responseData.data 
       ?"Profile::responseData.message:";responseData.data
      
        for each data  in ConnectedDevices
        ?"data: "data
        ?"userData[data]: ";data["device_id"] ;data["device_name"]
        count = 0
        if data["device_id"] <> invalid and  data["device_name"] <> invalid      
            assoArray = createObject("roAssociativeArray")    
            assoArray[count+". "+data["device_name"]] =   data["device_id"]         
            marupContent.createChild("ContentNode").addFields(assoArray)
        end if
      end for
        m.dialogOptions.content = marupContent
         showDialog("Registered Devices",true,false)
        showDialogError(true , responseData.message, "Ok")                
    end if
 else
   ?"error dialog to show the error:";responseData
    showDialog("",false,false)
     
    showDialogError(true , responseData.message, "Something error")
 end if
 
   
 
end sub

sub showDialog(dialogView as String , show as boolean ,focus as boolean) 
 ?"ProfileSetting::showDialog"
 m.logoutView.visible = false
 m.profileView.visible = false
 

  if  show
     if dialogView = "Sign out"
        
        m.dialogbackgndUri.width = 1223
        m.dialogbackgndUri.height = 488
        m.logoutView.visible = show
        m.Dialog.visible = show
        
     else if dialogView = "Account" or dialogView = "Registered Devices"
        
        m.dialogbackgndUri.width = 1223
        m.dialogbackgndUri.height = 724
        
        m.profileView.visible = show
        m.Dialog.visible = show
     
     else if dialogView = ""
     
     end if
  else
    m.Dialog.visible = show
    m.ProfileCategory.setFocus(focus)
  end if
 
end sub
 
'sub setfocus()
'  ?"ProfileScreen: m.top.visible ";m.top.visible
'  if m.top.visible  = true
'    m.ProfileCategory.setFocus(true)
'  end if 
'end sub 
sub showspinner(event as object)
?"ProfileSetting::showspinner:event : ";event


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
 
 
 
sub showDialogError(visible as boolean , text as String, buttontext as String)
?"ProfileSetting::showDialogError"
 if visible then

  
    m.ErrorDialogView.visible = visible

    m.messageBody.text = text
    m.ErrorDialogView.visible = visible
    m.buttonClick.setFocus(visible)
    m.buttonClick.text = buttontext
    m.buttonClick.observeField("buttonSelected","closeScreen")
   
 else 
    m.buttonClick.setFocus(visible)
    m.ErrorDialogView.visible = visible
 end if
end sub 
 
function onKeyEvent(key as String, press as Boolean) as Boolean
    ?"Profile::key: ";key
    ?"Profile::press: ";press
    result = false    
    if press
    
      if m.buttonClick.hasFocus() and m.logout = true and key = "back"
       m.buttonClick.setFocus(false)
       m.ErrorDialogView.visible =false
       m.top.logout = true
       m.logout = false
        return true
    else  if m.YesButton.hasFocus() and key = "left"
        m.YesButton.setFocus(false)   
        m.NoButton.setFocus(true)
         return true
     else if m.NoButton.hasFocus() and key = "right"
        m.NoButton.setFocus(false)   
        m.YesButton.setFocus(true) 
        return true   
     else if (m.NoButton.hasFocus() or m.YesButton.hasFocus() or m.ok.hasFocus() ) and key = "back" 
        
        m.ok.setFocus(false)
        m.NoButton.setFocus(false)
        m.YesButton.setFocus(false)
       
        
        showDialog("",false,true)
        return true
     
     end if 
    end if    
end function     