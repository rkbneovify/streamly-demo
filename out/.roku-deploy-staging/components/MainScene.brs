' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********

sub init()
    ? "MAin init()::accessToken: "; getAccountToken()
    ? "MAin init()::getDeviceInfo: "; getDeviceInfo()
    ? "Main init() :: getThePersonalDeviceName: "; getThePersonalDeviceName()
    ?"m.Personaldevicename_popup"m.Personaldevicename_popup
    ?"getPersonaldevicename_popup(): "getPersonaldevicename_popup()
    ?"getVide_val() "getVide_val()
    m.top.signalBeacon("AppLaunchComplete")
    ' InitScreenStack(m.global["screenStack"])
    ' ' setAccountToken("")
    ' analyticsInit()

    ' m.global.RSG_analytics.trackEvent = {
    '     google: {
    '         ec: "navigation",
    '         ea: "channel_launch"

    '     }
    ' }
    m.launcScreenMode = getUserHomeScreenMode()
    ?"m.launcScreenMode "getUserHomeScreenMode()
    m.popup = getUserPersonalScreenMode()
    ?"m.popup" m.popup
    m.roomSelectionFlag = getRoomSelectionFlag()
    m.personalDeviceName = getThePersonalDeviceName()
    m.accessTocken = getAccountToken()

    if(m.personalDeviceName = invalid)
        m.personalDeviceName = ""
    end if

    if m.accessTocken = invalid
        m.accessTocken = ""
    end if


    ' m.Personaldevicename_popup="true"
    m.Personaldevicename_popup = getPersonaldevicename_popup()
    if(m.Personaldevicename_popup = invalid)
        m.Personaldevicename_popup = "true"

    end if
    ' if(getThePersonalDeviceName() <> "")

    '     m.Personaldevicename_popup = "false"
    ' else
    '
    ' end if


    ' m.Personaldevicename_popup = "true"
    if(m.global.deeplink <> invalid)
        if (Instr(1, LCase("FNC"), LCase(m.global.deeplink.id)) > 0)
            m.screen = showDeepLinkingVideo()
        end if
    else
        ' if m.accessTocken <> "" and m.Personaldevicename_popup = "true" then
        '     ? "personal Device name : " m.Personaldevicename_popup
        '     m.screen = showPersonalDeviceNameScreen()
        if m.accessTocken <> "" then
            ? "login successfully: accessToken: "; getAccountToken()
            ?"checking"
            ' ShowScreen(m.global["screenStack"],CreateObject("roSgNode","HomeScreen"))
            ' ?"m.launchscreenmode: "m.screen



            m.screen = setHomeScreenAccordingMode() 'showHomeScreen()
            ' m.screen = showHomeScreen()


            'm.screen.observeField("ExitView", "openLoginView")
        else if getGuideMode() = "true"
            ' ?"m.remenberCheckList.checkedState "m.remenberCheckList.checkedState
            m.screen = showLoginScreen()

        else
            ? "expired access token: "
            m.screen = showLoginScreen()
            ' m.screen = showFrontScreen()
            ' ShowScreen(m.global["screenStack"],CreateObject("roSgNode","LoginScreen"))

        end if

    end if
end sub

function showPersonalDeviceNameScreen() as object
    ?"m.screen "m.screen
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ?"pppppppppppppp"
    ' ShowScreen("HomeScreen")
    personal_device_name = m.top.createChild("personal_device_name")
    personal_device_name.setFocus(true)
    personal_device_name.observeField("ExitView", "openLoginScreenAccordingToMode")
    personal_device_name.observeField("showLoginView", "showLoginScreen")
    return personal_device_name
end function

function showDeepLinkingVideo() as object
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    deep_link_screen = m.top.createChild("DeepLink_VideoPlayer")
    deep_link_screen.setFocus(true)
    deep_link_screen.observeField("ExitView", "onExitOfDeepLonkingScreen")
    return deep_link_screen
end function

function onExitOfDeepLonkingScreen()
    if m.accessTocken <> "" and m.Personaldevicename_popup = "true" then
        ? "personal Device name : " m.Personaldevicename_popup
        m.screen = showPersonalDeviceNameScreen()
    else if m.accessTocken <> "" then
        ? "login successfully: accessToken: "; getAccountToken()
        ' ShowScreen(m.global["screenStack"],CreateObject("roSgNode","HomeScreen"))
        ?"m.launchscreenmode: "m.screen
        m.screen = setHomeScreenAccordingMode() 'showHomeScreen()
        'm.screen.observeField("ExitView", "openLoginView")
    else
        ? "expired access token: "
        m.screen = showLoginScreen()
    end if
end function

function showHomeScreen() as object
    ? "showHomeScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    HomeScreen = m.top.createChild("HomeScreen")
    HomeScreen.setFocus(true)
    return HomeScreen
end function

function showLoginScreen() as object
    ? "showLoginScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    LoginScreen = m.top.createChild("LoginScreen")
    LoginScreen.observeField("dialog", "showDialog")
    LoginScreen.observeField("ExitView", "openHomeView")
    LoginScreen.observeField("showPersonalDeviceName", "showPersonalDeviceNameScreen")
    LoginScreen.setFocus(true)
    return LoginScreen
end function

function showFrontScreen() as object
    ? "showLoginScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    FrontScreen = m.top.createChild("FrontScreen")
    ' FrontScreen.observeField("showLoginView", "showLoginScreen")
    ' LoginScreen.observeField("dialog", "showDialog")
    ' LoginScreen.observeField("ExitView", "openHomeView")
    ' LoginScreen.observeField("showPersonalDeviceName", "showPersonalDeviceNameScreen")
    FrontScreen.setFocus(true)
    return FrontScreen
end function

sub openLoginView()
    ? "openLoginView"
    m.screen = showLoginScreen()
end sub

' sub openPersonal_ScreenView()
'     ? "openLoginView"
'     m.screen = showPScreen()
' end sub

sub openHomeView()
    ? "openHomeView"
    m.screen = showHomeScreen()
    m.screen.observeField("ExitView", "openLoginView")
end sub

sub openSettingView()
    ? "openHomeView"
    m.screen = showSettingScreen()
    m.screen.observeField("ExitView", "openHomeView")
end sub

function showSettingScreen()
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ProfileSettingScreen = m.top.createChild("ProfileSettingScreen")
    ProfileSettingScreen.observeField("exitView", "closeProfileScreen")
    ProfileSettingScreen.observeField("logout", "showLoginPage")
    ProfileSettingScreen.setFocus(true)
    return ProfileSettingScreen
end function

sub showDialog(event as object)
    ? "MainScreen: showDialog: ";
    m.top.dialog = event.getRoSGNode().dialog
end sub

sub openLoginScreenAccordingToMode()
    m.launcScreenMode = getUserHomeScreenMode()
    if m.launcScreenMode = "Login"
        '  Open Login screen here
        openLoginView()
    else if m.launcScreenMode = "Room"
        if m.roomSelectionFlag = "false"
            ' Open a Room screen for selection
            openLoginView()
        end if
    end if
end sub

sub setHomeScreenAccordingMode() as object
    ?"m.launchscreenmode: "m.launcScreenMode
    ?"getLogin() "getLogin()
    ?"getSettingMode() "getSettingMode()
    if m.launcScreenMode <> invalid

        if m.launcScreenMode = "Home"
            if getLogin() = "login"
                ' deleteLogin()
                return openLoginView()
            else if getSettingMode() = "setting"
                ?"get setting"
                return openSettingView()
            else
                return openHomeView()
            end if
            ' Open Home screen
            return openHomeView()
        else if m.launcScreenMode = "Login"
            '  Open Login screen here
            return openLoginView()
        else if m.launcScreenMode = "personalDeviceName"
            if m.popup = "personalDeviceName"
                return openHomeView()
            else
                '  Open personal_device_name screen here
                return showPersonalDeviceNameScreen()
            end if
        else if m.launcScreenMode = "Room"
            ?"room testing"m.roomSelectionFlag
            if m.roomSelectionFlag = "false"

                ' Open a Room screen for selection
                return openLoginView()
            else if m.roomSelectionFlag = ""

                ' Open a Room screen for selection
                return openHomeView()
            end if
        end if
    end if
end sub

function analyticsInit()

    di = CreateObject("roDeviceInfo")
    ?"di.GetDisplaySize()";di.GetDisplaySize()

    m.global.addField("RSG_analytics", "node", false)
    m.global.RSG_analytics = CreateObject("roSGNode", "Roku_Analytics:AnalyticsNode")
    'disables all debug prints, enable it in order to see which events are fired
    m.global.RSG_analytics.debug = true

    ' Analytics Initialization
    m.global.RSG_analytics.init = {

        'set data to IQ analytics
        Google: {
            ' trackingID : "G-S8PDH6LPDV"           G-RGE4ZXT43Q
            trackingID: "G-RGE4ZXT43Q"
            defaultParams: {
                an: "StreamlyTv"
            }
            an: "StreamlyTv"
            av: di.GetOsVersion()
            cid: di.GetChannelClientId()
            sr: Str(di.GetDisplaySize().w) + "*" + Str(di.GetDisplaySize().h) 'must be expressed as WWWWxHHHH
        }


    }


end function

