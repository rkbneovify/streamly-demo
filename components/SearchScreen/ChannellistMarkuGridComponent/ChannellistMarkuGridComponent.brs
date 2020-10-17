sub init()

    ' m.title = m.top.findNode("title")
    m.poster = m.top.findNode("poster")
    m.background = m.top.findNode("background")

end sub

sub showcontent()
?"logo url :";m.top.itemContent.HDSMALLICONURL
?"TITLE url :"; m.top.itemContent.TITLE

    if m.top.itemContent <> invalid

        ' m.title.font = "font:SmallSystemFont"
        ' m.title.font.size = 22
        m.poster.uri = m.top.itemContent.HDSMALLICONURL
        ' m.title.text = m.top.itemContent.TITLE
    
    end if

end sub

sub setSize()
    
    If m.top.height > 0 And m.top.width > 0 Then

    m.poster.width  = m.top.width * (70/100)
    m.poster.height = m.top.height * (70/100)  
    m.poster.translate = [(m.top.width - m.poster.boundingRect().width)/2, (m.top.height - m.poster.boundingRect().height)/2]               
    m.background.height = m.top.height                    
    m.background.width = m.top.width                    
    '                     
     End If
    
end sub

sub setfocus()
    ?"setfocus"
    ' m.title = m.top.findNode("title")
    if m.top.itemHasFocus = false
    
        ' m.focus.visible = m.top.itemHasFocus
        ' m.focus.opacity = 0
        ' m.title.replace("hello", "")
    
    else if m.top.itemHasFocus = true
    
        ' m.focus.visible = m.top.itemHasFocus
        ' m.title = m.title + "hello"
        ' m.focus.opacity = 1
    
    end if

end sub
