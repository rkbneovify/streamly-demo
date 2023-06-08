sub INIT()
?"Custom Dialog IniT "
    m.SupportDialog = m.top.createChild("Poster")
    m.SupportDialog.visible = false

    m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [0, 50]
    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [0]
    m.TextLabel = m.LayoutGroup.createChild("Label")
    
    '     * (83.6/100)
    m.TextLabel.font = getFont(35, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.TextLabel.height = 50
    m.TextLabel.horizAlign = "Center"
    m.TextLabel.vertAlign = "Center"


    m.contactNumber = m.LayoutGroup.createChild("Label")

    m.contactNumber.translation = [0, 0]
    
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
    m.okLabel = m.HelpButton.createChild("Label")
    m.okLabel.horizAlign = "center"
    m.okLabel.height = "84"
    m.okLabel.width = "280"
    m.okLabel.vertAlign = "center"
    m.okLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.currentResolution = m.top.getScene().currentDesignResolution


    m.top.observeField("height","setHeight")
    m.top.observeField("width","setWidth")
    m.top.observeField("backgroundUri","setBackgroundUri")
    m.top.observeField("title","setTitle")
    m.top.observeField("message","setMessage")
    m.top.observeField("buttonText","setbuttonText")
    m.HelpButton.observeField("buttonSelected","callButtonSelected")
    m.top.observeField("visible","showDialog")
end sub

function setHeight(event as object)
    m.dialogHeight = event.getData()

    ' m.SupportDialog.width = m.dialogWidth
    m.SupportDialog.height = m.dialogHeight

    m.contactNumber.height = m.dialogHeight * (25 / 100)
   
end function
function setWidth(event as object)
    m.dialogWidth = event.getData()

    m.SupportDialog.width = m.dialogWidth
    m.TextLabel.width = m.dialogWidth -50
    m.contactNumber.width = m.dialogWidth

    ' m.SupportDialog.height = m.dialogHeight
end function
function setBackgroundUri(event as object)
    ' m.SupportDialog.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialog.uri = event.getData()
end function
function setTitle(event as object)
    m.TextLabel.text = event.getData()
end function
function setMessage(event as object)
    m.contactNumber.text = event.getData()
end function
function setbuttonText(event as object)
    m.okLabel.text = event.getData()
end function
function callButtonSelected()
    
    m.top.close = true 

end function
function showDialog(event as object)
   
    m.SupportDialog.translation = [(m.currentResolution.width - m.dialogWidth) / 2, (m.currentResolution.height - m.dialogHeight) / 2]
    m.HelpButton.translation = [(m.dialogWidth - m.HelpButton.boundingRect().width) / 2, m.dialogHeight * (65 / 100)]

    m.SupportDialog.visible = true 
    
    m.SupportDialog.setFocus(true)
end function

function onKeyEvent(key as string, press as boolean) as boolean
 ?"Custom Dialg : onKeyEvent"; press ;" key: ";key

  if press
    callButtonSelected()
    return true 
  end if

 return false
end function 