sub init()

  m.ProfileCategory = m.top.findNode("ProfileCategory")
  m.ProfileCategory_unfocus = m.top.findNode("ProfileCategory_unfocus")


  deleteRegister()

  '  m.Dialog = m.top.findNode("Dialog")
  ' m.ProfileSettingScreen = CreateObject("roSGNode", "ProfileSettingScreen")

  'dialog
  '  m.dialogbackgndUri = m.top.findNode("dialogbackgndUri")
  ' m.dialogbackgndUri.uri = "pkg:/images/home/borderwhite3.png"

  m.registerDeviceView = m.top.findNode("registerDeviceView")
  m.focus_live = m.top.findNode("focus_live")
  m.device_connected = m.top.findNode("device_connected")
  m.logoutView = m.top.findNode("logoutView")
  m.profileView = m.top.findNode("profileView")
  m.NoButton = m.top.findNode("NoButton")
  m.YesButton = m.top.findNode("YesButton")
  m.logoutText = m.top.findNode("logoutText")
  m.Lbaelok = m.top.findNode("Lbaelok")
  m.Lbaeldelete = m.top.findNode("Lbaeldelete")
  m.labelYes = m.top.findNode("labelYes")
  m.labelNo = m.top.findNode("labelNo")
  m.Settings = m.top.findNode("Settings")
  m.pressbackLabel = m.top.findNode("pressbackLabel")

  m.pressbackLabel.font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.Settings.font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
  '  m.logoutView.height =724
  '  m.logoutView.width =1223
  '  m.logoutView.translation =[(1920-1223)/2,(1080-724)/2]
  m.deleteAccountView = m.top.findNode("deleteAccountView")
  m.NoDeleteButton = m.top.findNode("NoDeleteButton")
  m.YesDeleteButton = m.top.findNode("YesDeleteButton")
  m.DeleteAccountText = m.top.findNode("DeleteAccountText")

  m.remenberCheckList = m.top.findNode("rememberCheckList")
  m.simple3 = m.top.findNode("simple3")
  m.remenberCheckList.translation = [650, 660]
  ' m.remenberCheckList.itemSize = [250, 10]
  m.remenberCheckList.checkedIconUri = "pkg:/images/toggleon.png"
  m.remenberCheckList.uncheckedIconUri = "pkg:/images/toggleoff1.png"
  m.remenberCheckList.focusedCheckedIconUri = "pkg:/images/toggleon.png"
  m.remenberCheckList.focusedUncheckedIconUri = "pkg:/images/toggleoff1.png"
  m.remenberCheckList.focusBitmapUri = ""
  m.remenberCheckList.focusFootprintBitmapUri = "pkg:/images/transparentimg.png"
  m.remenberCheckList.color = "#ffffff"
  m.remenberCheckList.focusedColor = "#0db5ba"
  m.simple3.Font = getFont(32, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
  m.simple3.focusedFont = getFont(34, "pkg:/fonts/Helvetica-Neue-Bold_22503.ttf")

  checklistNode = CreateObject("roSGNode", "ContentNode")
  contentNode = checklistNode.createChild("ContentNode")
  ' contentNode.Title = "Help View"
  m.remenberCheckList.content = checklistNode
  m.remenberCheckList.ObserveField("checkedState", "checkUpdate")

  font = getFont(36, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
  m.Lbaelok.font = font
  m.labelYes.font = font
  m.labelNo.font = font
  m.Lbaeldelete.font = font
  m.logoutText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.DeleteAccountText.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
  textTranslate()

  '  m.ProfileTask = CreateObject("roSGNode", "TrackerTask")
  '  m.ProfileTask.control="init"
  m.logout = false


  'Search Keyboard
  m.KeyboardPosterDevice = m.top.findNode("KeyboardPosterDevice")
  m.KeyboardInputDevice = m.top.findNode("KeyboardInputDevice")
  m.KeyboardInputDevice.observeField("text", "FieldText")


  '  dialog error
  m.ErrorDialogView = m.top.findNode("ErrorDialogView")
  m.buttonClick = m.top.findNode("buttonClick")
  m.buttonClick.horizAlign = "center"
  m.buttonClick.vertAlign = "center"
  m.messageBody = m.top.findNode("messageBody")

  m.YesButton.observeField("buttonSelected", "logoutApiCall")
  m.NoButton.observeField("buttonSelected", "closeDialog")
  'loader

  m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
  m.LoaderBackground = m.top.findNode("LoaderBackground")
  m.LoginBusySpinner.observeField("visible", "showspinner")
  m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
  m.LoginBusySpinner.visible = false

  m.ok = m.top.findNode("ok")
  m.ok.observeField("buttonSelected", "closeDialog")



  m.dialogOptions = m.top.findNode("dialogOptions")
  m.delete_account = m.top.findNode("delete_account")
  m.delete_account.observeField("buttonSelected", "deleteAccount")
  m.YesDeleteButton.observeField("buttonSelected", "deleteAccountApiCall")
  m.NoDeleteButton.observeField("buttonSelected", "closeDialogDelete")
  m.ok = m.top.findNode("ok")
  m.ok.observeField("buttonSelected", "closeDialog")

  m.dialogOptions = m.top.findNode("dialogOptions")
  ' m.registredDevicesMarkup = m.top.findNode("registredDevicesMarkup")
  m.deviceSearchEditText = m.top.findNode("deviceSearchEditText")
  m.registredDevicesMarkup = m.top.findNode("registredDevicesMarkup")

  m.removeText = m.top.findNode("removeText")
  m.removeView = m.top.findNode("removeView")
  m.Noremove = m.top.findNode("Noremove")
  m.Yesremove = m.top.findNode("Yesremove")
  m.Noremove.observeField("buttonSelected", "closeDialogRemove")

  ' m.global.RSG_analytics.trackEvent = {
  '   google: {
  '       ec: "profile_screen",
  '       ea: "launch_complete"
  '   }
  ' }


  ' initMrkupWithData()


  m.top.observeField("focusedChild", "OnFocusChildChange")
  m.ProfileCategory.setFocus(true)
  initSupportDialog()
  privicyPolicy()
  m.DialogButton = invalid


  ' m.qrlogtask = CreateObject("roSGNode", "qrlogtask")



  ' m.qrlogtask.callType = "GET"
  ' '   data =  "email="+email+"&password="+password+deviceid
  ' ' ? "data: "; data
  ' ' m.qrlogintask.data = data
  ' m.qrlogtask.control = "RUN"
  ' m.qrlogtask.observeField("response", "qrLogResponse")
  qrLogResponse()
  ?"userid: "getUserCredential()

end sub

sub qrLogResponse()
  ' ? "LoginResponse :m.qrLoginTask: "; m.qrlogtask.response

  ?"ProfileSetting:MarkuGrid:init()"
  marupContent = createObject("roSGNode", "ContentNode")

  data = marupContent.createChild("ContentNode")
  data.text = "Account"
  data.SDPosterUrl = "pkg:/images/profile/profileicon.png"
  ' ?"testing: "m.top.removeChild(data)


  data = marupContent.createChild("ContentNode")
  data.text = "Registered Devices"
  ' data2.id = "foo"
  data.SDPosterUrl = "pkg:/images/profile/devicesicon.png"
  ?"hello2"




  data = marupContent.createChild("ContentNode")
  data.text = "Support"
  data.SDPosterUrl = "pkg:/images/profile/supporticon.png"

  data = marupContent.createChild("ContentNode")
  data.text = "Privacy & Policy"
  data.SDPosterUrl = "pkg:/images/profile/privicypolicyicon.png"


  data = marupContent.createChild("ContentNode")
  data.text = "Sign out"
  data.SDPosterUrl = "pkg:/images/profile/logouticon.png"


  m.ProfileCategory.content = marupContent
  ' m.ProfileCategory.translation = [720,400]

  m.ProfileCategory.observeField("itemSelected", "initDialogMrkupWithData")

  m.ProfileCategory_unfocus.content = marupContent
  ' m.ProfileCategory.translation = [720,400]

  m.ProfileCategory_unfocus.observeField("itemSelected", "initDialogMrkupWithData")



end sub

sub checkUpdate(event as object)
  ? "checkUpdate: "; event.getData()[0]
  m.rememberFlag = event.getData()[0]
  if event.getData()[0] = true
    setHelpMode("true")
    deleteCheckMode()
  else
    setHelpMode("false")
  end if
end sub

sub textTranslate()
  m.logoutText.text = tr("Are you sure you want to logout from this device?")
  m.DeleteAccountText.text = tr("Are you sure you want to delete this account?")
end sub

sub deleteAccount()
  ?"ProfileSetting:: delete Account"
  ?"ProfileSetting::else close dialog"
  m.delete_account.setFocus(false)
  showDialog("", false, true)
  m.buttonClick.setFocus(false)
  m.ErrorDialogView.visible = false
  showDialog("Delete Account", true, false)
  m.NoDeleteButton.setFocus(true)
end sub

sub closeDialogDelete()
  ?"ProfileSetting:: close dialog"
  ?"ProfileSetting::else close dialog"
  m.ok.setFocus(false)
  m.NoButton.setFocus(false)
  m.NoDeleteButton.setFocus(false)

  showDialog("", false, true)

  m.buttonClick.setFocus(false)
  m.ErrorDialogView.visible = false

end sub

sub deleteAccountApiCall()
  ?"ProfileSetting::deleteApiCall"
  m.YesDeleteButton.setFocus(false)
  showDialog("", false, false)
  m.LoginBusySpinner.visible = true
  m.DeleteAccount = CreateObject("roSGNode", "DeleteAccount")
  m.DeleteAccount.callType = "DELETE"
  m.DeleteAccount.control = "RUN"
  m.DeleteAccount.observeField("response", "DeleteResponse")
  setLogin("login")

end sub



sub DeleteResponse()
  ? "openLoginView"

  m.screen = showLogin1Screen()
end sub

sub logoutApiCall()
  setLogoutMode("logout")
  ?"ProfileSetting::logoutApiCall"
  m.YesButton.setFocus(false)
  showDialog("", false, false)
  ApiCallFor("Sign out", "POST")
end sub


sub closeScreen()
  ?"close for -> login screen ";
  m.logout = false
  m.buttonClick.setFocus(false)
  m.ErrorDialogView.visible = false

  m.top.logout = true

end sub
sub closeDialog()
  ?"ProfileSetting:: close dialog"
  ?"ProfileSetting::else close dialog"
  m.ok.setFocus(false)
  m.NoButton.setFocus(false)

  showDialog("", false, true)

  m.buttonClick.setFocus(false)
  m.ErrorDialogView.visible = false


end sub

' sub initMrkupWithData()
'   ?"ProfileSetting:MarkuGrid:init()"
'   marupContent = createObject("roSGNode", "ContentNode")

'   data = marupContent.createChild("ContentNode")
'   data.text = "Account"
'   data.SDPosterUrl = "pkg:/images/profile/profileicon.png"
'   ' ?"testing: "m.top.removeChild(data)

'   data = marupContent.createChild("ContentNode")
'   data.text = "Registered Devices"
'   data.id="foo"
'   data.SDPosterUrl = "pkg:/images/profile/devicesicon.png"
'   ?"hello2"
'   ' qrLogResponse()
'   marupContent.removeChild(data)

'   data = marupContent.createChild("ContentNode")
'   data.text = "Support"
'   data.SDPosterUrl = "pkg:/images/profile/supporticon.png"

'   data = marupContent.createChild("ContentNode")
'   data.text = "Privacy & Policy"
'   data.SDPosterUrl = "pkg:/images/profile/privicypolicyicon.png"

'   data = marupContent.createChild("ContentNode")
'   data.text = "Sign out"
'   data.SDPosterUrl = "pkg:/images/profile/logouticon.png"

'   m.ProfileCategory.content = marupContent

'   m.ProfileCategory.observeField("itemSelected", "initDialogMrkupWithData")

' end sub

sub FieldText(event as object)
  ? "event: "; event.getData()
  if m.TextField = "searchDevice"
    ? "event: "; event.getData()
    m.deviceSearchEditText.text = event.getData()
    m.deviceSearchEditText.active = true
    m.deviceSearchEditText.cursorPosition = m.KeyboardInputDevice.textEditBox.cursorPosition + 1
    SearchDevice(event.getData())
  end if
end sub

sub SearchDevice(searchName as string)


  ? "responseData profile: ";m.responseData.data
  ' showDialogDevice("LoginError", true, false)
  ConnectedDevices0 = m.responseData.numofdevice
  ConnectedDevices = m.responseData.data

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

end sub


sub initDialogMrkupWithData(event as object)
  ?"ProfileSetting::MarkuGrid:init(): event pos: ";event.getData()
  ?"m.ProfileCategory.content: "event.getRoSGNode().content.getChild(event.getData()).text
  m.settingsBox = event.getRoSGNode().content.getChild(event.getData()).text
  m.dialogOptions.visible = false
  m.remenberCheckList.visible = false
  m.registredDevicesMarkup.visible = false
  if event.getData() = 0
    m.ProfileCategory.setFocus(false)
    ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

  else if event.getData() = 1
    ' m.ProfileCategory.setFocus(false)
    ' m.NoButton.setFocus(true)
    ' showDialog("Sign out", true, false)

    m.ProfileCategory.setFocus(false)
    ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
  else if event.getData() = 2

    if m.settingsBox = "Support"
      m.ProfileCategory.setFocus(false)
      ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
    else if m.settingsBox = "Sign out"
      m.ProfileCategory.setFocus(false)
      m.NoButton.setFocus(true)
      showDialog("Sign out", true, false)
    end if


    ' m.ProfileCategory.setFocus(false)
    ' ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

  else if event.getData() = 3
    m.ProfileCategory.setFocus(false)
    ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")

    '    privicyPolicy()
    '    m.ProfileTask.setField("callType","GET")
    '    m.ProfileTask.setField("callFor","Privacy & Policy")

  else if event.getData() = 4 'Logout api call
    m.ProfileCategory.setFocus(false)
    m.NoButton.setFocus(true)
    showDialog("Sign out", true, false)


    ' m.ProfileCategory.setFocus(false)
    ' '     m.top.appendChild(m.DialogNode)
    ' '     m.OkButton.setFocus(true)
    ' ApiCallFor(event.getRoSGNode().content.getChild(event.getData()).text, "GET")
  end if

end sub

sub ApiCallFor(callto as string, callType as string)
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


function errorDialogShow(event as object)

  m.logout = true

  if event.getData().responseCode = 429 or event.getData().responseCode = 404
    ' event.getData().responseCode = 401 or
    ' m.DialogButton = popupDialog(m.top, "Error ", event.getData().Failure)
    m.DialogButton = popupDialog(m.top, "Error ", "Your session has expired. You have to log in again to proceed further.")
    m.DialogButton.observeField("buttonSelected", "showLoginScreen")
    m.DialogButton.setFocus(true)
    setAccountToken("")
    setThePersonalDeviceName("")
    setPersonaldeviceid("")
  end if
  ' m.top.logout = true
  ' else
  '     m.DialogButton = popupDialog(m.top , "Error " ,event.getData().Failure)
  '     m.DialogButton.observeField("buttonSelected","closeScreen")
  '     m.DialogButton.setFocus(true)
  ' end if

end function

function showLogin1Screen() as object
  ? "showLoginScreen"
  if m.screen <> invalid then m.top.removeChild(m.screen)
  setProfilescreen("login_screen")
  LoginScreen1 = m.top.createChild("LoginScreen")
  LoginScreen1.observeField("dialog", "showDialog")
  LoginScreen1.observeField("ExitView", "openHomeView")
  LoginScreen1.observeField("showPersonalDeviceName", "showPersonalDeviceNameScreen")
  LoginScreen1.setFocus(true)
  return LoginScreen1
end function

function showLoginScreen() as object
  ? "showLoginScreen"
  setAccountToken("")
  setThePersonalDeviceName("")
  setPersonaldeviceid("")
  m.logout = true
  m.top.logout = true
  Screen = m.top.createChild("LoginScreen")
  Screen.setFocus(true)
end function

function loginScreen()

  m.top.ExitView = true

end function

function closedilogScreen()
  ?"closeScreen"
  ' m.top.getScene().exitApp = "exitApp"
  ' closeErrorDialogShow(m.top)

end function

function RemoveStringBeforeSlash(inputString as string) as string
  stringArray = inputString.Split("/")
  if stringArray.Count() > 1 then
    standard = stringArray[1].Split(" ") ' Get the portion after the "/"
    if standard.Count() > 1 then
      return standard[0]
    end if
    return stringArray[1]
  end if
  return inputString ' Return the original string if no "/" is found
end function

sub responseAPI(event as object)
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
        marupContent.createChild("ContentNode").addFields({ "Version:": "2.14" })
      end if

      if userData.created <> invalid
        date = CreateObject("roDateTime")
        date.FromISO8601String(userData.created.ToStr())
        ' marupContent.createChild("ContentNode").addFields({ "Build Date:": date.AsDateString("short-date") })
        marupContent.createChild("ContentNode").addFields({ "Build Date:": "Tue, Aug 1 2023" })
      end if
      marupContent.createChild("ContentNode").addFields({ "Home Timezone :": getHomeTimezone() })


      di = CreateObject("roDeviceInfo")
      ?"getCurrentTimezone() "di.getTimeZone()
      marupContent.createChild("ContentNode").addFields({ "Current Timezone :": RemoveStringBeforeSlash(di.getTimeZone()) + " Standard Time  " })

      m.dialogOptions.itemSize = [900, 52]

      '         marupContent.createChild("ContentNode").addFields(assoArray)
      m.remenberCheckList.visible = true
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
      m.ProfileCategory.setFocus(false)

      showLogin1Screen()
    else if m.ProfileTask.callto = "Registered Devices"
      ' Here we have to show the list of the device. --------------------------------------------------------------------------------------------------------------------------
      setRegister("register")
      ConnectedDevices0 = m.responseData.numofdevice
      ConnectedDevices = m.responseData.data
      ?"Profile::responseData.message:";ConnectedDevices0
      ?"getdata mode "getDataMode()
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
      RegWrite("back", "back")
      ?"Profile::responseData.message:";m.responseData.data
      ?"Profile::responseData.message:";m.responseData.message
      '            "message": "For help and support call the numbers below"

      m.TextLabel.text = "Our team is available 24*7. We'll do our best to respond to your inquiries as soon as possible."
      m.TextLabel1.text = "Need help or have a question? Our support team is here to assist you. You can reach us at the following:"
      '         contactText = ""
      '         for each contacNo in responseData.data
      '              contactText = contactText +contacNo+chr(10)
      '         end for

      if m.responseData.data.email <> invalid
        m.contactNumber.text = "contact1@streamlytv.com"
        m.contactNumbers.text = m.responseData.data.num1
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

sub responseAPI2(event as object)
  setSessionDeviceLogout("")
  ApiCallFor("Registered Devices", "GET")
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

sub closeOkDialog()
  ?"closeOkDialog"
  ' if m.HelpButton.hasFocus() and m.top.isInFocusChain()

  '     m.HelpButton.setFocus(false)
  m.top.removeChild(m.SupportDialog)
  m.ProfileCategory.setFocus(true)

  ' else

  ' end if
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




sub initSupportDialog()

  m.SupportDialog = createObject("roSGNode", "Poster")
  '     m.SupportDialog.visible = false
  m.SupportDialog.width = 1320
  m.SupportDialog.height = 750
  m.SupportDialog.uri = "pkg:/images/homeRound.png"
  m.SupportDialog.translation = [(1920 - m.SupportDialog.boundingRect().width) / 2, (1080 - m.SupportDialog.boundingRect().height) / 2]

  m.SupportDialogCross = m.SupportDialog.createChild("Poster")
  '     m.SupportDialog.visible = false
  m.SupportDialogCross.width = 80
  m.SupportDialogCross.height = 60
  m.SupportDialogCross.uri = "pkg:/images/cross.png"
  m.SupportDialogCross.translation = [1180, 30]

  m.SupportDialogCircle = m.SupportDialog.createChild("Poster")
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

  m.SupportDialogEmail = m.SupportDialog.createChild("Poster")
  '     m.SupportDialog.visible = false
  m.SupportDialogEmail.width = 380
  m.SupportDialogEmail.height = 160
  m.SupportDialogEmail.uri = "pkg:/images/profile/markupitemcomponentBackground.png"
  m.SupportDialogEmail.translation = [220, 550]



  m.SupportDialogMail = m.SupportDialog.createChild("Poster")
  '     m.SupportDialog.visible = false
  m.SupportDialogMail.width = 50
  m.SupportDialogMail.height = 60
  m.SupportDialogMail.uri = "pkg:/images/mail1.png"
  m.SupportDialogMail.blendColor = "#FFFFFF"
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

  m.SupportDialogNumber = m.SupportDialog.createChild("Poster")
  '     m.SupportDialog.visible = false
  m.SupportDialogNumber.width = 380
  m.SupportDialogNumber.height = 160
  m.SupportDialogNumber.uri = "pkg:/images/profile/markupitemcomponentBackground.png"

  m.SupportDialogNumber.translation = [710, 550]



  m.SupportDialogPhone = m.SupportDialog.createChild("Poster")
  '     m.SupportDialog.visible = false
  m.SupportDialogPhone.width = 50
  m.SupportDialogPhone.height = 50
  m.SupportDialogPhone.uri = "pkg:/images/mobile1.png"
  m.SupportDialogNumber.blendColor = "#FFFFFF"
  m.SupportDialogPhone.translation = [875, 575]

  ' m.SupportDialogNumber = m.SupportDialog.createChild("Poster")
  ' '     m.SupportDialog.visible = false
  ' m.SupportDialogNumber.width = 320
  ' m.SupportDialogNumber.height = 250
  ' m.SupportDialogNumber.uri = "pkg:/images/profile/dialogBackground.png"
  ' m.SupportDialogNumber.translation = [1000 , 600]

  m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
  m.LayoutGroup.translation = [100, 400]
  m.LayoutGroup.layoutDirection = "vert"
  m.LayoutGroup.itemSpacings = [10]

  m.LayoutGroup1 = m.SupportDialog.createChild("LayoutGroup")
  m.LayoutGroup1.translation = [30, 460]
  m.LayoutGroup1.layoutDirection = "vert"
  m.LayoutGroup1.itemSpacings = [10]

  m.LayoutGroupEmail = m.SupportDialog.createChild("LayoutGroup")
  m.LayoutGroupEmail.translation = [-60, 620]
  m.LayoutGroupEmail.layoutDirection = "vert"

  m.LayoutGroupEmail.itemSpacings = [10]

  m.LayoutGroupNumber = m.SupportDialog.createChild("LayoutGroup")
  m.LayoutGroupNumber.translation = [330, 620]
  m.LayoutGroupNumber.layoutDirection = "vert"
  m.LayoutGroupNumber.itemSpacings = [10]

  '     m.LayoutGroup .horizAlignment ="vert"
  '      m.LayoutGroup .vertAlignment ="center"
  m.TextLabel = m.LayoutGroup.createChild("Label")
  '     m.TextLabel.translation = [0, 87]
  m.TextLabel.width = 2023
  m.TextLabel.height = 0
  ' m.TextLabel.horizAlign = "Center"
  ' m.TextLabel.vertAlign = "Center"
  m.TextLabel.text = "For Help and Support You can mail us on below Contact."
  m.TextLabel.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")

  m.TextLabel1 = m.LayoutGroup1.createChild("Label")
  '     m.TextLabel.translation = [0, 87]
  m.TextLabel1.width = 2023
  m.TextLabel1.height = 0
  ' m.TextLabel.horizAlign = "Center"
  ' m.TextLabel.vertAlign = "Center"
  m.TextLabel1.text = "For Help and Support You can mail us on below Contact."
  m.TextLabel1.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")



  m.contactNumber = m.LayoutGroupEmail.createChild("ScrollableText")
  m.contactNumber.width = 1020
  m.contactNumber.height = 100
  m.contactNumber.color = "#FFFFFF"

  ' m.contactNumber.translation = [0, 350]
  m.contactNumber.font = getFont(26, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.contactNumber.horizAlign = "Center"
  m.contactNumber.vertAlign = "Center"



  m.contactNumbers = m.LayoutGroupNumber.createChild("ScrollableText")
  m.contactNumbers.width = 1220
  m.contactNumbers.height = 100
  m.contactNumbers.color = "#FFFFFF"

  ' m.contactNumber.translation = [0, 350]
  m.contactNumbers.font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
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

' sub initSupportDialog()

'   m.SupportDialog = createObject("roSGNode", "Poster")
'   '     m.SupportDialog.visible = false
'   m.SupportDialog.width = 1920
'   m.SupportDialog.height = 1200
'   m.SupportDialog.uri = "pkg:/images/profile/profile_dialog.png"
'   m.SupportDialog.translation = [(1920 - m.SupportDialog.boundingRect().width) / 2, (1080 - m.SupportDialog.boundingRect().height) / 2]

'   m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
'   m.LayoutGroup.translation = [50, 80]
'   m.LayoutGroup.layoutDirection = "vert"
'   m.LayoutGroup.itemSpacings = [10]
'   '     m.LayoutGroup .horizAlignment ="vert"
'   '      m.LayoutGroup .vertAlignment ="center"
'   m.TextLabel = m.LayoutGroup.createChild("Label")
'   '     m.TextLabel.translation = [0, 87]
'   m.TextLabel.width = 1023
'   m.TextLabel.height = 0
'   m.TextLabel.horizAlign = "Center"
'   m.TextLabel.vertAlign = "Center"
'   m.TextLabel.text = "For Help and Support You can mail us on below Contact."
'   m.TextLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")

'   m.contactNumber = m.LayoutGroup.createChild("ScrollableText")
'   m.contactNumber.width = 1223
'   m.contactNumber.height = 80
'   '    m.contactNumber.translation = [0, 150]
'   m.contactNumber.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
'   m.contactNumber.horizAlign = "Center"
'   m.contactNumber.vertAlign = "Center"
'   '    m.contactNumber.text ="Shahjad.305@gmail.com"
'   '    m.contactNumber.translation = [500,0]


'   m.HelpButton = m.SupportDialog.createChild("Button")
'   m.HelpButton.observeField("buttonSelected", "closeOkDialog")

'   '                 m.OkButton.translation= [466,429]
'   m.HelpButton.minWidth = "280"
'   m.HelpButton.maxWidth = "280"
'   m.HelpButton.focusedTextColor = "#FFFFFF"
'   m.HelpButton.height = "84"
'   m.HelpButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
'   m.HelpButton.showFocusFootprint = "true"
'   m.HelpButton.iconUri = ""
'   m.HelpButton.focusedIconUri = ""
'   okLabel = m.HelpButton.createChild("Label")
'   okLabel.horizAlign = "center"
'   okLabel.height = "84"
'   okLabel.width = "280"
'   okLabel.vertAlign = "center"
'   okLabel.text = "OK"
'   font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
'   okLabel.font = font
'   m.HelpButton.translation = [(1223 - m.HelpButton.boundingRect().width) / 2, 250]
' end sub


sub showDialog(dialogView as string, show as boolean, focus as boolean)
  ?"ProfileSetting::showDialog"
  m.logoutView.visible = false
  m.profileView.visible = false
  m.deleteAccountView.visible = false
  m.registerDeviceView.visible = false

  if show
    if dialogView = "Sign out"

      m.logoutView.width = 1223
      m.logoutView.height = 488
      m.logoutView.visible = show
      ' m.Dialog.visible = show
      m.logoutView.translation = [(1920 - m.logoutView.boundingRect().width) / 2, (1080 - m.logoutView.boundingRect().height) / 2]
    else if dialogView = "Delete Account"

      m.deleteAccountView.width = 1223
      m.deleteAccountView.height = 488
      m.deleteAccountView.visible = show
      ' m.Dialog.visible = show
      m.deleteAccountView.translation = [(1920 - m.deleteAccountView.boundingRect().width) / 2, (1080 - m.deleteAccountView.boundingRect().height) / 2]
    else if dialogView = "Account"

      m.profileView.width = 1223
      m.profileView.height = 900
      m.profileView.visible = show
      ' m.profileView.translation = [(1920-1223)/2,(1080-640)/2]
      m.profileView.translation = [(2280 - m.profileView.boundingRect().width) / 2, (1080 - m.profileView.boundingRect().height) / 2]

      ' m.Dialog.visible = show
      m.ok.translation = [640, 750]
      m.ok.setFocus(true)

    else if dialogView = "Registered Devices"

      m.registerDeviceView.width = 1920
      m.registerDeviceView.height = 1080

      m.registerDeviceView.visible = show
      'm.profileView.translation = [(1920 - m.profileView.boundingRect().width) / 2, (1080 - m.profileView.boundingRect().height) / 2]
      ' m.Dialog.visible = show
      m.registredDevicesMarkup.setFocus(true)
      ' ragisterMargkupListTranslationX = (m.profileView.width - 1150) / 2
      ' m.registredDevicesMarkup.translation = [ragisterMargkupListTranslationX, 10]
      ' m.ok.translation = [471, 590]
    else if dialogView = ""

    end if
  else
    ' m.Dialog.visible = show
    m.ProfileCategory.setFocus(focus)
  end if

end sub




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

sub closePrivicyPolicy()
  ?"closePrivicyPolicy"
  m.ScrolableText.setFocus(false)
  m.OkButton1.setFocus(false)
  m.top.removeChild(m.DialogNodePoster)
  m.ProfileCategory.setFocus(true)

end sub

sub initDialogDevice(event as object)
  sessionId = event.getRoSGNode().content.getChild(event.getData()).session_id
  setSessionDeviceLogout(sessionId)
  m.registredDevicesMarkup.setFocus(false)
  m.YesRemove.observeField("buttonSelected", "logOutRegDevice")
  ?"Device: event pos device: ";event.getData()
  ?"Device.content: "event.getRoSGNode().content.getChild(event.getData()).session_id
  if getDataMode() <> event.getRoSGNode().content.getChild(event.getData()).session_id
    showDialogRemove("Registered Devices", true, false)
  end if
  m.Noremove.setFocus(true)
end sub

sub logOutRegDevice()
  m.deviceSearchEditText.text = ""
  m.registredDevicesMarkup.setFocus(false)
  showDialogRemove("", false, false)
  showDialog("", false, false)
  m.LoginBusySpinner.visible = true
  sessionId = getSessionDeviceLogout()
  m.DeviceLogout = CreateObject("roSGNode", "DeviceLogout")

  m.DeviceLogout.callType = "POST"
  data = {}
  data["id"] = sessionId
  '   data =  "email="+email+"&password="+password+deviceid
  ? "device: "; data
  m.DeviceLogout.data = data
  m.DeviceLogout.observeField("response", "responseAPI2")
  m.DeviceLogout.control = "RUN"

  'm.settingsBox = event.getRoSGNode().content.getChild(event.getData()).text
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

sub closeDialogRemove()
  ?"ProfileSetting:: close dialog"
  ?"ProfileSetting::else close dialog"
  ' m.ok.setFocus(false)
  setSessionDeviceLogout("")
  m.Noremove.setFocus(false)

  showDialogRemove("", false, true)
  ' m.device_name_edit_text.setFocus(true)
  ' m.device_name_edit_text.active = true
  ' m.buttonClick.setFocus(false)
  ' m.ErrorDialogView.visible = false
  m.registredDevicesMarkup.setFocus(true)
  ' m.RoomListMarkuGrid.setFocus(true)

end sub

function onKeyEvent(key as string, press as boolean) as boolean
  ?"Profile::key: ";key
  ?"Profile::press: ";press
  result = false
  if press

    if m.ScrolableText.hasFocus() and (key = "down" or key = "OK")
      m.OkButton1.setFocus(true)
      return true
      ' else if m.Ok.hasFocus() and key = "up" and m.ProfileTask.callto = "Registered Devices"
      '   ?"Ragister Device Focuse UP"
      '   m.registredDevicesMarkup.setFocus(true)
      '   m.Ok.setFocus(false)
      '   return true
      ' else if m.registredDevicesMarkup.hasFocus() and key = "down" and m.ProfileTask.callto = "Registered Devices"
      '   ?""
      '   m.registredDevicesMarkup.setFocus(false)
      '   m.Ok.setFocus(true)
      '   return true
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
    else if (m.ProfileCategory.hasFocus() and key = "up")
      setOpacity("focus")
      ? "m.ProfileCategory.hasFocus() and key = "
      m.ProfileCategory.setFocus(false)
      m.ProfileCategory.visible = false
      m.ProfileCategory_unfocus.visible = true
      m.focus_live.setFocus(true)
      m.focus_live.visible = true
      ? "m.focus_live.visible " m.focus_live.visible
      ? "m.ProfileCategory.hasFocus() " m.ProfileCategory.hasFocus()
      return true
    else if (m.focus_live.hasFocus() and key = "down")
      ? "m.focus_live.visible and key = "
      m.ProfileCategory.setFocus(true)
      m.ProfileCategory.visible = true
      m.ProfileCategory_unfocus.visible = false
      m.focus_live.setFocus(false)
      m.focus_live.visible = false
      return true
    
    else if (m.focus_live.hasFocus() and key = "OK")
      deleteSettingMode()
        m.top.exitView = true
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
    else if m.registredDevicesMarkup.hasFocus() and key = "back"
      ?""
      m.deviceSearchEditText.text = ""
      showDialog("", false, true)
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

      '    m.OkButton
      '
      '  else if  (m.HelpButton.hasFocus()  and key="back" )

      '        closeOkDialog()

      '   return true

    else if (m.DialogButton <> invalid and m.DialogButton.hasFocus() and m.logout = true and key = "back")


      ' ShowScreen(CreateObject("roSgNode","LoginScreen"))
      closeScreen()
      '  m.buttonClick.setFocus(false)
      '  m.ErrorDialogView.visible =false
      '  m.top.logout = true
      '  m.logout = true

      return true
    else if m.YesButton.hasFocus() and key = "left"
      m.YesButton.setFocus(false)
      m.NoButton.setFocus(true)
      return true
    else if m.NoButton.hasFocus() and key = "right"
      m.NoButton.setFocus(false)
      m.YesButton.setFocus(true)
      return true

    else if m.ok.hasFocus() and key = "left"
      m.ok.setFocus(false)
      m.delete_account.setFocus(true)
      return true
    else if m.ok.hasFocus() or m.delete_account.hasFocus() and key = "up"
      m.ok.setFocus(false)
      m.delete_account.setFocus(false)
      m.remenberCheckList.setFocus(true)
      m.simple3.Font = getFont(34, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
      return true
    else if m.remenberCheckList.hasFocus() and key = "down"
      m.ok.setFocus(true)
      m.delete_account.setFocus(false)
      m.remenberCheckList.setFocus(false)
      m.simple3.Font = getFont(32, "pkg:/fonts/Helvetica-Neue-Bold_22498.ttf")
      return true
    else if m.delete_account.hasFocus() and key = "right"
      m.delete_account.setFocus(false)
      m.ok.setFocus(true)
      return true
    else if m.Yesremove.hasFocus() and key = "left"
      m.Yesremove.setFocus(false)
      m.Noremove.setFocus(true)
      return true
    else if m.Noremove.hasFocus() and key = "right"
      m.Noremove.setFocus(false)
      m.Yesremove.setFocus(true)
      return true
    else if (m.Yesremove.hasFocus() or m.Noremove.hasFocus()) and key = "back"
      m.YesRemove.setFocus(false)
      m.Noremove.setFocus(false)
      showDialogRemove("", false, true)
      m.registredDevicesMarkup.setFocus(true)
      return true
    else if m.YesDeleteButton.hasFocus() and key = "left"
      m.YesDeleteButton.setFocus(false)
      m.NoDeleteButton.setFocus(true)
      return true
    else if m.NoDeleteButton.hasFocus() and key = "right"
      m.NoDeleteButton.setFocus(false)
      m.YesDeleteButton.setFocus(true)
      return true
    else if (m.NoButton.hasFocus() or m.YesButton.hasFocus() or m.ok.hasFocus()) and key = "back"

      m.ok.setFocus(false)
      m.NoButton.setFocus(false)
      m.YesButton.setFocus(false)


      showDialog("", false, true)
      return true
    else if key = "back"
      if m.deviceSearchEditText.hasFocus()
        ?"yes"
        m.deviceSearchEditText.active = false
        m.registredDevicesMarkup.setFocus(true)
        m.KeyboardPosterDevice.visible = false
        m.KeyboardInputDevice.setFocus(false)
        return true
        ' else if getRegister() = "register" and m.deviceSearchEditText.hasFocus() = false
        '   ?"getRegister() work: "getRegister()
        '   deleteRegister()
        '   if m.screen <> invalid then m.top.removeChild(m.screen)
        '   ProfileSettingScreen = m.top.createChild("ProfileSettingScreen")
        '   ProfileSettingScreen.setFocus(true)
        ' else if getRegister() = ""
        '   ?"getRegister(): "getRegister()
        '   ' if m.screen <> invalid then m.top.removeChild(m.screen)
        '   ' ShowScreen("HomeScreen")
        '   HomeScreen = m.top.createChild("HomeScreen")
        '   HomeScreen.setFocus(true)
      else
        deleteSettingMode()
        m.top.exitView = true

      end if


    end if

    return true
  end if


end function
