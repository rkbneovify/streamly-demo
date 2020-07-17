sub init()

 m.ProfileCategory = m.top.findNode("ProfileCategory")
 m.Dialog = m.top.findNode("Dialog")
' m.ProfileSettingScreen = CreateObject("roSGNode", "ProfileSettingScreen")
 
 'dialog
 m.dialogbackgndUri = m.top.findNode("dialogbackgndUri")
' m.dialogbackgndUri.uri = "pkg:/images/home/borderwhite3.png"
 m.logoutView = m.top.findNode("logoutView")
 m.profileView = m.top.findNode("profileView")
 m.NoButton = m.top.findNode("NoButton")
 m.YesButton = m.top.findNode("YesButton")
 m.logoutText = m.top.findNode("logoutText")
 m.Lbaelok = m.top.findNode("Lbaelok")
 m.labelYes = m.top.findNode("labelYes")
 m.labelNo = m.top.findNode("labelNo")


  font = getFont(36, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
  m.Lbaelok.font = font 
  m.labelYes.font = font
  m.labelNo.font = font
  m.logoutText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
  textTranslate()

'  m.ProfileTask = CreateObject("roSGNode", "TrackerTask")
'  m.ProfileTask.control="init"
   m.logout = false



'  dialog error
   m.ErrorDialogView = m.top.findNode("ErrorDialogView")
   m.buttonClick = m.top.findNode("buttonClick")
   m.buttonClick.horizAlign="center"
   m.buttonClick.vertAlign="center"
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
 
  m.dialogOptions = m.top.findNode("dialogOptions")
  m.registredDevicesMarkup = m.top.findNode("registredDevicesMarkup")
 
 
 initMrkupWithData()
 
 
 m.top.observeField("focusedChild","OnFocusChildChange")
 m.ProfileCategory.setFocus(true)   
 initSupportDialog()
 privicyPolicy()
 
end sub

sub textTranslate()
  m.logoutText.text = tr("Are you sure you want to logout from this device?")
end sub

sub logoutApiCall()
?"ProfileSetting::logoutApiCall"
 m.YesButton.setFocus(false)
 showDialog("",false,false)
 ApiCallFor("Sign out","POST")
end sub


sub closeScreen()
?"ProfileScreen::closeScreen"; 
       ?"ProfileSetting::if:close screen::" 
       m.logout = false
       m.buttonClick.setFocus(false)
       m.ErrorDialogView.visible = false
       
       m.top.logout = true

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
 
 m.ProfileCategory.observeField("itemSelected","initDialogMrkupWithData")
 
 end sub
 
sub initDialogMrkupWithData(event as object)
?"ProfileSetting::MarkuGrid:init(): event pos: ";event.getData()
 m.dialogOptions.visible = false
  m.registredDevicesMarkup.visible = false
 if  event.getData() = 0
      m.ProfileCategory.setFocus(false)
     ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text,"GET")
     
 else if event.getData() = 1
     
      m.ProfileCategory.setFocus(false)
      ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text,"GET")
 else if event.getData() = 2

     m.ProfileCategory.setFocus(false)  
'     m.top.appendChild(m.DialogNode)
'     m.OkButton.setFocus(true)
     ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text,"GET")
    
 else if event.getData() = 3
    m.ProfileCategory.setFocus(false)
    ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text,"GET")
    
'    privicyPolicy()
'    m.ProfileTask.setField("callType","GET")
'    m.ProfileTask.setField("callFor","Privacy & Policy")
    
 else if event.getData() = 4 'Logout api call 
     m.ProfileCategory.setFocus(false)
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
'     m.ProfileTask.observeField("response","responseAPI")
     m.ProfileTask.control = "RUN" 'Run the task node 
     
end sub 
sub responseAPI(event as object)
  responseData = event.getData()
  ?"ProfileScreen::responseAPI: event: ";responseData
  
  m.LoginBusySpinner.visible=false
  m.ProfileTask.control = "STOP"
'  m.ProfileCategory.setFocus(true)
 if responseData.response_code = true
    
    if m.ProfileTask.callto = "Account" 
       marupContent = createObject("roSGNode", "ContentNode")  
       userData = responseData.data
        
       if userData.name <> invalid        
            
            marupContent.createChild("ContentNode").addFields({"Username:":userData.name.ToStr()})
       end if
       if userData.email <> invalid        
        marupContent.createChild("ContentNode").addFields({"Email:":userData.email.ToStr()})
       end if
       if userData.version <> invalid        
        marupContent.createChild("ContentNode").addFields({"Version:":userData.version.ToStr()})
       end if
       
       if userData.created <> invalid        
           marupContent.createChild("ContentNode").addFields({"Build Date:":userData.created.ToStr()})
       end if
  
        m.dialogOptions.itemSize = [816,52]

        
'         marupContent.createChild("ContentNode").addFields(assoArray)
        m.dialogOptions.visible = true 
        m.dialogOptions.content = marupContent
        showDialog("Account",true,false) 
    else if m.ProfileTask.callto = "Sign out" 
        ?"Profile::responseData.message:";responseData.message
        setAccountToken("")
        m.logout = true
        m.top.logout = true
    else if m.ProfileTask.callto = "Registered Devices" 
        ConnectedDevices = responseData.data 
        ?"Profile::responseData.message:";responseData.data
        marupContent = createObject("roSGNode", "ContentNode")
        count = 1
        for each data  in ConnectedDevices
          ?"data: "data
          ?"userData[data]: ";data["device_id"] ;data["device_name"]        
          if data["device_id"] <> invalid and  data["device_name"] <> invalid      
            assoArray = createObject("roAssociativeArray")
            key = count.ToStr()+". "+(data["device_name"].ToStr())
            assoArray[key] =   data["device_id"].ToStr()         
            marupContent.createChild("ContentNode").addFields(assoArray)
            count=count+1
           end if        
        end for
       
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        showDialog("Registered Devices",true,false)
    
    else if m.ProfileTask.callto = "Support" 
        ?"Profile::responseData.message:";responseData.data
        ?"Profile::responseData.message:";responseData.message
'            "message": "For help and support call the numbers below"   
                 
         m.TextLabel.text = responseData.message
'         contactText = ""
'         for each contacNo in responseData.data
'              contactText = contactText +contacNo+chr(10)  
'         end for
     if responseData.data.email<> invalid
         m.contactNumber.text = responseData.data.email
         m.top.appendChild(m.SupportDialog)
         m.HelpButton.setFocus(true)
     end if
         
         
     
    else if m.ProfileTask.callto = "Privacy & Policy"
         ?"Profile::responseData.message:";responseData
'        name, email,created_at
         m.ScrolableText.text = responseData.data.Replace("\n",chr(10))
         m.top.appendChild(m.DialogNodePoster)
         m.ScrolableText.setFocus(true)
         
    end if
 else
   ?"error dialog to show the error:";responseData
'    showDialog("",false,false)
     
    showDialogError(true , responseData.Failure, "Ok")
 end if

end sub

sub privicyPolicy()
?"privicyPolicy"
    m.DialogNodePoster = createObject("roSGNode", "Poster")
'    m.DialogNodePoster.visible =false
    m.DialogNodePoster.width = 1223
    m.DialogNodePoster.height = 714
    m.DialogNodePoster.uri = "pkg:/images/profile/dialogBackground.png"
    m.DialogNodePoster.translation = [349,224]
    m.ScrolableText = m.DialogNodePoster.createChild("ScrollableText")
 
    m.ScrolableText.width = 1183
    m.ScrolableText.height = 500
    m.ScrolableText.translation = [50,60]
    m.ScrolableText.font = getFont(36,"pkg:/fonts/HelveticaNeueMedium.ttf")
    m.ScrolableText.text = ""
    m.ScrolableText.horizAlign = "left" 
    m.ScrolableText.vertAlign = "top"
    
    
    m.OkButton1 = m.DialogNodePoster.createChild("Button")
    m.OkButton1.observeField("buttonSelected","closePrivicyPolicy")  
'    m.OkButton.setFocus(true)           
                 m.OkButton1.translation= [466,592]
                 m.OkButton1.minWidth  = "270"
                 m.OkButton1.maxWidth  = "270"                
                 m.OkButton1.focusedTextColor="#FFFFFF"
                 m.OkButton1.height = "84"              
                 m.OkButton1.focusBitmapUri="pkg:/images/Login/buttonFoucs.png"
                 m.OkButton1.showFocusFootprint="true"             
                 m.OkButton1.iconUri=""
                 m.OkButton1.focusedIconUri=""
                 m.OkButton1.focusFootprintBitmapUri = "pkg:/images/home/borderwhite3.png"
'                 m.OkButton.horizAlign="center"
                 okLabel =  m.OkButton1.createChild("Label")
                        okLabel.horizAlign="center"
                        okLabel.height = "84"
                        okLabel.width  = "270"
                        okLabel.vertAlign = "center"
                        okLabel.text="OK" 
                        font = getFont(36,"pkg:/fonts/HelveticaNeueMedium.ttf")
                        okLabel.font = font 

end sub
  
sub closeOkDialog()
?"closeOkDialog"
  if m.HelpButton.hasFocus()  and m.top.isInFocusChain()
     
'     m.HelpButton.setFocus(false)
     m.top.removeChild(m.SupportDialog)                   
     m.ProfileCategory.setFocus(true)
 
  else  
    
  end if 
end sub

sub initSupportDialog()

     m.SupportDialog = createObject("roSGNode", "Poster")
'     m.SupportDialog.visible = false
     m.SupportDialog.width = 1223
     m.SupportDialog.height = 370
     m.SupportDialog.uri = "pkg:/images/profile/dialogBackground.png"
     m.SupportDialog.translation = [(1920-m.SupportDialog.boundingRect().width)/2,(1080-m.SupportDialog.boundingRect().height)/2]
     
     m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
     m.LayoutGroup.translation = [50, 80]
     m.LayoutGroup.layoutDirection = "vert"
     m.LayoutGroup.itemSpacings = [10]
'     m.LayoutGroup .horizAlignment ="vert"
'      m.LayoutGroup .vertAlignment ="center"
     m.TextLabel = m.LayoutGroup.createChild("Label")
'     m.TextLabel.translation = [0, 87]
     m.TextLabel.width = 1023
     m.TextLabel.height = 0
     m.TextLabel.horizAlign = "Center" 
     m.TextLabel.vertAlign = "Center"     
     m.TextLabel.text = "For Help and Support You can mail us on below Contact."
     m.TextLabel.font = getFont(36,"pkg:/fonts/HelveticaNeueMedium.ttf")
     
    m.contactNumber = m.LayoutGroup.createChild("ScrollableText") 
    m.contactNumber.width = 1223
    m.contactNumber.height = 80
'    m.contactNumber.translation = [0, 150]
    m.contactNumber.font = getFont(36,"pkg:/fonts/HelveticaNeueMedium.ttf")
    m.contactNumber.horizAlign = "Center" 
    m.contactNumber.vertAlign = "Center"
'    m.contactNumber.text ="Shahjad.305@gmail.com"
'    m.contactNumber.translation = [500,0]   
    
    
    m.HelpButton = m.SupportDialog.createChild("Button")
    m.HelpButton.observeField("buttonSelected","closeOkDialog")  
              
'                 m.OkButton.translation= [466,429]
                 m.HelpButton.minWidth  = "280"
                 m.HelpButton.maxWidth  = "280"                
                 m.HelpButton.focusedTextColor="#FFFFFF"
                 m.HelpButton.height = "84"              
                 m.HelpButton.focusBitmapUri="pkg:/images/Login/buttonFoucs.png"
                 m.HelpButton.showFocusFootprint="true"             
                 m.HelpButton.iconUri=""
                 m.HelpButton.focusedIconUri=""
                 okLabel =  m.HelpButton.createChild("Label")
                        okLabel.horizAlign="center"
                        okLabel.height = "84"
                        okLabel.width  = "280"
                        okLabel.vertAlign = "center"
                        okLabel.text="OK" 
                   font = getFont(36,"pkg:/fonts/HelveticaNeueMedium.ttf")
                        okLabel.font = font             
    m.HelpButton.translation = [(1223 -m.HelpButton.boundingRect().width)/2,250]                                      
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
        
     else if dialogView = "Account" 
        
        m.dialogbackgndUri.width = 1223
        m.dialogbackgndUri.height = 640       
        m.profileView.visible = show
        m.Dialog.visible = show
        m.ok.translation= [471,510]
        m.ok.setFocus(true)
          
     else if dialogView = "Registered Devices"
        
        m.dialogbackgndUri.width = 1223
        m.dialogbackgndUri.height = 714
        
        m.profileView.visible = show
        m.Dialog.visible = show
        m.ok.setFocus(true) 
        m.ok.translation= [471,590]
     else if dialogView = ""
     
     end if
  else
    m.Dialog.visible = show
    m.ProfileCategory.setFocus(focus)
  end if
 
end sub
 

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
'    m.buttonClick.getCh
    m.buttonClick.observeField("buttonSelected","closeScreen")
    m.buttonClick.setFocus(true)
    m.logout = true
 else 
    m.buttonClick.setFocus(visible)
    m.ErrorDialogView.visible = visible
 end if
end sub 
 
sub closePrivicyPolicy()
?"closePrivicyPolicy" 
    m.ScrolableText.setFocus(false)
    m.OkButton1.setFocus(false)
    m.top.removeChild(m.DialogNodePoster)
    m.ProfileCategory.setFocus(true)    
       
end sub
  
function onKeyEvent(key as String, press as Boolean) as Boolean
    ?"Profile::key: ";key
    ?"Profile::press: ";press
    result = false    
    if press
    
      if m.ScrolableText.hasFocus() and key="down"
         m.OkButton1.setFocus(true)
         return true 
     else  if m.OkButton1.hasFocus() and  key="up"
          m.ScrolableText.setFocus(true)
          m.OkButton1.setFocus(false)
          return true
     else if (m.ScrolableText.hasFocus() or m.OkButton1.hasFocus() ) and key="back" 
           
          closePrivicyPolicy()
          
          return true

'    m.OkButton
'      
     else if  m.HelpButton.hasFocus()  and key="back"          
                 
           closeOkDialog()
      
      return true
     
     else if m.buttonClick.hasFocus() and m.logout = true and key = "back"
       
       m.buttonClick.setFocus(false)
       m.ErrorDialogView.visible =false
       m.top.logout = true
       m.logout = true

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
     else if key="back"
       m.top.exitView = true
            
     
     end if 
     
     return true
    end if 
    
       
end function     
