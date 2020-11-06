
sub INIT()
    
    ? "login init()"

    m.SignInLabel = m.top.findNode("SignInLabel")
    ' m.SignInLabel.ali
    m.UsernamEditText = m.top.findNode("UsernamEditText")
    m.PasswordEditText = m.top.findNode("PasswordEditText")
    m.SignInButton = m.top.findNode("SignInButton")

    m.UsernamPoster = m.top.findNode("UsernamPoster")
    m.PasswordPoster = m.top.findNode("PasswordPoster")

    m.KeyboardPoster = m.top.findNode("KeyboardPoster")
    m.KeyboardInput = m.top.findNode("KeyboardInput")
    m.KeyboardInput.observeField("text", "FieldText")

    m.SignInButton.observeField("buttonSelected", "LoginValidation")
    m.KeyboardInput.textEditBox.observeField("cursorPosition", "cursorPosition")

    m.KeyboardPoster.visible = false
    m.KeyboardInput.setFocus(false)
    m.TextField = ""

    signInFont = getFont(44, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.SignInLabel.text = "Sign In"
    m.SignInLabel.font = signInFont

    m.UsernamLable = m.top.findNode("UsernamLable")
    m.PasswordLable = m.top.findNode("PasswordLable")

    m.UsernamLable.text = "Username"
    m.PasswordLable.text = "Password"

    UsernameFont = getFont(24, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.UsernamLable.font = UsernameFont
    m.PasswordLable.font = UsernameFont

    initSupportDialog()
    needHelp()
    rememberMe()
    dontHaveAccount()
    termCondition()
    termConditionUI()

    m.top.observeField("focusedChild", "OnFocusChildChange")

    m.setFocus = false

    initApi()

    ' loader
    m.LoaderBackground = m.top.createChild("Rectangle")
    m.LoaderBackground.color = "0x000000FF"
    m.LoaderBackground.visible = false
    m.LoaderBackground.opacity = "0.65"
    m.LoaderBackground.width = 1920
    m.LoaderBackground.height = 1080

    m.LoginBusySpinner = m.LoaderBackground.createChild("BusySpinner")
    m.LoginBusySpinner.observeField("visible", "showspinner")
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"

    centerx = (1920 - m.LoginBusySpinner.poster.bitmapWidth) / 2
    centery = (1080 - m.LoginBusySpinner.poster.bitmapHeight) / 2
    m.LoginBusySpinner.translation = [centerx, centery]
    m.needHelpText = ""


    m.global.RSG_analytics.trackEvent = {
        google: {
            ec: "login_screen",
            ea: "launch_complete"
        }
    }
end sub
sub termConditionUI()
    ? "termConditionUI";

    m.dialogHeight = 730
    m.dialogWidth = 1250

    m.termConditionbackgrd = m.top.createChild("Rectangle")
    m.termConditionbackgrd.color = "#000000"
    m.termConditionbackgrd.opacity = .95
    m.termConditionbackgrd.height = 1080
    m.termConditionbackgrd.width = 1920
    m.termConditionbackgrd.visible = false

    m.termCondition = m.termConditionbackgrd.createChild("Poster")

    m.termCondition.width = m.dialogWidth
    m.termCondition.height = m.dialogHeight
    m.termCondition.uri = "pkg:/images/Login/backGroundBox.png"
    m.termCondition.translation = [(1920 - m.dialogWidth) / 2, (1080 - m.dialogHeight) / 2]

    LayoutGroup = m.termCondition.createChild("LayoutGroup")
    LayoutGroup.translation = [40, 40]
    LayoutGroup.layoutDirection = "vert"
    '    LayoutGroup.itemSpacings = [20]
    m.termConditionTitle = LayoutGroup.createChild("Label")
    m.termConditionTitle.width = m.dialogWidth - 40
    m.termConditionTitle.text = "Term of uses"
    m.termConditionTitle.color = "#004E92"
    m.termConditionTitle.font = getFont(35, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.termConditionTitle.height = 50
    m.termConditionTitle.horizAlign = "left"
    m.termConditionTitle.vertAlign = "Center"

    m.termConditionScrollText = LayoutGroup.createChild("ScrollableText")

    '    m.termConditionScrollText.translation = [0, 0]
    m.termConditionScrollText.width = m.dialogWidth - 40
    m.termConditionScrollText.height = m.dialogHeight * (70 / 100)
    '    m.contactNumber.translation = [0, 150]
    '    m.contactNumber.font = "font:SmallBoldSystemFont"
    m.termConditionScrollText.horizAlign = "Left"
    m.termConditionScrollText.vertAlign = "top"
    m.termConditionScrollText.font = getFont(36, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
    m.termConditionScrollText.color = "#000000"
    m.termConditionDialogButton = m.termCondition.createChild("Button")
    m.termConditionDialogButton.observeField("buttonSelected", "closeDialog")

    '                 m.OkButton.translation= [466,429]
    m.termConditionDialogButton.minWidth = "260"
    m.termConditionDialogButton.maxWidth = "260"
    m.termConditionDialogButton.focusedTextColor = "#FFFFFF"
    m.termConditionDialogButton.height = "80"
    m.termConditionDialogButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.termConditionDialogButton.focusFootprintBitmapUri = "pkg:/images/Login/createAccountunFocus.png"
    m.termConditionDialogButton.showFocusFootprint = "true"
    m.termConditionDialogButton.iconUri = ""
    m.termConditionDialogButton.focusedIconUri = ""
    m.okLabel = m.termConditionDialogButton.createChild("Label")
    m.okLabel.horizAlign = "center"
    m.okLabel.height = "80"
    m.okLabel.width = "260"
    m.okLabel.vertAlign = "center"
    m.okLabel.text = "Accept"
    m.okLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.okLabel.color = "#000000"
    m.termConditionDialogButton.translation = [(m.dialogWidth - m.termConditionDialogButton.boundingRect().width) / 2, m.dialogHeight * (85 / 100)]
end sub

sub initApi()
    ? ""
    m.ProfileTask = CreateObject("roSGNode", "ProfileTask") ' Create XML Parsing task node
    '     ApiCallFor(callto,"GET")
    m.ProfileTask.callType = "GET"

    m.ProfileTask.observeField("response", "responseAPI")
end sub
sub responseAPI(event as object)
    responseData = event.getData()
    ? "ProfileScreen::responseAPI: event: "; responseData
    showspinner(false)
    if m.ProfileTask.callto = "termofuse"
        m.needHelpText = "termofuse"
        ? "termofuse:"; responseData
        m.termConditionbackgrd.visible = true
        m.termConditionScrollText.text = responseData.data

        ' m.termofUsesDialog = CreateObject("rosgnode","dialog")
        ' m.termofUsesDialog.appendChild(m.termCondition)
        ' m.top.getScene().dialog = m.termofUsesDialog
        m.termConditionScrollText.setFocus(true)
    else if m.ProfileTask.callto = "Support"
        m.SupportDialog.visible = true
        m.needHelpText = "helpSupport"
        m.TextLabel.text = responseData.message

        if responseData.data.email <> invalid
            m.contactNumber.text = responseData.data.email
            m.top.appendChild(m.SupportDialog)
            m.HelpButton.setFocus(true)
        end if
        m.HelpButton.setFocus(true)
    end if
end sub

sub needHelp()
    ? "needhelp:"

    m.needHelpButton = m.top.findNode("needHelpButton")
    m.needHelpButton.translation = [560, 455]
    m.needHelpButton.textFont = getFont(24, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.needHelpButton.focusedTextFont = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.needHelpButton.text = "Need help ?"

    '   m.needHelpButton.observeField("buttonSelected","")

    m.needHelpButton.minWidth = "220"
    m.needHelpButton.maxWidth = "220"
    m.needHelpButton.textColor = "#000000"
    m.needHelpButton.focusedTextColor = "#0db5ba"
    m.needHelpButton.height = "45"
    m.needHelpButton.focusBitmapUri = "pkg:/images/transparentimg.png"

    m.needHelpButton.iconUri = ""
    m.needHelpButton.focusedIconUri = ""

    m.needHelpButton.observeField("buttonSelected", "showSupportDialog")
end sub

sub showSupportDialog()
    ? "showSupportDialog"
    '      m.top.appendChild( m.LoaderBackground )
    m.ProfileTask.callto = "Support"
    m.ProfileTask.control = "RUN"
    showspinner(true)
    m.needHelpButton.setFocus(false)
end sub
sub termConditionApiCall()
    ? "termConditionApiCall"
    m.ProfileTask.callto = "termofuse"
    m.ProfileTask.control = "RUN"
    showspinner(true)
end sub

sub dontHaveAccount()
    
    m.dontHaveAccountLAbel = m.top.findNode("registerLabel")
    m.dontHaveAccountLAbel.text = "Don't have acount ?"
    m.dontHaveAccountLAbel.translation = [280, 660]
    m.dontHaveAccountLAbel.color = "#000000"
    m.dontHaveAccountLAbel.Font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.registerNow = m.dontHaveAccountLAbel.createChild("Button")
    m.registerNow.observeField("buttonSelected", "showDialogforCreateAccount")
    '    m.OkButton.setFocus(true)
    m.registerNow.translation = [260, -5]
    m.registerNow.minWidth = "200"
    m.registerNow.maxWidth = "200"
    m.registerNow.focusedTextColor = "#FFFFFF"
    m.registerNow.height = "50"
    m.registerNow.focusBitmapUri = "pkg:/images/Login/createAccountFocus1.png"
    m.registerNow.showFocusFootprint = "true"
    m.registerNow.iconUri = ""
    m.registerNow.focusedIconUri = ""
    m.registerNow.focusFootprintBitmapUri = "pkg:/images/Login/createAccountunFocus.png"
    '                 m.OkButton.horizAlign="center"
    okLabel = m.registerNow.createChild("Label")
    okLabel.horizAlign = "center"
    okLabel.height = "45"
    okLabel.color = "#004E92"
    okLabel.width = "200"
    okLabel.vertAlign = "center"
    okLabel.text = "Register now"
    okLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
end sub
sub termCondition()
    m.termConditionButton = m.top.findNode("termConditionButton")
    m.termConditionButton.observeField("buttonSelected", "termConditionApiCall")
    '    m.OkButton.setFocus(true)
    m.termConditionButton.translation = [127, 660]
    m.termConditionButton.minWidth = "130"
    m.termConditionButton.maxWidth = "130"
    m.termConditionButton.focusedTextColor = "#FFFFFF"
    m.termConditionButton.height = "50"
    m.termConditionButton.focusBitmapUri = "pkg:/images/Login/createAccountFocus1.png"
    m.termConditionButton.showFocusFootprint = "true"
    m.termConditionButton.iconUri = ""
    m.termConditionButton.focusedIconUri = ""
    m.termConditionButton.focusFootprintBitmapUri = "pkg:/images/Login/createAccountunFocus.png"
    '                 m.OkButton.horizAlign="center"
    termConditionButtonLabel = m.termConditionButton.createChild("Label")
    termConditionButtonLabel.horizAlign = "center"
    termConditionButtonLabel.height = "45"
    termConditionButtonLabel.color = "#004E92"
    termConditionButtonLabel.width = "130"
    termConditionButtonLabel.vertAlign = "center"
    termConditionButtonLabel.text = "T & C"
    termConditionButtonLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
end sub
sub showDialogforCreateAccount()
    '    m.top.appendChild(m.DialogNodePoster)
    m.needHelpText = "registerNow"

    signup = m.top.createChild("signup")
    signup.observeField("exit","closesignup")
    signup.setFocus(true)
    ' showDialog("Sign up for the best experience", "Please go to https://streamlytv.com for creating new account.", true, "", "")
    '    m.OkButton.setFocus(true)
end sub

sub closesignup(event as object)
  ?"closesignup"
    m.top.removeChild(event.getRoSgnode())
    m.registerNow.setFocus(true)

end sub



sub initSupportDialog()
    m.dialogHeight = 350
    m.dialogWidth = 1223

    m.SupportDialog = m.top.createChild("Poster")
    m.SupportDialog.visible = false
    m.SupportDialog.width = m.dialogWidth
    m.SupportDialog.height = m.dialogHeight
    m.SupportDialog.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialog.translation = [(1920 - m.dialogWidth) / 2, (1080 - m.dialogHeight) / 2]

    m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [0, 50]
    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [0]
    m.TextLabel = m.LayoutGroup.createChild("Label")
    m.TextLabel.width = m.dialogWidth
    '     * (83.6/100)
    m.TextLabel.font = getFont(35, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.TextLabel.height = 50
    m.TextLabel.horizAlign = "Center"
    m.TextLabel.vertAlign = "Center"

    m.contactNumber = m.LayoutGroup.createChild("Label")

    m.contactNumber.translation = [0, 0]
    m.contactNumber.width = m.dialogWidth
    m.contactNumber.height = m.dialogHeight * (25 / 100)
    '    m.contactNumber.translation = [0, 150]
    '    m.contactNumber.font = "font:SmallBoldSystemFont"
    m.contactNumber.horizAlign = "Center"
    m.contactNumber.vertAlign = "Center"
    m.contactNumber.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.HelpButton = m.SupportDialog.createChild("Button")
    m.HelpButton.observeField("buttonSelected", "closeDialog")

    '                 m.OkButton.translation= [466,429]
    m.HelpButton.minWidth = "280"
    m.HelpButton.maxWidth = "280"
    m.HelpButton.focusedTextColor = "#FFFFFF"
    m.HelpButton.height = "84"
    m.HelpButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.HelpButton.showFocusFootprint = "true"
    m.HelpButton.iconUri = ""
    m.HelpButton.focusedIconUri = ""
    okLabel = m.HelpButton.createChild("Label")
    okLabel.horizAlign = "center"
    okLabel.height = "84"
    okLabel.width = "280"
    okLabel.vertAlign = "center"
    okLabel.text = "OK"
    okLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.HelpButton.translation = [(m.dialogWidth - m.HelpButton.boundingRect().width) / 2, m.dialogHeight * (65 / 100)]
end sub

sub initTermDialog()
    m.dialogHeight = 750
    m.dialogWidth = 1223

    m.termDialog = m.top.createChild("Poster")
    m.termDialog.visible = false
    m.termDialog.width = m.dialogWidth
    m.termDialog.height = m.dialogHeight
    m.termDialog.uri = "pkg:/images/profile/dialogBackground.png"
    m.termDialog.translation = [(1920 - m.dialogWidth) / 2, (1080 - m.dialogHeight) / 2]

    m.LayoutGroup = m.termDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [0, 50]
    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [0]
    m.TextLabel = m.LayoutGroup.createChild("Label")
    m.TextLabel.width = m.dialogWidth
    '     * (83.6/100)
    m.TextLabel.font = getFont(35, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.TextLabel.height = 50
    m.TextLabel.horizAlign = "Center"
    m.TextLabel.vertAlign = "Center"

    m.termConditionText = m.LayoutGroup.createChild("ScrollableText")

    m.termConditionText.translation = [0, 0]
    m.termConditionText.width = m.dialogWidth
    m.termConditionText.height = m.dialogHeight * (25 / 100)
    '    m.contactNumber.translation = [0, 150]
    '    m.contactNumber.font = "font:SmallBoldSystemFont"
    m.termConditionText.horizAlign = "Center"
    m.termConditionText.vertAlign = "Center"
    m.termConditionText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.TermButton = m.termDialog.createChild("Button")
    m.TermButton.observeField("buttonSelected", "closeDialog")

    '                 m.OkButton.translation= [466,429]
    m.TermButton.minWidth = "280"
    m.TermButton.maxWidth = "280"
    m.TermButton.focusedTextColor = "#FFFFFF"
    m.TermButton.height = "84"
    m.TermButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.TermButton.showFocusFootprint = "true"
    m.TermButton.iconUri = ""
    m.TermButton.focusedIconUri = ""
    okLabel = m.TermButton.createChild("Label")
    okLabel.horizAlign = "center"
    okLabel.height = "84"
    okLabel.width = "280"
    okLabel.vertAlign = "center"
    okLabel.text = "OK"
    okLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.HelpButton.translation = [(m.dialogWidth - m.HelpButton.boundingRect().width) / 2, m.dialogHeight * (75 / 100)]
end sub

sub closeshowDialogforCreateAccoun()
    ? "closeshowDialogforCreateAccoun"
    m.top.removeChild(m.DialogNodePoster)
    m.OkButton.setFocus(false)
    m.registerNow.setFocus(true)
end sub

sub rememberMe()

    m.remenberCheckList = m.top.findNode("rememberCheckList")
    m.remenberCheckList.translation = [127, 455]
    m.remenberCheckList.itemSize = [300, 50]
    m.remenberCheckList.checkedIconUri = "pkg:/images/checked_icon_30.png"
    m.remenberCheckList.uncheckedIconUri = "pkg:/images/unchecked_icon_30.png"
    m.remenberCheckList.focusedCheckedIconUri = "pkg:/images/checked_icon_30.png"
    m.remenberCheckList.focusedUncheckedIconUri = "pkg:/images/unchecked_icon_30.png"
    m.remenberCheckList.focusBitmapUri = ""
    m.remenberCheckList.focusFootprintBitmapUri = "pkg:/images/transparentimg.png"
    m.remenberCheckList.color = "#000000"
    m.remenberCheckList.focusedColor = "#0db5ba"
    m.remenberCheckList.Font = getFont(24, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.remenberCheckList.focusedFont = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
    checklistNode = CreateObject("roSGNode", "ContentNode")
    contentNode = checklistNode.createChild("ContentNode")
    contentNode.Title = "Remember me"
    m.remenberCheckList.content = checklistNode
    m.remenberCheckList.ObserveField("checkedState", "remeberMeUpdate")

    if getUserName() <> invalid and getUserPassword() <> invalid and getUserName() <> "" and getUserPassword() <> ""
        m.rememberFlag = true

        m.remenberCheckList.checkedState = [m.rememberFlag]

        m.UsernamEditText.text = getUserName()
        m.PasswordEditText.text = getUserPassword()
    else
        m.rememberFlag = false
    end if

end sub

sub remeberMeUpdate(event as object)
    ? "remeberMeUpdate: "; event.getData()[0]
    m.rememberFlag = event.getData()[0]
end sub

' sub OnFocusChildChange()
'     ? "OnFocusChildChange"
'     if m.top.hasFocus() and (not m.UsernamEditText.hasFocus() and m.setFocus = false)
'         m.UsernamEditText.setFocus(true)
'         m.setFocus = true
'     end if
' end sub

sub LoginValidation()
    ? "LoginValidation: ";

    if m.UsernamEditText.text <> ""
        if IsValidEmail(m.UsernamEditText.text)
            if m.PasswordEditText.text <> ""
                if Len((m.PasswordEditText.text)) < 11
                    if m.rememberFlag then
                        setUserName(m.UsernamEditText.text)
                        setUserPassword(m.PasswordEditText.text)
                    else
                        setUserName("")
                        setUserPassword("")
                    end if

                    LoginProcess()
                else
                    showDialog("Error", "Password length should not grater than 10", true, "", "")
                end if
            else
                showDialog("Error", "Please fill the Password", true, "", "")
            end if
        else
            showDialog("Error", "Please Enter Valid email", true, "", "")
        end if
    else
        showDialog("Error", "Please fill the user name first", true, "", "")
    end if
end sub

sub LoginProcess()
    ? "LoginProcess: ";

    showspinner(true)

    m.LoginTask = CreateObject("roSGNode", "LoginTask")

    data = ""
    email = ""
    password = ""

    email = (m.UsernamEditText.text).Trim()
    password = (m.PasswordEditText.text).Trim()
    data = {}

    deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()

    data["email"] = email
    data["password"] = password
    data["deviceid"] = deviceInfo.GetChannelClientId()

    m.LoginTask.callType = "POST"
    '   data =  "email="+email+"&password="+password+deviceid
    ? "data: "; data
    m.LoginTask.data = data
    m.LoginTask.control = "RUN"
    m.LoginTask.observeField("response", "LoginResponse")
end sub

sub LoginResponse()
    ? "LoginResponse :m.LoginTask: "; m.LoginTask.response
    showspinner(false)
    m.loginsuccess = false
    if m.LoginTask.response.response_code = 200
        setAccountToken(m.LoginTask.response.access_token)
        m.loginsuccess = true
        buttons = []
        buttons.Push("OK")

        if m.LoginTask.response.termandcondition = true
            showdialogTermCondition(m.LoginTask.response.message, m.LoginTask.response.connected_device, false, buttons, "dialogButtonResponse")
        else
            showDialog(m.LoginTask.response.message, m.LoginTask.response.connected_device, false, buttons, "dialogButtonResponse")
        end if
    else
        buttons = []
        buttons.Push("OK")
        '        buttons.Push("Cancel")
        showDialog("Login Error", m.LoginTask.response.message, true, buttons, "")
    end if
end sub

sub dialogButtonResponse()
    ? "dialogButtonResponse: "m.dialog.buttonSelected
    if m.loginsuccess = true and m.dialog.buttonSelected = 0
        
        ' CloseScreen(m.top)
        ' ShowScreen(CreateObject("roSgNode","HomeScreen"))
        m.dialog.close = true
        m.top.visible = false
    end if
end sub

sub showspinner(event as object)
    ' ?"showspinner:event : ";event.getData()
    m.LoaderBackground.visible = event
    if event
        m.LoginBusySpinner.control = "start"
    else
        m.LoginBusySpinner.control = "stop"
    end if
end sub

sub showDialog(title as string, message as string, optionsDialog as boolean, buttons as object, onserveField as object)
    ? "showDialog: message: "; message
    m.HelpButton.setFocus(true)
    m.contactNumber.text = message
    m.TextLabel.text = title
    m.SupportDialog.visible = true
end sub

sub showdialogTermCondition(title as string, message as string, optionsDialog as boolean, buttons as object, onserveField as object)
    m.dialogHeight = 800
    m.dialogWidth = 1223

    ? "showDialog: message: "; message
    m.HelpButton.setFocus(true)
    m.contactNumber.text = message
    m.TextLabel.text = title
    m.SupportDialog.visible = true
end sub

sub closeDialog()
    ? "closeDialog true"
    ? "m.needHelpText:"; m.needHelpText
    if m.loginsuccess = true
        ' m.top.visible = false
        m.top.ExitView = true
    else if m.needHelpText = "helpSupport"
        m.SupportDialog.visible = false
        m.HelpButton.setFocus(false)
        m.needHelpButton.setFocus(true)
    else if m.needHelpText = "registerNow"
        m.SupportDialog.visible = false
        m.registerNow.setFocus(true)
    else if m.needHelpText = "termofuse"
        ? "termofuse:"; responseData
        m.okLabel.color = "#000000"
        m.termConditionbackgrd.visible = false
        m.termConditionButton.setFocus(true)
    else
        m.HelpButton.setFocus(false)
        m.SupportDialog.visible = false
        m.SignInButton.setFocus(true)
    end if
    m.needHelpText = ""

    ' m.dialog.close=true
end sub

sub cursorPosition(event as object)
    ? "cursorPosition: event: "; event.getData()
    if m.TextField = "UsernamEditText"
        m.UsernamEditText.cursorPosition = event.getData()
    else if m.TextField = "PasswordEditText"
        m.PasswordEditText.cursorPosition = event.getData()
    end if
end sub

sub FieldText(event as object)
    ? "event: "; event.getData()
    if m.TextField = "PasswordEditText"
        m.PasswordEditText.text = event.getData()
        m.PasswordEditText.active = true
        m.UsernamEditText.cursorPosition = m.KeyboardInput.textEditBox.cursorPosition
    else if m.TextField = "UsernamEditText"
        m.UsernamEditText.text = event.getData()
        m.UsernamEditText.active = true
        m.UsernamEditText.cursorPosition = m.KeyboardInput.textEditBox.cursorPosition
    end if
end sub

function onTextBoxActive(field as string) as void
    '  isActive =  event
    m.TextField = ""
    if field = "username"
        m.UsernamPoster.Uri = "pkg:/images/Login/borderof.png"
        m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
        m.TextField = "UsernamEditText"
    else if field = "password"
        m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
        m.PasswordPoster.Uri = "pkg:/images/Login/borderof.png"
        m.TextField = "PasswordEditText"
    else
        m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
        m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
    end if
end function

function IsValidEmail(email as string) as boolean
    regexEmail = "^[A-Za-z0-9_%+-]+(\.[A-Za-z0-9_%+-]+)*@([A-Za-z0-9-]+\.)+[A-Za-z]{2,6}$"
    return CreateObject("roRegex", regexEmail, "i").IsMatch(email)
end function

function onTextBoxActives(event as boolean) as void
    isActive = event

    if (isActive)
        m.PasswordPoster.Uri = "pkg:/images/Login/borderof.png"
    else
        m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
    end if
end function

function onKeyEvent(key as string, press as boolean) as boolean
    ? "Login: key: "; key
    ? "Login: press: "; press
    ? "m.UsernamEditText.hasFocus(): "; m.UsernamEditText.hasFocus()

    result = false
    if press

        ' TC_Remember_needHelp_RegisterNow()
        if m.needHelpButton.hasFocus() and key = "left"
            m.remenberCheckList.setFocus(true)
            return true

        else if m.remenberCheckList.hasFocus() and key = "right"
            m.needHelpButton.setFocus(true)
            return true

        else if (m.HelpButton.hasFocus() and key = "back")

            closeDialog()
            return true

        else if (m.termConditionDialogButton.hasFocus() and key = "up")
            m.okLabel.color = "#000000"
            m.termConditionScrollText.setFocus(true)
            return true


        else if (m.termConditionScrollText.hasFocus() and (key = "down" or key = "OK"))
            m.okLabel.color = "#ffffff"
            m.termConditionDialogButton.setFocus(true)
            return true

        else if ((m.termConditionButton.hasFocus() or m.termConditionScrollText.hasFocus()) and key = "back")
            closeDialog()
            return true

        else if ((m.termConditionDialogButton.hasFocus() or m.termConditionScrollText.hasFocus()) and key = "back")
            m.termConditionDialogButton.setFocus(true)
            return true

        else if (key = "left" and m.registerNow.hasFocus())
            m.termConditionButton.setFocus(true)
            return true

        else if (key = "right" and m.termConditionButton.hasFocus())
            m.registerNow.setFocus(true)
            return true

        else if key = "down"
            ? "Login: if: "
            return pressDown()
        else if key = "up"
            ? "Login: else: "
            return pressUp()

        else if (m.TextField = "UsernamEditText" or m.TextField = "PasswordEditText") and key ="back"
            return keybordClose()    

        else if (m.UsernamEditText.hasFocus() and key = "OK")

            ? "keyboard open for email field"


            m.UsernamEditText.setFocus(false)

            m.UsernamEditText.active = true

            onTextBoxActive("username")

            m.KeyboardInput.textEditBox.text = m.UsernamEditText.text

            m.KeyboardInput.textEditBox.cursorPosition = (m.UsernamEditText.text).Len()

            m.UsernamEditText.cursorPosition = (m.UsernamEditText.text).Len()

            m.KeyboardPoster.visible = true

            m.KeyboardInput.setFocus(true)

            return true

        else if (m.PasswordEditText.hasFocus() and key = "OK")

            ? "keyboard open for password field"
            m.TextField = "PasswordEditText"
            m.PasswordEditText.setFocus(false)
            onTextBoxActive("password")
            m.PasswordEditText.active = true
            m.KeyboardInput.textEditBox.text = m.PasswordEditText.text
            m.KeyboardInput.textEditBox.cursorPosition = (m.PasswordEditText.text).Len()
            m.PasswordEditText.cursorPosition = (m.PasswordEditText.text).Len()
            m.KeyboardPoster.visible = true
            m.KeyboardInput.setFocus(true)
            return true

        end if

    end if

    return result
end function

function pressDown() as boolean
    if m.SignInButton.hasFocus() then
        ? "termConditionButton"
        m.termConditionButton.setFocus(true)
        return true

    else if m.PasswordEditText.hasFocus()
        m.PasswordEditText.setFocus(false)
        m.remenberCheckList.setFocus(true)
        onTextBoxActive("")
        return true

    else if (m.remenberCheckList.hasFocus() or m.needHelpButton.hasFocus())
        ? "SignInButton: true"
        m.remenberCheckList.setFocus(false)
        m.needHelpButton.setFocus(false)
        m.SignInButton.setFocus(true)
        return true
    else if m.UsernamEditText.hasFocus()
        ? "PasswordEditText: true"
        m.UsernamEditText.setFocus(false)
        m.PasswordEditText.setFocus(true)
        onTextBoxActive("password")
        return true
    end if
end function

function pressUp() as boolean
    if m.termConditionButton.hasFocus()
        m.SignInButton.setFocus(true)
        return true
    else if m.registerNow.hasFocus() then
        m.registerNow.setFocus(false)
        m.SignInButton.setFocus(true)
    else if m.PasswordEditText.hasFocus()
        ? "UsernamEditText: true"

        m.PasswordEditText.setFocus(false)
        m.UsernamEditText.setFocus(true)
        onTextBoxActive("username")

        return true
    else if m.remenberCheckList.hasFocus()
        m.remenberCheckList.setFocus(false)
        m.PasswordEditText.setFocus(true)
        onTextBoxActive("password")
        return true
    else if m.SignInButton.hasFocus()
        ? "remenberCheckList: true"
        m.remenberCheckList.setFocus(true)
        m.SignInButton.setFocus(false)
        return true
    else if m.TextField = "UsernamEditText" or m.TextField = "PasswordEditText"
        return keybordClose()
    end if

end function

function keybordClose() as boolean
    

    ? "keyboard close"
    m.KeyboardPoster.visible = false
    m.KeyboardInput.setFocus(false)
    m.UsernamEditText.active = false
    m.PasswordEditText.active = false

    if m.TextField = "UsernamEditText"
        m.UsernamEditText.setFocus(true)
    else if m.TextField = "PasswordEditText"
        m.PasswordEditText.setFocus(true)
    end if

    return true
end function