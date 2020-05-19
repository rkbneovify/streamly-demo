sub init()
      
      m.labelName = m.top.findNode("labelName")  
      m.labelvalue = m.top.findNode("labelvalue") 
      m.underline = m.top.findNode("underline") 
 
'     m.labelName.font = "font:MediumBoldSystemFont"
     
'     m.labelName.font= getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    
'     m.labelvalue.font.size = 24
     
    m.labelName.font = getFont(32, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.labelvalue.font = getFont(28, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
     
           
end sub

    sub showcontent()               

       for each userdetail in m.top.itemContent.Keys()
        m.labelName.text = userdetail
         m.labelvalue.text = m.top.itemContent[userdetail]
       end for
'       m.labelvalue.text = m.top.itemContent.textValue       
                    m.underline. width  = m.labelvalue.boundingRect().width
                    m.underline. height = 2
    end sub
  
    Sub updateLayout()
                If m.top.height > 0 And m.top.width > 0 Then
                
                    m.labelName . width  = (m.top.width/2)
                    m.labelName . height = m.top.height  
                    m.labelvalue. width  = (m.top.width/2)
                    m.labelvalue. height = m.top.height-20
                    
                    
                       
                     
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
    