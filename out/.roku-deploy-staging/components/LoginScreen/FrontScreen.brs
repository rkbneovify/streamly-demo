sub INIT()
    ' ? "login init()";getPersonaldeviceid()
    login_guide()
    privicyPolicy()
    initSupportDialog()
    m.image1 = m.top.findNode("image1")
    m.image2 = m.top.findNode("image2")
    m.image3 = m.top.findNode("image3")
    m.circ1 = m.top.findNode("circ1")
    m.circ2 = m.top.findNode("circ2")
    m.circ3 = m.top.findNode("circ3")
    m.circ1.uri = "pkg:/images/bluec.png"
    m.simple_lable_1 = m.top.findNode("simple_lable_1")
    m.simple_lable_2 = m.top.findNode("simple_lable_2")
    m.simple_lable_3 = m.top.findNode("simple_lable_3")
    m.simple1 = m.top.findNode("simple1")
    m.simple2 = m.top.findNode("simple2")
    m.simple3 = m.top.findNode("simple3")
    m.simple_lable_1.visible = true
    m.simple_lable_2.visible = false
    m.simple_lable_3.visible = false
    m.simple1.visible = true
    m.simple2.visible = false
    m.simple3.visible = false
    m.sign = m.top.findNode("sign")
    m.remenberCheckList = m.top.findNode("rememberCheckList")
    m.remenberCheckList.translation = [760, 790]
    m.remenberCheckList.itemSize = [400, 100]
    m.remenberCheckList.checkedIconUri = "pkg:/images/uncheck.png"
    m.remenberCheckList.uncheckedIconUri = "pkg:/images/check1.png"
    m.remenberCheckList.focusedCheckedIconUri = "pkg:/images/uncheck.png"
    m.remenberCheckList.focusedUncheckedIconUri = "pkg:/images/check1.png"
    m.remenberCheckList.focusBitmapUri = ""
    m.remenberCheckList.focusFootprintBitmapUri = "pkg:/images/transparentimg.png"
    m.remenberCheckList.color = "#ffffff"
    m.remenberCheckList.focusedColor = "#0db5ba"
    m.remenberCheckList.Font = getFont(34, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.remenberCheckList.focusedFont = getFont(34, "pkg:/fonts/HelveticaNeueMedium.ttf")
    checklistNode = CreateObject("roSGNode", "ContentNode")
    contentNode = checklistNode.createChild("ContentNode")
    contentNode.Title = "Don't show again"
    m.remenberCheckList.content = checklistNode
    m.remenberCheckList.ObserveField("checkedState", "remeberMeUpdate")
    m.logout2 = m.top.findNode("logout2")
    m.logout = m.top.findNode("logout")

    ' Help
   

    m.textchange = false
    m.timer = m.top.findNode("exampleTimer")
    m.timer.ObserveField("fire", "changetext")
    m.timer.control = "start"
    m.sign.observeField("buttonSelected", "showLoginScreen")
    m.top.signalBeacon("AppLaunchComplete")
    m.rememberFlag = false
    ' m.remenberCheckList.checkedState = false
    ?"getGuideMode() "getGuideMode()
    if getGuideMode() = "true"
        ?"m.remenberCheckList.checkedState "m.remenberCheckList.checkedState
        showLoginScreen()

    end if
    m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
    m.LoaderBackground = m.top.findNode("LoaderBackground")
    m.LoginBusySpinner.observeField("visible", "showspinner")
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    m.LoginBusySpinner.visible = false

    ' Privacy Policy

    m.logout2.setFocus(true)
    m.logout2.observeField("buttonSelected", "showPrivacy")
    m.logout.observeField("buttonSelected", "showSupportDialog")
?"m.logout2 "m.logout2.hasFocus()
end sub

sub initSupportDialog()

    m.SupportDialog = createObject("roSGNode", "Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialog.width = 1920
    m.SupportDialog.height = 1200
    m.SupportDialog.uri = "pkg:/images/LoginBackgroundpng.png"
    m.SupportDialog.translation = [(1920 - m.SupportDialog.boundingRect().width) / 2, (1080 - m.SupportDialog.boundingRect().height) / 2]
  
    m.SupportDialogCircle = m.SupportDialog.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogCircle.width = 280
    m.SupportDialogCircle.height = 280
    m.SupportDialogCircle.uri = "pkg:/images/profile/circle.png"
    m.SupportDialogCircle.translation = [830, 170]
  
    m.SupportDialogSupport = m.SupportDialog.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogSupport.width = 280
    m.SupportDialogSupport.height = 230
    m.SupportDialogSupport.uri = "pkg:/images/profile/support.png"
    m.SupportDialogSupport.translation = [830, 200]
  
    m.SupportDialogEmail = m.SupportDialog.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogEmail.width = 320
    m.SupportDialogEmail.height = 280
    m.SupportDialogEmail.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialogEmail.translation = [620, 600]
  
  
  
    m.SupportDialogMail = m.SupportDialog.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogMail.width = 120
    m.SupportDialogMail.height = 100
    m.SupportDialogMail.uri = "pkg:/images/profile/mail.png"
    m.SupportDialogMail.translation = [720, 620]
  
    ' m.SupportDialogEmail = m.SupportDialog.createChild("Poster")
    ' '     m.SupportDialog.visible = false
    ' m.SupportDialogEmail.width = 320
    ' m.SupportDialogEmail.height = 250
    ' m.SupportDialogEmail.uri = "pkg:/images/profile/dialogBackground.png"
    ' m.SupportDialogEmail.translation = [650 , 600]
  
  
    ' m.SupportDialogPhone = m.SupportDialogEmail.createChild("Poster")
    ' '     m.SupportDialog.visible = false
    ' m.SupportDialogPhone.width = 250
    ' m.SupportDialogPhone.height = 200
    ' m.SupportDialogPhone.uri = "pkg:/images/profile/mobile.png"
    ' m.SupportDialogPhone.translation = [250 , 520]
  
    m.SupportDialogNumber = m.SupportDialog.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogNumber.width = 320
    m.SupportDialogNumber.height = 280
    m.SupportDialogNumber.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialogNumber.translation = [970, 600]
  
    m.SupportDialogPhone = m.SupportDialog.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogPhone.width = 130
    m.SupportDialogPhone.height = 100
    m.SupportDialogPhone.uri = "pkg:/images/profile/mobile.png"
    m.SupportDialogPhone.translation = [1060, 620]
  
    ' m.SupportDialogNumber = m.SupportDialog.createChild("Poster")
    ' '     m.SupportDialog.visible = false
    ' m.SupportDialogNumber.width = 320
    ' m.SupportDialogNumber.height = 250
    ' m.SupportDialogNumber.uri = "pkg:/images/profile/dialogBackground.png"
    ' m.SupportDialogNumber.translation = [1000 , 600]
  
    m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [580, 480]
    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [10]
  
    m.LayoutGroupEmail = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroupEmail.translation = [210, 720]
    m.LayoutGroupEmail.layoutDirection = "vert"
  
    m.LayoutGroupEmail.itemSpacings = [10]
  
    m.LayoutGroupNumber = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroupNumber.translation = [560, 740]
    m.LayoutGroupNumber.layoutDirection = "vert"
    m.LayoutGroupNumber.itemSpacings = [10]
  
    '     m.LayoutGroup .horizAlignment ="vert"
    '      m.LayoutGroup .vertAlignment ="center"
    m.TextLabel = m.LayoutGroup.createChild("Label")
    '     m.TextLabel.translation = [0, 87]
    m.TextLabel.width = 1023
    m.TextLabel.height = 0
    ' m.TextLabel.horizAlign = "Center"
    ' m.TextLabel.vertAlign = "Center"
    m.TextLabel.text = "For Help and Support You can mail us on below Contact."
    m.TextLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
  
    m.contactNumber = m.LayoutGroupEmail.createChild("ScrollableText")
    m.contactNumber.width = 1220
    m.contactNumber.height = 100
    m.contactNumber.color = "#004E92"
  
    ' m.contactNumber.translation = [0, 350]
    m.contactNumber.font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.contactNumber.horizAlign = "Center"
    m.contactNumber.vertAlign = "Center"
  
  
  
    m.contactNumbers = m.LayoutGroupNumber.createChild("ScrollableText")
    m.contactNumbers.width = 1220
    m.contactNumbers.height = 100
    m.contactNumbers.color = "#004E92"
  
    ' m.contactNumber.translation = [0, 350]
    m.contactNumbers.font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.contactNumbers.horizAlign = "Center"
    m.contactNumbers.vertAlign = "Center"
  
  
    '    m.contactNumber.text ="Shahjad.305@gmail.com"
    '    m.contactNumber.translation = [500,0]
  
  
    ' m.HelpButton = m.SupportDialog.createChild("Button")
    ' m.HelpButton.observeField("buttonSelected", "closeOkDialog")
  
    ' '                 m.OkButton.translation= [466,429]
    ' m.HelpButton.minWidth = "280"
    ' m.HelpButton.maxWidth = "280"
    ' m.HelpButton.focusedTextColor = "#FFFFFF"
    ' m.HelpButton.height = "84"
    ' m.HelpButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    ' m.HelpButton.showFocusFootprint = "true"
    ' m.HelpButton.iconUri = ""
    ' m.HelpButton.focusedIconUri = ""
    ' okLabel = m.HelpButton.createChild("Label")
    ' okLabel.horizAlign = "center"
    ' okLabel.height = "84"
    ' okLabel.width = "280"
    ' okLabel.vertAlign = "center"
    ' okLabel.text = "OK"
    ' font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    ' okLabel.font = font
    ' m.HelpButton.translation = [600 ,600]
  end sub

sub showSupportDialog()
    ? "showSupportDialog"
    '      m.top.appendChild( m.LoaderBackground )
    ApiCallFor("Support", "GET")
    ' showspinner(true)
    ' m.logout.setFocus(false)
end sub

sub showPrivacy()
    ?"privacy"
    ApiCallFor("Privacy & Policy", "GET")
end sub

sub changetext()
    if m.image1.visible then
        m.image1.visible = false
        m.image2.visible = true
        m.image3.visible = false
        m.simple_lable_1.visible = false
        m.simple_lable_2.visible = true
        m.simple_lable_3.visible = false
        m.simple1.visible = false
        m.simple2.visible = true
        m.simple3.visible = false
        m.circ1.uri = "pkg:/images/circ.png"
        m.circ2.uri = "pkg:/images/bluec.png"
        ' ?"m.image2.visible "m.image2.visible
        m.timer.control = "start"
        m.timer.duration = "2"
    else if m.image2.visible then
        m.image1.visible = false
        m.image2.visible = false
        m.image3.visible = true
        m.simple_lable_1.visible = false
        m.simple_lable_2.visible = false
        m.simple_lable_3.visible = true
        m.simple1.visible = false
        m.simple2.visible = false
        m.simple3.visible = true
        m.circ1.uri = "pkg:/images/circ.png"
        m.circ2.uri = "pkg:/images/circ.png"
        m.circ3.uri = "pkg:/images/bluec.png"

        m.timer.control = "start"
        m.timer.duration = "2"
    else if m.image3.visible then
        m.image1.visible = true
        m.image2.visible = false
        m.image3.visible = false
        m.simple_lable_2.visible = false
        m.simple_lable_3.visible = false
        m.simple_lable_1.visible = true
        m.simple2.visible = false
        m.simple3.visible = false
        m.simple1.visible = true
        m.circ1.uri = "pkg:/images/bluec.png"
        m.circ2.uri = "pkg:/images/circ.png"
        m.circ3.uri = "pkg:/images/circ.png"

        m.timer.control = "start"
        m.timer.duration = "2"
    end if
end sub


sub login_guide()
    m.personalDeviceName = CreateObject("roSGNode", "FrontApi")
    m.personalDeviceName.callType = "POST"
    m.personalDeviceName.control = "RUN"
    m.personalDeviceName.observeField("response", "loginguide_response")
end sub

sub loginguide_response(param as object)
    ? " personal Device name response param data"; param.getData()
    ? "persona device name response :" m.personalDeviceName.response.data
    for each data in m.personalDeviceName.response.data
        ?"data: "data
        m.image1.uri = m.personalDeviceName.response.data[0].image
        m.image2.uri = m.personalDeviceName.response.data[1].image
        m.image3.uri = m.personalDeviceName.response.data[2].image
        m.simple_lable_1.text = m.personalDeviceName.response.data[0].title
        m.simple_lable_2.text = m.personalDeviceName.response.data[1].title
        m.simple_lable_3.text = m.personalDeviceName.response.data[2].title
        m.simple1.text = m.personalDeviceName.response.data[0].description
        m.simple2.text = m.personalDeviceName.response.data[1].description
        m.simple3.text = m.personalDeviceName.response.data[2].description
    end for
end sub

function showLoginScreen() as object
    ? "LoginScreen"
    if m.remenberCheckList.checkedState[0] = true

        setGuideMode("true")

    else if m.remenberCheckList.checkedState[0] = false

        setGuideMode("false")

    end if

    if m.screen <> invalid then m.top.removeChild(m.screen)
    LoginScreen = m.top.createChild("LoginScreen")
    LoginScreen.observeField("dialog", "showDialog1")
    LoginScreen.observeField("ExitView", "openHomeView")
    LoginScreen.observeField("showPersonalDeviceName", "showPersonalDeviceNameScreen")
    LoginScreen.setFocus(true)
    return LoginScreen
end function

sub openLoginScreenAccordingToMode()
    ?"m.launcScreenMode"
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

sub openLoginView()
    ? "openLoginView"
    m.screen = showLoginScreen()
end sub

function showPersonalDeviceNameScreen() as object
    ?"personal screen "m.screen
    setFrontScreenMode("front")
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    personal_device_name = m.top.createChild("personal_device_name")
    personal_device_name.setFocus(true)
    personal_device_name.observeField("ExitView", "openLoginScreenAccordingToMode")
    personal_device_name.observeField("showLoginView", "showLoginScreen")
    return personal_device_name
end function

sub openHomeView()
    ? "openHomeView"
    ' m.screen = showHomeScreen()
    ' m.screen.observeField("ExitView", "openLoginView")
end sub

sub showDialog1(event as object)
    ? "MainScreen: showDialog: ";
    m.top.dialog = event.getRoSGNode().dialog
end sub

function showHomeScreen() as object
    ? "showHomeScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    HomeScreen = m.top.createChild("HomeScreen")
    HomeScreen.setFocus(true)
    return HomeScreen
end function

sub closePrivicyPolicy()
    ?"closePrivicyPolicy"
    m.ScrolableText.setFocus(false)
    m.OkButton1.setFocus(false)
    m.top.removeChild(m.DialogNodePoster)
    m.logout2.setFocus(true)
  
  end sub

function onKeyEvent(key as string, press as boolean) as boolean
    ? "Login: key: "; key
    ? "Login: press: "; press
    if press
        ' TC_Remember_needHelp_RegisterNow()

        if m.logout2.hasFocus() and key = "right"
            m.logout2.setFocus(false)
            m.logout.setFocus(true)
            return true
        else if m.logout.hasFocus() and key = "left"
            m.logout.setFocus(false)
            m.logout2.setFocus(true)
            return true
        else if m.logout.hasFocus() or m.logout2.hasFocus() and key = "down"
            m.logout.setFocus(false)
            m.logout2.setFocus(false)
            m.remenberCheckList.setFocus(true)
            return true
        else if m.remenberCheckList.hasFocus() and key = "up"
            m.logout.setFocus(false)
            m.logout2.setFocus(true)
            m.remenberCheckList.setFocus(false)
            ?"m.remenberCheckList.checkedState "m.remenberCheckList.checkedState[0]
            return true
        else if m.remenberCheckList.hasFocus() and key = "down"

            m.remenberCheckList.setFocus(false)
            m.sign.setFocus(true)
            return true
        else if m.sign.hasFocus() and key = "up"

            m.remenberCheckList.setFocus(true)
            m.sign.setFocus(false)
            return true
        else if m.logout2.hasFocus() and key = "OK"
            showPrivacy()
            m.logout2.setFocus(false)
            m.ScrolableText.setFocus(true)
            ?"privacy"
            return true
        else if m.ScrolableText.hasFocus() and (key = "down" or key = "OK")
            m.OkButton1.setFocus(true)
            return true
        else if m.OkButton1.hasFocus() and key = "up"
            m.ScrolableText.setFocus(true)
            m.OkButton1.setFocus(false)
            return true
          else if (m.ScrolableText.hasFocus() or m.OkButton1.hasFocus()) and key = "back"
      
            closePrivicyPolicy()
      
            return true
        else if (m.contactNumber.hasFocus()) and key = "back"
            ?"yes"
            closeOkDialog()
      
            return true

        else if m.remenberCheckList.hasFocus() and key = "OK"
            if m.rememberFlag = false
                m.rememberFlag = true
                m.remenberCheckList.checkedState = [m.rememberFlag]
                '  setGuideMode("true")

            else if m.rememberFlag = true
                m.rememberFlag = false
                m.remenberCheckList.checkedState = [m.rememberFlag]
                ' setGuideMode("false")

            end if

            return true

        ' else if key = "up"
        '     return on_up_press()
        ' else if key = "down"
        '     return on_down_press()
        end if
    else if key = "back"
        ?"back testing"
    
    else if m.logout2.hasFocus() and key = "OK" 
        ' showPrivacy()
        ?"privacy"
        return true  
         

    end if
    return false
end function

sub showspinner(event as object)
    ?"ProfileSetting::showspinner:event : ";event
  
  
    if(event.getData() = true)
  
      centerx = (1920 - m.LoginBusySpinner.poster.bitmapWidth) / 2
      centery = (1080 - m.LoginBusySpinner.poster.bitmapHeight) / 2
      m.LoginBusySpinner.translation = [centerx, centery]
  
      m.LoaderBackground.width = 1920
      m.LoaderBackground.height = 1080
      m.LoaderBackground.visible = true
  
      m.LoginBusySpinner.control = "start"
  
    else if(event.getData() = false)
  
      m.LoaderBackground.visible = false
      m.LoginBusySpinner.control = "stop"
  
    end if
  
  end sub

sub closeOkDialog()
    ?"closeOkDialog"
    ' if m.HelpButton.hasFocus() and m.top.isInFocusChain()
  
    '     m.HelpButton.setFocus(false)
    m.top.removeChild(m.SupportDialog)
    m.logout2.setFocus(true)
  
    ' else
  
    ' end if
  end sub

sub FieldText(event as object)
    ? "event: "; event.getData()
    m.device_name_edit_text.text = event.getData()
    m.device_name_edit_text.active = true
    m.device_name_edit_text.cursorPosition = m.KeyboardInput.textEditBox.cursorPosition + 1
end sub

function keybordClose() as boolean
    ? "keyboard close"
    m.KeyboardPoster.visible = false
    m.KeyboardInput.setFocus(false)
    m.device_name_edit_text.active = false
    return true
end function


function on_up_press() as boolean
    if m.KeyboardInput.hasFocus() or m.device_name_edit_text.hasFocus() or m.KeyboardPoster.visible = true
        ?"keybordClose :"
        keybordClose()
        m.device_name_edit_text.setFocus(false)
        m.device_name_edit_text.active = false

        m.KeyboardPoster.visible = false
        m.KeyboardInput.setFocus(false)
        m.personal_device_name_button_next.setFocus(true)
    else if m.personal_device_name_button_next.hasFocus()
        ?"Logout :"


        m.device_name_edit_text.setFocus(false)
        m.device_name_edit_text.active = false

        m.KeyboardPoster.visible = false
        m.KeyboardInput.setFocus(false)
        m.logout.setFocus(true)



    end if
    return true
end function

function on_down_press() as boolean
    if m.logout.hasFocus()
        ?"down :"

        m.device_name_edit_text.setFocus(true)
        m.device_name_edit_text.active = true

        m.KeyboardPoster.visible = true
        m.KeyboardInput.setFocus(true)
    end if
    return true
end function




sub showDialog(dialogView as string, show as boolean, focus as boolean)
    ?"ProfileSetting::showDialog"
    m.logoutView.visible = false



    if show
        m.logoutText.text = tr("Are you sure you want to logout from this device?")
        m.logoutView.width = 1223
        m.logoutView.height = 488
        m.logoutView.visible = show
        ' m.Dialog.visible = show
        m.logoutView.translation = [(1920 - m.logoutView.boundingRect().width) / 2, (1080 - m.logoutView.boundingRect().height) / 2]
    end if

end sub

'   sub ApiCallFor(callto as string, callType as string)
'     ?"ProfileSetting::ApiCallFor: "
'     m.ProfileTask = CreateObject("roSGNode", "ProfileTask") 'Create XML Parsing task node
'     m.LoginBusySpinner.visible = true
'     '     ApiCallFor(callto,"GET")
'     m.ProfileTask.callto = callto
'     m.ProfileTask.callType = callType

'     m.ProfileTask.observeField("response", "responseAPI")
'     m.ProfileTask.observeField("errorMessage", "errorDialogShow")

'     '     m.ProfileTask.observeField("response","responseAPI")
'     m.ProfileTask.control = "RUN" 'Run the task node

'   end sub




sub logoutApiCall()
    ?"ProfileSetting::logoutApiCall"
    m.YesButton.setFocus(false)
    showDialog("", false, false)
    ApiCallFor("Sign out", "POST")
end sub

sub closeDialog()
    ?"ProfileSetting:: close dialog"
    ?"ProfileSetting::else close dialog"
    ' m.ok.setFocus(false)
    m.NoButton.setFocus(false)

    showDialog("", false, true)
    m.device_name_edit_text.setFocus(true)
    m.device_name_edit_text.active = true
    ' m.buttonClick.setFocus(false)
    ' m.ErrorDialogView.visible = false

end sub



sub showDialogNext(title as string, message as string, optionsDialog as boolean, buttons as object, onserveField as object)
    ? "showDialog: message: "; message
    m.HelpButton.setFocus(true)
    m.contactNumber.text = message
    m.TextLabel.text = title
    m.SupportDialog.visible = true
    ' if not m.isSubscribe
    m.ButtonLabelText.text = "OK"
    ' m.device_name_edit_text.setFocus(true)
    ' end if
end sub

sub closeDialogNext()
    ? "closeDialog true"
    ? ""; m.needHelpText
    if m.needHelpText = "registerNow"
        m.SupportDialog.visible = false
        m.device_name_edit_text.setFocus(true)

    else
        m.HelpButton.setFocus(false)
        m.SupportDialog.visible = false
        m.SignInButton.setFocus(true)
    end if
    m.needHelpText = ""
end sub

function responseAPiMethod(jsonResponse as object)
    ?"responseAPiMethod:"
    if jsonResponse.responseCode = 200
        m.top.response = jsonResponse.jsonResponse
    else if jsonResponse.responseCode = 401 or jsonResponse.responseCode = 429 or jsonResponse.responseCode = 404
        m.top.errorMessage = jsonResponse
    end if
end function

sub initDialogMrkupWithData()
    ?"logout::MarkuGrid:init(): event pos: "
    m.NoButton.setFocus(true)
    showDialog("Sign out", true, false)
end sub

sub ApiCallFor(callto as string, callType as string)
    ?"ProfileSetting::ApiCallFor: "
    m.ProfileTask = CreateObject("roSGNode", "ProfileTask") 'Create XML Parsing task node
    m.LoginBusySpinner.visible = true
    '     ApiCallFor(callto,"GET")
    m.ProfileTask.callto = callto
    m.ProfileTask.callType = callType

    m.ProfileTask.observeField("response", "responseAPIPrivacy")
    m.ProfileTask.observeField("errorMessage", "errorDialogShow")

    '     m.ProfileTask.observeField("response","responseAPI")
    m.ProfileTask.control = "RUN" 'Run the task node
    ' m.top.ExitView = true

end sub

function errorDialogShow(event as object)
    if event.getData().responseCode = 401 or event.getData().responseCode = 429 or event.getData().responseCode = 404
        setAccountToken("")
        setThePersonalDeviceName("")
        setPersonaldeviceid("")
    end if
end function

sub responseAPIPrivacy(event as object)
    m.responseData = event.getData()
    ?"ProfileScreen::responseAPI: event: ";m.responseData.data
  ?"getHomeTimezone() "getHomeTimezone()
    m.LoginBusySpinner.visible = false
    m.ProfileTask.control = "STOP"
    '  m.ProfileCategory.setFocus(true)
    if m.responseData.response_code = true
  
      if m.ProfileTask.callto = "Account"
        marupContent = createObject("roSGNode", "ContentNode")
        userData = m.responseData.data
  
        if userData.name <> invalid
  
          marupContent.createChild("ContentNode").addFields({ "Username:": userData.name.ToStr() })
        end if
        if userData.email <> invalid
          marupContent.createChild("ContentNode").addFields({ "Email:": userData.email.ToStr() })
        end if
        if userData.version <> invalid
          marupContent.createChild("ContentNode").addFields({ "Version:": "1.18" })
        end if
  
        if userData.created <> invalid
          date = CreateObject("roDateTime")
          date.FromISO8601String(userData.created.ToStr())
          ' marupContent.createChild("ContentNode").addFields({ "Build Date:": date.AsDateString("short-date") })
          marupContent.createChild("ContentNode").addFields({ "Build Date:": "26-12-2022" })
        end if
          marupContent.createChild("ContentNode").addFields({ "Home Timezone :": "Indian Standard Time " })
  
          ?"getCurrentTimezone() "getCurrentTimezone()
          if getCurrentTimezone() = "-06:00"
            marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Central Standard Time " })
          else if getCurrentTimezone() = "-05:00"
            marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Eastern Standard Time " })
          else if getCurrentTimezone() = "-07:00"
            marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Mountain Standard Time " })
          else if getCurrentTimezone() = "-08:00"
            marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Pacific Standard Time " })
          else if getCurrentTimezone() = "-09:00"
              marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Alaska Standard Time" })
          else if getCurrentTimezone() = "-10:00"
            marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Hawaii Standard Time " })
          else if getCurrentTimezone() = "+05:30"
            marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Indian Standard Time " })
          else 
            marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Your Current Timezone GMT "+getCurrentTimezone() })
          end if 
          
  
        m.dialogOptions.itemSize = [900, 52]
  
        '         marupContent.createChild("ContentNode").addFields(assoArray)
        m.dialogOptions.visible = true
        m.dialogOptions.content = marupContent
        showDialog("Account", true, false)
      else if m.ProfileTask.callto = "Sign out"
        ?"Profile::responseData.message:";m.responseData.message
        setAccountToken("")
        setThePersonalDeviceName("")
        setPersonaldeviceid("")
        m.logout = true
        m.top.logout = true
        Screen = m.top.createChild("LoginScreen")
        Screen.setFocus(true)
      else if m.ProfileTask.callto = "Registered Devices"
        ' Here we have to show the list of the device. --------------------------------------------------------------------------------------------------------------------------
        setRegister("register")
        ConnectedDevices0 = m.responseData.numofdevice
        ConnectedDevices = m.responseData.data
        ?"Profile::responseData.message:";ConnectedDevices0
        marupContent = createObject("roSGNode", "ContentNode")
        marupContent = createObject("roSGNode", "ContentNode")
          for each data in ConnectedDevices
              ?"data: "data.session_id
              ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"] ;data["hotel_room"]
              marupContent.createChild("ContentNode").addFields(data)
        end for
  
          m.device_connected.visible = true
          m.device_connected.text = ConnectedDevices0
          m.registredDevicesMarkup.visible = true
          m.registredDevicesMarkup.content = marupContent
          m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
          showDialog("Registered Devices", true, false)
  
      else if m.ProfileTask.callto = "Support"
        ' RegWrite("back", "back")
        ?"Profile::responseData.message:";m.responseData.data
        ?"Profile::responseData.message:";m.responseData.message
        '            "message": "For help and support call the numbers below"
  
        m.TextLabel.text = m.responseData.message
        '         contactText = ""
        '         for each contacNo in responseData.data
        '              contactText = contactText +contacNo+chr(10)
        '         end for
  
        if m.responseData.data.email <> invalid
          m.contactNumber.text = "contact1@streamlytv" + chr(10) + ".com"
          m.contactNumbers.text = m.responseData.data.num1 + chr(10) + m.responseData.data.num2
          m.top.appendChild(m.SupportDialog)
          m.contactNumber.setFocus(true)
          ' m.HelpButton.setFocus(true)
        end if
  
  
  
      else if m.ProfileTask.callto = "Privacy & Policy"
        ?"Profile::responseData.message:";m.responseData
        '        name, email,created_at
        m.ScrolableText.text = m.responseData.data.Replace("\n", chr(10))
        m.top.appendChild(m.DialogNodePoster)
        m.ScrolableText.setFocus(true)
      end if
    else
      ?"error dialog to show the error:";m.responseData
      '    showDialog("",false,false)
  
      showDialogError(true, m.responseData.Failure, "Ok")
    end if
  
  end sub

  sub privicyPolicy()
    ?"privicyPolicy"
    m.DialogNodePoster = createObject("roSGNode", "Poster")
    '    m.DialogNodePoster.visible =false
    m.DialogNodePoster.width = 1223
    m.DialogNodePoster.height = 714
    m.DialogNodePoster.uri = "pkg:/images/profile/profile_dialog.png"
    m.DialogNodePoster.translation = [349, 224]
    m.ScrolableText = m.DialogNodePoster.createChild("ScrollableText")
    m.ScrolableText.width = 1183
    m.ScrolableText.height = 500
    m.ScrolableText.translation = [50, 60]
    m.ScrolableText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.ScrolableText.text = ""
    m.ScrolableText.horizAlign = "left"
    m.ScrolableText.vertAlign = "top"
    m.OkButton1 = m.DialogNodePoster.createChild("Button")
    m.OkButton1.observeField("buttonSelected", "closePrivicyPolicy")
    m.OkButton1.translation = [466, 592]
    m.OkButton1.minWidth = "270"
    m.OkButton1.maxWidth = "270"
    m.OkButton1.focusedTextColor = "#FFFFFF"
    m.OkButton1.height = "84"
    m.OkButton1.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.OkButton1.showFocusFootprint = "true"
    m.OkButton1.iconUri = ""
    m.OkButton1.focusedIconUri = ""
    m.OkButton1.focusFootprintBitmapUri = "pkg:/images/home/borderwhite3.png"
    '                 m.OkButton.horizAlign="center"
    okLabel = m.OkButton1.createChild("Label")
    okLabel.horizAlign = "center"
    okLabel.height = "84"
    okLabel.width = "270"
    okLabel.vertAlign = "center"
    okLabel.text = "OK"
    font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    okLabel.font = font
  
  end sub

sub responseAPI(event as object)
    responseData = event.getData()
    ?"ProfileScreen::responseAPI: event: ";responseData

    ' m.LoginBusySpinner.visible = false
    if responseData.response_code = true

        ' ?"Profile::responseData.message:";responseData.message
        ' setAccountToken("")
        ' setThePersonalDeviceName("")
        ' setPersonaldeviceid("")
        ' setUserHomeScreenMode("Login")
        ' m.top.showLoginView = true
    else
        ?"error dialog to show the error:";responseData
        '    showDialog("",false,false)

        showDialogError(true, responseData.Failure, "Ok")
        m.top.showLoginView = true
    end if
end sub

sub showDialogError(visible as boolean, text as string, buttontext as string)
    ?"ProfileSetting::showDialogError"

    if visible then
        m.ErrorDialogView.visible = visible

        m.messageBody.text = text
        m.ErrorDialogView.visible = visible
        m.buttonClick.setFocus(visible)
        m.buttonClick.text = buttontext
        '    m.buttonClick.getCh
        m.buttonClick.observeField("buttonSelected", "closeScreen")
        m.buttonClick.setFocus(true)
        m.logout = true
    else
        m.buttonClick.setFocus(visible)
        m.ErrorDialogView.visible = visible
    end if
end sub

