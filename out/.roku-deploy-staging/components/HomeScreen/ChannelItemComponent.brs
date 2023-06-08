
sub init()

    m.title = m.top.findNode("title")
    m.poster = m.top.findNode("poster")
    m.channelfocusborder = m.top.findNode("channelfocusborder")

end sub

sub showcontent()
    ?"m.top.content.channelfocus";m.top.content
    if m.top.content <> invalid

        m.title.font = "font:SmallSystemFont"
        m.title.font.size = 25
        m.poster.uri = m.top.content.HDSMALLICONURL
        m.title.text = m.top.content.TITLE
        ' m.channelfocusborder.uri = "pkg:/images/home/categiryNavFocusIcon.png"
        if m.top.content.channelfocus <> invalid and m.top.content.channelfocus
            m.channelfocusborder.visible = true
        else
            m.channelfocusborder.visible = false
        end if

    end if

end sub

sub setfocus()
    ?"setfocus"
    m.title = m.top.findNode("title")
    if m.top.itemHasFocus = false

        m.focus.visible = m.top.itemHasFocus
        m.focus.opacity = 0
        m.title.replace("hello", "")

    else if m.top.itemHasFocus = true

        m.focus.visible = m.top.itemHasFocus
        m.title = m.title + "hello"
        m.focus.opacity = 1

    end if

end sub
