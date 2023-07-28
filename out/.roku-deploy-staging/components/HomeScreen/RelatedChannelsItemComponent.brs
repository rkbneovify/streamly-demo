sub init()

    m.focusBorder = m.top.findNode("focusBorder")
    m.channelborder = m.top.findNode("channelborder")
    m.channelICon = m.top.findNode("channelICon")
    m.channelId = m.top.findNode("channelId")

    m.focusBorder.uri = "pkg:/images/videoplayer/related_channel_focus.png"
    ' m.labelName.font = "font:SmallSystemFont"
    ' m.labelName.font.size = 20

end sub

sub showcontent()
    '       m.focus.opacity =0.0
    ?"related_itemcomponent" ;m.top.itemcontent
    m.channelId.text = m.top.itemcontent.id
    m.channelICon.uri = m.top.itemcontent.HDSMALLICONURL


end sub

sub updateLayout()
    if m.top.height > 0 and m.top.width > 0 then
        m.focusBorder.width = m.top.width
        m.focusBorder.height = m.top.height

        m.channelborder.width = m.top.width - 4
        m.channelborder.height = m.top.height - 4

        m.channelborder.translation = [2, 2]


        movieTitlerect = m.channelICon.boundingRect()
        centerx = (m.top.width - movieTitlerect.width) / 2
        centery = (m.top.height - movieTitlerect.height) / 2
        m.channelICon.translation = [centerx, centery]
    end if
end sub


sub setfocus()
    ?"itemHasFocus"
    ?"m.top.listHasFocus ";m.top.listHasFocus
    ?"m.top.focusPercent ";m.top.focusPercent
    if m.top.listHasFocus

        if m.top.focusPercent > .5
            m.focusBorder.visible = true

        else
            ' if getVideoid() <> ""
            '     m.focusBorder.visible = true
            ' else 
                m.focusBorder.visible = false
            ' end if
            
        end if
    else
        m.focusBorder.visible = false
    end if


end sub
