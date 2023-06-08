' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********
'Function GetSceneName() as String
'    return "MainScene"
'end Function

sub Main(args as dynamic) as void
    
    showChannelSGScreen(args)
end sub

sub showChannelSGScreen(args as dynamic)
    screen = CreateObject("roSGScreen")
    m.port = CreateObject("roMessagePort")
    screen.setMessagePort(m.port)
    scene = screen.CreateScene("MainScene")
    Scene.signalBeacon("AppLaunchComplete")

    

    scene.observeField("exitApp", m.port)
    m.global = screen.getGlobalNode()

    stack = []
    m.global.addFields({ "screenStack": stack, "args": args })
    ' deeplink = {}

    ' ?"deeplinking args; ";args
    ' if (args.mediaType <> invalid) and (args.contentId <> invalid)
    '     contentId = args.contentId
    '     if (args.mediaType = "movie" or args.mediaType = "episode" or args.mediaType = "short-form" or args.mediaType = "series" or args.mediaType = "special")


    '         if valid_contentId(contentId) ' You define this function in your back-end
    '             'play content directly, starting at last bookmarked position
    '             screen.show()
    '             deeplink.mediaType = args.mediaType
    '             deeplink.contentId = args.contentId
    '         else
    '             'pop an error message and launch channel home page.
    '         end if

    '     else if (args.mediaType = "season")
    '         if valid_contentId(contentId) ' You define this function in your back-end
    '             'display an episodic picker screen with the episode of the contentId selected
    '             screen.show()
    '             deeplink.mediaType = args.mediaType
    '             deeplink.contentId = args.contentId
    '         else
    '             screen.show()
    '             'pop an error message and launch channel home page.
    '         end if
    '     else if (args.mediaType = "live")
    '         if valid_contentId(contentId) ' You define this function in your back-end
    '             'display an episodic picker screen with the episode of the contentId selected
    '             deeplink.mediaType = args.mediaType
    '             deeplink.contentId = args.contentId
    '             screen.show()
    '         end if
    '     else
    '         'deep linking issue such as contentId not matching any content in the partner's catalog
    '         'display an appropriate error message for the user and launch home page.
    '     end if

    ' else
    '     screen.show()
    ' end if
    deeplink = getDeepLinks(args)
    ? "deeplink= "; deeplink
    m.global.addField("deeplink", "assocarray", false)
    m.global.deeplink = deeplink
    ' m.top.signalBeacon("VODStartInitiate")
    screen.show()
    

    while(true)
        msg = wait(0, m.port)
        msgType = type(msg)
        if msgType = "roSGScreenEvent"
            if msg.isScreenClosed() then return
        else if msg.GetData() = "exitApp"
            ?"close Mian brs "
            screen.Close()
            screen = invalid
            exit while
        else if type(msg) = "roInputEvent"
            if msg.IsInput()
                info = msg.GetInfo()
                if info.DoesExist("mediatype") and info.DoesExist("contentid")
                    mediaType = info.mediatype
                    contentId = info.contentid
                    deeplink.mediaType = mediaType
                    deeplink.contentId = contentId
                    m.global.addFields(deeplink)
                    ' m.top.signalBeacon("VODStartComplete")
                end if
            end if
        end if
    end while

    
end sub

function valid_contentId(contentId as object) 

    return true
end function

function getDeepLinks(args) as object
    deeplink = invalid

    if args.contentid <> invalid and args.mediaType <> invalid
        deeplink = {
            id: args.contentId
            type: args.mediaType
        }
    end if


    return deeplink
end function

