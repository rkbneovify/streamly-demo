' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********  

sub init()
    ? "MAin init()::accessToken: "; getAccountToken()
    ? "MAin init()::getDeviceInfo: "; getDeviceInfo()
    ' InitScreenStack(m.global["screenStack"])
'        setAccountToken("")  
analyticsInit()

' m.global.RSG_analytics.trackEvent = {
'     google: {
'         ec: "navigation",
'         ea: "channel_launch"
'    
' }

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
function analyticsInit()
    
    di = CreateObject("roDeviceInfo")
    ?"di.GetDisplaySize()";di.GetDisplaySize()

    m.global.addField("RSG_analytics","node",false)
    m.global.RSG_analytics = CreateObject("roSGNode","Roku_Analytics:AnalyticsNode")
    'disables all debug prints, enable it in order to see which events are fired
    m.global.RSG_analytics.debug = true
    
    ' Analytics Initialization
    m.global.RSG_analytics.init = {
         
        'set data to IQ analytics
        Google : {
            ' trackingID : "G-S8PDH6LPDV"           G-RGE4ZXT43Q             
            trackingID :"G-RGE4ZXT43Q"                      
            defaultParams : {
                an : "StreamlyTv"
            }
            an : "StreamlyTv"
            av : di.GetVersion()
            cid : di.GetChannelClientId()
            sr : Str(di.GetDisplaySize().w)+"*"+ Str(di.GetDisplaySize().h) 'must be expressed as WWWWxHHHH
        }
     
        
    }


end function

