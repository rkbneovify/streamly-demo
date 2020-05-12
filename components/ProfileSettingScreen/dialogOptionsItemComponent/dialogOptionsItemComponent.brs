sub init()
      
      m.labelName = m.top.findNode("labelName")  
      m.labelvalue = m.top.findNode("labelvalue") 
      
       
      
'     m.labelvalue.font = "font:SmallestBoldSystemFont"
     m.labelvalue.font.size = 24
           
end sub

    sub showcontent()               

       m.labelName.text = m.top.itemContent.textName+":"
       m.labelvalue.text = m.top.itemContent.textValue
       
     
    end sub
  
    Sub updateLayout()
                If m.top.height > 0 And m.top.width > 0 Then
                
                    m.labelName . width  = m.top.width/2
                    m.labelName . height = m.top.height  
                    m.labelvalue. width  = m.top.width/2
                    m.labelvalue. height = m.top.height   
                     
                End If
    End Sub
sub showfocus()
     
    ?"showfocus:";m.top.focusPercent
'      m.currentfocus.opacity = m.top.focusPercent           

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
    