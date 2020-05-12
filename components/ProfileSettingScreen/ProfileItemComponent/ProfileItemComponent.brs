sub init()
      
      m.currentfocus = m.top.findNode("currentfocus")  
      m.background = m.top.findNode("background") 
      m.icon = m.top.findNode("icon") 
      m.textLabel = m.top.findNode("textLabel") 
       
      
     m.textLabel.font = "font:SmallestBoldSystemFont"
     m.textLabel.font.size = 27
           
end sub


    sub showcontent()               

       m.icon.uri = m.top.itemContent.SDPosterUrl
       m.textLabel.text = m.top.itemContent.text
       Titlerect =   m.textLabel.boundingRect()
       centerx = (280 - Titlerect.width)/2
       centery = 209
      m.textLabel.translation= [ centerx, centery ]
     
    end sub
  
    Sub updateLayout()
                If m.top.height > 0 And m.top.width > 0 Then
                
                     m.background . width  = m.top.width
                     m.background . height = m.top.height  
                     m.currentfocus.width  = m.top.width
                     m.currentfocus.height = m.top.height   
                     
                End If
    End Sub
sub showfocus()
     
    ?"showfocus:";m.top.focusPercent
      m.currentfocus.opacity = m.top.focusPercent           

end sub

sub setfcous()
'?"itemHasFocus"
'if m.top.itemHasFocus =false
'm.focus.visible=m.top.itemHasFocus
'else if m.top.itemHasFocus =true
'm.focus.visible=m.top.itemHasFocus
'
'
'end if
  
end sub
    