

function getAccountToken() as Dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("accountToken")
end function

function setAccountToken(accountToken as String) as Dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("accountToken", accountToken)
end function

function getUserName() as Dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("UserName")
end function

function setUserName(userName as String) as Dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("UserName", userName)
end function

function getUserPassword() as Dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("UserPassword")
end function

function setUserPassword(UserPassword as String) as Dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("UserPassword", UserPassword)
end function

function getFont(fontSize as Integer, fontUri as String) as Object
    Font = CreateObject("roSGNode", "Font")
    Font.uri = fontUri
    Font.size = fontSize
    return Font
end function


function APICallWithHeader(url as String, callType as String, data as Object, parameters as Object, headers as object) as Object
    requestObject = CreateObject("roUrlTransfer")

    ' ?"parameters tessting:";parameters
        urlParameters = ""
        if parameters <> invalid and parameters.Count() > 0
            for each parameter in parameters.Keys()
                urlParameters = (parameter).toStr() + "=" + (parameters[parameter]).toStr() + "&" + urlParameters
            end for
        end if
    
        if urlParameters <> ""
            url = url + "?" + urlParameters
            requestObject.SetUrl(url)
        else
            requestObject.SetUrl(url)
        end if
    

        ' for each key in headers.Keys()

        '    if key<>invalid
            requestObject.setHeaders(headers)         
        '    end if  
        ' end for
        
        ' ? "request url:"; url
        requestObject.addHeader("Content-Type", "application/json")
        requestObject.addHeader("Authorization", "Bearer " + getAccountToken())
        requestObject.setCertificatesFile("common:/certs/ca-bundle.crt")
        requestObject.initClientCertificates()
        requestObject.enableEncodings(true)
        requestObject.RetainBodyOnError(true)
        requestObject.SetRequest(callType)
        port = CreateObject("roMessagePort")
        requestObject.setMessagePort(port)
    '    requestObject.addHeader("Content-Type","application/json") 
        if callType = "POST"
            ' ? "APICall:::POST CALL"
            requestObject.SetRequest(callType)
            body = FormatJson(data)
    
            req = requestObject.AsyncPostFromString(body)
        else if callType = "GET"
            ' ? "APICall:::GET CALL"
            requestObject.SetRequest(callType)
            req = requestObject.AsyncGetToString()
        end if
    
        if req
            while true
                msg = Wait(0, port)
                if Type(msg) = "roUrlEvent"
                    
                    responseJson = {}
    
                    if msg.GetResponseCode() = 200
                        
    
                        responseJson["responseCode"] = msg.GetResponseCode()
                        responseJson["data"] = ParseJson(msg.GetString())
    
                    ?"APICall::JsonResponse: ";JsonResponse
    
                        return responseJson
                    else
                        responseJson["responseCode"] = msg.GetResponseCode()
                        responseJson["data"] =  ParseJson(msg.GetString())
                        responseJson["Failure"] =  msg.GetFailureReason()
    
                    ?"APICall::JsonResponse: ";responseJson
    
                        return responseJson
                    end if
                else
                    ? "APICall::ResponseCode: "; msg.GetResponseCode()
                    ? "APICall::FailureReason: "; msg.GetFailureReason()
                end if
            end while
        else
            ? "req: "; req
        end if
end function

function APICall(url as String, callType as String, data as Object, parameters as Object) as Object
    ' ? "APICall:"
    requestObject = CreateObject("roUrlTransfer")

' ?"parameters tessting:";parameters
    urlParameters = ""
    if parameters <> invalid and parameters.Count() > 0
        for each parameter in parameters.Keys()
            urlParameters = (parameter).toStr() + "=" + (parameters[parameter]).toStr() + "&" + urlParameters
        end for
    end if

    if urlParameters <> ""
        url = url + "?" + urlParameters
        requestObject.SetUrl(url)
    else
        requestObject.SetUrl(url)
    end if

    ? "request url:"; url
    ?"method :";callType
    requestObject.addHeader("Content-Type", "application/json")
    requestObject.addHeader("Authorization", "Bearer " + getAccountToken())
    requestObject.setCertificatesFile("common:/certs/ca-bundle.crt")
    requestObject.initClientCertificates()
    requestObject.enableEncodings(true)
    requestObject.RetainBodyOnError(true)
    requestObject.SetRequest(callType)
    port = CreateObject("roMessagePort")
    requestObject.setMessagePort(port)
'    requestObject.addHeader("Content-Type","application/json") 
    if callType = "POST"
        ' ? "APICall:::POST CALL"
        requestObject.SetRequest(callType)
        body = FormatJson(data)

        req = requestObject.AsyncPostFromString(body)
    else if callType = "GET"
        ' ? "APICall:::GET CALL"
        requestObject.SetRequest(callType)
        req = requestObject.AsyncGetToString()
    end if

    if req
        while true
            msg = Wait(0, port)
            if Type(msg) = "roUrlEvent"
                
                responseJson = {}

                if msg.GetResponseCode() = 200
                    
                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["jsonResponse"] = ParseJson(msg.GetString())

                ' ?"APICall::JsonResponse: ";JsonResponse

                    return responseJson
                else
                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["jsonResponse"] =  ParseJson(msg.GetString())
                    responseJson["Failure"] =  msg.GetFailureReason()

                ' ?"APICall::JsonResponse: ";responseJson

                    return responseJson
                end if
            else
                ? "APICall::ResponseCode: "; msg.GetResponseCode()
                ? "APICall::FailureReason: "; msg.GetFailureReason()
            end if
        end while
    else
        ? "req: "; req
    end if
end function

function getBaseUrl()
' return "http://100.26.190.92/"
' return "https://b86ed54d.ngrok.io/"
    return "https://streamlytv.com/"
end function

function drmkeyUrl()
    return "/api/auth/getdrmkey"
end function  

function profileUrl()
' return "api/auth/profile"
    return "api/auth/tvprofile"
end function

function logoutUrl()
    return "api/auth/logout"
end function

function termOfUse()
    return "api/auth/termofuse"
end function


function loginUrl()
    return "api/auth/login"
end function

function connectedDeviceUrl() as String
    return "api/auth/connecteddevice"
end function

function getChannelListUrl() as String
    return "api/auth/channellist"
end function

function getchannelSearchUrl() as String
    return "/api/auth/search"
end function

function getchannelschdeuleUrl() as String
    return "api/auth/channelschdeule"
end function

function getsupportUrl() as String
    return "/api/auth/support"
end function

function getprivacypolUrl() as String
    return "api/auth/privacypol"
end function

function getBaseUrlTemp() as String
    return "http://ab8c26b210d7.ngrok.io/"
end function

function getDeviceInfo() as Object
    ? "getDeviceInfo"
    return CreateObject("roDeviceInfo")
end function

function getRecentUrl() as Object
    return "api/auth/my_recents"
end function
function addRecentUrl() as Object
    return "api/auth/addtorecent"
end function



function popupDialog(node as object, title as string , message as string) as object
    
    m.dialogHeight = 350
    m.dialogWidth = 1223

    m.SupportDialog = node.createChild("Poster")
    m.SupportDialog.visible = true
    m.SupportDialog.width = m.dialogWidth
    m.SupportDialog.height = m.dialogHeight
    m.SupportDialog.uri = "pkg:/images/profile/dialogBackground.png"
    m.SupportDialog.translation = [(1920 - m.dialogWidth) / 2, (1080 - m.dialogHeight) / 2]

    m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [0, 50]
    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [0]
    m.TextLabel = m.LayoutGroup.createChild("Label")
    m.TextLabel.width = m.dialogWidth
    '     * (83.6/100)
    m.TextLabel.font = getFont(35, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.TextLabel.height = 50
    m.TextLabel.horizAlign = "Center"
    m.TextLabel.vertAlign = "Center"
    m.TextLabel.text  = title

    m.contactNumber = m.LayoutGroup.createChild("Label")

    m.contactNumber.translation = [0, 0]
    m.contactNumber.width = m.dialogWidth
    m.contactNumber.height = m.dialogHeight * (25 / 100)
    '    m.contactNumber.translation = [0, 150]
    '    m.contactNumber.font = "font:SmallBoldSystemFont"
    m.contactNumber.horizAlign = "Center"
    m.contactNumber.vertAlign = "Center"
    m.contactNumber.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.contactNumber.text = message

    m.HelpButton = m.SupportDialog.createChild("Button")
    ' m.HelpButton.observeField("buttonSelected", "closeDialog")

    '                 m.OkButton.translation= [466,429]
    m.HelpButton.minWidth = "280"
    m.HelpButton.maxWidth = "280"
    m.HelpButton.focusedTextColor = "#FFFFFF"
    m.HelpButton.height = "84"
    m.HelpButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.HelpButton.showFocusFootprint = "true"
    m.HelpButton.iconUri = ""
    m.HelpButton.focusedIconUri = ""
    okLabel = m.HelpButton.createChild("Label")
    okLabel.horizAlign = "center"
    okLabel.height = "84"
    okLabel.width = "280"
    okLabel.vertAlign = "center"
    okLabel.text = "OK"
    okLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.HelpButton.translation = [(m.dialogWidth - m.HelpButton.boundingRect().width) / 2, m.dialogHeight * (65 / 100)]
return m.HelpButton
end function

function closeErrorDialogShow(node as object)

    if m.SupportDialog<>invalid
        node.removeChild(m.SupportDialog)
    end if
    
end function