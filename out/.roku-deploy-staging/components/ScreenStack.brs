sub InitScreenStack()
    m.screenStack = []
end sub

sub ShowScreen(node as object)
    prev = m.screenStack.Peek()
    if prev <> invalid
        prev.visible = false
    end if
    m.top.AppendChild(node)
    node.visible = true
    node.setFocus(true)
    m.screenStack.Push(node)
    ?"m.screenStack.count";m.screenStack.Count()
end sub


' sub CloseCurrentScreen(node as object)
'     if node <> invalid or (m.screenStack.Peek() <> invalid and m.screenStack.IsSameNode(node))
'         last = m.screenStack.Pop()
'         last.visible = false
'         m.top.RemoveChild(last)
'         prev = m.screenStack.Peek()
'         if prev <> invalid
'             prev.visible = true
'             prev.setFocus(true)
'         end if
'     end if
' end sub
sub CloseCurrentScreen()
    ?" m.screenStack.count()"; m.screenStack
    if m.screenStack.Peek() <> invalid 
        last = m.screenStack.Peek()
        last.visible = false
        m.top.RemoveChild(last)
        m.screenStack.Pop()
        prev = m.screenStack.Peek()
        if prev <> invalid
            prev.visible = true
            prev.setFocus(true)
        end if
    end if
end sub
function GetCurrentScreen()
    return m.screenStack.Peek()
end function