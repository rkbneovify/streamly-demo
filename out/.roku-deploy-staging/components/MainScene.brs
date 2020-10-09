' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********  

sub init()
    ? "MAin init()::accessToken: "; getAccountToken()
    ' InitScreenStack(m.global["screenStack"])
'        setAccountToken("")  
analyticsInit()

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
    
    m.global.addField("RSG_analytics","node",false)
    m.global.RSG_analytics = CreateObject("roSGNode","Roku_Analytics:AnalyticsNode")
    'disables all debug prints, enable it in order to see which events are fired
    m.global.RSG_analytics.debug = true
    
    ' Analytics Initialization
    m.global.RSG_analytics.init = {
         
        'set data to IQ analytics
        Google : {
            trackingID : "UA-107042774-1"
            defaultParams : {
                an : "RokuAnalyticsClient"
            }
        }
        'set proper init values for Omniture analytics
        ' Omniture : {
        '     baseUrl : "http://test.com/"
        '     defaultParams : {
        '         sv         : "AnalyticNodeSample"
        '         v11        : "D=c11"
        '         c11        : "<TIME_PARTING_HOUR>"
        '         v12        : "D=c12"
        '         c12        : "<TIME_PARTING_DAY>"
        '         v14        : "D=c14"
        '         c14        : "D=User-Agent:Roku/3100X"'CreateObject("roDeviceInfo").GetModel()
        '         v55        : "D=c55"
        '         c55        : "no sponsor"
        '         v66        : "D=c66"
        '         c66        : "analytic_node_sample"
        '         ch         : "roku:analytic_node_sample"
        '     }
        ' }
        
    }


end function

