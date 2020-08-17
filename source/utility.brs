

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

function APICall(url as String, callType as String, data as Object, parameters as Object) as Object
    ? "APICall:"
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
        ? "APICall:::POST CALL"
        requestObject.SetRequest(callType)
        body = FormatJson(data)

        req = requestObject.AsyncPostFromString(body)
    else if callType = "GET"
        ? "APICall:::GET CALL"
        requestObject.SetRequest(callType)
        req = requestObject.AsyncGetToString()
    end if

    if req
        while true
            msg = Wait(0, port)
            if Type(msg) = "roUrlEvent"
                code = msg.GetResponseCode()
                responseJson = {}

                if code = 200
                    JsonResponse = ParseJson(msg.GetString())

                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["data"] = JsonResponse

'                 ?"APICall::JsonResponse: ";JsonResponse

                    return JsonResponse
                else
                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["data"] =  ParseJson(msg.GetString())
                    responseJson["Failure"] =  msg.GetFailureReason()

'                 ?"APICall::JsonResponse: ";responseJson

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


