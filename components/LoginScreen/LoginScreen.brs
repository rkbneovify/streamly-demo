sub init()
  ?"login init()"
    m.SignInLabel = m.top.findNode("SignInLabel")
    m.UsernamEditText = m.top.findNode("UsernamEditText")
    m.PasswordEditText = m.top.findNode("PasswordEditText")
    m.SignInButton = m.top.findNode("SignInButton")
    
    'loader
    m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
    m.LoaderBackground = m.top.findNode("LoaderBackground")    
    m.LoginBusySpinner.observeField("visible", "showspinner")
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    m.LoginBusySpinner.visible = false
      
 
    m.UsernamPoster = m.top.findNode("UsernamPoster")
    m.PasswordPoster = m.top.findNode("PasswordPoster") 
    
    m.KeyboardPoster = m.top.findNode("KeyboardPoster") 
    m.KeyboardInput = m.top.findNode("KeyboardInput")  
    m.KeyboardInput.observeField("text","FieldText")
    
    m.SignInButton.observeField("buttonSelected","LoginValidation")
    m.KeyboardInput.textEditBox.observeField("cursorPosition","cursorPosition")
    
    m.KeyboardPoster.visible = false
    m.KeyboardInput.setFocus(false)
    m.TextField=""
    
    signInFont  = getFont(44,"pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.SignInLabel.text = "Sign In"   
    m.SignInLabel.font = signInFont
      
    m.UsernamLable = m.top.findNode("UsernamLable")
    m.PasswordLable = m.top.findNode("PasswordLable")
    
    m.UsernamLable.text = "Username"
    m.PasswordLable.text = "Password"
        
    UsernameFont  = getFont(24,"pkg:/fonts/HelveticaNeueMedium.ttf") 
    m.UsernamLable.font = UsernameFont
    m.PasswordLable.font = UsernameFont

    rememberMe()
    dontHaveAccount()

    m.top.observeField("focusedChild","OnFocusChildChange")

    m.setFocus=false
    initDialogforCreateAccount()

end sub
sub dontHaveAccount()
    
    m.dontHaveAccountLAbel = m.top.findNode("registerLabel")
    m.dontHaveAccountLAbel.text ="Don't have acount ?"
    m.dontHaveAccountLAbel.translation =[227,660]
    m.dontHaveAccountLAbel.color = "#000000"
    m.dontHaveAccountLAbel.Font= getFont(26,"pkg:/fonts/HelveticaNeueMedium.ttf") 
 
    
    m.OkButton1 = m.dontHaveAccountLAbel.createChild("Button")
    m.OkButton1.observeField("buttonSelected","showDialogforCreateAccount")  
'    m.OkButton.setFocus(true)           
                 m.OkButton1.translation= [250,-5]
                 m.OkButton1.minWidth  = "180"
                 m.OkButton1.maxWidth  = "180"                
                 m.OkButton1.focusedTextColor="#FFFFFF"
                 m.OkButton1.height = "45"              
                 m.OkButton1.focusBitmapUri="pkg:/images/Login/borderof.png"
                 m.OkButton1.showFocusFootprint="true"             
                 m.OkButton1.iconUri=""
                 m.OkButton1.focusedIconUri=""
                 m.OkButton1.focusFootprintBitmapUri = "pkg:/images/Login/borderon.png"
'                 m.OkButton.horizAlign="center"
                 okLabel =  m.OkButton1.createChild("Label")
                        okLabel.horizAlign="center"
                        okLabel.height = "45"
                        okLabel.color ="#004E92"
                        okLabel.width  = "180"
                        okLabel.vertAlign = "center"
                        okLabel.text="Register now"
                        okLabel.font = getFont(20,"pkg:/fonts/HelveticaNeueMedium.ttf")     
end sub
 
sub showDialogforCreateAccount()
    m.top.appendChild(m.DialogNodePoster)
    m.OkButton.setFocus(true) 
end sub
   
 
sub initDialogforCreateAccount()
?"showDialogforCreateAccount ;";

    m.DialogNodePoster = createObject("roSGNode", "Poster")
    m.DialogNodePoster.width = 1223
    m.DialogNodePoster.height = 554
    m.DialogNodePoster.uri = "pkg:/images/profile/dialogBackground.png"
    m.DialogNodePoster.translation = [389,324]
    
    m.labelTitle = m.DialogNodePoster.createChild("Label") 
    m.labelTitle.text = "Sign up for the best experience"
    m.labelTitle.font =  getFont(44,"pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.labelTitle.width = 1223
    m.labelTitle.height = 40
    m.labelTitle.horizAlign="center"
    m.labelTitle.vertAlign = "center"     
    m.labelTitle.translation = [0,50]
     
    m.ScrolableText = m.DialogNodePoster.createChild("ScrollableText") 
    m.ScrolableText.width = 1183
    m.ScrolableText.height = 300
    m.ScrolableText.translation = [50,130]
    m.ScrolableText.font = "font:SmallBoldSystemFont"
    m.ScrolableText.text = "Create account to see your favorite Sports Channels, Movies, News and much more." + chr(10) + chr(10) + "Just Go to the Website for sign up Now: " + chr(10) + "https://www.StreamlyTv.com/account"
    m.ScrolableText.horizAlign = "center" 
    m.ScrolableText.vertAlign = "center"
    
    
    m.OkButton = m.DialogNodePoster.createChild("Button")
    m.OkButton.observeField("buttonSelected","closeshowDialogforCreateAccoun") 
           
                 m.OkButton.translation= [470,440]
                 m.OkButton.minWidth  = "240"
                 m.OkButton.maxWidth  = "240"                
                 m.OkButton.focusedTextColor="#FFFFFF"
                 m.OkButton.height = "74"              
                 m.OkButton.focusBitmapUri="pkg:/images/Login/buttonFoucs.png"
                 m.OkButton.showFocusFootprint="true"             
                 m.OkButton.iconUri=""
                 m.OkButton.focusedIconUri=""
                 m.OkButton.focusFootprintBitmapUri = "pkg:/images/profile/button1focus.png"
'                 m.OkButton.horizAlign="center"
                 okLabel =  m.OkButton.createChild("Label")
                        okLabel.horizAlign="center"
                        okLabel.height = "74"
                        okLabel.width  = "240"
                        okLabel.vertAlign = "center"
                        okLabel.text="OK" 
    
end sub

sub closeshowDialogforCreateAccoun()
?"closeshowDialogforCreateAccoun"
    m.top.removeChild(m.DialogNodePoster)
    m.OkButton.setFocus(false)
    m.OkButton1.setFocus(true)
end sub
   
 
sub rememberMe()
       
     m.remenberCheckList =   m.top.findNode("rememberCheckList")
     m.remenberCheckList.translation =[127,455]
     m.remenberCheckList.itemSize=[300,50]
     m.remenberCheckList.checkedIconUri = "pkg:/images/checked_icon_30.png"
     m.remenberCheckList.uncheckedIconUri = "pkg:/images/unchecked_icon_30.png"
     m.remenberCheckList.focusedCheckedIconUri = "pkg:/images/checked_icon_30.png"
     m.remenberCheckList.focusedUncheckedIconUri = "pkg:/images/unchecked_icon_30.png"
     m.remenberCheckList.focusBitmapUri = ""
     m.remenberCheckList.focusFootprintBitmapUri = "pkg:/images/transparentimg.png"
     m.remenberCheckList.color="#000000"
     m.remenberCheckList.focusedColor="#0db5ba"
     m.remenberCheckList.Font= getFont(20,"pkg:/fonts/HelveticaNeueMedium.ttf") 
     m.remenberCheckList.focusedFont = getFont(30,"pkg:/fonts/HelveticaNeueMedium.ttf") 
     checklistNode = createObject("roSGNode", "ContentNode") 
     contentNode = checklistNode.createChild("ContentNode")
     contentNode.Title = "Remember me"
     m.remenberCheckList.content =  checklistNode 
     m.remenberCheckList.ObserveField("checkedState", "remeberMeUpdate")
     
     if getUserName() <> Invalid and getUserPassword()  <> Invalid  and getUserName() <> "" and getUserPassword() <> ""
       
         m.rememberFlag = true
          
         m.remenberCheckList.checkedState = [ m.rememberFlag ]
         
         m.UsernamEditText.text =  getUserName()
         m.PasswordEditText.text = getUserPassword()   
     else      
         m.rememberFlag = false 
     end if
   
     
end sub

sub remeberMeUpdate(event as object)
?"remeberMeUpdate: ";event.getData()[0]
    m.rememberFlag = event.getData()[0]
end sub

sub OnFocusChildChange()
 ?"OnFocusChildChange" 
 if m.top.hasFocus() and not m.UsernamEditText.hasFocus()  and  m.setFocus=false
    m.UsernamEditText.setFocus(true) 
    m.setFocus=true
 end if
 
end sub


sub LoginValidation()
 ?"LoginValidation: ";
  
  if m.UsernamEditText.text <>"" 
      
       if IsValidEmail(m.UsernamEditText.text)
           
           if m.PasswordEditText.text <>""
               
               if Len((m.PasswordEditText.text)) < 11
                   
                  if  m.rememberFlag then
                    
                    setUserName(m.UsernamEditText.text)
                    setUserPassword(m.PasswordEditText.text)
                  else
                    setUserName("")
                    setUserPassword("")
                  end if
                   
                   LoginProcess()
               else
                   showdialog("Error ","Password length should not grater than 10",true,"","")     
               end if
           else
               showdialog("Error ","Please fill the Password",true,"","")
           end if
          
       else
          
           showdialog("Error ","Please Enter Valid email",true,"","")
       end if 
     
  else 
     showdialog("Error ","Please fill the user name first",true,"","")
  end if   
  
end sub

sub LoginProcess()
 
 ?"LoginProcess: ";
 

' m.LoginBusySpinner.control="start"
 m.LoginBusySpinner.visible=true
 m.LoginTask  = CreateObject("roSGNode", "LoginTask")
 
 
   data=""
   email=""
   password=""
            
   email=(m.UsernamEditText.text).Trim()
   password=(m.PasswordEditText.text).Trim()
   data={}
   
   deviceInfo = getDeviceInfo()  'GetDeviceUniqueId()
  
   data["email"] = email
   data["password"] = password
   data["deviceid"] = deviceInfo.GetChannelClientId()
   
    m.LoginTask.callType = "POST" 
'   data =  "email="+email+"&password="+password+deviceid
    ?"data: ";data
   m.LoginTask.data = data
   m.LoginTask.control = "RUN"
   m.LoginTask.observeField("response","LoginResponse")

end sub

sub LoginResponse()

?"LoginResponse :m.LoginTask: ";m.LoginTask.response
m.loginsuccess = false
  if m.LoginTask.response.response_code = 200
        
        setAccountToken(m.LoginTask.response.access_token)  
        m.loginsuccess = true
        buttons = []
        buttons.Push("OK")         
        showdialog(m.LoginTask.response.message,m.LoginTask.response.connected_device , false,buttons ,"dialogButtonResponse" )
  else
        buttons = []
        buttons.Push("OK")
        buttons.Push("Cancel")
        showdialog("Login Error",m.LoginTask.response.message , true,buttons ,"")

  end if

'm.LoginBusySpinner.control="stop"
m.LoginBusySpinner.visible=false

end sub

sub dialogButtonResponse()
 ?"dialogButtonResponse: "m.dialog.buttonSelected
  
   if  m.loginsuccess = true and m.dialog.buttonSelected = 0
     m.dialog.close=true
     m.top.visible = false
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

sub showdialog(title as String,message as string , optionsDialog as boolean,buttons as object,onserveField as object)
      
      ?"showDialog: message: ";message

      m.dialog = createObject("roSGNode", "Dialog")
      m.dialog.backgroundUri = "pkg:/images/Login/backGroundBox.png" 
      m.dialog.title = title
      
      m.dialog.iconUri ="pkg:/images/Login/error_16.png"
      titleFont  = CreateObject("roSGNode", "Font") 
      titleFont.uri = "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf"
      titleFont.size = 44    
    
      m.dialog.titleFont = titleFont
'      m.dialog.iconUri=""
           
       m.dialog.titleColor = "#004E92"  
       m.dialog. messageColor = "#000000"
       m.dialog.dividerUri = "pkg:/images/Login/horizontal_line80.png"
      
      if onserveField <> ""   
    
           for each button in buttons
              m.dialog.buttonGroup.createChild("Button")
     
'             print "button ";button        
              m.dialog.buttonGroup.getChild( m.dialog.buttonGroup.getChildCount()-1).text = button 
              m.dialog.buttonGroup.getChild( m.dialog.buttonGroup.getChildCount()-1).focusedTextColor = "0xffffffff"             
           end for
     
         m.dialog.buttonGroup.setFields( {
          focusBitmapUri : "pkg:/images/Login/buttonFoucs.png"
          focusFootprintBitmapUri : "pkg:/images/Login/buttonFoucsFootprint.png"
         
          iconuri :""
          focusedIconUri : ""
          textColor : "#000000"
          showFocusFootprint : "true"
          
         })
         
'      m.dialog.graphicWidth = 300
'      m.dialog.graphicHeight = 300
  end if
     
    if onserveField <> "" then
          
       if message <> "" m.dialog.message = "   "+message
       
       m.dialog.observeField("buttonSelected","dialogButtonResponse")
       m.dialog.observeField("wasClosed","closeDialog")
    else
      m.dialog.optionsDialog = optionsDialog
      if message <> "" m.dialog.message = "   "+message+" ( Press * To Dismiss )"  
      m.dialog.observeField("wasClosed","closeDialog")               
    end if
     
      m.top.dialog = m.dialog

end sub
sub closeDialog()
 ?"closeDialog true"
 
 if m.loginsuccess = true
    'm.top.visible = false
    
      
      m.top.ExitView = true
'      LoginScreen = CreateObject("roSGNode", "LoginScreen")
'      m.LoginScreen.close = true
 end if
 
 m.dialog.close=true
end sub

sub cursorPosition(event as object)
?"cursorPosition: event: ";event.getData()
  if  m.TextField="UsernamEditText"           
           m.UsernamEditText.cursorPosition =  event.getData()
  else if  m.TextField="PasswordEditText"         
           m.PasswordEditText.cursorPosition = event.getData() 
  end if
end sub

sub FieldText(event as object)
?"event: ";event.getData()
 if m.TextField="PasswordEditText"
       m.PasswordEditText.text = event.getData()
       m.PasswordEditText.active=true
       m.UsernamEditText.cursorPosition =  m.KeyboardInput.textEditBox.cursorPosition
 else if m.TextField="UsernamEditText"
  
       m.UsernamEditText.text = event.getData()
       m.UsernamEditText.active=true       
       m.UsernamEditText.cursorPosition =  m.KeyboardInput.textEditBox.cursorPosition
 
 end if
end sub

function onTextBoxActive( field as string) as void

'  isActive =  event
m.TextField=""
  if field="username"
  
    m.UsernamPoster.Uri = "pkg:/images/Login/borderof.png"
    m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
    m.TextField = "UsernamEditText"
            
  else if field="password"
    m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
    m.PasswordPoster.Uri = "pkg:/images/Login/borderof.png"
    m.TextField="PasswordEditText"
  else
     m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"  
     m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png" 
  end if

end function

function IsValidEmail(email as String) as Boolean   
    regexEmail = "^[A-Za-z0-9_%+-]+(\.[A-Za-z0-9_%+-]+)*@([A-Za-z0-9-]+\.)+[A-Za-z]{2,6}$"
    return CreateObject("roRegex", regexEmail, "i").IsMatch(email)
end function


function onTextBoxActives( event as boolean )as void

  isActive =  event

  if ( isActive )
     m.PasswordPoster.Uri = "pkg:/images/Login/borderof.png"
  else
     m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
  end if

end function


function onKeyEvent(key as String, press as Boolean) as Boolean
 ?"Login: key: ";key
  ?"Login: press: ";press
  
  result = false
  if press
  
   if  m.OkButton.hasFocus()
   
    closeshowDialogforCreateAccoun()
     return true 
   else if key="down" 
            
       ?"Login: if: "
    
     if m.SignInButton.hasFocus() then
       
       m.SignInButton.setFocus(false)
       m.OkButton1.setFocus(true)
       
     else if m.PasswordEditText.hasFocus()
     
      m.PasswordEditText.setFocus(false)  
      m.remenberCheckList.setFocus(true)
      onTextBoxActive("")
      return true
     else if  m.remenberCheckList.hasFocus()
         
         ?"SignInButton: true"
       m.remenberCheckList.setFocus(false)
       
       m.SignInButton.setFocus(true)
             
       return true
        
     
     else if m.UsernamEditText.hasFocus() 
      
       ?"PasswordEditText: true"
       m.UsernamEditText.setFocus(false)

       m.PasswordEditText.setFocus(true)
      
       onTextBoxActive("password")

       return true 
    end if
  
    else if  key="up"
     ?"Login: else: "
       
     if m.OkButton1.hasFocus() then
       
       m.OkButton1.setFocus(false) 
       m.SignInButton.setFocus(true)     
       
     else if m.PasswordEditText.hasFocus()
     
        ?"UsernamEditText: true"
      
       m.PasswordEditText.setFocus(false)
       m.UsernamEditText.setFocus(true)
       onTextBoxActive("username")

       return true
      else if  m.remenberCheckList.hasFocus()
        
        m.remenberCheckList.setFocus(false)
        m.PasswordEditText.setFocus(true)
        onTextBoxActive("password")       
        return true
        
      else  if m.SignInButton.hasFocus() 
       
       ?"remenberCheckList: true"         
       m.remenberCheckList.setFocus(true)       
       m.SignInButton.setFocus(false)             
       return true
            
  else if   m.TextField = "UsernamEditText" or m.TextField="PasswordEditText" and key="up" 
      ?"keyboard close"
       m.KeyboardPoster.visible = false
       m.KeyboardInput.setFocus(false)
       m.UsernamEditText.active=false
       m.PasswordEditText.active=false
'      m.TextField = ""
     
      if  m.TextField="UsernamEditText"

        m.UsernamEditText.setFocus(true)
      
      else if m.TextField="PasswordEditText"
        
         m.PasswordEditText.setFocus(true)

       end if 
        
      return true      
     end if
   end if 
  
 else if m.UsernamEditText.hasFocus() and key="OK"
     
     ?"keyboard open for email field"
           
'      m.UsernamEditText.active = false
'      m.PasswordEditText.active=false
      
'      m.TextField="UsernamEditText"
      m.UsernamEditText.setFocus(false)      
      m.UsernamEditText.active=true
       onTextBoxActive("username")
      m.KeyboardInput.textEditBox.text = m.UsernamEditText.text
      m.KeyboardInput.textEditBox.cursorPosition = (m.UsernamEditText.text).Len()
      m.UsernamEditText.cursorPosition = (m.UsernamEditText.text).Len()
      m.KeyboardPoster.visible = true
      m.KeyboardInput.setFocus(true)
 
      return true
 
 
  else if m.PasswordEditText.hasFocus() and key = "OK"
      
      ?"keyboard open for password field"
'      m.TextField="PasswordEditText"
      m.PasswordEditText.setFocus(false)
      onTextBoxActive("password")
      
      m.PasswordEditText.active = true
      
      m.KeyboardInput.textEditBox.text = m.PasswordEditText.text
      m.KeyboardInput.textEditBox.cursorPosition = (m.PasswordEditText.text).Len()     
      m.PasswordEditText.cursorPosition = (m.PasswordEditText.text).Len()
      
      m.KeyboardPoster.visible = true
      m.KeyboardInput.setFocus(true)
 
     return true
   
  end if   
    return result 
end function