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
 
 m.dialogOptions = m.top.findNode("dialogOptions")
 
 initMrkupWithData()
' SupportDialog()
' m.top.observeField("visible","setfocus")
 m.top.observeField("focusedChild","OnFocusChildChange")
 m.ProfileCategory.setFocus(true)   
 SupportDialog()
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
 
 m.ProfileCategory.observeField("itemSelected","initDialogMrkupWithData")
 
 end sub
 
sub initDialogMrkupWithData(event as object)
?"ProfileSetting::MarkuGrid:init(): event pos: ";event.getData()
 
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
     
     m.ProfileTask.observeField("response","responseAPI")
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
       for each data  in userData.Keys()
         ?"data: "data
         ?"userData[data]: "userData[data]
         if userData[data] <> invalid        
            assoArray = createObject("roAssociativeArray")    
            assoArray[data+":"] =   userData[data].ToStr()          
            marupContent.createChild("ContentNode").addFields(assoArray)
          end if
        end for  
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
        m.dialogOptions.content = marupContent
        showDialog("Registered Devices",true,false)
    
    else if m.ProfileTask.callto = "Support" 
        ?"Profile::responseData.message:";responseData.data
        ?"Profile::responseData.message:";responseData.message
'            "message": "For help and support call the numbers below"
   
                 
         m.TextLabel.text = responseData.message
         contactText = ""
         for each contacNo in responseData.data
              contactText = contactText +contacNo+chr(10)  
         end for
         m.contactNumber.text = contactText
         m.top.appendChild(m.DialogNode)
         m.OkButton.setFocus(true)
         
         
         
'      02181
'      jbl to gwl
      
'      SupportDialog()
'       m.DialogNode.visible = true
'       m.OkButton.setFocus(true)
'       m.top.appendChild(m.DialogNode)
     
    else if m.ProfileTask.callto = "Privacy & Policy"
         ?"Profile::responseData.message:";responseData
         
         m.ScrolableText.text = responseData.data.Replace("\n",chr(10))
         m.top.appendChild(m.DialogNodePoster)
         m.ScrolableText.setFocus(true)
         
    end if
 else
   ?"error dialog to show the error:";responseData
'    showDialog("",false,false)
     
    showDialogError(true , responseData.Failure, "Something error")
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
    m.ScrolableText.font = "font:SmallBoldSystemFont"
    m.ScrolableText.text = "Roku now offers Nielsen audience measurement software on the Roku platform. You may choose to turn on these Nielsen audience measurement features in your channel. By doing so, you acknowledge and agree: &#xA; &#xA;YOU MAY NOT ENABLE THE NIELSEN AUDIENCE MEASUREMENT FEATURES IF YOU DO NOT AGREE TO ABOVE. PLEASE CONTACT ROKU OR NIELSEN FOR FURTHER INFORMATION. Roku now offers Nielsen audience measurement software on the Roku platform. You may choose to turn on these Nielsen audience measurement features in your channel. By doing so, you acknowledge and agree: &#xA; Roku now offers Nielsen audience measurement software on the Roku platform. You may choose to turn on these Nielsen audience measurement features in your channel. By doing so, you acknowledge and agree: &#xA;"
    m.ScrolableText.horizAlign = "left" 
    m.ScrolableText.vertAlign = "top"
    
    
    m.OkButton1 = m.DialogNodePoster.createChild("Button")
    m.OkButton1.observeField("buttonSelected","closePrivicyPolicy")  
'    m.OkButton.setFocus(true)           
                 m.OkButton1.translation= [466,592]
                 m.OkButton1.minWidth  = "280"
                 m.OkButton1.maxWidth  = "280"                
                 m.OkButton1.focusedTextColor="#FFFFFF"
                 m.OkButton1.height = "84"              
                 m.OkButton1.focusBitmapUri="pkg:/images/Login/buttonFoucs.png"
                 m.OkButton1.showFocusFootprint="true"             
                 m.OkButton1.iconUri=""
                 m.OkButton1.focusedIconUri=""
                 m.OkButton1.focusFootprintBitmapUri = "pkg:/images/profile/button1focus.png"
'                 m.OkButton.horizAlign="center"
                 okLabel =  m.OkButton1.createChild("Label")
                        okLabel.horizAlign="center"
                        okLabel.height = "84"
                        okLabel.width  = "280"
                        okLabel.vertAlign = "center"
                        okLabel.text="OK" 

end sub
  

sub SupportDialog()

     m.DialogNode = createObject("roSGNode", "Poster")
'     m.top.appendChild(m.DialogNode)
'     m.DialogNode.visible =false
     m.DialogNode.width = 1223
     m.DialogNode.height = 574
     m.DialogNode.uri = "pkg:/images/profile/dialogBackground.png"
     m.DialogNode.translation = [349,224]
     
     m.TextLabel = m.DialogNode.createChild("Label")
'     m.TextLabel.text = "For Help and Support You can call us on below numbers."
     m.TextLabel.translation = [0, 87]
     m.TextLabel.width = 1223
     m.TextLabel.height = 100
     m.TextLabel.horizAlign = "Center" 
     m.TextLabel.vertAlign = "Center"     
     
    m.contactNumber = m.DialogNode.createChild("ScrollableText") 
    m.contactNumber.width = 1223
    m.contactNumber.height = 200
    m.contactNumber.translation = [0, 150]
    m.contactNumber.font = "font:SmallBoldSystemFont"
    m.contactNumber.horizAlign = "Center" 
    m.contactNumber.vertAlign = "Center"
   
   
     
     
'     m.contactNumber = m.DialogNode.createChild("Label")
'     text ="+1-548965-51 (24*7) " '&#xA; 1-1800-815065-00"
'     m.contactNumber.text = text
'     m.contactNumber.translation = [423, 226]
'     m.contactNumber.width =0
'     m.contactNumber.height = 0
'     m.contactNumber.numLines = 0
     
    m.OkButton = m.DialogNode.createChild("Button")
    m.OkButton.observeField("buttonSelected","closeOkDialog")  
'    m.OkButton.setFocus(true)           
                 m.OkButton.translation= [466,429]
                 m.OkButton.minWidth  = "280"
                 m.OkButton.maxWidth  = "280"                
                 m.OkButton.focusedTextColor="#FFFFFF"
                 m.OkButton.height = "84"              
                 m.OkButton.focusBitmapUri="pkg:/images/Login/buttonFoucs.png"
                 m.OkButton.showFocusFootprint="true"             
                 m.OkButton.iconUri=""
                 m.OkButton.focusedIconUri=""
'                 m.OkButton.horizAlign="center"
                 okLabel =  m.OkButton.createChild("Label")
                        okLabel.horizAlign="center"
                        okLabel.height = "84"
                        okLabel.width  = "280"
                        okLabel.vertAlign = "center"
                        okLabel.text="OK"                          
end sub
sub closeOkDialog()
?"closeOkDialog"
  if m.OkButton.hasFocus()  and m.top.isInFocusChain()
     
     m.OkButton.setFocus(false)
     m.top.removeChild(m.DialogNode)                   
     m.ProfileCategory.setFocus(true)
 
  else  
    
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
        m.ok.setFocus(true) 
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
'    m.buttonClick.getCh
    m.buttonClick.observeField("buttonSelected","closeScreen")
   
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
     else if  m.OkButton.hasFocus()  and key="back"          
                 
           closeOkDialog()
      
      return true
     
     else if m.buttonClick.hasFocus() and m.logout = true and key = "back"
       
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
     else if key="back"
       m.top.exitView = true
            
     
     end if 
     
     return true
    end if 
    
       
end function     
