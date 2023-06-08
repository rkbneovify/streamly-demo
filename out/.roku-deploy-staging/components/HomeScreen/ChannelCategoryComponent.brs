sub init()

    m.focus = m.top.findNode("focus")
    m.labelName = m.top.findNode("labelName")
    

    m.labelName.font = getFont(20, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.labelName.font.size = 22

end sub

sub showcontent()
    ?"showcontent"
    '       m.focus.opacity =0.0
    m.labelName.text = m.top.itemContent.title

    movieTitlerect = m.labelName.boundingRect()
    centerx = (200 - movieTitlerect.width) / 2
    centery = (60 - movieTitlerect.height) / 2
    m.labelName.translation = [centerx, centery]

    m.focus.width = movieTitlerect.width + 95
    m.focus.height = movieTitlerect.height + 55

    focuslerect = m.focus.boundingRect()
    centerx1 = (200 - focuslerect.width) / 2
    centery1 = (60 - focuslerect.height) / 2

    m.focus.translation = [centerx1, centery1]

end sub

sub updateLayout()
    ' ?"selected"

    '                If m.top.height > 0 And m.top.width > 0 Then
    '                     m.focus.width  = m.top.width
    '                     m.focus.height = m.top.height
    '
    '                End If
end sub
sub showfocus()

    '    ?"showfocus:"
    '    ' scale = 1 + (m.top.focusPercent * 0.08)
    ''    if m.top.focusPercent<.5
    '
    '      m.focus.opacity = m.top.focusPercent
    ''    end if
end sub

sub setfcous()
    ?"itemHasFocus1"
    if m.top.itemHasFocus = false
        m.focus.visible = m.top.itemHasFocus
        m.focus.opacity = 0
    else if m.top.itemHasFocus = true
        m.focus.visible = m.top.itemHasFocus
        ' m.focus.uri = "pkg:/images/home/buttonFoucs1.9.png"
        m.focus.opacity = 1
    end if

end sub
