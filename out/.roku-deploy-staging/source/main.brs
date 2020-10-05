' ********** Copyright 2016 Roku Corp.  All Rights Reserved. ********** 
'function GetSceneName() as String
'
'    return "MainScene"
'end function


'
sub Main()
    showChannelSGScreen()
end sub

sub showChannelSGScreen()
    screen = CreateObject("roSGScreen")
    m.port = CreateObject("roMessagePort")
    screen.setMessagePort(m.port)
    scene = screen.CreateScene("MainScene")

    ' screen.show()

    scene.observeField("exitApp", m.port)
    m.global = screen.getGlobalNode()
    stack=[]
    
    m.global.addFields({ "screenStack": stack  })
    
    screen.show()
    


    while(true)
        msg = wait(0, m.port)
        msgType = type(msg)
        if msgType = "roSGScreenEvent"
            if msg.isScreenClosed() then return
        else if  msg.GetData() = "exitApp"
            screen.Close()
            screen = invalid   
        end if
    end while
end sub
