sub init()
      
      m.focus = m.top.findNode("focus")  
      m.labelName = m.top.findNode("labelName")  
      
      m.labelName.font = "font:SmallSystemFont"
      m.labelName.font.size = 20
             
end sub

    sub showcontent()
'       m.focus.opacity =0.0               
       m.labelName.text = m.top.itemContent.title
       
       movieTitlerect =   m.labelName.boundingRect()
       centerx = (210 - movieTitlerect.width)/2
       centery = (60 - movieTitlerect.height)/2
       m.labelName.translation= [ centerx, centery ]
       
       m.focus.width = movieTitlerect.width + 40
       m.focus.height= movieTitlerect.height + 25
       
       focuslerect =   m.focus.boundingRect()
       centerx = (210 - focuslerect.width)/2
       centery = (60 - focuslerect.height)/2
       
       m.focus.translation = [ centerx, centery ]
'      
      
    end sub
  
    Sub updateLayout()
'                If m.top.height > 0 And m.top.width > 0 Then
'                     m.focus.width  = m.top.width
'                     m.focus.height = m.top.height                    
'                     
'                End If
    End Sub
sub showfocus()
     
'    ?"showfocus:";m.top.focusPercent
'    ' scale = 1 + (m.top.focusPercent * 0.08)
''    if m.top.focusPercent<.5
'     
'      m.focus.opacity = m.top.focusPercent           
''    end if
end sub

sub setfcous()
?"itemHasFocus"
if m.top.itemHasFocus =false
m.focus.visible=m.top.itemHasFocus
m.focus.opacity = 0
else if m.top.itemHasFocus =true
m.focus.visible=m.top.itemHasFocus
 m.focus.opacity = 1
end if
  
end sub
    