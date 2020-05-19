' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********  

sub init()

    m.tracker=m.top.createChild("TrackerTask")
'    m.TrackerTask = CreateObject("roSGNode", "TrackerTask")
'    m.TrackerTask.control="RUN"
'    m.TrackerTask.control = "RUN" 'Run the task node 
    m.HomeScreen = m.top.findNode("HomeScreen")
   
    m.LoginScreen = m.top.findNode("LoginScreen")
   
    
    m.ProfileSettingScreen = m.top.findNode("ProfileSettingScreen")

    m.LoginScreen.observeField("visible","closeScreen")
    m.LoginScreen.observeField("dialog","showDialog")
    
    m.HomeScreen.observeField("openProfileScreen","openScreen")
    
    m.ProfileSettingScreen.observeField("visible","focusSet")
    m.ProfileSettingScreen.observeField("logout","logout")
   
   
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
      m.LoginScreen.findNode("UsernamEditText").setFocus(true)
   endif  
    
End sub
sub logout()
?"logout::expired access token: "
      
      m.HomeScreen.visible = false
      m.ProfileSettingScreen.visible = false
      m.LoginScreen.visible = true 
      
end sub
sub focusSet()
?"focusSet: ";

  if m.ProfileSettingScreen.visible = false and  m.ProfileSettingScreen.findNode("ProfileCategory").hasFocus()    
     m.HomeScreen.visible = true
     m.HomeScreen.findNode("timeGrid").setFocus(true)
          
  end if
end sub

sub closeScreen()
?"MainScreeen::closeScreen: ";
  
  if m.LoginScreen.visible = false
 
   ?"if close"
   m.HomeScreen.visible = true
   m.HomeScreen.setFocus(true) 
  
  else if m.LoginScreen.visible = true
 
   ?"else login screen open"
      
      m.HomeScreen.visible = false
      m.HomeScreen.findNode("myVideo").visible= false
      m.HomeScreen.findNode("ChannelsGroup").visible= false
      m.LoginScreen.visible = true
      
'      m.LoginScreen.findNode("UsernamEditText").setFocus(true)
  
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
 ?"MainScree::key: ";key
    result = false
    
    return result 
end function