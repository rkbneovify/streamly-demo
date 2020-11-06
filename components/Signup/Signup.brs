sub init()
    ?"SignUp screen init"


    ' showProgressBar()
    ' apiCAll()
    ScreenBackgroundSet()
    logoSet()
    LabelSet()
    BrowserLinkSet()
    QRScaneSet()
    LableSignupHoldset()

    createLoader()
    showspinner(true)

    TaskSignup = CreateObject("roSgNode", "TaskSignup")
    TaskSignup.observeField("response", "apiResponse")
    TaskSignup.control = "RUN"

end sub

sub apiResponse(event as object)
    ?"apiResponse:";event.getData()
    event.getRosgNode().control = "STOP"

    if event.getData().responseCode = 200
        ?"apiResponse:";event.getData().jsonResponse
        if event.getData().jsonResponse.data <> invalid
            m.QRPoster.Uri = event.getData().jsonResponse.data

        end if
        if event.getData().jsonResponse.message <> invalid
            m.browserLink.text = event.getData().jsonResponse.message

        end if
    else

    end if
    ' TaskSignup.control ="STOP"
    showspinner(false)
end sub

sub createLoader()

    m.LoaderBackground = m.top.createChild("Rectangle")
    m.LoaderBackground.color = "0x000000FF"
    m.LoaderBackground.visible = false
    '  m.LoaderBackground.opacity="0.65"
    m.LoginBusySpinner = m.top.createChild("BusySpinner")
    m.LoginBusySpinner.visible = false
    m.LoginBusySpinner.clockwise = false
    m.LoginBusySpinner.poster.uri = "pkg:/images/busyspinner_hd.png"
    '  m.LoginBusySpinner.visible = false

end sub

' sub showProgressBar()
'     ' m.LoginBusySpinner = m.top.findNode("LoginBusySpinner")
'     ' m.LoaderBackground = m.top.findNode("LoaderBackground")
'     ' m.LoginBusySpinner.observeField("visible", "showspinner")

' end sub
sub showspinner(event as object)
    ?"SignUp::showspinner:event : ";event

    if(event = true)

        centerx = (1920 - m.LoginBusySpinner.poster.bitmapWidth) / 2
        centery = (1080 - m.LoginBusySpinner.poster.bitmapHeight) / 2
        m.LoginBusySpinner.translation = [centerx, centery]

        m.LoaderBackground.width = 1920
        m.LoaderBackground.height = 1080
        m.LoaderBackground.visible = true
        m.LoginBusySpinner.visible = true
        m.LoginBusySpinner.control = "start"

    else if(event = false)

        m.LoaderBackground.visible = false
        m.LoginBusySpinner.visible = false
        m.LoginBusySpinner.control = "stop"

    end if
end sub

' sub apiCAll()

' end sub
sub ScreenBackgroundSet()
    screenBackground = m.top.createChild("Poster")
    screenBackground.height = 1080
    screenBackground.width = 1920
    screenBackground.Uri = "pkg:/images/signup/signup background.png"
end sub

sub logoSet()
    channleLogo = m.top.createChild("Poster")
    channleLogo.height = 78
    channleLogo.width = 314
    channleLogo.Uri = "pkg:/images/streamly_logo.png"
    channleLogo.translation = [(1920 - channleLogo.boundingRect().width) / 2, 50]
end sub

sub LabelSet()
    activateLable = m.top.createChild("Label")
    activateLable.text = "Activate your Streamly Account on your TV"
    activateLable.translation = [(1920 - activateLable.boundingRect().width) / 2, 232]

end sub

sub BrowserLinkSet()

    m.layoutGroup = m.top.createChild("LayoutGroup")
    m.layoutGroup.layoutDirection = "horiz"
    '    m.layoutGroup.horizAlignment = "center"
    m.layoutGroup.vertAlignment = "center"
    m.layoutGroup.itemSpacings = [25, 25]
    '    m.layoutGroup.translation = [110, 590]


    browserLinkBackground = m.layoutGroup.createChild("Poster")
    browserLinkBackground.height = 384
    browserLinkBackground.width = 786
    browserLinkBackground.Uri = "pkg:/images/Signup/backgroundrect.png"

    m.BrouserlayoutGroup = browserLinkBackground.createChild("LayoutGroup")
    m.BrouserlayoutGroup.layoutDirection = "vert"
    m.BrouserlayoutGroup.horizAlignment = "left"
    '    m.BrouserlayoutGroup.vertAlignment = "center"
    m.BrouserlayoutGroup.itemSpacings = [150]
    orLabel = m.BrouserlayoutGroup.createChild("Label")
    orLabel.text = "Open the link in a browser"
    orLabel.font = getFont(35, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.browserLink = m.BrouserlayoutGroup.createChild("Label")
    m.browserLink.text = "Streamly.co/tv/562888"
    m.browserLink.font = getFont(55, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.browserLink.numlines = 1
    m.BrouserlayoutGroup.translation = [40, (browserLinkBackground.boundingRect().height - m.BrouserlayoutGroup.boundingRect().height) / 2]

    ' browserLinkBackground.translation = [99, 390]

end sub


sub QRScaneSet()

    orLabel = m.layoutGroup.createChild("Label")
    orLabel.text = "Or"
    ' orLabel.translation = [,232]
    QRScaneBackground = m.layoutGroup.createChild("Poster")
    QRScaneBackground.height = 384
    QRScaneBackground.width = 786
    QRScaneBackground.Uri = "pkg:/images/Signup/backgroundrect.png"
    ' QRScaneBackground.translation = [1035, 390]
    m.layoutGroup.translation = [(1920 - m.layoutGroup.boundingRect().width) / 2, 590]


    QRScanelayoutGroup = QRScaneBackground.createChild("LayoutGroup")
    QRScanelayoutGroup.layoutDirection = "horiz"
    ' QRScanelayoutGroup.horizAlignment = "center"
    QRScanelayoutGroup.vertAlignment = "center"
    QRScanelayoutGroup.itemSpacings = [50]
    ' QRScanelayoutGroup.vertAlignment ="center"

    QRTextLayoutGroup = QRScanelayoutGroup.createChild("LayoutGroup")
    QRTextLayoutGroup.itemSpacings = [130]
    orLabel = QRTextLayoutGroup.createChild("Label")
    orLabel.text = "Scane this QR code"
    orLabel.font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")

    orLabel = QRTextLayoutGroup.createChild("Label")
    orLabel.text = "Try using a phone camera or a QR code scanner"
    orLabel.width = 400
    orLabel.numlines = 2
    orLabel.wrap = true
    orLabel.font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")

    m.QRPoster = QRScanelayoutGroup.createChild("Poster")

    m.QRPoster.height = 225
    m.QRPoster.width = 225
    m.QRPoster.Uri = "pkg:/images/Signup/qr_testing.png"

    QRScanelayoutGroup.translation = [60, (QRScaneBackground.boundingRect().height - QRScanelayoutGroup.boundingRect().height) / 2 + 60]


end sub

sub LableSignupHoldset()

    LableSignupHold = m.top.createChild("Label")
    LableSignupHold.text = "Stay on this while you sign in on your phone or computer"
    LableSignupHold.translation = [(1920 - LableSignupHold.boundingRect().width) / 2, 980]

end sub

function onKeyEvent(key as string, press as boolean) as boolean
?"Signup screen ::onKeyEvent;key"; key
?"Signup screen ::onKeyEvent;press"; press

  if ( press and key ="back")
    
    m.top.exit = true
    return true
  end if

  return false

end function