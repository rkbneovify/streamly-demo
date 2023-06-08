sub init()

    ' m.title = m.top.findNode("title")
    m.poster = m.top.findNode("poster")
    m.background = m.top.findNode("background")
    m.channel_info = m.top.findNode("channel_info")
    m.channel_name = m.top.findNode("channel_name")
    m.id = m.top.findNode("id")

    m.channel_name.font = getFont(28, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.id.font = getFont(18, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
    m.count =0
end sub

sub showcontent()
    
    ?"logo url :";m.top.itemContent.HDSMALLICONURL
    ?"TITLE url :"; m.top.itemContent.TITLE
    
    ?"count"m.count+1
    ' m.ChannelsMarkuGrid.numColumns = "1"
    ' m.top.itemContent.numColumns = "2"
    if m.top.itemContent <> invalid

        ' m.title.font = "font:SmallSystemFont"
        ' m.title.font.size = 22
        m.poster.uri = m.top.itemContent.HDSMALLICONURL
        m.id.text = m.top.itemContent.id
        m.channel_name.text = m.top.itemContent.title
        ' m.title.text = m.top.itemContent.TITLE

    end if

end sub

sub setSize()

    if m.top.height > 0 and m.top.width > 0 then

        ' m.poster.width = m.top.width * (70 / 100)
        ' m.poster.height = m.top.height * (70 / 100)
        ' m.poster.translate = [(m.top.width - m.poster.boundingRect().width) / 2, (m.top.height - m.poster.boundingRect().height) / 2]
        m.background.height = m.top.height
        m.background.width = m.top.width

        m.channel_info.height = 80
        m.channel_info.width = m.top.width
        translationHeight = m.top.height - m.channel_info.height
        m.channel_info.translation = [0, translationHeight]
    end if

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
