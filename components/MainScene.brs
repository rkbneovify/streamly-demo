' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********  

sub init()

    
    m.HomeScreen = m.top.findNode("HomeScreen")
   
    m.LoginScreen = m.top.findNode("LoginScreen")
    
    m.ProfileSettingScreen = m.top.findNode("ProfileSettingScreen")

    m.LoginScreen.observeField("visible","closeScreen")
    m.LoginScreen.observeField("dialog","showDialog")
    
    m.HomeScreen.observeField("openProfileScreen","openScreen")
    
    m.ProfileSettingScreen.observeField("visible","focusSet")
    
   
'    setAccountToken("")  
   if getAccountToken() <> Invalid and getAccountToken() <> ""
            
      ?"login successfully: accessToken: ";getAccountToken()
      m.LoginScreen.visible = false
      m.HomeScreen.visible = true
      m.HomeScreen.setFocus(true)
       
   else
   
      ?"expired access token: "
      m.HomeScreen.visible = false
      m.LoginScreen.visible = true
      m.LoginScreen.setFocus(true)
   
   endif  
    
End sub

sub focusSet()
?"focusSet: ";

  if m.ProfileSettingScreen.visible = false    
     m.HomeScreen.visible = true
     m.HomeScreen.findNode("timeGrid").setFocus(true)
          
  end if
end sub

sub closeScreen()
?"closeScreen: ";
  
  if m.LoginScreen.visible = false
 
   ?"if close"
   m.HomeScreen.visible = true
   m.HomeScreen.setFocus(true) 
  
  end if
  
end sub
sub showDialog()

 ?"MainScreen: showDialog: ";
 m.top.dialog = m.LoginScreen.dialog
 
end sub


sub openScreen(event as object) 
?"openScreen: ";event
  
 if event.getData()=true
 
      m.HomeScreen.visible = false
      m.HomeScreen.setFocus(false)
      m.ProfileSettingScreen.visible=true
      m.ProfileSettingScreen.setFocus(true)
 
 end if
end sub

function onKeyEvent(key as String, press as Boolean) as Boolean
 ?"key: ";key
    result = false
    
    return result 
end function