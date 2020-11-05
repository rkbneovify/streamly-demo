sub init()
    ?"SignUp screen init"


    ScreenBackgroundSet()
    logoSet()
    LabelSet()
    BrowserLinkSet()
    QRScaneSet()
    LableSignupHoldset()

end sub

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

    orLabel = m.BrouserlayoutGroup.createChild("Label")
    orLabel.text = "Streamly.co/tv/562888"
    orLabel.font = getFont(55, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
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

    QRPoster = QRScanelayoutGroup.createChild("Poster")

    QRPoster.height = 225
    QRPoster.width = 225
    QRPoster.Uri = "pkg:/images/Signup/qr_testing.png"

    QRScanelayoutGroup.translation = [60, (QRScaneBackground.boundingRect().height - QRScanelayoutGroup.boundingRect().height) / 2+60]


end sub

sub LableSignupHoldset()

    LableSignupHold = m.top.createChild("Label")
    LableSignupHold.text = "Stay on this while you sign in on your phone or computer"
    LableSignupHold.translation = [(1920 - LableSignupHold.boundingRect().width) / 2, 980]

end sub