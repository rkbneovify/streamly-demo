' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********  

sub init()
    
     ?"lMAin::accessToken: ";getAccountToken()
'        setAccountToken("")  
   if getAccountToken() <> Invalid and getAccountToken() <> ""
            
      ?"login successfully: accessToken: ";getAccountToken()
      m.screen = showHomeScreen()
      m.screen.observeField("ExitView","openLoginView") 
   else
      ?"expired access token: "
       m.screen = showLoginScreen()
       m.screen.observeField("ExitView","openHomeView")
   end if  
    
End sub


Function showHomeScreen() as object
 ?"showHomeScreen"   
    if m.screen <> invalid then  m.top.removeChild(m.screen)
    HomeScreen = m.top.createChild("HomeScreen")  
    HomeScreen.setFocus(true)
    
    return HomeScreen
    
end Function

Function showLoginScreen()as object
?"showLoginScreen"  
    if m.screen <> invalid then  m.top.removeChild(m.screen)
    LoginScreen = m.top.createChild("LoginScreen")
    LoginScreen.observeField("dialog","showDialog")
      
    LoginScreen.setFocus(true)
    return LoginScreen
end Function

sub openLoginView()
?"openLoginView"
  m.screen = showLoginScreen()
  m.screen.observeField("ExitView","openHomeView")
end sub

sub openHomeView()
?"openHomeView"
    m.screen = showHomeScreen()
    m.screen.observeField("ExitView","openLoginView") 
end sub
 

sub showDialog(event as object)

 ?"MainScreen: showDialog: ";
 m.top.dialog = event.getRoSGNode().dialog
 
end sub
