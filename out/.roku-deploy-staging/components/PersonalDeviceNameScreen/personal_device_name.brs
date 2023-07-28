sub INIT()
    ? "login init()";getPersonaldeviceid()
    ?"m.screen"m.screen
    
    

    m.personal_device_name_background_img = m.top.findNode("personal_device_name_background_img")
    m.personal_device_name_text = m.top.findNode("personal_device_text_rect")
    m.personal_device_edit_text_rect = m.top.findNode("personal_device_edit_text_rect")
    m.simple_heading_text = m.top.findNode("simple_lable_1")
    m.simple_text = m.top.findNode("simple_lable_2")
    m.main_layout_group = m.top.findNode("main_layout_group")
    m.device_name_edit_text = m.top.findNode("device_name_edit_text")
    m.personal_device_name_button_next = m.top.findNode("personal_device_name_button_next")
    m.personal_device_edit_text_bottom_line = m.top.findNode("personal_device_edit_text_bottom_line")
    m.personal_device_text_rect = m.top.findNode("personal_device_text_rect")
    m.KeyboardPoster = m.top.findNode("KeyboardPoster")
    m.KeyboardInput = m.top.findNode("KeyboardInput")
    
    m.logout = m.top.findNode("logout")
    m.logoutText = m.top.findNode("logoutText")
    m.logoutView = m.top.findNode("logoutView")
    m.NoButton = m.top.findNode("NoButton")
    m.YesButton = m.top.findNode("YesButton")
    m.YesButton.observeField("buttonSelected", "logoutApiCall")
    m.NoButton.observeField("buttonSelected", "closeDialog")
    m.device_name_edit_text.cursorPosition = m.KeyboardInput.textEditBox.cursorPosition

    m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
    m.LoaderBackground = m.top.findNode("LoaderBackground")
    m.LoginBusySpinner.observeField("visible", "showspinner")
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    m.LoginBusySpinner.visible = false


    setUserHomeScreenMode("personalDeviceName")
    initView()
    initSupportDialog()
    m.launchScreenMode = getUserHomeScreenMode()

    m.logoutPassCode = false

    m.device_name_edit_text.setFocus(true)
        m.device_name_edit_text.active = true

        ' m.KeyboardPoster.visible = true
        ' m.KeyboardInput.setFocus(true)
    m.top.signalBeacon("AppLaunchComplete")

end sub

sub initSupportDialog()
    m.dialogHeight = 400
    m.dialogWidth = 1223

    m.SupportDialog = m.top.createChild("Poster")
    m.SupportDialog.visible = false
    m.SupportDialog.width = m.dialogWidth
    m.SupportDialog.height = m.dialogHeight
    m.SupportDialog.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialog.translation = [(1920 - m.dialogWidth) / 2, (1080 - m.dialogHeight) / 2]

    m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [(m.SupportDialog.width) / 2, 50]
    ' m.LayoutGroup.translation = [(1920 - m.dialogWidth) / 2, 0]
    m.LayoutGroup.layoutDirection = "vert"
    ' m.LayoutGroup.vertAlignment = "bottom"
    m.LayoutGroup.itemSpacings = [20]
    m.LayoutGroup.horizAlignment = "center"
    m.TextLabel = m.LayoutGroup.createChild("Label")
    m.TextLabel.width = m.dialogWidth - 10
    '     * (83.6/100)
    m.TextLabel.font = getFont(35, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    ' m.TextLabel.height = 70
    ' m.TextLabel.numLines = 3
    m.TextLabel.maxLines = 3
    m.TextLabel.horizAlign = "Center"
    m.TextLabel.vertAlign = "Center"

    m.contactNumber = m.LayoutGroup.createChild("Label")

    ' m.contactNumber.translation = [0, 0]
    ' m.contactNumber.maxLines = 3
    ' m.contactNumber.numLines = 3
    m.contactNumber.wrap = true
    m.contactNumber.width = m.dialogWidth
    m.contactNumber.height = m.dialogHeight * (35 / 100)
    '    m.contactNumber.translation = [0, 150]
    '    m.contactNumber.font = "font:SmallBoldSystemFont"
    m.contactNumber.horizAlign = "Center"
    m.contactNumber.vertAlign = "Center"
    m.contactNumber.font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.HelpButton = m.LayoutGroup.createChild("Button")
    m.HelpButton.observeField("buttonSelected", "closeDialogNext")

    '                 m.OkButton.translation= [466,429]
    m.HelpButton.minWidth = "280"
    m.HelpButton.maxWidth = "280"
    m.HelpButton.focusedTextColor = "#FFFFFF"
    m.HelpButton.height = "84"
    m.HelpButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.HelpButton.showFocusFootprint = "true"
    m.HelpButton.iconUri = ""
    m.HelpButton.focusedIconUri = ""
    m.ButtonLabelText = m.HelpButton.createChild("Label")
    m.ButtonLabelText.horizAlign = "center"
    m.ButtonLabelText.height = "84"
    m.ButtonLabelText.width = "280"
    m.ButtonLabelText.vertAlign = "center"
    m.ButtonLabelText.text = "OK"
    m.ButtonLabelText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.HelpButton.translation = [(m.dialogWidth - m.HelpButton.boundingRect().width) / 2, m.dialogHeight * (65 / 100)]
end sub

sub initClickListener()

end sub

sub initView()
    ' functionality of left side view
    boundRectOfBackgroundImg = m.personal_device_name_background_img.boundingRect()

    m.personal_device_name_text.width = (boundRectOfBackgroundImg.width * 0.65)
    m.personal_device_name_text.translation = [0, 0]
    m.personal_device_edit_text_rect.width = (boundRectOfBackgroundImg.width * 0.35)
    m.personal_device_edit_text_rect.translation = [0, 0]

    m.simple_heading_text.width = (boundRectOfBackgroundImg.width * 0.65) - 100
    m.simple_text.width = (boundRectOfBackgroundImg.width * 0.65) - 100

    m.main_layout_group.height = 500
    translateY = (boundRectOfBackgroundImg.height - 500) / 2
    translatex = 100

    m.main_layout_group.translation = [translatex, translateY]

    ' functinality of right side view.
    m.personal_device_text_rect.width = boundRectOfBackgroundImg.width * 0.35

    m.device_name_edit_text.width = (boundRectOfBackgroundImg.width * 0.35) - 200

    m.device_name_edit_text.translation = [0, (translateY + 100)]


    m.personal_device_name_button_next.width = 50
    m.personal_device_name_button_next.height = 90

    m.personal_device_edit_text_bottom_line.width = (boundRectOfBackgroundImg.width * 0.35) - 150
    m.personal_device_edit_text_bottom_line.height = 1

    m.KeyboardInput.observeField("text", "FieldText")

end sub

sub personal_device_name_update(deviceName as string, devicetype as string)
    m.personalDeviceName = CreateObject("roSGNode", "PersonalDeviceName")
    m.personalDeviceName.callType = "PUT"


    data = {}
    data["devicename"] = deviceName
    data["device_type"] = devicetype

    m.personalDeviceName.data = data
    m.personalDeviceName.control = "RUN"
    m.personalDeviceName.observeField("response", "personal_device_name_response")
end sub

sub personal_device_name_response(param as object)
    ? " personal Device name response param data"; param.getData()
    ? "persona device name response :" m.personalDeviceName.response.data
    m.personalDeviceName.response = param.getData()
    if m.personalDeviceName.response.response_code = 200
        ? " personal Device name response code"; m.personalDeviceName.response.response_code
        ?"getUserType(): "getUserType()
        m.userType = getUserType()
        m.LoginBusySpinner.visible = false
        setThePersonalDeviceName(m.personalDeviceNameText)
        if m.userType = "tieruser"
            m.screen = showRoomScreen()
            result = true
        else
            m.screen = showHomeScreen()
            result = true
        end if
        m.top.ExitView = true
    end if
end sub

function showRoomScreen() as object
    ? "showRoomScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    setUserHomeScreenMode("Room")
    setRoomSelectionFlag(false)
    RoomScreen = m.top.createChild("LoginScreen")
    return RoomScreen
end function


function showHomeScreen() as object
    ? "showHomeScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    HomeScreen = m.top.createChild("HomeScreen")
    HomeScreen.setFocus(true)
    return HomeScreen
end function

function onKeyEvent(key as string, press as boolean) as boolean
    ? "Login: key: "; key
    ? "Login: press: "; press
    if not press
        ' TC_Remember_needHelp_RegisterNow()
        if m.device_name_edit_text.hasFocus() and key = "down"
            m.device_name_edit_text.setFocus(false)
            m.personal_device_name_button_next.setFocus(true)
            m.KeyboardPoster.visible = false
            return true
            ' else if m.personal_device_name_button_next.hasFocus() and key = "up"
            '     m.device_name_edit_text.setFocus(true)
            '     m.KeyboardPoster.visible = true
            '     m.personal_device_name_button_next.setFocus(false)
            '     return true

        else if m.YesButton.hasFocus() and key = "left"
            m.YesButton.setFocus(false)
            m.NoButton.setFocus(true)
            return true
        else if m.NoButton.hasFocus() and key = "right"
            m.NoButton.setFocus(false)
            m.YesButton.setFocus(true)
            return true

        else if key = "OK"
            return on_ok_key_press()

        else if key = "up"
            return on_up_press()
        else if key = "down"
            return on_down_press()
        end if
    else if key = "back"
        ?"back testing"
        if m.KeyboardInput.hasFocus() or m.device_name_edit_text.hasFocus() or m.KeyboardPoster.visible = true
            ?"keybordClose :"
            keybordClose()
            m.device_name_edit_text.setFocus(false)
            m.device_name_edit_text.active = false

            m.KeyboardPoster.visible = false
            m.KeyboardInput.setFocus(false)
            m.personal_device_name_button_next.setFocus(true)
            return true
        else if (m.NoButton.hasFocus() or m.YesButton.hasFocus())

            ' m.ok.setFocus(false)
            m.NoButton.setFocus(false)
            m.YesButton.setFocus(false)
            m.logout.setFocus(true)

            showDialog("", false, true)
            return true
        end if
    else 
        
    
    end if
    return false
end function

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

function on_ok_key_press() as boolean
    if m.device_name_edit_text.hasFocus()
        m.device_name_edit_text.setFocus(false)
        m.device_name_edit_text.active = true
        m.KeyboardInput.textEditBox.text = m.device_name_edit_text.text
        m.KeyboardInput.textEditBox.cursorPosition = (m.device_name_edit_text.text).Len()
        m.device_name_edit_text.cursorPosition = (m.device_name_edit_text.text).Len()
        m.KeyboardPoster.visible = true
        m.KeyboardInput.setFocus(true)
    else if m.personal_device_name_button_next.hasFocus()
        m.LoginBusySpinner.visible = true
        ?"m.device_name_edit_text.text"m.device_name_edit_text.text
        if m.device_name_edit_text.text = ""
            m.needHelpText = "registerNow"
            m.LoginBusySpinner.visible = false
            showDialogNext("Error", "Please fill device name ", true, "", "")
            return true
        end if

        m.personalDeviceNameText = m.device_name_edit_text.text
        deviceType = getDeviceType()

        ?"getDevicetype: "getDeviceType()

        if deviceType = invalid
            deviceType = non - web
        end if
        if(m.personalDeviceNameText <> invalid and m.personalDeviceNameText <> "")
            personal_device_name_update(m.personalDeviceNameText, deviceType)

        end if
    else if m.logout.hasFocus()
        initDialogMrkupWithData()
        ' '         m.top.response = jsonResponse
        ' jsonResponse = APICall(getBaseUrl() + logoutUrl(), m.top.callType, m.top.data, m.top.parameters)
        ' ?"getProfile: jsonResponse: ";jsonResponse
        ' '   m.top.response = jsonResponse.jsonResponse
        ' responseAPiMethod(jsonResponse)
    else if m.YesButton.hasFocus()
        m.top.data = {}
        jsonResponse = APICall(getBaseUrl() + logoutUrl(), "POST", m.top.data, m.top.parameters)
        ?"getProfile: jsonResponse: ";jsonResponse
        '   m.top.response = jsonResponse.jsonResponse
        responseAPiMethod(jsonResponse)


    end if
    return true
end function

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

    m.ProfileTask.observeField("response", "responseAPI")
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

sub responseAPI(event as object)
    responseData = event.getData()
    ?"ProfileScreen::responseAPI: event: ";responseData

    m.LoginBusySpinner.visible = false
    if responseData.response_code = true

        ?"Profile::responseData.message:";responseData.message
        setAccountToken("")
        setThePersonalDeviceName("")
        setPersonaldeviceid("")
        setUserHomeScreenMode("Login")
        m.top.showLoginView = true
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

