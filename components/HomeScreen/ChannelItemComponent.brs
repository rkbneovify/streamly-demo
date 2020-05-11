 sub init()
'      m.color=""
'      m.top.id = "markuplistitem"
'      m.itemicon = m.top.findNode("itemIcon")
'      m.itemlabel = m.top.findNode("itemLabel")
'      
      m.title = m.top.findNode("title")
      m.poster = m.top.findNode("poster")
      
'          
'      m.itemselectedcursor = m.top.findNode("itemselectedcursor")     
'      
    end sub

    sub showcontent()
    Print "content:itemcomponent: ";m.top.content
    
'    signInFont  = CreateObject("roSGNode", "Font")
if m.top.content<>invalid
      m.title.font= "font:SmallSystemFont"
      m.title.font.size=25
      m.poster.uri=m.top.content.HDSMALLICONURL
      m.title.text = m.top.content.TITLE
end if 
    
  
  
      
    end sub  