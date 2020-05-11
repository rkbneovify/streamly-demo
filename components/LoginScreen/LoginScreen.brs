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
    
    signInFont  = CreateObject("roSGNode", "Font") 
    signInFont.uri = "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf"
    m.SignInLabel.text = "Sign In"
    signInFont.size = 44    
    m.SignInLabel.font = signInFont
    
    
    m.UsernamLable = m.top.findNode("UsernamLable")
    m.PasswordLable = m.top.findNode("PasswordLable")
    
    m.UsernamLable.text = "Username"
    m.PasswordLable.text = "Password"
        
    UsernameFont  = CreateObject("roSGNode", "Font") 
    UsernameFont.uri = "pkg:/fonts/HelveticaNeueMedium.ttf"
    UsernameFont.size = 24
    
    m.UsernamLable.font = UsernameFont
    m.PasswordLable.font = UsernameFont
    m.UsernamEditText.setFocus(true)
    
end sub

sub LoginValidation()
 ?"LoginValidation: ";
  
  if m.UsernamEditText.text <>"" 
      
       if IsValidEmail(m.UsernamEditText.text)
           
           if m.PasswordEditText.text <>""
               
               if Len((m.PasswordEditText.text)) < 11
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
   data =  "email="+email+"&password="+password
    ?"data: ";data
    m.LoginTask.data = data
   m.LoginTask.control = "RUN"
   m.LoginTask.observeField("response","LoginResponse")

end sub

sub LoginResponse()

?"LoginResponse :m.LoginTask: ";m.LoginTask.response
m.loginsuccess = false
  if m.LoginTask.response.success = true
        
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
    m.top.visible = false
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

function onTextBoxActive( event as boolean ) as void

  isActive =  event

  if ( isActive )
    m.UsernamPoster.Uri = "pkg:/images/Login/borderof.png"
  else
    m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
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
    if key="down" 
 
        
       ?"Login: if: "
     
      if m.PasswordEditText.hasFocus()
       ?"SignInButton: true"
'       m.PasswordEditText.active=false
       m.PasswordEditText.setFocus(false)
       
       m.SignInButton.setFocus(true)
       onTextBoxActive(false)
       onTextBoxActives(false) 
       m.UsernamEditText.active=false
       m.PasswordEditText.active=false   
       return true
     else if m.UsernamEditText.hasFocus() 
      
       ?"PasswordEditText: true"
       m.UsernamEditText.setFocus(false)
'       m.UsernamEditText.active=false

'       m.PasswordEditText.active=true
      
       m.PasswordEditText.setFocus(true)
       onTextBoxActives(true)
       onTextBoxActive(false)
       m.UsernamEditText.active=false
       m.PasswordEditText.active=false
       return true 
    end if
  
    else if  key="up"
     ?"Login: else: "
       
      if m.PasswordEditText.hasFocus()
     
        ?"PasswordEditText: true"
       m.UsernamEditText.setFocus(true)
      
       m.UsernamEditText.active=false
       m.PasswordEditText.active=false
      
      
       m.PasswordEditText.setFocus(false)
       onTextBoxActive(true)
       onTextBoxActives(false)
       m.UsernamEditText.active=false
       m.PasswordEditText.active=false
       return true
      else   if m.SignInButton.hasFocus() 
              
       m.SignInButton.setFocus(false)
       m.PasswordEditText.setFocus(true)
'       m.PasswordEditText.active=true
       onTextBoxActives(true)
       onTextBoxActive(false)
       
       m.UsernamEditText.active = false
       m.PasswordEditText.active = false
        
      return true
            
  else if ( m.TextField="UsernamEditText" or m.TextField="PasswordEditText") and key="up" 
     
      if  m.TextField="UsernamEditText"
        m.KeyboardPoster.visible = false
        m.KeyboardInput.setFocus(false)
        m.TextField=""
        m.UsernamEditText.setFocus(true)
        
        m.UsernamEditText.active = false
        m.PasswordEditText.active = false
       else if m.TextField="PasswordEditText"
         m.KeyboardPoster.visible = false
         m.KeyboardInput.setFocus(false)
         m.TextField=""
         m.PasswordEditText.setFocus(true)
         
         m.UsernamEditText.active = false
         m.PasswordEditText.active = false
       end if 
      return true      
     end if
   end if 
   
   
  else if m.UsernamEditText.hasFocus() and key="OK"
      
      m.TextField="UsernamEditText"
      m.UsernamEditText.setFocus(false)
      
      m.UsernamEditText.active=true
      m.PasswordEditText.active=false
      m.KeyboardInput.textEditBox.text = m.UsernamEditText.text
      m.KeyboardInput.textEditBox.cursorPosition = (m.UsernamEditText.text).Len()
      m.UsernamEditText.cursorPosition = (m.UsernamEditText.text).Len()
      m.KeyboardPoster.visible = true
      m.KeyboardInput.setFocus(true)
      
  else if m.PasswordEditText.hasFocus() and key = "OK"
  
      m.TextField="PasswordEditText"
      m.PasswordEditText.setFocus(false)
      
      m.PasswordEditText.active = true
      m.UsernamEditText.active = false
      
      m.KeyboardInput.textEditBox.text = m.PasswordEditText.text
      m.KeyboardInput.textEditBox.cursorPosition = (m.PasswordEditText.text).Len()
      
      m.PasswordEditText.cursorPosition = (m.PasswordEditText.text).Len()
      m.KeyboardPoster.visible = true
      m.KeyboardInput.setFocus(true)
  
  else    
  end if   
    return result 
end function