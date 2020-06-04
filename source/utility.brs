Function getAccountToken() as dynamic
 sec = CreateObject("roRegistrySection", "Authentication")
 return sec.Read("accountToken")
end Function

Function setAccountToken(accountToken as String) as dynamic
  sec = CreateObject("roRegistrySection", "Authentication")
  sec.Write("accountToken",accountToken)
end Function

Function getUserName() as dynamic
 sec = CreateObject("roRegistrySection", "Authentication")
 return sec.Read("UserName")
end Function

Function setUserName(userName as String) as dynamic
  sec = CreateObject("roRegistrySection", "Authentication")
  sec.Write("UserName",userName)
end Function


Function getUserPassword() as dynamic
 sec = CreateObject("roRegistrySection", "Authentication")
 return sec.Read("UserPassword")
end Function

Function setUserPassword(UserPassword as String) as dynamic
  sec = CreateObject("roRegistrySection", "Authentication")
  sec.Write("UserPassword",UserPassword)
end Function


Function getFont(fontSize as integer, fontUri as string) as object

     Font  = CreateObject("roSGNode", "Font") 
     Font.uri = fontUri
     Font.size = fontSize
     return Font
end Function


Function APICall(url as string, callType as String ,data as object,parameters as object) as object
?"APICall:"
    requestObject = CreateObject("roUrlTransfer")
    
   urlParameters=""
   if parameters<>invalid and parameters.Count()>0
   
     for each parameter in parameters.Keys()
     ?"parameter:";parameter
     ?"parameters value";parameters[parameter]
'     ?" "+parameter+":"+parameters[parameter]
            urlParameters=urlParameters+"&"+(parameter).toStr()+"="+(parameters[parameter]).toStr() 
    end for
    
    
   end if
   
   if urlParameters<>""
     url =url+"?"+urlParameters
     requestObject.SetUrl(url)
     else
     requestObject.SetUrl(url)
   end if
    
    ?"request url:";url
    requestObject.addHeader("Content-Type","application/json")
    requestObject.addHeader("Authorization","Bearer "+getAccountToken())
    requestObject.setCertificatesFile("common:/certs/ca-bundle.crt")
    requestObject.initClientCertificates()
    requestObject.enableEncodings(true)
    requestObject.RetainBodyOnError(true)
    requestObject.SetRequest(callType)
    port = CreateObject("roMessagePort")
    requestObject.setMessagePort(port)
'    requestObject.addHeader("Content-Type","application/json") 
    
    if callType="POST" 
     ?"APICall:::POST CALL"
       requestObject.SetRequest(callType) 
       body = FormatJSON(data)
       
       
       req = requestObject.AsyncPostFromString(body)
  
     else if callType="GET"
       ?"APICall:::GET CALL"
      requestObject.SetRequest(callType) 
      req = requestObject.AsyncGetToString()
        
     end if

     if req
       while true
         msg = wait(0, port)
          if type(msg) = "roUrlEvent"
       
              code = msg.GetResponseCode()              
              responseJson={}
                
              if code = 200
                               
                 JsonResponse = ParseJson(msg.GetString()) 
                 
                 responseJson["responseCode"] = msg.GetResponseCode()
                 responseJson["data"] = JsonResponse
                 
                 ?"APICall::JsonResponse: ";JsonResponse
'                
                 return JsonResponse
                
              else                                
                 responseJson["responseCode"] = msg.GetResponseCode()
                 responseJson["data"] =  ParseJson(msg.GetString()) 
                 responseJson["Failure"] =  msg.GetFailureReason()
                 
                 ?"APICall::JsonResponse: ";responseJson
'                
                 return responseJson
              
              end if  
               
          else
           ?"APICall::ResponseCode: ";msg.GetResponseCode() 
           ?"APICall::FailureReason: ";msg.GetFailureReason()
           
          end if
       end while  
     else
      ?"req: ";req
     
     end if
   
end Function

Function getBaseUrl()
 return "http://100.26.190.92/"
' return "https://b86ed54d.ngrok.io/"
'http://100.26.190.92/
 
end Function

Function profileUrl()
 return "api/auth/profile"
end Function


Function logoutUrl()
 return "api/auth/logout"
end Function
Function loginUrl()
 return "api/auth/login"
end Function

Function connectedDeviceUrl() as String

return "api/auth/connecteddevice"
        

end Function

Function getChannelListUrl() as String

return "api/auth/channellist"

end Function

Function getchannelSearchUrl() as String

return "/api/auth/search"

end Function

Function getchannelschdeuleUrl() as String
 return "api/auth/channelschdeule"
end Function


Function getDeviceInfo() as object
 ?"getDeviceInfo"
 return createObject("roDeviceInfo")
end Function




