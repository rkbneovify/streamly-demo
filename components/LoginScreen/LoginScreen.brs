
sub INIT()
    ' Front screen
    ' ? "login init()";getPersonaldeviceid()
    login_guide()
    privicyPolicy()

    m.image1 = m.top.findNode("image1")
    m.image2 = m.top.findNode("image2")
    m.image3 = m.top.findNode("image3")
    m.circ1 = m.top.findNode("circ1")
    m.circ2 = m.top.findNode("circ2")
    m.circ3 = m.top.findNode("circ3")
    m.circ1.uri = "pkg:/images/green.png"
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
    m.sign1 = m.top.findNode("sign1")
    m.sign_text = m.top.findNode("sign_text")
    m.dontshow = m.top.findNode("dontshow")
    m.dontshow.translation = [760, 790]
    m.dontshow.itemSize = [400, 100]
    m.dontshow.checkedIconUri = "pkg:/images/unchecke.png"
    m.dontshow.uncheckedIconUri = "pkg:/images/check1.png"
    m.dontshow.focusedCheckedIconUri = "pkg:/images/unchecke.png"
    m.dontshow.focusedUncheckedIconUri = "pkg:/images/check1.png"
    m.dontshow.focusBitmapUri = ""
    m.dontshow.focusFootprintBitmapUri = "pkg:/images/transparentimg.png"
    m.dontshow.color = "#ffffff"
    m.dontshow.focusedColor = "#ffffff"
    m.dontshow.Font = getFont(34, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.dontshow.focusedFont = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    checklistNode = CreateObject("roSGNode", "ContentNode")
    contentNode = checklistNode.createChild("ContentNode")
    contentNode.Title = "Don't show again"
    m.dontshow.content = checklistNode
    m.dontshow.ObserveField("checkedState", "checkUpdate")
    m.Privacy = m.top.findNode("Privacy")
    m.priv_text = m.top.findNode("priv_text")
    m.help_text = m.top.findNode("help_text")
    m.Privacy1 = m.top.findNode("privacy1")
    m.Help = m.top.findNode("Help")
    m.help1 = m.top.findNode("help1")
    m.Front_View = m.top.findNode("Front_View")
    m.LoginBackgroundImage = m.top.findNode("LoginBackgroundImage")
    m.logo = m.top.findNode("logo")
    m.time = 0
    initSupportDialog1()
    ' Help


    m.textchange = false
    m.timer = m.top.findNode("exampleTimer")
    m.timer.ObserveField("fire", "changetext")
    m.timer.control = "start"
    m.sign.observeField("buttonSelected", "showLoginScreen")
    m.top.signalBeacon("AppLaunchComplete")
    m.rememberFlag = false
    ' m.remenberCheckList.checkedState = false

    m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
    m.LoaderBackground = m.top.findNode("LoaderBackground")
    m.LoginBusySpinner.observeField("visible", "showspinner")
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    m.LoginBusySpinner.visible = false

    ' Privacy Policy

    m.Privacy.setFocus(true)
    m.Privacy.height = 114
    m.Privacy.width = 229
    m.Privacy1.height = 147
    m.Privacy1.width = 260
    m.priv_text.translation = [30, 30]
    m.Privacy.observeField("buttonSelected", "showPrivacy")
    m.Help.observeField("buttonSelected", "showSupportDialog1")
    ?"m.Privacy "m.Privacy.hasFocus()






    ' calculate the display size and set the Search edit text width according to screeen size



    deleteFront()

    di = CreateObject("roDeviceInfo")
    ?"di.GetDisplaySize()";di.GetDisplaySize().w

    m.screenWidth = di.GetDisplaySize().w



    ? "login init()"

    m.SignInLabel = m.top.findNode("SignInLabel")
    m.isSubscribe = true
    ' m.SignInLabel.ali
    m.UsernamEditText = m.top.findNode("UsernamEditText")
    m.UsernamEditText.setFocus(false)
    m.UsernamEditText.active = false
    m.PasswordEditText = m.top.findNode("PasswordEditText")
    m.PasswordShowPoster = m.top.findnode("PasswordShowPoster")
    m.PasswordShowPoster.visible = true
    m.SignInButton = m.top.findNode("SignInButton")
    m.SignInButtonLabel = m.SignInButton.createChild("Label")
    m.SignInButtonLabel.width = 654
    ' m.LoginUsingPassCodeLabel.height = 54
    m.SignInButtonLabel.translation = [0, 5]
    m.SignInButtonLabel.text = "Sign In"
    m.SignInButtonLabel.color = "#ffffff"
    m.SignInButtonLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.SignInButtonLabel.height = 50
    m.SignInButtonLabel.horizAlign = "Center"
    m.SignInButtonLabel.vertAlign = "Center"

    m.UsernamPoster = m.top.findNode("UsernamPoster")
    m.PasswordPoster = m.top.findNode("PasswordPoster")
    m.SearchBoxPoster = m.top.findNode("SearchBoxPoster")
    m.searchStart = m.top.findNode("searchStart")
    m.password_show = m.top.findNode("passoword_show")
    m.password_hide = m.top.findNode("password_hide")

    m.KeyboardPoster = m.top.findNode("KeyboardPoster")
    m.KeyboardInput = m.top.findNode("KeyboardInput")
    m.KeyboardInput.observeField("text", "FieldText")

    m.KeyboardPosterDevice = m.top.findNode("KeyboardPosterDevice")
    m.KeyboardInputDevice = m.top.findNode("KeyboardInputDevice")
    m.KeyboardInputDevice.observeField("text", "FieldText")

    m.registredDevicesMarkup = m.top.findNode("registredDevicesMarkup")
    m.registredDevicesMarkup.visible = false
    m.device_connected = m.top.findNode("device_connected")
    m.device_connected.visible = false
    m.deviceSearchEditText = m.top.findNode("deviceSearchEditText")


    ' m.ProfileCategory = m.top.findNode("ProfileCategory")
    ' marupContent = createObject("roSGNode", "ContentNode")
    ' data = marupContent.createChild("ContentNode")
    ' data.text = "Registered Devices"
    ' ' data2.id = "foo"
    ' data.SDPosterUrl = "pkg:/images/profile/devicesicon.png"
    ' ?"hello2"
    ' m.ProfileCategory.content = marupContent
    ' ' m.ProfileCategory.translation = [720,400]

    ' m.ProfileCategory.observeField("itemSelected", "initDialogMrkupWithData")
    '   m.LoginView.visible = false

    ' m.searchEditTextPoster = m.top.findNode("search_edit_text_poster")
    m.roomSearchEditText = m.top.findNode("roomSearchEditText")
    m.logout = m.top.findNode("logout")


    ' m.searchStart.focusfootprintBitmapUri ="pkg:/images/Search/mic_focus_icon.png"
    m.searchStart.uri = "pkg:/images/Search/mic.png"
    m.searchStart.translation = [540, 20]
    m.searchStart.visible = true
    m.searchStart.height = 60
    m.searchStart.width = 60

    m.password_hide.translation = [540, 20]
    m.password_hide.uri = "pkg:/images/password_hide.png"
    m.password_hide.height = 60
    m.password_hide.width = 60
    m.password_hide.visible = false

    m.password_show.translation = [540, 20]
    m.password_show.uri = "pkg:/images/password_show.png"
    m.password_show.height = 60
    m.password_show.width = 60
    m.password_show.visible = true

    m.SignInButton.observeField("buttonSelected", "LoginValidation")
    m.KeyboardInput.textEditBox.observeField("cursorPosition", "cursorPosition")

    m.KeyboardPoster.visible = false
    m.KeyboardInput.setFocus(false)
    m.TextField = ""

    signInFont = getFont(44, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.SignInLabel.text = "Sign In"
    m.SignInLabel.font = signInFont

    ' Specify RFI type when handling userData'


    m.PasswordEditText.secureMode = "true"



    loginWithPassCodeLabelSet()
    EnterPassCodeLableSet()
    enterPasscodeEditText()
    signInPassCodeButton()
    RoomListUI()
    m.LoginView = m.top.findNode("LoginView")
    m.LoginWithPassCodeView = m.top.findNode("LoginWithPassCodeView")
    m.mainLoginView = m.top.findNode("LoginView")
    m.mainBarcodeView = m.top.findNode("barCodeView")
    ' m.LoginView.visible = false
    m.showRoomView = false

    m.UsernamLable = m.top.findNode("UsernamLable")
    m.PasswordLable = m.top.findNode("PasswordLable")

    m.UsernamLable.text = "Username"
    m.PasswordLable.text = "Password"

    UsernameFont = getFont(24, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.UsernamLable.font = UsernameFont
    m.PasswordLable.font = UsernameFont
    m.launchScreenMode = getUserHomeScreenMode()
    m.roomSelectFlag = getRoomSelectionFlag()
    loginWithPassCode()
    loginWithQrCode()
    initSupportDialog()
    needHelp()
    rememberMe()
    dontHaveAccount()
    termCondition()
    termConditionUI()

    m.logoutPassCode = false

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
    m.LoginBusySpinner.translation = [900, centery]
    m.needHelpText = ""
    m.top.signalBeacon("AppDialogInitiate")

    ' m.global.RSG_analytics.trackEvent = {
    '     google: {
    '         ec: "login_screen",
    '         ea: "launch_complete"
    '     }
    ' }
    m.checkForBArcodeContinue = false



    if m.launchScreenMode <> invalid and m.roomSelectFlag <> invalid
        if m.roomSelectFlag = "false"
            setHomeScreenAccordingMode()
        end if
    end if


    ' set the width of search edit text
    ' m.searchEditTextPoster.width = m.screenWidth - 200
    m.roomSearchEditText.width = 1720
    m.roomSearchEditText.visible = false
    m.logout.visible = false

    m.logoutText = m.top.findNode("logoutText")
    m.logoutView = m.top.findNode("logoutView")
    m.NoButton = m.top.findNode("NoButton")
    m.YesButton = m.top.findNode("YesButton")
    m.YesButton.observeField("buttonSelected", "logoutApiCall")
    m.NoButton.observeField("buttonSelected", "closeDialogLogout")

    m.deviceText = m.top.findNode("deviceText")
    m.deviceView = m.top.findNode("deviceView")
    m.Nodevice = m.top.findNode("Nodevice")
    m.Yesdevice = m.top.findNode("Yesdevice")
    m.profileView = m.top.findNode("profileView")
    m.Yesdevice.observeField("buttonSelected", "deviceScreen")
    m.Nodevice.observeField("buttonSelected", "closeDialogDevice")
    m.deviceSearchEditText = m.top.findNode("deviceSearchEditText")
    m.removeText = m.top.findNode("removeText")
    m.removeView = m.top.findNode("removeView")
    m.Noremove = m.top.findNode("Noremove")
    m.Yesremove = m.top.findNode("Yesremove")
    m.Noremove.observeField("buttonSelected", "closeDialogRemove")


    m.qrlogintask = CreateObject("roSGNode", "qrlogintask")



    m.qrlogintask.callType = "GET"
    '   data =  "email="+email+"&password="+password+deviceid
    ? "data: "; data
    ' m.qrlogintask.data = data
    m.qrlogintask.control = "RUN"
    m.qrlogintask.observeField("response", "qrLoginResponse")


    ' m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
    ' m.LoaderBackground = m.top.findNode("LoaderBackground")
    ' m.LoginBusySpinner.observeField("visible", "showspinner")
    ' m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    ' m.LoginBusySpinner.visible = false
    ' m.logout = false
    m.ErrorDialogView = m.top.findNode("ErrorDialogView")
    m.buttonClick = m.top.findNode("buttonClick")
    m.buttonClick.horizAlign = "center"
    m.buttonClick.vertAlign = "center"

    ' if RegRead("personalDevice")
    '     ' Open a Room screen for selection
    '     GetRoomsmAPicall()
    '     m.SupportDialog.visible = false
    '     m.roomSearchEditText.visible = true
    '     ' m.searchEditTextPoster.visible = true
    '     m.RoomListMarkuGrid.visible = true
    '     m.RoomListMarkuGrid.setFocus(false)
    '     m.logout.visible = true
    '     m.LoginView.visible = false
    '     m.LoginWithPassCodeView.visible = false
    '     RegDelete("personalDevice")
    ' end if


    ?"getGuideMode() "getGuideMode()
    ?"getCheckMode() "getCheckMode()
    if getHelpMode() = "true"
        deleteHelpMode()

        m.sign.setFocus(true)
        m.UsernamEditText.setFocus(false)
        m.UsernamEditText.active = false
        m.Front_View.visible = true
        m.LoginView.visible = false
        m.LoginBackgroundImage.visible = false
        m.logo.visible = false
        ' showLoginScreen()
    else if getCheckMode() = "true"
        ' deleteCheckMode()
        m.sign.setFocus(false)
        m.Privacy.setFocus(false)
        m.UsernamEditText.setFocus(true)
        m.UsernamEditText.active = true
        m.Front_View.visible = false
        m.LoginView.visible = true
        m.LoginBackgroundImage.visible = true
        m.logo.visible = true

        ' showLoginScreen()

    end if
    ' if getGuideMode() = "true"
    '     ' ?"m.remenberCheckList.checkedState "m.remenberCheckList.checkedState
    '

    ' end if



    m.top.signalBeacon("AppLaunchComplete")
end sub

' Frontscreen
sub login_guide()
    m.personalDeviceName = CreateObject("roSGNode", "FrontApi")
    m.personalDeviceName.callType = "POST"
    m.personalDeviceName.control = "RUN"
    m.personalDeviceName.observeField("response", "loginguide_response")
end sub

sub loginguide_response(param as object)
    ? " personal Device name response param data"; param.getData().slide_interval_seconds
    m.time = param.getData().slide_interval_seconds
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

sub initSupportDialog1()

    m.SupportDialog1 = createObject("roSGNode", "Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialog1.width = 1320
    m.SupportDialog1.height = 750
    m.SupportDialog1.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialog1.translation = [(1920 - m.SupportDialog1.boundingRect().width) / 2, (1080 - m.SupportDialog1.boundingRect().height) / 2]
    m.SupportDialog1.blendColor = "#f5f5f5"


    m.SupportDialogCross = m.SupportDialog1.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogCross.width = 80
    m.SupportDialogCross.height = 60
    m.SupportDialogCross.uri = "pkg:/images/cross_b.png"
    m.SupportDialogCross.translation = [1180, 30]

    m.SupportDialogCircle = m.SupportDialog1.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogCircle.width = 520
    m.SupportDialogCircle.height = 320
    m.SupportDialogCircle.uri = "pkg:/images/supports.png"
    m.SupportDialogCircle.translation = [380, 30]

    ' m.SupportDialogSupport = m.SupportDialog.createChild("Poster")
    ' '     m.SupportDialog.visible = false
    ' m.SupportDialogSupport.width = 280
    ' m.SupportDialogSupport.height = 230
    ' m.SupportDialogSupport.uri = "pkg:/images/profile/support.png"
    ' m.SupportDialogSupport.translation = [830, 200]

    m.SupportDialogEmail = m.SupportDialog1.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogEmail.width = 380
    m.SupportDialogEmail.height = 160
    m.SupportDialogEmail.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialogEmail.blendColor = "#b0c4de"
    m.SupportDialogEmail.translation = [220, 550]



    m.SupportDialogMail = m.SupportDialog1.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogMail.width = 50
    m.SupportDialogMail.height = 60
    m.SupportDialogMail.uri = "pkg:/images/mail_b.png"

    m.SupportDialogMail.translation = [380, 565]

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

    m.SupportDialogNumber = m.SupportDialog1.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogNumber.width = 380
    m.SupportDialogNumber.height = 160
    m.SupportDialogNumber.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialogNumber.blendColor = "#b0c4de"
    m.SupportDialogNumber.translation = [710, 550]



    m.SupportDialogPhone = m.SupportDialog1.createChild("Poster")
    '     m.SupportDialog.visible = false
    m.SupportDialogPhone.width = 50
    m.SupportDialogPhone.height = 50
    m.SupportDialogPhone.uri = "pkg:/images/mobile_b.png"

    m.SupportDialogPhone.translation = [875, 575]

    ' m.SupportDialogNumber = m.SupportDialog.createChild("Poster")
    ' '     m.SupportDialog.visible = false
    ' m.SupportDialogNumber.width = 320
    ' m.SupportDialogNumber.height = 250
    ' m.SupportDialogNumber.uri = "pkg:/images/profile/dialogBackground.png"
    ' m.SupportDialogNumber.translation = [1000 , 600]

    m.LayoutGroup = m.SupportDialog1.createChild("LayoutGroup")
    m.LayoutGroup.translation = [100, 400]
    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [10]

    m.LayoutGroup1 = m.SupportDialog1.createChild("LayoutGroup")
    m.LayoutGroup1.translation = [30, 460]
    m.LayoutGroup1.layoutDirection = "vert"
    m.LayoutGroup1.itemSpacings = [10]

    m.LayoutGroupEmail = m.SupportDialog1.createChild("LayoutGroup")
    m.LayoutGroupEmail.translation = [-60, 620]
    m.LayoutGroupEmail.layoutDirection = "vert"

    m.LayoutGroupEmail.itemSpacings = [10]

    m.LayoutGroupNumber = m.SupportDialog1.createChild("LayoutGroup")
    m.LayoutGroupNumber.translation = [330, 620]
    m.LayoutGroupNumber.layoutDirection = "vert"
    m.LayoutGroupNumber.itemSpacings = [10]

    '     m.LayoutGroup .horizAlignment ="vert"
    '      m.LayoutGroup .vertAlignment ="center"
    m.TextLabel2 = m.LayoutGroup.createChild("Label")
    '     m.TextLabel.translation = [0, 87]
    m.TextLabel2.width = 2023
    m.TextLabel2.height = 0
    ' m.TextLabel.horizAlign = "Center"
    ' m.TextLabel.vertAlign = "Center"
    m.TextLabel2.text = "For Help and Support You can mail us on below Contact."
    m.TextLabel2.color = "#004E92"
    m.TextLabel2.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.TextLabel1 = m.LayoutGroup1.createChild("Label")
    '     m.TextLabel.translation = [0, 87]
    m.TextLabel1.width = 2023
    m.TextLabel1.height = 0
    ' m.TextLabel.horizAlign = "Center"
    ' m.TextLabel.vertAlign = "Center"
    m.TextLabel1.text = "For Help and Support You can mail us on below Contact."
    m.TextLabel1.color = "#004E92"
    m.TextLabel1.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")



    m.contactNumber1 = m.LayoutGroupEmail.createChild("ScrollableText")
    m.contactNumber1.width = 1020
    m.contactNumber1.height = 100
    m.contactNumber1.color = "#004E92"

    ' m.contactNumber.translation = [0, 350]
    m.contactNumber1.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.contactNumber1.horizAlign = "Center"
    m.contactNumber1.vertAlign = "Center"



    m.contactNumbers = m.LayoutGroupNumber.createChild("ScrollableText")
    m.contactNumbers.width = 1220
    m.contactNumbers.height = 100
    m.contactNumbers.color = "#004E92"

    ' m.contactNumber.translation = [0, 350]
    m.contactNumbers.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
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


sub showSupportDialog1()

    showspinner(true)
    ? "showSupportDialog"
    '      m.top.appendChild( m.LoaderBackground )
    ApiCallFor1("Support", "GET")
    ' showspinner(true)
    ' m.logout.setFocus(false)
end sub

sub showPrivacy()
    showspinner(true)
    ?"m.loginview "m.LoginView.visible
    ?"privacy"
    ApiCallFor1("Privacy & Policy", "GET")
end sub

sub ApiCallFor1(callto as string, callType as string)
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

' function errorDialogShow(event as object)
'     if event.getData().responseCode = 401 or event.getData().responseCode = 429 or event.getData().responseCode = 404
'         setAccountToken("")
'         setThePersonalDeviceName("")
'         setPersonaldeviceid("")
'     end if
' end function

sub responseAPIPrivacy(event as object)
    m.responseData = event.getData()
    ?"ProfileScreen::responseAPI: event: ";m.responseData.data
    ?"getHomeTimezone() "getHomeTimezone()
    showspinner(false)
    ' m.LoginBusySpinner.visible = false
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
                marupContent.createChild("ContentNode").addFields({ "Current Timezone :": "Your Current Timezone GMT " + getCurrentTimezone() })
            end if


            m.dialogOptions.itemSize = [900, 52]

            '         marupContent.createChild("ContentNode").addFields(assoArray)
            m.dialogOptions.visible = true
            m.dialogOptions.content = marupContent
            ' showDialog("Account", true, false)
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
            '   showDialog("Registered Devices", true, false)

        else if m.ProfileTask.callto = "Support"
            m.SupportDialog1.visible = true
            ' m.Front_View.visible = false
            ' RegWrite("back", "back")
            ?"Profile::responseData.message:";m.responseData.data
            ?"Profile::responseData.message:";m.responseData.message
            '            "message": "For help and support call the numbers below"

            m.TextLabel2.text = "Our team is available 24*7. We'll do our best to respond to your inquiries as soon as possible."
            m.TextLabel1.text = "Need help or have a question? Our support team is here to assist you. You can reach us at the following:"

            '         contactText = ""
            '         for each contacNo in responseData.data
            '              contactText = contactText +contacNo+chr(10)
            '         end for

            if m.responseData.data.email <> invalid
                m.contactNumber1.text = "contact1@streamlytv.com"
                m.contactNumbers.text = m.responseData.data.num1
                m.top.appendChild(m.SupportDialog1)
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
        m.circ2.uri = "pkg:/images/green.png"
        ' ?"m.image2.visible "m.image2.visible
        m.timer.control = "start"
        m.timer.duration = m.time
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
        m.circ3.uri = "pkg:/images/green.png"

        m.timer.control = "start"
        m.timer.duration = m.time
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
        m.circ1.uri = "pkg:/images/green.png"
        m.circ2.uri = "pkg:/images/circ.png"
        m.circ3.uri = "pkg:/images/circ.png"

        m.timer.control = "start"
        m.timer.duration = m.time
    end if
end sub

function showLoginScreen() as object
    ? "LoginScreen"
    if m.remenberCheckList <> invalid
        if m.remenberCheckList.checkedState[0] = true

            setGuideMode("true")

        else if m.remenberCheckList.checkedState[0] = false

            setGuideMode("false")

        end if

        if getDataMode() <> ""
            deleteDataMode()
            ApiCallFor("Sign out", "POST")
            m.sign.setFocus(false)
            m.UsernamEditText.setFocus(true)
            m.UsernamEditText.active = true
            m.Front_View.visible = false
            m.LoginView.visible = true
            m.LoginBackgroundImage.visible = true
            m.logo.visible = true

            if m.roomSelectFlag <> "false"
                ' m.rfiType = "None"
                m.store = CreateObject("roSGNode", "ChannelStore")
                m.store.ObserveField("userData", "onGetUserData")

                m.rfiType = "signin"
                ' Set sign-in context for RFI screen
                info = CreateObject("roSGNode", "ContentNode")
                info.addFields({ context: "signin" })
                m.store.requestedUserDataInfo = info

                m.store.requestedUserData = "email"
                m.store.command = "getUserData"
            end if
        else
            m.sign.setFocus(false)
            m.UsernamEditText.setFocus(true)
            m.UsernamEditText.active = true
            m.Front_View.visible = false
            m.LoginView.visible = true
            m.LoginBackgroundImage.visible = true
            m.logo.visible = true

            if m.roomSelectFlag <> "false"
                ' m.rfiType = "None"
                m.store = CreateObject("roSGNode", "ChannelStore")
                m.store.ObserveField("userData", "onGetUserData")

                m.rfiType = "signin"
                ' Set sign-in context for RFI screen
                info = CreateObject("roSGNode", "ContentNode")
                info.addFields({ context: "signin" })
                m.store.requestedUserDataInfo = info

                m.store.requestedUserData = "email"
                m.store.command = "getUserData"
            end if
        end if
    end if
end function

sub reqApiSession()
    m.ChannelEpisodeTask = CreateObject("roSGNode", "ChannelEpisodeTask")
    ' m.ChannelEpisodeTask.observeField("CategoriesList", "CategoryResponseData")
    m.ChannelEpisodeTask.observeField("errorMessage", "errorDialogSession")
    m.ChannelEpisodeTask.control = "RUN"
end sub

function errorDialogSession(event as object)
    ?"errorDialogShow: ";event.getData()

    if event.getData().responseCode = 401
        ' Session message
        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "closeSession")
        m.DialogButton.setFocus(true)
    else
        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "closeSession")
        m.DialogButton.setFocus(true)

    end if

end function

sub closeSession()
    ?"close for -> login screen ";
    ' m.logout = false
    m.buttonClick.setFocus(false)
    m.ErrorDialogView.visible = false

    m.SupportDialog.visible = false
    m.RoomListMarkuGrid.visible = false
    m.roomSearchEditText.visible = false
    m.logout.visible = false
    m.RoomListMarkuGrid.setFocus(false)
    m.LoginView.visible = true
    m.LoginWithPassCodeView.visible = false
    m.mainBarcodeView.visible = false
    setRoomSelectionFlag(false)
    m.UsernamEditText.text = ""
    m.PasswordEditText.text = ""


    m.UsernamEditText.setFocus(false)
    m.UsernamEditText.active = true
    onTextBoxActive("username")
    m.KeyboardInput.textEditBox.text = m.UsernamEditText.text
    m.KeyboardInput.textEditBox.cursorPosition = (m.UsernamEditText.text).Len()
    m.UsernamEditText.cursorPosition = (m.UsernamEditText.text).Len()
    m.KeyboardPoster.visible = true
    m.KeyboardInput.setFocus(true)
    setUserHomeScreenMode("Login")

end sub


sub qrLoginResponse()
    ' ? "LoginResponse :m.qrLoginTask: "; m.qrlogintask.response.visible
    ' if m.qrlogintask.response.visible = false
    '     ?"hello"
    '     m.LoginUsingPassCodeButton.visible = true
    '     m.qrLoginBtn.visible = true
    '     m.termConditionButton.visible = true
    ' else
    '     m.LoginUsingPassCodeButton.visible = false
    '     m.qrLoginBtn.visible = false
    '     m.termConditionButton.visible = false
    ' end if
end sub

' This is the function that handles the RFI result'
function onGetUserData()
    if m.rfiType = "signin"
        if (m.store.userData <> invalid)
            email = m.store.userData.email
            ? "email of user is: " email
        else
            ? "[user cancelled obtaining email, returning to displaying channel UI]"
            email = ""
        end if
        displaySignInScreen(email)
    end if
    m.rfiType = "None"
end function

function displaySignInScreen(email as string)
    print "Sign in screen - email= "; email

    if email <> ""
        m.UsernamEditText.text = email
        m.UsernamEditText.setFocus(false)
        m.PasswordEditText.setFocus(true)
        onTextBoxActive("password")
    end if
    'signinScreen.findNode("signinKeyboard").setFocus(true)
end function



sub signInPassCodeButton()
    m.SignInWithPasscodeButton = m.top.findNode("SignInWithPasscodeButton")
    m.SignInWithPasscodeButton.observeField("buttonSelected", "PassCodeValidation")


end sub

sub setHomeScreenAccordingMode()
    if m.launchScreenMode = "Login"
        '  Open Login screen here
        ' m.showRoomView = true
        m.logout.visible = false
        m.LoginView.visible = true
        m.LoginWithPassCodeView.visible = false

    else if m.launchScreenMode = "Room"

        if m.roomSelectFlag = "false"
            reqApiSession()
            ' Open a Room screen for selection
            GetRoomsmAPicall()
            m.SupportDialog.visible = false
            m.roomSearchEditText.visible = true
            ' m.searchEditTextPoster.visible = true
            m.RoomListMarkuGrid.visible = true
            m.RoomListMarkuGrid.setFocus(true)
            m.logout.visible = true
            m.LoginView.visible = false
            m.LoginWithPassCodeView.visible = false
            return
        end if
    end if
end sub

sub PassCodeValidation()
    ?"Passcode validation"
    ' m.EnterPasscodeEditText.text = "wP5JzL174M"
    if m.EnterPasscodeEditText.text <> ""
        loginWithPassCodeAPicall()
    else
        showDialog("Error", "Please fill the Pass code first", true, "", "")
        m.needHelpText = "passcodeUI"

    end if
end sub

sub loginWithPassCodeAPicall()
    ?"loginWithPassCodeAPicall"
    showspinner(true)
    m.LoginPasscodeTask = CreateObject("roSGNode", "LoginPasscodeTask")

    passcode = (m.EnterPasscodeEditText.text).Trim()
    data = {}

    deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()

    data["passcode"] = passcode
    data["deviceid"] = deviceInfo.GetChannelClientId()

    m.LoginPasscodeTask.callType = "POST"
    '   data =  "email="+email+"&password="+password+deviceid
    ? "data: "; data
    m.LoginPasscodeTask.data = data
    m.LoginPasscodeTask.control = "RUN"
    m.LoginPasscodeTask.observeField("response", "LoginPasscodeResponse")
end sub

sub logOutPassCodeAPicall()
    ?"logOutPassCodeAPicall"
    showspinner(true)

    m.LogoutPassCodeTask = CreateObject("roSGNode", "LogoutPassCode")

    ' passcode = (m.EnterPasscodeEditText.text).Trim()
    data = {}

    ' deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()

    data["id"] = m.LogoutCode
    ' data["deviceid"] = deviceInfo.GetChannelClientId()

    m.LogoutPassCodeTask.callType = "POST"
    '   data =  "email="+email+"&password="+password+deviceid
    ? "data: "; data
    m.LogoutPassCodeTask.data = data
    m.LogoutPassCodeTask.control = "RUN"
    m.LogoutPassCodeTask.observeField("response", "LogOutPasscodeResponse")
end sub

sub LogOutPasscodeResponse(response as object)
    ?"LogOutPasscodeResponse;"response.getData()
    responseData = response.getData()
    showspinner(false)

    if responseData <> invalid and responseData.response_code = 200
        ' m.needHelpText = "passcodeUI"
        ' m.logoutPassCode = true
        ' closeDialog()
        if(m.EnterPasscodeEditText.text <> "")
            loginWithPassCodeAPicall()
        else if(m.UsernamEditText.text <> "")
            LoginValidation()
        else if(m.qrCodeImageKey <> "")
            qr_login()
        end if
        ' showDialog("Success", responseData.message, true, "", "")

    end if


    m.logoutPassCode = false
end sub




sub LoginPasscodeResponse(params as object)
    responseData = params.getData()
    ?"LoginPasscodeResponse:"; responseData
    showspinner(false)
    m.isSubscribe = true
    if responseData <> invalid and responseData.response_code = 404
        showDialog("Error", responseData.message, true, "", "")
        m.needHelpText = "passcodeUI"
    else if responseData <> invalid and responseData.response_code = 401
        showDialog("Error", responseData.message, true, "", "")
        setLogin("login")
        m.needHelpText = "passcodeUI"
        m.isSubscribe = false
    else if responseData <> invalid and responseData.response_code = 429 and responseData.data = invalid
        ' m.ButtonLabelText.text = "Logout"
        ' m.LogoutCode = responseData.data
        m.needHelpText = "passcodeUI"
        m.logoutPassCode = false
        showDialog("Error", responseData.message, true, "", "")
    else if responseData <> invalid and responseData.response_code = 429 and responseData.data <> invalid
        ' m.ButtonLabelText.text = "Logout"
        m.LogoutCode = responseData.data
        m.needHelpText = "passcodeUI"
        m.logoutPassCode = true
        closeDialog()
        ' showDialog("Error", "responseData.message", true, "", "")
        ' showDialog("Error", "Already logged in with this device, press 'OK' for logout and login again", true, "", "")
    else if responseData <> invalid and responseData.response_code = 479
        setDeviceMode("device")
        showspinner(false)
        m.Nodevice.setFocus(true)
        showDialogDevice("LoginError", true, false, responseData.message)
        ConnectedDevices0 = responseData.loggedindeviceinfolist.numofdevice
        ConnectedDevices = responseData.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";responseData.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ?"data: "data
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            marupContent.createChild("ContentNode").addFields(data)
        end for

    else if responseData <> invalid and responseData.response_code = 200
        ' showDialog("Success", responseData.message, true, "", "")
        ? "subscribed :"responseData.subscribed
        if responseData.subscribed = true
            setAccountToken(responseData.access_token)
            setPersonaldeviceid(responseData.personaldeviceid)
            setDeviceType(responseData.device_type)
            setUserType(responseData.userrole)
            setPersonaldevicename_popup(responseData.personaldevicename_popup)
            setHomeTimezone(responseData.timezone)
            m.needHelpText = "passcodeUI"
            if responseData.personaldevicename_popup = true
                ' ?"m.LoginTask.response.personaldevicename_popup: "m.LoginTask.response.personaldevicename_popup
                ' show personal device name
                RegWrite("personalDevice", "personalDevice")
                setPersonaldevicename_popup(true)
                setUserHomeScreenMode("personalDeviceName")
                setUserPersonalScreenMode("popup")
                m.top.showPersonalDeviceName = true
            else if responseData.userrole = "tieruser"
                ?"getDeviceMode()"getDeviceMode()
                if getDeviceMode() = "device"
                    setUserHomeScreenMode("Home")
                    m.loginsuccess = true
                    buttons = []
                    buttons.Push("OK")
                    m.top.signalBeacon("AppDialogComplete")
                else
                    setUserHomeScreenMode("Room")
                    setRoomSelectionFlag(false)
                    m.roomContent = createObject("roSGNode", "ContentNode")
                    if responseData.rooms[0] <> invalid
                        ' ?"responseData.rooms[0]:";m.LoginTask.response.rooms[0]
                        ' stringid = (m.LoginTask.response.rooms[0].user_id).toStr()
                        ' setUserCredential(stringid)
                        ?"responseData.rooms[0].user:";responseData.rooms[0].user_id
                        for each item in responseData.rooms

                            roomDetail = m.roomContent.createChild("ContentNode")
                            ' itemKey = {}
                            roomDetail.title = item.hotel_room
                            roomDetail.id = item.id
                            roomDetail.HDSMALLICONURL = "pkg:/images/profile/room.png"
                            ' roomDetail.addFields(itemKey)
                        end for
                        m.needHelpText = "roomSelectUI"
                        m.RoomListMarkuGrid.content = m.roomContent
                        m.showRoomView = true
                        m.loginsuccess = false
                        closeDialog()
                        ' showDialog(m.LoginTask.response.message, "Please select a room to start streaming", false, "", "")
                        return
                    else
                        setUserHomeScreenMode("Home")
                        m.loginsuccess = true
                        buttons = []
                        buttons.Push("OK")
                        m.top.signalBeacon("AppDialogComplete")
                    end if
                end if

            else
                setUserHomeScreenMode("Room")
                m.loginsuccess = true
                buttons = []
                buttons.Push("OK")
                m.top.signalBeacon("AppDialogComplete")
            end if

            if responseData.termandcondition = true
                showdialogTermCondition(responseData.message, responseData.connected_device, false, "", "dialogButtonResponse")
            else
                closeDialog()
                ' showDialog(responseData.message, responseData.connected_device, false, "", "dialogButtonResponse")
            end if
        else
            setUserHomeScreenMode("Login")
            m.needHelpText = "passcodeUI"
            m.logoutPassCode = true
            m.isSubscribe = false
            showDialog("Subscription Error", responseData.subscribedmsgis, true, "", "")
        end if
    end if
end sub

sub enterPasscodeEditText()
    m.EnterPasscodeEditText = m.top.findNode("EnterPasscodeEditText")
    m.EnterPasscodePoster = m.top.findNode("EnterPasscodePoster")
end sub

sub EnterPassCodeLableSet()
    m.EnterPasscodeLabel = m.top.findNode("EnterPasscodeLabel")
    m.EnterPasscodeLabel.font = getFont(24, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.EnterPasscodeLabel.text = "Enter your Passcode"
end sub

sub loginWithPassCodeLabelSet()
    m.LoginUsingPasscodeLabel = m.top.findNode("LoginUsingPasscodeLabel")
    m.LoginUsingPasscodeLabel.text = "Login Using PassCode"
    m.LoginUsingPasscodeLabel.font = getFont(44, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
end sub

sub loginWithPassCode()
    m.LoginUsingPassCodeButton = m.top.findNode("LoginUsingPassCodeButton")
    m.LoginUsingPassCodeLabel = m.LoginUsingPassCodeButton.createChild("Label")
    m.LoginUsingPassCodeLabel.width = 654
    ' m.LoginUsingPassCodeLabel.height = 54
    m.LoginUsingPassCodeLabel.translation = [0, 5]
    m.LoginUsingPassCodeLabel.text = "Login with pass code"
    m.LoginUsingPassCodeLabel.color = "#ffffff"
    m.LoginUsingPassCodeLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.LoginUsingPassCodeLabel.height = 50
    m.LoginUsingPassCodeLabel.horizAlign = "Center"
    m.LoginUsingPassCodeLabel.vertAlign = "Center"

    m.LoginUsingPassCodeButton.observeField("buttonSelected", "LoginUsingPassCodeButtonClick")
end sub

sub loginWithQrCode()
    m.qrLoginBtn = m.top.findNode("qrLoginBtn")
    m.qrLoginBtnLable = m.qrLoginBtn.createChild("Label")
    m.qrLoginBtnLable.width = 654
    ' m.LoginUsingPassCodeLabel.height = 54
    m.qrLoginBtnLable.translation = [0, 5]
    m.qrLoginBtnLable.text = "QR Login"
    m.qrLoginBtnLable.color = "#ffffff"
    m.qrLoginBtnLable.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.qrLoginBtnLable.height = 50
    m.qrLoginBtnLable.horizAlign = "Center"
    m.qrLoginBtnLable.vertAlign = "Center"

    m.qrLoginBtn.observeField("buttonSelected", "qrLoginBtnClick")
end sub

sub CallQrCodeFromNetwork()
    m.GetQrCode = CreateObject("roSGNode", "GetQrCode")
    m.GetQrCode.callType = "POST"
    m.GetQrCode.control = "RUN"
    m.GetQrCode.observeField("response", "GetQrCodeResponse")
end sub


sub qrLoginBtnClick()
    ' m.qrLoginBtn.setFocus(false)
    ?"m.RoomListMarkuGrid "m.RoomListMarkuGrid.hasFocus()
    ?"m.RoomListMarkuGrid"m.logout.hasFocus()
    m.logout.setFocus(false)
    m.RoomListMarkuGrid.setFocus(false)
    if m.mainBarcodeView.visible = true
        ' m.mainBarcodeView.visible = true
        showspinner(false)
    else
        showspinner(true)

        ' first api call to load qr code in the view
        ' https://streamlytv.com/api/login/create/qrcode
        CallQrCodeFromNetwork()
        ' second screen calculation
        ' m.LoginWithPassCodeView = m.top.findNode("LoginWithPassCodeView")
        ' m.mainLoginView = m.top.findNode("LoginView")
        ' m.mainBarcodeView = m.top.findNode("barCodeView")

        examplerect = m.mainBarcodeView.boundingRect()
        centerx = (1920 - examplerect.width) / 2
        centery = 200
        m.mainBarcodeView.translation = [centerx, centery]

        m.qrCodeDiscriptionRect = m.top.findNode("qrCodeDiscriptionRect")
        m.qrCodeRect = m.top.findNode("qrCodeRect")

        m.qrCodeDiscriptionRect.width = examplerect.width * 4 / 6
        m.qrCodeRect.width = examplerect.width * 2 / 6

        m.qrCodeDisLableRect = m.top.findNode("qrCodeDisLableRect")
        m.qrCodeDisLableRect.width = (examplerect.width * 4 / 6) - 100

        m.qrLoginTextLable = m.top.findNode("qrLoginTextLable")
        ltransCenterx = (((examplerect.width * 4 / 6) - 100) - m.qrLoginTextLable.width) / 2
        m.qrLoginTextLable.translation = [ltransCenterx, 0]

        ' working in qr code layout set to center of the parent view
        m.qrCodeImagePster = m.top.findNode("qrCodeImg")
        qrCodeCenterX = ((examplerect.width * 2 / 6) - m.qrCodeImagePster.width) / 2
        qrCodeCentery = 100
        m.qrCodeImagePster.translation = [qrCodeCenterX, qrCodeCentery]

        m.mainLoginView.visible = false
        m.LoginWithPassCodeView.visible = false
        m.mainBarcodeView.visible = true
    end if
end sub

sub GetQrCodeResponse(params as object)
    ?"m.mainBarcodeView.visible "m.mainBarcodeView.visible
    ?"qr response are comming " ; params.getData()
    m.qrCodeImageUrl = params.getData().msg
    m.qrCodeImageKey = params.getData().key
    ?"qrkey"; m.qrCodeImageKey
    m.qrCodePosterImg = m.top.findNode("qrCodePosterImg")
    m.qrCodePosterImg.uri = m.qrCodeImageUrl
    showspinner(false)
    ' call for continue check of the api ------------------------------------------------------------------------------------------------
    check_for_scan_or_not()
end sub

sub LoginUsingPassCodeButtonClick(params as object)
    ?"LoginUsingPassCodeButtonClick: ";params.getRoSgnode()

    m.LoginWithPassCodeView = m.top.findNode("LoginWithPassCodeView")
    m.LoginView.visible = false
    m.LoginWithPassCodeView.visible = true

    m.EnterPasscodeEditText.setFocus(true)

    onTextBoxActive("passcode")
    m.TextField = ""

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
        m.HelpButton.setFocus(true)
        if responseData.data.email <> invalid
            m.contactNumber.text = responseData.data.email
            m.top.appendChild(m.SupportDialog)
            m.HelpButton.setFocus(true)
        end if
    else if m.ProfileTask.callto = "Sign out"
        ?"Profile::responseData.message:";m.responseData.message
        setAccountToken("")
        setThePersonalDeviceName("")
        setPersonaldeviceid("")
        m.logout = true
        m.top.logout = true
        m.ProfileCategory.setFocus(false)



    else if responseData.response_code = true

        ?"Profile::responseData.message:";responseData.message
        setAccountToken("")
        setThePersonalDeviceName("")
        setPersonaldeviceid("")

        ' hide the room selection screen and show the login screen
        m.LoginView.visible = true
        m.LoginWithPassCodeView.visible = false
        m.mainBarcodeView.visible = false
        m.roomSearchEditText.visible = false
        m.RoomListMarkuGrid.visible = false
        m.HotelLoginLableInfo.visible = false
        m.logout.visible = false
        setUserHomeScreenMode("Login")
        m.UsernamEditText.setFocus(true)

        ' setPersonaldevicename_popup(true)
        ' setUserHomeScreenMode("personalDeviceName")
        ' m.top.showPersonalDeviceName = true
    else
        ?"error dialog to show the error:";responseData
        '    showDialog("",false,false)

        showDialogError(true, responseData.Failure, "Ok")

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
    m.dontHaveAccountLAbel.translation = [280, 780]
    m.dontHaveAccountLAbel.color = "#000000"
    m.dontHaveAccountLAbel.Font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.version = m.top.findNode("version")
    m.version.text = "Version : 2.9"
    m.version.translation = [320, 850]
    m.version.color = "#004E92"
    m.version.Font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")


    m.registerNow = m.dontHaveAccountLAbel.createChild("Button")
    m.registerNow.observeField("buttonSelected", "showDialogforCreateAccount")
    '    m.OkButton.setFocus(true)
    m.registerNow.translation = [260, -5]
    m.registerNow.minWidth = "200"
    m.registerNow.maxWidth = "200"
    m.registerNow.focusedTextColor = "#000000"
    m.registerNow.height = "49"
    m.registerNow.focusBitmapUri = "pkg:/images/Home/categiryNavFocusIcon.png"
    ' m.registerNow.focusBitmapUri.width = 150
    m.registerNow.showFocusFootprint = "true"
    m.registerNow.iconUri = ""
    m.registerNow.focusedIconUri = ""
    m.registerNow.focusFootprintBitmapUri = "pkg:/images/Login/createAccountunFocus.png"
    '                 m.OkButton.horizAlign="center"
    okLabel = m.registerNow.createChild("Label")
    okLabel.horizAlign = "center"
    okLabel.height = "45"
    ' okLabel.color = "#004E92"
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
    m.termConditionButton.translation = [127, 780]
    m.termConditionButton.minWidth = "130"
    m.termConditionButton.maxWidth = "130"
    m.termConditionButton.focusedTextColor = "#FFFFFF"
    m.termConditionButton.height = "50"
    m.termConditionButton.focusBitmapUri = "pkg:/images/Home/categiryNavFocusIcon.png"
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
    signup.observeField("exit", "closesignup")
    signup.setFocus(true)

    ' showDialog("Open the link in a browser", " https://streamlytv.com/ ", true, "", "")
    '    m.OkButton.setFocus(true)
end sub



sub closesignup(event as object)
    ?"closesignup"
    m.top.removeChild(event.getRoSgnode())
    m.registerNow.setFocus(true)

end sub



sub initSupportDialog()
    m.dialogHeight = 400
    m.dialogWidth = 1223

    m.SupportDialog = m.top.createChild("Poster")
    m.SupportDialog.visible = false
    m.SupportDialog.width = m.dialogWidth
    m.SupportDialog.height = m.dialogHeight
    m.SupportDialog.uri = "pkg:/images/profile/profile_dialog.png"
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
    m.ButtonLabelText = m.HelpButton.createChild("Label")
    m.ButtonLabelText.horizAlign = "center"
    m.ButtonLabelText.height = "84"
    m.ButtonLabelText.width = "280"
    m.ButtonLabelText.vertAlign = "center"
    m.ButtonLabelText.text = "OK"
    m.ButtonLabelText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
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
    ?"getLogoutMode() "getLogoutMode()
    if getUserName() <> invalid and getUserPassword() <> invalid and getUserName() <> "" and getUserPassword() <> "" and getLogoutMode() <> "logout"
        m.rememberFlag = true

        m.remenberCheckList.checkedState = [m.rememberFlag]

        m.UsernamEditText.text = getUserName()
        m.PasswordEditText.text = getUserPassword()
    else
        deleteLogoutMode()
        m.UsernamEditText.text = ""
        m.PasswordEditText.text = ""
        m.rememberFlag = false
    end if

end sub

sub remeberMeUpdate(event as object)
    ? "remeberMeUpdate: "; event.getData()[0]
    m.rememberFlag = event.getData()[0]
end sub

sub checkUpdate(event as object)
    ? "checkUpdate: "; event.getData()[0]
    m.rememberFlag = event.getData()[0]
    if event.getData()[0] = true
        setCheckMode("true")
    else
        setCheckMode("false")
    end if
end sub

' sub OnFocusChildChange()
'     ? "OnFocusChildChange"
'     if m.top.hasFocus() and (not m.UsernamEditText.hasFocus() and m.setFocus = false)
'         m.UsernamEditText.setFocus(true)
'         m.setFocus = true
'     end if
' end sub

sub LoginValidation()
    deleteLogin()
    ? "LoginValidation: ";
    ?"getdata "getDataMode()

    m.UsernamEditText.text = "roku@yopmail.com"
    ' m.UsernamEditText.text = "jmheil@gmail.com"
    m.PasswordEditText.text = "123456789"



    ' m.device_connected.visible = true
    ' m.device_connected.text = ConnectedDevices0
    ' m.registredDevicesMarkup.visible = true
    ' m.registredDevicesMarkup.content = marupContent
    m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice1")

    if m.password_show.visible = true
        ?"password show"
        ?"password text: "m.PasswordEditText.text
    end if

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
                showDialog("Error", "Please enter password", true, "", "")
            end if
        else
            showDialog("Error", "Please Enter Valid email", true, "", "")
        end if
    else
        showDialog("Error", "Please enter email", true, "", "")
    end if
    ' LoginProcess()
end sub





sub LoginProcess()
    ? "LoginProcess: ";

    showspinner(true)

    m.LoginTask = CreateObject("roSGNode", "LoginTask")
    ' m.LoginPasscodeTask = CreateObject("roSGNode", "LoginPasscodeTask")

    data = ""
    ' email = "vikas.saini1@oodles.io"
    ' password = "123456789"
    email = m.UsernamEditText.text
    password = m.PasswordEditText.text

    ' email = (m.UsernamEditText.text).Trim()
    ' password = (m.PasswordEditText.text).Trim()
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

sub ApiCallFor_session(callto as string, callType as string)
    ?"ProfileSetting::ApiCallFor: ";callto ;callType
    m.ProfileTask = CreateObject("roSGNode", "ProfileTask") 'Create XML Parsing task node
    m.LoginBusySpinner.visible = true
    '     ApiCallFor(callto,"GET")
    m.ProfileTask.callto = callto
    m.ProfileTask.callType = callType

    m.ProfileTask.observeField("response", "responseAPI")
    m.ProfileTask.observeField("errorMessage", "errorDialogShow")

    '     m.ProfileTask.observeField("response","responseAPI")
    m.ProfileTask.control = "RUN" 'Run the task node

end sub


sub LoginResponse()


    ? "LoginResponse :m.LoginTask: user"; m.LoginTask.response


    if m.LoginTask.response.response_code = 200
        setDataMode(m.LoginTask.response.sessionid)
        stringid = (m.LoginTask.response.user.id).toStr()
        setUserCredential(stringid)
    end if


    if m.LoginTask.response.response_code = 479
        setDeviceMode("device")
        showspinner(false)
        m.Nodevice.setFocus(true)
        showDialogDevice("LoginError", true, false, m.LoginTask.response.message)
        ConnectedDevices0 = m.LoginTask.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.LoginTask.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.LoginTask.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ?"data: "data
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            marupContent.createChild("ContentNode").addFields(data)
        end for

        ' m.registredDevicesMarkup.visible = true
        ' m.registredDevicesMarkup.content = marupContent
    else

        showspinner(false)
        m.loginsuccess = false
        if m.LoginTask.response <> invalid and m.LoginTask.response.response_code = 404
            showDialog("Error", m.LoginTask.response.message, true, "", "")
            m.needHelpText = "normalLoginUI"
            m.isSubscribe = false
        else if m.LoginTask.response <> invalid and m.LoginTask.response.response_code = 401
            showDialog("Error", m.LoginTask.response.message, true, "", "")
            setLogin("login")
            m.needHelpText = "normalLoginUI"
            m.isSubscribe = false
        else if m.LoginTask.response <> invalid and m.LoginTask.response.response_code = 429 and m.LoginTask.response.data = invalid
            ' m.ButtonLabelText.text = "Logout"
            ' m.LogoutCode = responseData.data
            m.needHelpText = "normalLoginUI"
            m.logoutPassCode = false
            m.isSubscribe = false
            showDialog("Error", m.LoginTask.response.message, true, "", "")
        else if m.LoginTask.response <> invalid and m.LoginTask.response.response_code = 429 and m.LoginTask.response.data <> invalid
            ' m.ButtonLabelText.text = "Logout"
            m.LogoutCode = m.LoginTask.response.data
            m.needHelpText = "normalLoginUI"
            m.logoutPassCode = true
            m.isSubscribe = false

            closeDialog()
            ' showDialog("Error", "responseData.message", true, "", "")
            ' showDialog("Error", "Already logged in with this device, press 'OK' for logout and login again", true, "", "")
        else if m.LoginTask.response.response_code = 200
            m.isSubscribe = true
            if m.LoginTask.response.subscribed = true
                setAccountToken(m.LoginTask.response.access_token)
                setPersonaldeviceid(m.LoginTask.response.personaldeviceid)
                setDeviceType(m.LoginTask.response.device_type)
                setUserType(m.LoginTask.response.userrole)
                ?"setUserType value: "getUserType()
                setPersonaldevicename_popup(m.LoginTask.response.personaldevicename_popup)
                setHomeTimezone(m.LoginTask.response.timezone)
                if m.LoginTask.response.personaldevicename_popup = true
                    ' ?"m.LoginTask.response.personaldevicename_popup: "m.LoginTask.response.personaldevicename_popup
                    ' show personal device name
                    RegWrite("personalDevice", "personalDevice")
                    setPersonaldevicename_popup(true)
                    setUserHomeScreenMode("personalDeviceName")
                    setUserPersonalScreenMode("popup")
                    m.top.showPersonalDeviceName = true
                else if m.LoginTask.response.userrole = "tieruser"
                    ' RegWrite("personalDevice","personalDevice")
                    ' setPersonaldevicename_popup(true)
                    ' setUserHomeScreenMode("personalDeviceName")
                    ' m.top.showPersonalDeviceName = true
                    ?"getDeviceMode()"getDeviceMode()
                    if getDeviceMode() = "device"
                        setUserHomeScreenMode("Home")
                        m.loginsuccess = true
                        buttons = []
                        buttons.Push("OK")
                        m.top.signalBeacon("AppDialogComplete")
                    else
                        setUserHomeScreenMode("Room")
                        setRoomSelectionFlag(false)
                        m.roomContent = createObject("roSGNode", "ContentNode")
                        if m.LoginTask.response.rooms[0] <> invalid
                            ?"responseData.rooms[0]:";m.LoginTask.response.rooms[0]
                            stringid = (m.LoginTask.response.rooms[0].user_id).toStr()
                            setUserCredential(stringid)
                            for each item in m.LoginTask.response.rooms
                                roomDetail = m.roomContent.createChild("ContentNode")
                                ' itemKey = {}
                                roomDetail.title = item.hotel_room
                                roomDetail.id = item.id
                                roomDetail.HDSMALLICONURL = "pkg:/images/profile/room.png"
                                ' roomDetail.addFields(itemKey)
                            end for
                            m.needHelpText = "roomSelectUI"
                            m.RoomListMarkuGrid.content = m.roomContent
                            m.showRoomView = true
                            m.loginsuccess = false
                            closeDialog()
                            ' showDialog(m.LoginTask.response.message, "Please select a room to start streaming", false, "", "")
                            return
                        else
                            setUserHomeScreenMode("Home")
                            m.loginsuccess = true
                            buttons = []
                            buttons.Push("OK")
                            m.top.signalBeacon("AppDialogComplete")
                        end if
                    end if
                else
                    ?"homescreen switching "
                    setUserHomeScreenMode("Home")
                    m.loginsuccess = true
                    buttons = []
                    buttons.Push("OK")
                    openHomeView()
                    m.top.signalBeacon("AppDialogComplete")
                end if
            else
                ' m.needHelpText = "passcodeUI"
                m.logoutPassCode = true
                m.isSubscribe = false
                showDialog("Subscription Error", m.LoginTask.response.subscribedmsgis, true, "", "")
                return
            end if

            if m.LoginTask.response.termandcondition = true
                showdialogTermCondition(m.LoginTask.response.message, m.LoginTask.response.connected_device, false, "", "dialogButtonResponse")
            else
                closeDialog()
                ' 4 connected device message removed
                ' showDialog(m.LoginTask.response.message, "Please select a room to start streaming", false, "", "dialogButtonResponse")
            end if
        else
            buttons = []
            buttons.Push("OK")
            '        buttons.Push("Cancel")
            ' showDialog("Login Error", m.LoginTask.response.message, true, buttons, "")
            m.Nodevice.setFocus(true)
            showDialogDevice("Login Error", true, false, "Invalid credentials")
        end if
    end if
end sub

sub openHomeView()
    ? "openHomeView"
    m.screen = showHomeScreen()
    m.screen.observeField("ExitView", "openLoginView")
end sub

sub showDialogDevice(dialogView as string, show as boolean, focus as boolean, message as string)
    ?"ProfileSetting::show"
    m.deviceView.visible = false



    if show
        m.deviceText.text = tr(message)
        m.deviceView.width = 1620
        m.deviceView.height = 488
        m.deviceView.visible = show
        ' m.Dialog.visible = show
        m.deviceView.translation = [(1920 - m.deviceView.boundingRect().width) / 2, (1080 - m.deviceView.boundingRect().height) / 2]
    end if

end sub

sub showDialogRemove(dialogView as string, show as boolean, focus as boolean)
    ?"ProfileSetting::showDialog"
    m.removeView.visible = false



    if show
        m.removeText.text = tr("Are you sure you want to logout from this device?")
        m.removeView.width = 1450
        m.removeView.height = 488
        m.removeView.visible = show
        ' m.Dialog.visible = show
        m.removeView.translation = [(1920 - m.removeView.boundingRect().width) / 2, (1080 - m.removeView.boundingRect().height) / 2]
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
    if not m.isSubscribe
        m.ButtonLabelText.text = "OK"
    end if
    ' m.SignInButton.setFocus(true)
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
    ? ""; m.needHelpText
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
    else if m.needHelpText = "roomSelectUI"

        GetRoomsmAPicall()
        m.SupportDialog.visible = false
        m.RoomListMarkuGrid.visible = true
        m.roomSearchEditText.visible = true
        m.logout.visible = true
        m.RoomListMarkuGrid.setFocus(false)
        m.LoginView.visible = false
        m.LoginWithPassCodeView.visible = false
        m.mainBarcodeView.visible = false
        setRoomSelectionFlag(false)
        setUserHomeScreenMode("Room")
        ' m.LoginWithPassCodeView.visible = false
    else if m.needHelpText = "passcodeUI" or m.needHelpText = "qrUI" or m.needHelpText = "normalLoginUI"
        if m.needHelpText = "passcodeUI"
            m.SignInWithPasscodeButton.setFocus(true)
        end if
        ?"passcodeUI focus true"
        m.ButtonLabelText.text = "Ok"
        if m.logoutPassCode
            m.logoutPassCode = false
            logOutPassCodeAPicall()
            if m.needHelpText = "passcodeUI"
                m.SignInWithPasscodeButton.setFocus(true)
            end if
            m.SupportDialog.visible = false

        else if m.showRoomView
            m.LoginView.visible = false
            m.showRoomView = false
            m.LoginWithPassCodeView.visible = false
            m.mainBarcodeView.visible = false
            ' m.mainLoginView.visible = false
            m.SupportDialog.visible = false
            m.RoomListMarkuGrid.visible = true
            m.HotelLoginLableInfo.visible = true
            m.RoomListMarkuGrid.setFocus(true)

        else
            ' m.SignInWithPasscodeButton.setFocus(true)
            m.SupportDialog.visible = false
            m.SignInButton.setFocus(true)
        end if


    else


        m.HelpButton.setFocus(false)
        m.SupportDialog.visible = false
        m.SignInButton.setFocus(true)
    end if
    m.needHelpText = ""

    ' m.dialog.close=true
end sub


sub RoomListUI()

    ?"RoomListUI"
    m.HotelLoginLableInfo = m.top.findNode("HotelLoginLableInfo")
    m.HotelLoginLableInfo.font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.HotelLoginLableInfo.text = "Hotel Login Streamly On your TV Select Room"
    m.HotelLoginLableInfo.translation = [(1920 - m.HotelLoginLableInfo.boundingRect().width) / 2, 220]
    m.HotelLoginLableInfo.visible = false


    m.RoomListMarkuGrid = m.top.findNode("RoomList")
    m.RoomListMarkuGrid.translation = "[115,250]"
    m.RoomListMarkuGrid.itemComponentName = "ChannellistMarkuGridComponent"
    m.RoomListMarkuGrid.itemSpacing = "[ 20, 20 ]"
    '  m.ChannelsMarkuGrid.rowSpacings = "[ 200, 200 ]"
    '  m.ChannelsMarkuGrid.columnSpacings = "[ 200, 0 ]"
    ' m.ChannelsMarkuGrid.rowHeights =	[ 260, 120, 220 ]
    '  m.ChannelsMarkuGrid.wrapDividerHeight="200"
    ' m.itemWidht = (m.screenWidth - 215) / 4 - 20
    m.itemWidht = 406.25
    m.RoomListMarkuGrid.numColumns = "4"
    m.RoomListMarkuGrid.numRows = "3"
    m.RoomListMarkuGrid.itemSize = [m.itemWidht, m.itemWidht - 100]

    m.RoomListMarkuGrid.drawFocusFeedback = "true"
    m.RoomListMarkuGrid.visible = false
    m.RoomListMarkuGrid.observeField("itemSelected", "selectRoomCallback")

    ?"m.itemwidth" m.itemWidht

end sub

sub selectRoomCallback(event as object)
    selectedData = event.getRosgnode().content.getchild(event.getRosgnode().itemSelected)
    ?"selectRoomCallback";selectedData
    ' m.top.playChannel = event.getRosgnode().content.getchild(event.getRosgnode().itemSelected)

    if selectedData <> invalid and selectedData.id <> invalid
        selectRoomAPicall(selectedData.id)
    end if

end sub

sub selectRoomAPicall(id as object)
    ?"selectRoomAPicall"
    showspinner(true)
    m.RoomSelectTask = CreateObject("roSGNode", "RoomSelectTask")

    ' passcode = (m.EnterPasscodeEditText.text).Trim()
    data = {}

    ' deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()

    data["roomid"] = id
    ' data["deviceid"] = deviceInfo.GetChannelClientId()

    m.RoomSelectTask.callType = "POST"
    '   data =  "email="+email+"&password="+password+deviceid
    ? "data: "; data
    m.RoomSelectTask.data = data
    m.RoomSelectTask.control = "RUN"
    m.RoomSelectTask.observeField("response", "selectRoomResponse")
    ' m.RoomSelectTask.observeField("errorMessage", "errorDialogShow")
end sub

function RegRead(key, section = invalid)
    if section = invalid section = "Default"
    sec = CreateObject("roRegistrySection", section)
    if sec.Exists(key)
        return true
    end if
    return false
end function

function RegWrite(key, val, section = invalid)
    if section = invalid section = "Default"
    sec = CreateObject("roRegistrySection", section)
    sec.Write(key, val)
    ' sec.Flush() 'commit it
end function

function RegDelete(key, section = invalid)
    if section = invalid section = "Default"
    sec = CreateObject("roRegistrySection", section)
    sec.Delete(key)
    sec.Flush()
end function


sub selectRoomResponse(params as object)
    ?"selectRoomResponse;";params.getData()
    responseData = params.getData()
    showspinner(false)

    if responseData.response_code = 409
        m.needHelpText = "roomSelectUI"
        showDialog("Error", "That Room is already taken, Please refresh the list to select again", true, "", "")
        setRoomSelectionFlag(false)
        setUserHomeScreenMode("Room")
    else if responseData.response_code = 200
        m.loginsuccess = true
        setRoomSelectionFlag(true)
        if RegRead("personalDevice")
            RegDelete("personalDevice")
            m.screen = showHomeScreen()
        else
            setUserHomeScreenMode("Home")

            closeDialog()
        end if

        ' showDialog("Success", responseData.message, true, "", "")
    else if responseData.response_code = 401
        ' setUserHomeScreenMode("Login")
        ' m.needHelpText = "passcodeUI"
        ' m.logoutPassCode = true
        ' m.isSubscribe = false
        ' showDialog("Subscription Error", responseData.subscribedmsgis, true, "", "")
        ' showDialogRoom("Error", "Your session has expired. You have to log in again to proceed further.", true, "", "")
        ' m.logout = true


        m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
        m.DialogButton.observeField("buttonSelected", "closeScreen")
        m.DialogButton.setFocus(true)
        setAccountToken("")
        setThePersonalDeviceName("")
        setPersonaldeviceid("")


    end if


end sub

function showHomeScreen() as object
    ? "showHomeScreen"
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    HomeScreen = m.top.createChild("HomeScreen")
    HomeScreen.setFocus(true)
    return HomeScreen
end function

sub closeScreen()
    ?"close for -> login screen ";
    ' m.logout = false
    m.buttonClick.setFocus(false)
    m.ErrorDialogView.visible = false

    m.SupportDialog.visible = false
    m.RoomListMarkuGrid.visible = false
    m.roomSearchEditText.visible = false
    m.logout.visible = false
    m.RoomListMarkuGrid.setFocus(false)
    m.LoginView.visible = true
    m.LoginWithPassCodeView.visible = false
    m.mainBarcodeView.visible = false
    setRoomSelectionFlag(false)
    m.UsernamEditText.text = ""
    m.PasswordEditText.text = ""


    m.UsernamEditText.setFocus(false)
    m.UsernamEditText.active = true
    onTextBoxActive("username")
    m.KeyboardInput.textEditBox.text = m.UsernamEditText.text
    m.KeyboardInput.textEditBox.cursorPosition = (m.UsernamEditText.text).Len()
    m.UsernamEditText.cursorPosition = (m.UsernamEditText.text).Len()
    m.KeyboardPoster.visible = true
    m.KeyboardInput.setFocus(true)
    setUserHomeScreenMode("Login")

end sub




sub GetRoomsmAPicall()
    ?"selectRoomAPicall"
    showspinner(true)
    m.GetRoomsTask = CreateObject("roSGNode", "GetRoomsTask")

    ' passcode = (m.EnterPasscodeEditText.text).Trim()
    data = {}

    ' deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()

    ' data["roomid"] = id
    ' data["deviceid"] = deviceInfo.GetChannelClientId()

    m.GetRoomsTask.callType = "POST"
    '   data =  "email="+email+"&password="+password+deviceid
    ? "data: "; data
    m.GetRoomsTask.data = data
    m.GetRoomsTask.control = "RUN"
    m.GetRoomsTask.observeField("response", "GetRoomsmAPicallResponse")
end sub
sub GetRoomsmAPicallResponse(params as object)
    m.roomResponseData = params.getData()
    ?"GetRoomsmAPicallResponse";responseData
    showspinner(false)
    m.roomSearchEditText.visible = true
    m.logout.visible = true
    if m.roomResponseData <> invalid and m.roomResponseData.response_code = 200 and m.roomResponseData.data <> invalid
        ' showDialog("Success", responseData.message, true, "", "")
        ' m.needHelpText = "passcodeUI"
        m.roomContent = createObject("roSGNode", "ContentNode")
        ?"responseData.rooms[0]:";m.roomResponseData.data[0]
        m.roomContent = createObject("roSGNode", "ContentNode")
        for each item in m.roomResponseData.data
            ?"item or room" ; item
            roomDetail = m.roomContent.createChild("ContentNode")
            ' itemKey = {}
            roomDetail.title = item.hotel_room
            roomDetail.id = item.id
            roomDetail.HDSMALLICONURL = "pkg:/images/profile/room.png"
            ' roomDetail.addFields(itemKey)
        end for
        m.RoomListMarkuGrid.content = m.roomContent
    end if

    m.RoomListMarkuGrid.setFocus(true)
end sub

sub SearchDevice(searchName as string)


    ?"m.device"m.devices

    if m.LoginTask.response <> invalid and m.LoginTask.response.response_code = 479


        showspinner(false)
        m.Nodevice.setFocus(true)
        ' showDialogDevice("LoginError", true, false)
        ConnectedDevices0 = m.LoginTask.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.LoginTask.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.LoginTask.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ' ?"data: "data.session_id
            ' ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            m.device_name = data["device_name"]
            m.device_type = data["device_type"]
            if(data["personaldevicename"] <> invalid)
                m.device_name_per = data["personaldevicename"]
            else
                m.device_name_per = "unknown"
            end if
            if(data["hotel_room"] <> invalid)
                m.room_number = data["hotel_room"]
            else
                m.room_number = "unknown"
            end if
            ?"m.device_name "Lcase(m.device_name)
            ?"m.device_name_per " ; data

            if (Instr(1, Lcase(m.device_name_per), searchName) > 0 or Instr(1, Lcase(m.device_type), searchName) or Instr(1, Lcase(m.room_number), searchName))
                marupContent.createChild("ContentNode").addFields(data)
                ' roomDetail.addFields(itemKey)
            end if
            ' marupContent.createChild("ContentNode").addFields(data)
        end for
        ?"searchediting"
        m.deviceSearchEditText.active = true
        m.KeyboardPosterDevice.visible = true
        m.KeyboardInputDevice.setFocus(true)
        m.KeyboardInputDevice.textEditBox.text = m.deviceSearchEditText.text
        m.KeyboardInputDevice.textEditBox.cursorPosition = (m.deviceSearchEditText.text).Len()
        m.deviceSearchEditText.cursorPosition = (m.deviceSearchEditText.text).Len()
        m.TextField = "searchDevice"
        ' m.registredDevicesMarkup.setFocus(true)
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        ?"m.registredDevicesMarkup"m.registredDevicesMarkup.hasFocus()
        ' showDevice("Registered Devices", true, false)

        ' m.DeviceLogout.callType = "POST"
        ' '   data =  "email="+email+"&password="+password+deviceid
        ' ? "device: "; data
        ' m.DeviceLogout.data = data
        ' m.DeviceLogout.control = "RUN"
    else if m.LoginPasscodeTask.response <> invalid and m.LoginPasscodeTask.response.response_code = 479


        showspinner(false)
        m.Nodevice.setFocus(true)
        ' showDialogDevice("LoginError", true, false)
        ConnectedDevices0 = m.LoginPasscodeTask.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.LoginPasscodeTask.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.LoginPasscodeTask.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ' ?"data: "data.session_id
            ' ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            m.device_name = data["device_name"]
            m.device_type = data["device_type"]
            if(data["personaldevicename"] <> invalid)
                m.device_name_per = data["personaldevicename"]
            else
                m.device_name_per = "unknown"
            end if
            if(data["hotel_room"] <> invalid)
                m.room_number = data["hotel_room"]
            else
                m.room_number = "unknown"
            end if
            ?"m.device_name "Lcase(m.device_name)
            ?"m.device_name_per " ; data

            if (Instr(1, Lcase(m.device_name_per), searchName) > 0 or Instr(1, Lcase(m.device_type), searchName) or Instr(1, Lcase(m.room_number), searchName))
                marupContent.createChild("ContentNode").addFields(data)
                ' roomDetail.addFields(itemKey)
            end if
            ' marupContent.createChild("ContentNode").addFields(data)
        end for
        m.deviceSearchEditText.active = true
        m.KeyboardPosterDevice.visible = true
        m.KeyboardInputDevice.setFocus(true)
        m.KeyboardInputDevice.textEditBox.text = m.deviceSearchEditText.text
        m.KeyboardInputDevice.textEditBox.cursorPosition = (m.deviceSearchEditText.text).Len()
        m.deviceSearchEditText.cursorPosition = (m.deviceSearchEditText.text).Len()
        m.TextField = "searchDevice"
        ' m.registredDevicesMarkup.setFocus(true)
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        ' showDevice("Registered Devices", true, false)

        ' m.DeviceLogout.callType = "POST"
        ' '   data =  "email="+email+"&password="+password+deviceid
        ' ? "device: "; data
        ' m.DeviceLogout.data = data
        ' m.DeviceLogout.control = "RUN"
    else if m.qr_login.response <> invalid and m.qr_login.response.response_code = 479

        showspinner(false)
        m.Nodevice.setFocus(true)
        ' showDialogDevice("LoginError", true, false)
        ConnectedDevices0 = m.qr_login.response.loggedindeviceinfolist.data
        ConnectedDevices = m.qr_login.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.qr_login.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ' ?"data: "data.session_id
            ' ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            m.device_name = data["device_name"]
            m.device_type = data["device_type"]
            if(data["personaldevicename"] <> invalid)
                m.device_name_per = data["personaldevicename"]
            else
                m.device_name_per = "unknown"
            end if
            if(data["hotel_room"] <> invalid)
                m.room_number = data["hotel_room"]
            else
                m.room_number = "unknown"
            end if
            ?"m.device_name "Lcase(m.device_name)
            ?"m.device_name_per " ; data

            if (Instr(1, Lcase(m.device_name_per), searchName) > 0 or Instr(1, Lcase(m.device_type), searchName) or Instr(1, Lcase(m.room_number), searchName))
                marupContent.createChild("ContentNode").addFields(data)
                ' roomDetail.addFields(itemKey)
            end if
            ' marupContent.createChild("ContentNode").addFields(data)
        end for
        ?"searchediting"
        m.deviceSearchEditText.active = true
        m.KeyboardPosterDevice.visible = true
        m.KeyboardInputDevice.setFocus(true)
        m.KeyboardInputDevice.textEditBox.text = m.deviceSearchEditText.text
        m.KeyboardInputDevice.textEditBox.cursorPosition = (m.deviceSearchEditText.text).Len()
        m.deviceSearchEditText.cursorPosition = (m.deviceSearchEditText.text).Len()
        m.TextField = "searchDevice"
        ' m.registredDevicesMarkup.setFocus(true)
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        ' showDevice("Registered Devices", true, false)

        ' m.DeviceLogout.callType = "POST"
        ' '   data =  "email="+email+"&password="+password+deviceid
        ' ? "device: "; data
        ' m.DeviceLogout.data = data
        ' m.DeviceLogout.control = "RUN"
    end if
end sub

sub searchFilter(searchName as string)
    if m.roomResponseData <> invalid and m.roomResponseData.response_code = 200 and m.roomResponseData.data <> invalid
        ' showDialog("Success", responseData.message, true, "", "")
        ' m.needHelpText = "passcodeUI"
        m.roomContent = createObject("roSGNode", "ContentNode")
        ?"responseData.rooms[0]:";m.roomResponseData.data[0]
        for each item in m.roomResponseData.data
            if (Instr(1, Lcase(item.hotel_room), searchName) > 0 or Instr(1, item.id.toStr(), searchName))
                roomDetail = m.roomContent.createChild("ContentNode")
                ' itemKey = {}
                roomDetail.title = item.hotel_room
                roomDetail.id = item.id
                roomDetail.HDSMALLICONURL = "pkg:/images/profile/room.png"
                ' roomDetail.addFields(itemKey)
            end if
        end for
        m.RoomListMarkuGrid.content = m.roomContent
    end if
end sub

sub cursorPosition(event as object)
    ? "cursorPosition: event: "; event.getData()
    ?"m.TextField "m.TextField
    if m.TextField = "UsernamEditText"
        m.UsernamEditText.cursorPosition = event.getData()
    else if m.TextField = "PasswordEditText"
        m.PasswordEditText.cursorPosition = event.getData()
    else if m.TextField = "passcode"
        m.EnterPasscodeEditText.cursorPosition = event.getData()
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
    else if m.TextField = "passcode"
        m.RoomListMarkuGrid.setFocus(false)
        m.roomSearchEditText.setFocus(false)
        m.roomSearchEditText.active = false


        ? "event passcode: "; event.getData()
        m.EnterPasscodeEditText.text = event.getData()
        m.EnterPasscodeEditText.active = true
        m.EnterPasscodeEditText.cursorPosition = m.KeyboardInput.textEditBox.cursorPosition
    else if m.TextField = "searchRoom"
        ? "event: "; event.getData()
        m.roomSearchEditText.text = event.getData()
        m.roomSearchEditText.active = true
        m.roomSearchEditText.cursorPosition = m.KeyboardInput.textEditBox.cursorPosition + 1
        searchFilter(event.getData())
    else if m.TextField = "searchDevice"
        ? "event: "; event.getData()
        m.deviceSearchEditText.text = event.getData()
        m.deviceSearchEditText.active = true
        m.deviceSearchEditText.cursorPosition = m.KeyboardInputDevice.textEditBox.cursorPosition + 1
        SearchDevice(event.getData())
    end if
end sub

function onTextBoxActive(field as string) as void
    '  isActive =  event
    m.TextField = ""
    m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
    m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
    m.EnterPasscodePoster.Uri = "pkg:/images/Login/borderon.png"
    ' m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
    if field = "username"
        m.UsernamPoster.Uri = "pkg:/images/Login/borderof.png"
        ' m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
        m.TextField = "UsernamEditText"
    else if field = "password"
        ' m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
        m.PasswordPoster.Uri = "pkg:/images/Login/borderof.png"
        m.TextField = "PasswordEditText"
    else if field = "passcode"
        ' m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
        m.EnterPasscodePoster.Uri = "pkg:/images/Login/borderof.png"
        m.TextField = "passcode"
    else if field = "PasswordShowPoster"
        ' m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
        m.PasswordShowPoster.Uri = "pkg:/images/Login/borderof.png"
        m.TextField = "PasswordShowPoster"
    else if field = "SearchBoxPoster"
        ' m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
        m.SearchBoxPoster.Uri = "pkg:/images/Login/borderof.png"
        m.TextField = "SearchBoxPoster"
    else
        m.UsernamPoster.Uri = "pkg:/images/Login/borderon.png"
        m.PasswordPoster.Uri = "pkg:/images/Login/borderon.png"
        m.EnterPasscodePoster.Uri = "pkg:/images/Login/borderon.png"
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

function recognizer()
    if m.recognizer = invalid
        m.recognizer = CreateObject("roSGNode", "SpeechRecognizerEmail")
        m.recognizer.delegate = m.top
    end if
    return m.recognizer
end function

function speechRecognizerDidReceiveTranscriptEmail(transcript as string)
    ?"speechRecognizerDidReceiveTranscript"
    ?"transcript:"; transcript
    m.lower = LCase(transcript).Replace(".", "")
    m.UsernamEditText.text = m.lower

end function

sub closePrivicyPolicy()
    ?"closePrivicyPolicy"
    m.ScrolableText.setFocus(false)
    m.OkButton1.setFocus(false)
    m.top.removeChild(m.DialogNodePoster)
    m.Privacy.setFocus(true)
    m.Privacy.height = 116
    m.Privacy.width = 233
    m.Privacy1.height = 151
    m.Privacy1.width = 264
    m.priv_text.translation = [30, 30]
end sub

sub closeOkDialog()
    ?"closeOkDialog"
    ' if m.HelpButton.hasFocus() and m.top.isInFocusChain()

    '     m.HelpButton.setFocus(false)
    m.top.removeChild(m.SupportDialog1)
    m.Front_View.visible = true
    m.Help.setFocus(true)
    m.Help.height = 116
    m.Help.width = 233
    m.help1.height = 151
    m.help1.width = 264
    m.help_text.translation = [30, 30]
    ' else

    ' end if
end sub

function onKeyEvent(key as string, press as boolean) as boolean
    ? "Login: key: "; key
    ? "Login: press: "; press
    ? "HelpButton "; m.HelpButton.hasFocus()
    ? "serach "; m.roomSearchEditText.hasFocus()
    ?"register"m.registredDevicesMarkup.hasFocus()
    ?m.UsernamEditText.active
    ?m.PasswordEditText.active
    ?m.LoginUsingPassCodeButton.hasFocus()
    ' ?m.EnterPasscodeEditText.setFocus(false)
    ' ? "LogoutButton "; m.logout.hasFocus()
    ' getFrontScreenMode()
    ' if getFrontScreenMode() = "front"
    '     if m.screen <> invalid then m.top.removeChild(m.screen)
    '     ' RegWrite("personalDevice", "personalDevice")
    '     setPersonaldevicename_popup(true)
    '     setUserHomeScreenMode("personalDeviceName")
    '     setUserPersonalScreenMode("popup")
    '     m.top.showPersonalDeviceName = true

    ' end if

    result = false
    if press

        ' TC_Remember_needHelp_RegisterNow()
        if m.Privacy.hasFocus() and key = "right"
            m.Privacy.setFocus(false)
            m.Privacy.height = 110
            m.Privacy.width = 225
            m.Privacy1.height = 140
            m.Privacy1.width = 256
            m.priv_text.translation = [25, 25]
            m.Help.setFocus(true)

            m.Help.height = 116
            m.Help.width = 233
            m.help1.height = 151
            m.help1.width = 264
            m.help_text.translation = [30, 30]
            return true
        else if m.Help.hasFocus() and key = "left"
            m.Help.setFocus(false)

            m.Help.height = 110
            m.Help.width = 225
            m.help1.height = 140
            m.help1.width = 256
            m.help_text.translation = [25, 25]

            m.Privacy.setFocus(true)
            m.Privacy.height = 116
            m.Privacy.width = 233
            m.Privacy1.height = 151
            m.Privacy1.width = 264
            m.priv_text.translation = [30, 30]
            return true
        else if m.Privacy.hasFocus() and key = "left"

            m.Privacy.setFocus(true)
            m.Privacy.height = 116
            m.Privacy.width = 233
            m.Privacy1.height = 151
            m.Privacy1.width = 264
            m.priv_text.translation = [25, 25]
            return true
        else if (m.contactNumber1.hasFocus()) and key = "back"
            ?"yes"
            closeOkDialog()

            return true
        else if m.Help.hasFocus() and key = "up"

            m.dontshow.setFocus(false)
            m.Help.setFocus(true)
            m.Help.height = 116
            m.Help.width = 233
            m.help1.height = 151
            m.help1.width = 264
            m.help_text.translation = [30, 30]
            return true
        else if m.Help.hasFocus() and key = "right"

            ' m.dontshow.setFocus(false)
            m.Help.setFocus(true)
            m.Help.height = 116
            m.Help.width = 233
            m.help1.height = 151
            m.help1.width = 264
            m.help_text.translation = [30, 30]
            return true
        else if m.Help.hasFocus() or m.Privacy.hasFocus() and key = "down"
            m.Help.setFocus(false)
            m.Privacy.setFocus(false)
            m.dontshow.setFocus(true)
            m.Help.height = 110
            m.Help.width = 225
            m.help1.height = 140
            m.help1.width = 256

            m.Privacy.height = 110
            m.Privacy.width = 225
            m.Privacy1.height = 140
            m.Privacy1.width = 256
            m.priv_text.translation = [25, 25]
            return true

        else if m.dontshow.hasFocus() and key = "up"
            m.Help.setFocus(false)
            m.Privacy.setFocus(true)
            m.Privacy.height = 116
            m.Privacy.width = 233
            m.Privacy1.height = 151
            m.Privacy1.width = 264
            m.priv_text.translation = [30, 30]
            m.dontshow.setFocus(false)
            ?"m.dontshow.checkedState "m.dontshow.checkedState[0]
            return true
        else if m.dontshow.hasFocus() and key = "down"

            m.dontshow.setFocus(false)
            m.sign.width = 693
            m.sign.height = 103
            m.sign1.width = 708
            m.sign1.height = 108
            m.sign.setFocus(true)
            m.sign_text.translation = [250, 15]
            return true
        else if m.sign.hasFocus() and key = "up"

            m.dontshow.setFocus(true)
            m.sign.width = 685
            m.sign.height = 94
            m.sign1.width = 700
            m.sign1.height = 100
            m.sign.setFocus(false)
            m.sign_text.translation = [250, 10]
            return true
        else if m.sign.hasFocus() and key = "down"

            ' m.dontshow.setFocus(true)
            m.sign.width = 693
            m.sign.height = 103
            m.sign1.width = 708
            m.sign1.height = 108
            m.sign.setFocus(true)
            m.sign_text.translation = [250, 15]
            return true

        else if m.Privacy.hasFocus() and key = "OK"

            showPrivacy()
            m.Privacy.setFocus(false)
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

        else if m.dontshow.hasFocus() and key = "OK"
            if m.rememberFlag = false
                m.rememberFlag = true
                m.dontshow.checkedState = [m.rememberFlag]
                '  setGuideMode("true")

            else if m.rememberFlag = true
                m.rememberFlag = false
                m.dontshow.checkedState = [m.rememberFlag]
                ' setGuideMode("false")

            end if

            return true




        else if m.needHelpButton.hasFocus() and key = "left"
            m.remenberCheckList.setFocus(true)
            return true

        else if m.remenberCheckList.hasFocus() and key = "right"
            m.needHelpButton.setFocus(true)
            return true
        else if m.SignInWithPasscodeButton.hasFocus() and key = "down"
            m.SignInWithPasscodeButton.setFocus(true)
            return true
        else if m.UsernamEditText.hasFocus() and key = "right"
            m.UsernamEditText.setFocus(false)
            m.SearchBoxPoster.setFocus(true)
            onTextBoxActive("SearchBoxPoster")
            return true
        else if m.SearchBoxPoster.hasFocus() and key = "left"
            m.SearchBoxPoster.setFocus(false)
            m.SearchBoxPoster.Uri = "pkg:/images/Login/buttonFoucsFootprint.png"
            m.UsernamEditText.setFocus(true)
            onTextBoxActive("username")
            return true
        else if m.SearchBoxPoster.hasFocus() and key = "OK"
            ?"start voice ..."
            recognizer().callFunc("startListening", {})
            return true
        else if m.PasswordEditText.hasFocus() and key = "right"
            ? "PasswordShowPoster true"
            m.PasswordEditText.setFocus(false)
            m.PasswordShowPoster.setFocus(true)
            onTextBoxActive("PasswordShowPoster")
            return true
        else if m.PasswordShowPoster.hasFocus() and key = "up"
            ? "UsernamEditText: true"
            m.PasswordShowPoster.setFocus(false)
            m.PasswordShowPoster.Uri = "pkg:/images/Login/buttonFoucsFootprint.png"
            m.UsernamEditText.setFocus(true)
            onTextBoxActive("username")
            return true
        else if m.PasswordShowPoster.hasFocus() and key = "down"
            ? "UsernamEditText: true"
            m.PasswordShowPoster.setFocus(false)
            m.PasswordShowPoster.Uri = "pkg:/images/Login/buttonFoucsFootprint.png"
            m.remenberCheckList.setFocus(true)
            return true
        else if m.PasswordShowPoster.hasFocus() and key = "left"
            ? "PasswordShowPoster true"
            m.PasswordShowPoster.setFocus(false)
            m.PasswordEditText.setFocus(true)
            m.PasswordShowPoster.Uri = "pkg:/images/Login/buttonFoucsFootprint.png"
            onTextBoxActive("password")
            return true

        else if m.PasswordShowPoster.hasFocus() and m.password_show.visible = true and key = "OK"
            m.PasswordEditText.secureMode = "false"
            m.password_hide.visible = true
            m.password_show.visible = false
            return true

        else if m.PasswordShowPoster.hasFocus() and m.password_hide.visible = true and key = "OK"
            m.PasswordEditText.secureMode = "true"
            m.password_show.visible = true
            m.password_hide.visible = false
            return true

        else if (m.HelpButton.hasFocus() and key = "back")

            closeDialog()
            return true
        else if(m.mainBarcodeView.visible = true and key = "up") or ((m.mainBarcodeView.visible = true and key = "down") and m.registredDevicesMarkup.hasFocus() = false)
            m.KeyboardPosterDevice.visible = false
            m.KeyboardInputDevice.setFocus(false)
            ' m.qrLoginBtn.setFocus(true)
            return true
        else if(m.mainBarcodeView.visible = true and key = "ok")
            ' m.qrLoginBtn.setFocus(false)
            showspinner(false)
            return true
        else if(m.Nodevice.hasFocus() and key = "up") or ((m.Nodevice.hasFocus() and key = "down"))
            ' m.qrLoginBtn.setFocus(false)
            m.Nodevice.setFocus(true)
            return true
        else if(m.Yesdevice.hasFocus() and key = "up") or ((m.Yesdevice.hasFocus() and key = "down"))
            ' m.qrLoginBtn.setFocus(false)
            m.Yesdevice.setFocus(true)
            return true
        else if m.registredDevicesMarkup.hasFocus() and key = "up"
            ?"Ragister Device Focuse UP"

            ' m.registredDevicesMarkup.setFocus(false)
            m.deviceSearchEditText.setFocus(true)

            m.deviceSearchEditText.active = true
            m.KeyboardPosterDevice.visible = true
            m.KeyboardInputDevice.setFocus(true)
            m.KeyboardInputDevice.textEditBox.text = m.deviceSearchEditText.text
            m.KeyboardInputDevice.textEditBox.cursorPosition = (m.deviceSearchEditText.text).Len()
            m.deviceSearchEditText.cursorPosition = (m.deviceSearchEditText.text).Len()
            m.TextField = "searchDevice"
            ' m.profileView.visible = false
            ' m.registredDevicesMarkup.visible = false
            ' m.TextField = "searchRoom"
            ' ?"m.KeyboardPoster"m.KeyboardPoster
            return true
        else if (m.KeyboardPosterDevice.visible = true or m.KeyboardInputDevice.hasFocus()) and key = "up"
            ?"in keyboard close"
            m.KeyboardPosterDevice.visible = false
            m.KeyboardInputDevice.setFocus(false)
            m.deviceSearchEditText.setFocus(true)
            m.deviceSearchEditText.active = true
            return true
        else if m.deviceSearchEditText.hasFocus() and key = "down"
            m.deviceSearchEditText.active = false
            m.registredDevicesMarkup.setFocus(true)
            m.KeyboardPosterDevice.visible = false
            m.KeyboardInputDevice.setFocus(false)
            return true
        else if m.deviceSearchEditText.hasFocus() and key = "back"

            m.deviceSearchEditText.active = false
            m.registredDevicesMarkup.setFocus(true)
            m.KeyboardPosterDevice.visible = false
            m.KeyboardInputDevice.setFocus(false)
            return true
        else if m.registredDevicesMarkup.hasFocus() and key = "back"
            ?"getRegister()"getRegister()
            if getRegister() = "register"
                ProfileSettingScreen = m.top.createChild("ProfileSettingScreen")
                ProfileSettingScreen.setFocus(true)
            else
                closeDevice2()
            end if

            return true
        else if m.registredDevicesMarkup.hasFocus() and key = "down"
            ?""
            m.registredDevicesMarkup.setFocus(true)
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
            if (m.LoginUsingPassCodeButton.visible = false or m.qrLoginBtn.visible = false or m.termConditionButton.visible = false)
                m.registerNow.setFocus(true)
            else
                m.registerNow.setFocus(false)
                m.termConditionButton.setFocus(true)
            end if
            ' m.termConditionButton.setFocus(true)
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



        else if m.YesButton.hasFocus() and key = "left"
            m.YesButton.setFocus(false)
            m.NoButton.setFocus(true)
            return true
        else if m.NoButton.hasFocus() and key = "right"
            m.NoButton.setFocus(false)
            m.YesButton.setFocus(true)
            return true

        else if m.Yesdevice.hasFocus() and key = "left"
            m.Yesdevice.setFocus(false)
            m.Nodevice.setFocus(true)
            return true
        else if m.Nodevice.hasFocus() and key = "right"
            m.Nodevice.setFocus(false)
            m.Yesdevice.setFocus(true)
            return true
        else if m.Yesremove.hasFocus() and key = "left"
            m.Yesremove.setFocus(false)
            m.Noremove.setFocus(true)
            return true
        else if m.Noremove.hasFocus() and key = "right"
            m.Noremove.setFocus(false)
            m.Yesremove.setFocus(true)
            return true
        else if (m.LoginView.visible = false and key = "back" and m.KeyboardPoster.visible = false and m.LoginWithPassCodeView.visible = true)
            m.LoginView.visible = true
            m.LoginWithPassCodeView.visible = false
            m.HotelLoginLableInfo.visible = false
            m.RoomListMarkuGrid.visible = false

            m.LoginUsingPassCodeButton.setFocus(true)
            return true

        else if (m.LoginView.visible = false and key = "back" and m.KeyboardPoster.visible = false and m.mainBarcodeView.visible = true)
            m.checkForBArcodeContinue = false
            m.LoginView.visible = true
            m.LoginWithPassCodeView.visible = false
            m.mainBarcodeView.visible = false
            m.HotelLoginLableInfo.visible = false
            m.RoomListMarkuGrid.visible = false
            m.qrLoginBtn.setFocus(true)
            return true
        else if (m.TextField = "UsernamEditText" or m.TextField = "PasswordEditText" or m.TextField = "passcode" or m.TextField = "searchDevice") and key = "back"

            return keybordClose()

            ' else if (m.LoginUsingPassCodeButton.hasFocus() and key = "OK")
            '     ?"LoginUsingPassCodeButton has focus: true"
            '     return true
        else if (m.UsernamEditText.hasFocus() and key = "OK")

            ? "keyboard open for email field"

            '         m.UsernamEditText.text = "vikas.saini1@oodles.io"
            ' m.PasswordEditText.text = "123456789"


            m.UsernamEditText.setFocus(false)
            m.UsernamEditText.active = true
            onTextBoxActive("username")
            m.KeyboardInput.textEditBox.text = m.UsernamEditText.text
            m.KeyboardInput.textEditBox.cursorPosition = (m.UsernamEditText.text).Len()
            m.UsernamEditText.cursorPosition = (m.UsernamEditText.text).Len()
            m.KeyboardPoster.visible = true
            m.KeyboardInput.setFocus(true)

            return true

        else if (m.EnterPasscodeEditText.hasFocus() and key = "OK")

            ? "keyboard open for password field"
            m.TextField = "passcode"
            ' m.PasswordEditText.setFocus(false)
            onTextBoxActive("passcode")
            m.EnterPasscodeEditText.active = true
            m.KeyboardInput.textEditBox.text = m.EnterPasscodeEditText.text
            m.KeyboardInput.textEditBox.cursorPosition = (m.EnterPasscodeEditText.text).Len()
            m.EnterPasscodeEditText.cursorPosition = (m.EnterPasscodeEditText.text).Len()
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
        else if (m.PasswordEditText.hasFocus() and key = "right")

            ? "keyboard open for password field"
            m.TextField = "PasswordShowPoster"
            m.PasswordEditText.setFocus(false)
            onTextBoxActive("PasswordShowPoster")

            return true

        end if
    else if not press
        if(m.logout.hasFocus() and key = "OK") then
            ? "Logout focus working: "
            m.NoButton.setFocus(true)
            showDialogLogout("Sign out", true, false)
            return true
        else if m.roomSearchEditText.hasFocus() and key = "up" then
            m.roomSearchEditText.setFocus(false)
            m.logout.setFocus(true)
        else if m.roomSearchEditText.hasFocus() and key = "down" then
            m.roomSearchEditText.setFocus(false)
            m.RoomListMarkuGrid.setFocus(true)
        else if key = "back"
            ?"barcode1"
            m.mainBarcodeView.visible = false
        end if


    end if

    return result
end function

sub showDialogLogout(dialogView as string, show as boolean, focus as boolean)
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

sub LogInAfterLogout()
    showspinner(true)
    m.deviceSearchEditText.text = ""
    showDialogDevice("", false, false, "")
    showDialogRemove("", false, false)
    sessionId = getSessionDeviceLogout()
    m.DeviceLogout = CreateObject("roSGNode", "DeviceLogout")

    m.DeviceLogout.callType = "POST"
    data = {}
    data["id"] = sessionId
    '   data =  "email="+email+"&password="+password+deviceid
    ? "device: "; data
    m.DeviceLogout.data = data
    m.DeviceLogout.control = "RUN"
    m.DeviceLogout.observeField("response", "responseAPI2")

    'm.settingsBox = event.getRoSGNode().content.getChild(event.getData()).text

end sub

sub responseAPI2()
    setSessionDeviceLogout("")
    ' showDialogRemove("", false, true)
    ' showDialogDevice("", false, false, "")
    if(m.LoginPasscodeTask.response <> invalid)
        loginWithPassCodeAPicall()
    else if(m.LoginTask.response <> invalid)
        LoginValidation()
    else if(m.qr_login.response <> invalid)
        ?"qrlogin...";m.qr_login.response
        qr_login()
    end if
end sub

sub logoutApiCall()
    ?"ProfileSetting::logoutApiCall"
    showspinner(true)
    m.YesButton.setFocus(false)
    showDialogLogout("", false, false)
    ApiCallFor("Sign out", "POST")
end sub

sub deviceScreen()
    ' ?"ProfileSetting::logoutApiCall "event.getRoSGNode().content.getChild().text
    m.Yesdevice.setFocus(false)
    showDialogDevice("", false, false, "")
    ' ApiCallFor("Registered Devices", "GET")


    m.LoginTask = CreateObject("roSGNode", "LoginTask")

    m.LoginPasscodeTask = CreateObject("roSGNode", "LoginPasscodeTask")
    m.qr_login = CreateObject("roSGNode", "QrLogin")
    data = ""
    ' email = "vikas.saini1@oodles.io"
    ' password = "123456789"
    email = m.UsernamEditText.text
    password = m.PasswordEditText.text
    logInPass = (m.EnterPasscodeEditText.text).Trim()
    qrKey = m.qrCodeImageKey


    ' email = (m.UsernamEditText.text).Trim()
    ' password = (m.PasswordEditText.text).Trim()
    if(email <> "")
        data = {}

        deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()

        data["email"] = email
        data["password"] = password
        data["deviceid"] = deviceInfo.GetChannelClientId()

        m.LoginTask.callType = "POST"
        '   data =  "email="+email+"&password="+password+deviceid
        ? "logindata: "; data
        m.LoginTask.data = data
        m.LoginTask.control = "RUN"
        m.LoginTask.observeField("response", "LoginDevice")

    else if(logInPass <> "")
        data = {}

        deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()

        data["passcode"] = logInPass
        data["deviceid"] = deviceInfo.GetChannelClientId()

        m.LoginPasscodeTask.callType = "POST"
        '   data =  "email="+email+"&password="+password+deviceid
        ? "passcodedata: "; data
        m.LoginPasscodeTask.data = data
        m.LoginPasscodeTask.control = "RUN"
        m.LoginPasscodeTask.observeField("response", "LoginPasscodeDevice")
    else if(qrKey <> "")
        ? "qrkey: "; qrKey

        m.qr_login.callType = "POST"
        '   data =  "email="+email+"&password="+password+deviceid
        deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()
        data = {}
        data["key"] = qrKey
        data["deviceid"] = deviceInfo.GetChannelClientId()

        m.qr_login.data = data
        m.qr_login.control = "RUN"
        m.qr_login.observeField("response", "QrLoginDevice")
    end if

    ' m.DeviceLogout.callType = "POST"
    ' '   data =  "email="+email+"&password="+password+deviceid
    ' ? "device: "; data
    ' m.DeviceLogout.data = data
    ' m.DeviceLogout.control = "RUN"
    ' ' m.DeviceLogout.observeField("response", "LoginDevice")



end sub

sub removeScreen()
    ' ?"ProfileSetting::logoutApiCall "event.getRoSGNode().content.getChild().text
    m.Noremove.setFocus(true)
    ' showDialogRemove("", false, false)
    ' ApiCallFor("Registered Devices", "GET")
    showDialogRemove("Login Error", true, false)

    m.LoginTask = CreateObject("roSGNode", "LoginTask")
    ' m.DeviceLogout = CreateObject("roSGNode", "DeviceLogout")

    ' m.LoginPasscodeTask = CreateObject("roSGNode", "LoginPasscodeTask")

    data = ""
    ' email = "vikas.saini1@oodles.io"
    ' password = "123456789"
    email = m.UsernamEditText.text
    password = m.PasswordEditText.text

    ' email = (m.UsernamEditText.text).Trim()
    ' password = (m.PasswordEditText.text).Trim()
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
    m.LoginTask.observeField("response", "removeDevice")

    ' m.DeviceLogout.callType = "POST"
    ' '   data =  "email="+email+"&password="+password+deviceid
    ' ? "device: "; data
    ' m.DeviceLogout.data = data
    ' m.DeviceLogout.control = "RUN"
    ' ' m.DeviceLogout.observeField("response", "LoginDevice")



end sub

sub LoginPasscodeDevice()


    ? "LoginResponse :m.LoginTask: "; m.LoginPasscodeTask.response.response_code

    if m.LoginPasscodeTask.response <> invalid and m.LoginPasscodeTask.response.response_code = 479
        showspinner(false)
        m.Nodevice.setFocus(true)
        showDialogDevice("LoginError", true, false, m.LoginPasscodeTask.response.message)
        ConnectedDevices0 = m.LoginPasscodeTask.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.LoginPasscodeTask.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.LoginPasscodeTask.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ?"data: "data
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            marupContent.createChild("ContentNode").addFields(data)
        end for
        m.device_connected.visible = true
        m.device_connected.text = ConnectedDevices0
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        showDevice("Registered Devices", true, false)

        ' m.DeviceLogout.callType = "POST"
        ' '   data =  "email="+email+"&password="+password+deviceid
        ' ? "device: "; data
        ' m.DeviceLogout.data = data
        ' m.DeviceLogout.control = "RUN"
    end if
end sub

sub QrLoginDevice()
    if m.qr_login.response <> invalid and m.qr_login.response.response_code = 479
        showspinner(false)
        m.Nodevice.setFocus(true)
        ' showDialogDevice("LoginError", true, false, m.qr_login.response.message)
        ConnectedDevices0 = m.qr_login.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.qr_login.response.loggedindeviceinfolist.data
        ?"Profile::qrlogin.message:";m.qr_login.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ?"data: "data
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            marupContent.createChild("ContentNode").addFields(data)
        end for
        m.device_connected.visible = true
        m.device_connected.text = ConnectedDevices0
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        ' showDevice("Registered Devices", true, false)
        showDevice("Registered Devices", true, false)


    end if
end sub

sub LoginQrDevice()


    ? "LoginResponse :m.LoginTask: "; m.LoginPasscodeTask.response.response_code

    if m.LoginPasscodeTask.response <> invalid and m.LoginPasscodeTask.response.response_code = 479
        showspinner(false)
        m.Nodevice.setFocus(true)
        showDialogDevice("LoginError", true, false, m.LoginPasscodeTask.response.message)
        ConnectedDevices0 = m.LoginPasscodeTask.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.LoginPasscodeTask.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.LoginPasscodeTask.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevicesuserData[data]:
            ?"data: "data
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            marupContent.createChild("ContentNode").addFields(data)
        end for
        m.device_connected.visible = true
        m.device_connected.text = ConnectedDevices0
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        showDevice("Registered Devices", true, false)

        ' m.DeviceLogout.callType = "POST"
        ' '   data =  "email="+email+"&password="+password+deviceid
        ' ? "device: "; data
        ' m.DeviceLogout.data = data
        ' m.DeviceLogout.control = "RUN"
    end if
end sub

sub LoginDevice()


    ? "LoginResponse :m.LoginTask: "; m.LoginTask.response.response_code

    if m.LoginTask.response.response_code = 479


        showspinner(false)
        m.Nodevice.setFocus(true)
        ' showDialogDevice("LoginError", true, false)
        ConnectedDevices0 = m.LoginTask.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.LoginTask.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.LoginTask.response.loggedindeviceinfolist.data
        m.devices = m.LoginTask.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ?"data: "data.session_id
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            m.device_name = data["device_name"]
            m.device_type = data["device_type"]
            ?"m.device_name "Lcase(m.device_name)
            ?"m.device_name "m.device_type
            marupContent.createChild("ContentNode").addFields(data)
        end for

        m.device_connected.visible = true
        m.device_connected.text = ConnectedDevices0
        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        showDevice("Registered Devices", true, false)

        ' m.DeviceLogout.callType = "POST"
        ' '   data =  "email="+email+"&password="+password+deviceid
        ' ? "device: "; data
        ' m.DeviceLogout.data = data
        ' m.DeviceLogout.control = "RUN"
    end if
end sub

sub removeDevice()


    ? "LoginResponse :m.LoginTask: "; m.LoginTask.response.response_code

    if m.LoginTask.response.response_code = 479


        showspinner(false)
        m.Nodevice.setFocus(true)
        ' showDialogDevice("LoginError", true, false)
        ConnectedDevices0 = m.LoginTask.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.LoginTask.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.LoginTask.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ?"data: "data.session_id
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]

            marupContent.createChild("ContentNode").addFields(data)
        end for

        m.registredDevicesMarkup.visible = true
        m.registredDevicesMarkup.content = marupContent
        m.registredDevicesMarkup.observeField("itemSelected", "initDialogDevice")
        ' showDevice("Registered Devices", true, false)

        ' m.DeviceLogout.callType = "POST"
        ' '   data =  "email="+email+"&password="+password+deviceid
        ' ? "device: "; data
        ' m.DeviceLogout.data = data
        ' m.DeviceLogout.control = "RUN"
    end if
end sub

sub initDialogDevice(event as object)
    showDevice("Registered Devices", true, false)
    sessionId = event.getRoSGNode().content.getChild(event.getData()).session_id
    setSessionDeviceLogout(sessionId)
    m.registredDevicesMarkup.setFocus(false)
    m.Yesremove.observeField("buttonSelected", "LogInAfterLogout")
    ?"Device: event pos device: ";event.getData()
    ?"Device.content: "event.getRoSGNode().content.getChild(event.getData()).session_id
    ?"Device Name: "event.getRoSGNode().content.getChild(event.getData()).device_name
    ?"Device Type: "event.getRoSGNode().content.getChild(event.getData()).device_type
    m.settingsBox = event.getRoSGNode().content.getChild(event.getData()).text
    ?"getDataMode() "getDataMode()
    ' if getDataMode() <> event.getRoSGNode().content.getChild(event.getData()).session_id
    '     showDialogRemove("Registered Devices", true, false)
    ' end if
    showDialogRemove("Registered Devices", true, false)
    ' closeDevice()
    m.Noremove.setFocus(true)
    ' m.dialogOptions.visible = false
    ' m.registredDevicesMarkup.visible = false
    ' if event.getData() = 0
    '   m.ProfileCategory.setFocus(false)
    '   ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

    ' else if event.getData() = 1
    '   ' m.ProfileCategory.setFocus(false)
    '   ' m.NoButton.setFocus(true)
    '   ' showDialog("Sign out", true, false)

    '   m.ProfileCategory.setFocus(false)
    '   ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
    ' else if event.getData() = 2

    '   if m.settingsBox = "Support"
    '     m.ProfileCategory.setFocus(false)
    '     ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
    '   else if m.settingsBox = "Sign out"
    '     m.ProfileCategory.setFocus(false)
    '     m.NoButton.setFocus(true)
    '     showDialog("Sign out", true, false)
    '   end if


    '   ' m.ProfileCategory.setFocus(false)
    '   ' ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

    ' else if event.getData() = 3
    '   m.ProfileCategory.setFocus(false)
    '   ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

    '   '    privicyPolicy()
    '   '    m.ProfileTask.setField("callType","GET")
    '   '    m.ProfileTask.setField("callFor","Privacy & Policy")

    ' else if event.getData() = 4 'Logout api call
    '   m.ProfileCategory.setFocus(false)
    '   m.NoButton.setFocus(true)
    '   showDialog("Sign out", true, false)


    '   ' m.ProfileCategory.setFocus(false)
    '   ' '     m.top.appendChild(m.DialogNode)
    '   ' '     m.OkButton.setFocus(true)
    '   ' ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
    ' end if

end sub

sub initDialogDevice1(event as object)
    ' showDevice("Registered Devices", true, false)
    sessionId = event.getRoSGNode().content.getChild(event.getData()).session_id
    setSessionDeviceLogout(sessionId)
    ' m.registredDevicesMarkup.setFocus(false)
    ' m.Yesremove.observeField("buttonSelected", "LogInAfterLogout")
    ?"Device: event pos device: ";event.getData()
    ?"Device.content: "event.getRoSGNode().content.getChild(event.getData()).session_id
    ?"Device Name: "event.getRoSGNode().content.getChild(event.getData()).device_name
    ?"Device Type: "event.getRoSGNode().content.getChild(event.getData()).device_type
    m.settingsBox = event.getRoSGNode().content.getChild(event.getData()).text
    ?"getDataMode() "getDataMode()
    ' if getDataMode() <> event.getRoSGNode().content.getChild(event.getData()).session_id
    '     showDialogRemove("Registered Devices", true, false)
    ' end if
    ' showDialogRemove("Registered Devices", true, false)
    ' closeDevice()
    m.Noremove.setFocus(true)
    ' m.dialogOptions.visible = false
    ' m.registredDevicesMarkup.visible = false
    ' if event.getData() = 0
    '   m.ProfileCategory.setFocus(false)
    '   ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

    ' else if event.getData() = 1
    '   ' m.ProfileCategory.setFocus(false)
    '   ' m.NoButton.setFocus(true)
    '   ' showDialog("Sign out", true, false)

    '   m.ProfileCategory.setFocus(false)
    '   ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
    ' else if event.getData() = 2

    '   if m.settingsBox = "Support"
    '     m.ProfileCategory.setFocus(false)
    '     ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
    '   else if m.settingsBox = "Sign out"
    '     m.ProfileCategory.setFocus(false)
    '     m.NoButton.setFocus(true)
    '     showDialog("Sign out", true, false)
    '   end if


    '   ' m.ProfileCategory.setFocus(false)
    '   ' ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

    ' else if event.getData() = 3
    '   m.ProfileCategory.setFocus(false)
    '   ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

    '   '    privicyPolicy()
    '   '    m.ProfileTask.setField("callType","GET")
    '   '    m.ProfileTask.setField("callFor","Privacy & Policy")

    ' else if event.getData() = 4 'Logout api call
    '   m.ProfileCategory.setFocus(false)
    '   m.NoButton.setFocus(true)
    '   showDialog("Sign out", true, false)


    '   ' m.ProfileCategory.setFocus(false)
    '   ' '     m.top.appendChild(m.DialogNode)
    '   ' '     m.OkButton.setFocus(true)
    '   ' ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
    ' end if

end sub


sub showDevice(dialogView as string, show as boolean, focus as boolean)
    ?"ProfileSetting"
    ' m.logoutView.visible = false
    m.profileView.visible = false


    if show


        if dialogView = "Registered Devices"

            m.profileView.width = 1920
            m.profileView.height = 1080

            m.profileView.visible = show
            'm.profileView.translation = [(1920 - m.profileView.boundingRect().width) / 2, (1080 - m.profileView.boundingRect().height) / 2]
            ' m.Dialog.visible = show
            m.mainBarcodeView.visible = false
            m.registredDevicesMarkup.setFocus(true)
            ' ragisterMargkupListTranslationX = (m.profileView.width - 1150) / 2
            ' m.registredDevicesMarkup.translation = [ragisterMargkupListTranslationX, 10]
            ' m.ok.translation = [471, 590]

        else if dialogView = ""
            ?"else if working "
        end if
    else
        ' m.Dialog.visible = show
        '   m.ProfileCategory.setFocus(focus)
    end if

end sub

sub closeDevice()
    if(m.mainBarcodeView.visible = true)
        m.NoButton.setFocus(false)
        showDevice("", false, true)
        showDialogDevice("", false, true, "")
    else if(m.LoginWithPassCodeView.visible = true)
        m.NoButton.setFocus(false)
        showDevice("", false, true)
        showDialogDevice("", false, true, "")
        m.SignInWithPasscodeButton.setFocus(true)
    else
        ?"ProfileSetting:: close dialog"
        ?"ProfileSetting::else close dialog"
        ' m.ok.setFocus(false)
        m.NoButton.setFocus(false)
        showDevice("", false, true)
        showDialogDevice("", false, true, "")
        m.SignInButton.setFocus(true)
    end if
end sub

sub closeDevice2()
    if(m.mainBarcodeView.visible = true)
        m.NoButton.setFocus(false)
        showDevice("", false, true)
        showDialogDevice("", false, true, "")
        qrLoginBtnClick()
    else if(m.LoginWithPassCodeView.visible = true)
        m.NoButton.setFocus(false)
        showDevice("", false, true)
        showDialogDevice("", false, true, "")
        m.SignInWithPasscodeButton.setFocus(true)
    else
        ?"ProfileSetting:: close dialog"
        ?"ProfileSetting::else close dialog"
        ' m.ok.setFocus(false)
        m.NoButton.setFocus(false)
        showDevice("", false, true)
        showDialogDevice("", false, true, "")
        m.SignInButton.setFocus(true)
    end if
end sub

sub closeDialogLogout()
    ?"ProfileSetting:: close dialog"
    ?"ProfileSetting::else close dialog"
    ' m.ok.setFocus(false)
    m.NoButton.setFocus(false)

    showDialogLogout("", false, true)
    m.RoomListMarkuGrid.setFocus(true)
    ' m.device_name_edit_text.setFocus(true)
    ' m.device_name_edit_text.active = true
    ' m.buttonClick.setFocus(false)
    ' m.ErrorDialogView.visible = false
    ' m.SignInButton.setFocus(true)
    'm.RoomListMarkuGrid.setFocus(true)

end sub

sub closeDialogDevice()
    if(m.mainBarcodeView.visible = true)
        m.NoButton.setFocus(false)
        showDialogDevice("", false, true, "")
        ' qrLoginBtnClick()
        CallQrCodeFromNetwork()
    else if(m.LoginWithPassCodeView.visible = true)
        m.NoButton.setFocus(false)
        showDialogDevice("", false, true, "")
        m.SignInWithPasscodeButton.setFocus(true)
    else
        ?"ProfileSetting:: close dialog"
        ?"ProfileSetting::else close dialog"
        ' m.ok.setFocus(false)
        m.NoButton.setFocus(false)

        showDialogDevice("", false, true, "")
        ' m.device_name_edit_text.setFocus(true)
        ' m.device_name_edit_text.active = true
        ' m.buttonClick.setFocus(false)
        ' m.ErrorDialogView.visible = false
        m.SignInButton.setFocus(true)
        ' m.RoomListMarkuGrid.setFocus(true)
    end if
end sub

sub closeDialogRemove()
    m.deviceSearchEditText.text = ""
    if(m.mainBarcodeView.visible = true)
        ?"barcodeview"
        m.NoButton.setFocus(false)
        showDialogDevice("", false, true, "")
        showDialogRemove("", false, true)
        m.SignInButton.setFocus(true)
        showDevice("Registered Devices", true, false)
        m.registredDevicesMarkup.setFocus(true)
    else if(m.LoginWithPassCodeView.visible = true)
        ?"sign:: click"
        m.NoButton.setFocus(false)
        showDialogDevice("", false, true, "")
        showDialogRemove("", false, true)
        showDevice("Registered Devices", true, false)
        m.registredDevicesMarkup.setFocus(true)
    else
        ?"ProfileSetting:: close dialog"
        ?"ProfileSetting::else close dialog"
        ' m.ok.setFocus(false)
        m.NoButton.setFocus(false)
        showDialogDevice("", false, false, "")

        showDialogRemove("", false, true)
        ' m.device_name_edit_text.setFocus(true)
        ' m.device_name_edit_text.active = true
        ' m.buttonClick.setFocus(false)
        ' m.ErrorDialogView.visible = false
        showDevice("Registered Devices", true, false)
        m.registredDevicesMarkup.setFocus(true)
        ' m.RoomListMarkuGrid.setFocus(true)
    end if
end sub

function on_ok_key_press() as boolean
    ?"logout : focus" m.logout.hasFocus()
    if m.logout.hasFocus()
        'initDialogMrkupWithData()

    else if m.YesButton.hasFocus()
        ' m.top.data = {}
        ' jsonResponse = APICall(getBaseUrl() + logoutUrl(), "POST", m.top.data, m.top.parameters)
        ' ?"getProfile: jsonResponse: ";jsonResponse
        ' '   m.top.response = jsonResponse.jsonResponse
        ' responseAPiMethod(jsonResponse)
        ' showLoginScreen()
        setUserHomeScreenMode("Login")
        m.needHelpText = "passcodeUI"
        m.logoutPassCode = true
        m.isSubscribe = false
        showDialog("Subscription Error", responseData.subscribedmsgis, true, "", "")

    end if
    return true
end function

function responseAPiMethod(jsonResponse as object)
    ?"responseAPiMethod:"
    if jsonResponse.responseCode = 200
        m.top.response = jsonResponse.jsonResponse
    else if jsonResponse.responseCode = 401 or jsonResponse.responseCode = 429 or jsonResponse.responseCode = 404
        m.top.errorMessage = jsonResponse
    end if
end function

sub initDialogMrkupWithData() as boolean
    ?"logout::MarkuGrid:init(): event pos: "
    m.NoButton.setFocus(true)
    showDialogLogout("Sign out", true, false)
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

' function errorDialogShow(event as object)

'     m.logout = true

'     if event.getData().responseCode = 401 or event.getData().responseCode = 429 or event.getData().responseCode = 404


'         setAccountToken("")
'         setThePersonalDeviceName("")
'         setPersonaldeviceid("")
'     end if

' end function



' function showLoginScreen() as object
'     ? "showLoginScreen"
'     m.top.removeChild(m.screen)
'     LoginScreen = m.top.createChild("LoginScreen")
'     LoginScreen.observeField("dialog", "showDialog")

'     LoginScreen.setFocus(true)
'     return LoginScreen
' end function

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

    else
        m.buttonClick.setFocus(visible)
        m.ErrorDialogView.visible = visible
    end if
end sub




function pressDown() as boolean

    if m.LoginView.visible = true
        if m.SignInButton.hasFocus() then
            ? "LoginUsingPassCodeButton"
            if (m.LoginUsingPassCodeButton.visible = false or m.qrLoginBtn.visible = false or m.termConditionButton.visible = false)
                m.registerNow.setFocus(true)
            else
                m.LoginUsingPassCodeButton.setFocus(true)
            end if

            ' m.termConditionButton.setFocus(true)
            return true
        else if m.LoginUsingPassCodeButton.hasFocus()
            ? "termConditionButton"
            ' m.LoginUsingPassCodeButton.setFocus(true)
            m.qrLoginBtn.setFocus(true)
            return true
        else if m.qrLoginBtn.hasFocus()
            ? "qrLoginButton has focus"
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
            ' m.PasswordEditText.text = "123456789"
            m.UsernamEditText.setFocus(false)
            m.PasswordEditText.setFocus(true)
            onTextBoxActive("password")
            return true
        end if
    else

        if m.EnterPasscodeEditText.hasFocus()
            m.EnterPasscodeEditText.setFocus(false)
            m.SignInWithPasscodeButton.setFocus(true)
            onTextBoxActive("")
            return true
        else if m.logout.hasFocus()
            ? "focuse Change"; " to  Box "
            m.RoomListMarkuGrid.setFocus(false)
            m.roomSearchEditText.setFocus(true)
            m.roomSearchEditText.active = true
            m.KeyboardPoster.visible = true
            m.KeyboardInput.setFocus(true)
            m.TextField = "searchRoom"

            return true
        else if (m.KeyboardPoster.visible = true or m.KeyboardInput.hasFocus())
            ?"in keyboard close"
            m.KeyboardPoster.visible = false
            m.KeyboardInput.setFocus(false)
            m.roomSearchEditText.setFocus(true)
            ' m.roomSearchEditText.active = true
            return true
        else if m.roomSearchEditText.hasFocus() = false
            m.RoomListMarkuGrid.visible = true
            m.RoomListMarkuGrid.setFocus(true)
            m.roomSearchEditText.setFocus(false)
            m.roomSearchEditText.active = false
            return true
        end if

    end if

end function

function pressUp() as boolean

    if m.LoginView.visible = true
        if m.termConditionButton.hasFocus()
            ' m.SignInButton.setFocus(true)
            m.qrLoginBtn.setFocus(true)
            return true
        else if m.LoginUsingPassCodeButton.hasFocus()
            m.SignInButton.setFocus(true)
            ' m.LoginUsingPassCodeButton.setFocus(true)
            return true
        else if m.qrLoginBtn.hasFocus()
            m.LoginUsingPassCodeButton.setFocus(true)
            ' m.LoginUsingPassCodeButton.setFocus(true)
            return true
        else if m.registerNow.hasFocus() then

            ? "LoginUsingPassCodeButton"
            if (m.LoginUsingPassCodeButton.visible = false or m.qrLoginBtn.visible = false or m.termConditionButton.visible = false)
                m.SignInButton.setFocus(true)
            else
                m.registerNow.setFocus(false)
                m.qrLoginBtn.setFocus(true)
            end if

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
        else if m.TextField = "UsernamEditText" or m.TextField = "PasswordEditText" or m.TextField = "passcode"
            return keybordClose()
        end if
    else

        ?"in else "
        if m.SignInWithPasscodeButton.hasFocus()
            m.EnterPasscodeEditText.setFocus(true)
            onTextBoxActive("passcode")
            return true
        else if m.RoomListMarkuGrid.hasFocus() and m.LoginWithPassCodeView.visible = false
            ? "focuse Change"; " to search Box "
            m.RoomListMarkuGrid.setFocus(false)
            m.roomSearchEditText.setFocus(true)
            m.roomSearchEditText.active = true
            m.logout.setFocus(false)
            ' open keyboard
            m.KeyboardPoster.visible = true
            m.KeyboardInput.setFocus(true)
            m.TextField = "searchRoom"
            return true
        else if m.LoginWithPassCodeView.visible = true or (m.KeyboardPoster.visible = true or m.KeyboardInput.hasFocus())
            ?"in keyboard close"
            m.KeyboardPoster.visible = false
            m.KeyboardInput.setFocus(false)
            m.EnterPasscodeEditText.setFocus(true)
            ' m.TextField = "passcode"
            ' m.roomSearchEditText.active = true
            return true
        else if m.RoomListMarkuGrid.visible = true or (m.KeyboardPoster.visible = true or m.KeyboardInput.hasFocus())
            ?"in keyboard close"
            m.KeyboardPoster.visible = false
            m.KeyboardInput.setFocus(false)
            m.roomSearchEditText.setFocus(true)
            m.roomSearchEditText.active = false
            return true
        else if m.roomSearchEditText.hasFocus()
            ? "focuse Change"; " to  up press "
            ' m.RoomListMarkuGrid.setFocus(false)
            m.logout.setFocus(true)
            m.RoomListMarkuGrid.setFocus(false)
            m.roomSearchEditText.setFocus(false)
            m.roomSearchEditText.active = false

            return true

        else
            ?"in last else "
        end if

    end if


end function





function keybordClose() as boolean


    ? "keyboard close"
    m.KeyboardPoster.visible = false
    m.KeyboardInput.setFocus(false)
    m.UsernamEditText.active = false
    m.PasswordEditText.active = false
    m.EnterPasscodeEditText.active = false

    if m.TextField = "UsernamEditText"
        m.UsernamEditText.setFocus(true)
    else if m.TextField = "PasswordEditText"
        m.PasswordEditText.setFocus(true)
    else if m.TextField = "passcode"
        m.EnterPasscodeEditText.setFocus(true)
    end if
    m.TextField = ""

    return true
end function


' sub get_qr_code_for_login(url as string)
'     request = CreateObject("roUrlTransfer")
'     port = CreateObject("roMessagePort")
'     request.SetMessagePort(port)
'     request.SetUrl(url)
'     if (request.AsyncGetToString())
'         while (true)
'             msg = wait(2000, port)
'             if (type(msg) = "roUrlEvent")
'                 code = msg.GetResponseCode()
'                 if (code = 200)
'                     json = ParseJSON(msg.GetString())
'                     m.qrCodeImageUrl = json.msg
'                     m.qrCodeImageTocken = json.key
'                     m.qrCodeImagePster.uri = m.qrCodeImageUrl
'                     showspinner(false)
'                 end if
'             end if
'             if (msg = invalid)
'                 request.AsyncCancel()
'             end if
'         end while
'     end if
' end sub

' sub get_qr_code_for_login(url)
'     ?"loginWithPassCodeAPicall"
'     showspinner(true)
'     m.qrCodeForLogin = CreateObject("roSGNode", "qrCodeForLogin")

'     m.qrCodeForLogin.callType = "POST"
'     '   data =  "email="+email+"&password="+password+deviceid
'     ? "data: "; data
'     m.qrCodeForLogin.data = data
'     m.qrCodeForLogin.control = "RUN"
'     m.qrCodeForLogin.observeField("response", "LoginPasscodeResponse")
' end sub


sub check_for_scan_or_not()
    m.checkForBArcodeContinue = true

    m.check_for_bar_code = CreateObject("roSGNode", "CheckForBarcode")
    m.check_for_bar_code.callType = "POST"
    '   data =  "email="+email+"&password="+password+deviceid
    data = {}
    data["key"] = m.qrCodeImageKey
    m.check_for_bar_code.data = data
    m.check_for_bar_code.control = "RUN"
    m.check_for_bar_code.observeField("response", "check_for_bar_code")
end sub

sub check_for_bar_code(param as object)
    ? "qr_status " ; param.getData()
    if(m.checkForBArcodeContinue = true)
        if(param.getData().status = 1)
            m.checkForBArcodeContinue = false
            qr_login()
            ' move screen to login
        else if (param.getData().status = 2)
            m.checkForBArcodeContinue = false
            CallQrCodeFromNetwork()
        else
            check_for_scan_or_not()
        end if
    end if
end sub

function showPersonalDeviceNameScreen() as object
    if m.screen <> invalid then m.top.removeChild(m.screen)
    ' ShowScreen("HomeScreen")
    personal_device_name = m.top.createChild("personal_device_name")
    personal_device_name.setFocus(true)
    personal_device_name.observeField("ExitView", "openLoginScreenAccordingToMode")
    personal_device_name.observeField("showLoginView", "showLoginScreen")
    return personal_device_name
end function

sub qr_login()
    ?"qrLogin start ..."
    showspinner(true)
    m.qr_login = CreateObject("roSGNode", "QrLogin")
    m.qr_login.callType = "POST"
    '   data =  "email="+email+"&password="+password+deviceid
    deviceInfo = getDeviceInfo() ' GetDeviceUniqueId()
    data = {}
    data["key"] = m.qrCodeImageKey
    data["deviceid"] = deviceInfo.GetChannelClientId()

    m.qr_login.data = data
    m.qr_login.control = "RUN"
    m.qr_login.observeField("response", "qr_login_response")
end sub

sub qr_login_response(param as object)
    ? "LoginResponse :m.qr_login: "; m.qr_login.response
    showspinner(false)
    m.loginsuccess = false
    m.isSubscribe = true
    if m.qr_login.response <> invalid and m.qr_login.response.response_code = 404
        showDialog("Error", m.qr_loginre.response.message, true, "", "")
        m.needHelpText = "qrUI"
        m.isSubscribe = false
    else if m.qr_login.response <> invalid and m.qr_login.response.response_code = 401
        showDialog("Error", m.qr_login.response.message, true, "", "")
        setLogin("login")
        m.needHelpText = "qrUI"
        m.isSubscribe = false
    else if m.qr_login.response <> invalid and m.qr_login.response.response_code = 429 and m.qr_login.response.data = invalid
        ' m.ButtonLabelText.text = "Logout"
        ' m.LogoutCode = responseData.data
        m.needHelpText = "qrUI"
        m.logoutPassCode = false
        m.isSubscribe = false
        qr_login()
    else if m.qr_login.response <> invalid and m.qr_login.response.response_code = 429 and m.qr_login.response.data <> invalid
        ' m.ButtonLabelText.text = "Logout"
        m.LogoutCode = m.qr_login.response.data
        m.needHelpText = "qrUI"
        m.logoutPassCode = true
        m.isSubscribe = false
        closeDialog()
        ' showDialog("Error", "responseData.message", true, "", "")
        ' showDialog("Error", "Already logged in with this device, press 'OK' for logout and login again", true, "", "")
    else if m.qr_login.response <> invalid and m.qr_login.response.response_code = 479
        setDeviceMode("device")
        showspinner(false)
        m.Nodevice.setFocus(true)
        showDialogDevice("LoginError", true, false, m.qr_login.response.message)
        ConnectedDevices0 = m.qr_login.response.loggedindeviceinfolist.numofdevice
        ConnectedDevices = m.qr_login.response.loggedindeviceinfolist.data
        ?"Profile::responseData.message:";m.qr_login.response.loggedindeviceinfolist.data
        marupContent = createObject("roSGNode", "ContentNode")
        for each data in ConnectedDevices
            ?"data: "data
            ?"userData[data]: ";data["device_name"] ;data["device_type"] ;data["personaldevicename"]
            marupContent.createChild("ContentNode").addFields(data)
        end for

    else if m.qr_login.response.response_code = 200
        if m.qr_login.response.subscribed = true
            setAccountToken(m.qr_login.response.access_token)
            setPersonaldeviceid(m.qr_login.response.personaldeviceid)
            setDeviceType(m.qr_login.response.device_type)
            setUserType(m.qr_login.response.userrole)
            setPersonaldevicename_popup(m.qr_login.response.personaldevicename_popup)
            setHomeTimezone(m.qr_login.response.timezone)
            if m.qr_login.response.personaldevicename_popup = true
                ' ?"m.LoginTask.response.personaldevicename_popup: "m.LoginTask.response.personaldevicename_popup
                ' show personal device name
                RegWrite("personalDevice", "personalDevice")
                setPersonaldevicename_popup(true)
                setUserHomeScreenMode("personalDeviceName")
                setUserPersonalScreenMode("popup")
                m.top.showPersonalDeviceName = true
            else if m.qr_login.response.userrole = "tieruser"

                ?"getDeviceMode()"getDeviceMode()
                if getDeviceMode() = "device"
                    setUserHomeScreenMode("Home")
                    m.loginsuccess = true
                    buttons = []
                    buttons.Push("OK")
                    m.top.signalBeacon("AppDialogComplete")
                else
                    setUserHomeScreenMode("Room")
                    setRoomSelectionFlag(false)
                    m.roomContent = createObject("roSGNode", "ContentNode")
                    ' ?"responseData.rooms[0]:";m.LoginTask.response.rooms[0]
                    if m.qr_login.response.rooms[0] <> invalid

                        ' stringid = (m.LoginTask.response.rooms[0].user_id).toStr()
                        ' setUserCredential(stringid)
                        ?"responseData.rooms[0]:";m.qr_login.response.rooms[0]
                        for each item in m.qr_login.response.rooms

                            roomDetail = m.roomContent.createChild("ContentNode")
                            ' itemKey = {}
                            roomDetail.title = item.hotel_room
                            roomDetail.id = item.id
                            roomDetail.HDSMALLICONURL = "pkg:/images/profile/room.png"
                            ' roomDetail.addFields(itemKey)
                        end for
                        m.needHelpText = "roomSelectUI"
                        m.RoomListMarkuGrid.content = m.roomContent
                        m.showRoomView = true
                        m.loginsuccess = false
                        closeDialog()
                        ' showDialog(m.LoginTask.response.message, "Please select a room to start streaming", false, "", "")
                        return
                    else
                        setUserHomeScreenMode("Home")
                        m.loginsuccess = true
                        buttons = []
                        buttons.Push("OK")
                        m.top.signalBeacon("AppDialogComplete")
                    end if
                end if



                ' showDialog(m.qr_login.response.message, "Please select a room to start streaming", false, "", "")
                ' return
            else
                setUserHomeScreenMode("Home")
                m.loginsuccess = true
                buttons = []
                buttons.Push("OK")
                m.top.signalBeacon("AppDialogComplete")
            end if

            if m.qr_login.response.termandcondition = true
                showdialogTermCondition(m.qr_login.response.message, m.qr_login.response.connected_device, false, "", "dialogButtonResponse")
            else
                closeDialog()
            end if
        else
            m.needHelpText = "qrUI"
            m.logoutPassCode = true
            m.isSubscribe = false
            showDialog("Subscription Error", m.qr_login.response.subscribedmsgis, true, "", "")
        end if

    else
        buttons = []
        buttons.Push("OK")
        '        buttons.Push("Cancel")
        showDialog("Login Error", m.qr_login.response.message, true, buttons, "")
    end if
end sub
