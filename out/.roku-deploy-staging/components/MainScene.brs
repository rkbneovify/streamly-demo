' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********  

sub init()
    ? "MAin init()::accessToken: "; getAccountToken()
    ' InitScreenStack(m.global["screenStack"])
'        setAccountToken("")  
    if getAccountToken() <> invalid and getAccountToken() <> ""
        ? "login successfully: accessToken: "; getAccountToken()
        ' ShowScreen(m.global["screenStack"],CreateObject("roSgNode","HomeScreen"))
        m.screen = showHomeScreen()
        m.screen.observeField("ExitView", "openLoginView")
    else
        ? "expired access token: "
        m.screen = showLoginScreen()
        m.screen.observeField("ExitView", "openHomeView")
        ' ShowScreen(m.global["screenStack"],CreateObject("roSgNode","LoginScreen"))

    end if
end sub

function showHomeScreen() as Object
    ? "showHomeScreen"
    if m.screen <> invalid then  m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    HomeScreen = m.top.createChild("HomeScreen")
    HomeScreen.setFocus(true)

    return HomeScreen
end function

function showLoginScreen() as Object
    ? "showLoginScreen"
    if m.screen <> invalid then  m.top.removeChild(m.screen)
    LoginScreen = m.top.createChild("LoginScreen")
    LoginScreen.observeField("dialog", "showDialog")

    LoginScreen.setFocus(true)
    return LoginScreen
end function

sub openLoginView()
    ? "openLoginView"
    m.screen = showLoginScreen()
    m.screen.observeField("ExitView", "openHomeView")
end sub

sub openHomeView()
    ? "openHomeView"
    m.screen = showHomeScreen()
    m.screen.observeField("ExitView", "openLoginView")
end sub

sub showDialog(event as Object)
    ? "MainScreen: showDialog: ";
    m.top.dialog = event.getRoSGNode().dialog
end sub
