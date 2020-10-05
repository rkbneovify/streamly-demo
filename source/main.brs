' ********** Copyright 2016 Roku Corp.  All Rights Reserved. ********** 
'function GetSceneName() as String
'
'    return "MainScene"
'end function


'
sub Main(args as Dynamic) as Void
    showChannelSGScreen(args)
end sub

sub showChannelSGScreen(args as Dynamic)
    screen = CreateObject("roSGScreen")
    m.port = CreateObject("roMessagePort")
    screen.setMessagePort(m.port)
    scene = screen.CreateScene("MainScene")

    ' screen.show()

    scene.observeField("exitApp", m.port)
    m.global = screen.getGlobalNode()
    stack=[]
    
    m.global.addFields({ "screenStack": stack  })
    
   
    
    if (args.mediaType <> invalid) and (args.contentId <> invalid)
        if (args.mediaType = "movie" or args.mediaType = "episode" or args.mediaType = "short-form" or args.mediaType = "series" or args.mediaType = "special")
            if valid_contentId(contentId) ' You define this function in your back-end
                'play content directly, starting at last bookmarked position
                screen.show()
            else
                'pop an error message and launch channel home page.
            end if
         else if (args.mediaType = "season")
            if valid_contentId(contentId) ' You define this function in your back-end
                'display an episodic picker screen with the episode of the contentId selected
                screen.show()
            else
                screen.show()
                'pop an error message and launch channel home page.
            end if
         else
             'deep linking issue such as contentId not matching any content in the partner's catalog
             'display an appropriate error message for the user and launch home page.
         end if

         else
            screen.show()
    end if


    while(true)
        msg = wait(0, m.port)
        msgType = type(msg)
        if msgType = "roSGScreenEvent"
            if msg.isScreenClosed() then return
        else if  msg.GetData() = "exitApp"
            screen.Close()
            screen = invalid   
        else  if type(msg) = "roInputEvent"
            if msg.IsInput()
                info = msg.GetInfo()
                if info.DoesExist("mediatype") and info.DoesExist("contentid")
                    mediaType = info.mediatype
                    contentId = info.contentid
                end if
            end if    
        end if
    end while
end sub

function valid_contentId(params)
    
end function