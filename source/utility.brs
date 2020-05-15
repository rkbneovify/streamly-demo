Function getAccountToken() as dynamic
 sec = CreateObject("roRegistrySection", "Authentication")
 return sec.Read("accountToken")
end Function

Function setAccountToken(accountToken as String) as dynamic
  sec = CreateObject("roRegistrySection", "Authentication")
  sec.Write("accountToken",accountToken)
end Function


Function getFont(fontSize as integer, fontUri as string) as object

     Font  = CreateObject("roSGNode", "Font") 
     Font.uri = fontUri
     Font.size = fontSize
     return Font
end Function


Function APICall(url as string, callType as String ,data as object) as object
?"APICall:"
    requestObject = CreateObject("roUrlTransfer")
    requestObject.SetUrl(url)
    
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
       requestObject.SetRequest("POST") 
       body = FormatJSON(data)
       
       
       req = requestObject.AsyncPostFromString(body)
  
     else if callType="GET"
       ?"APICall:::GET CALL"
      requestObject.SetRequest("GET") 
      req = requestObject.AsyncGetToString()
        
     end if

     if req
       while true
         msg = wait(0, port)
          if type(msg) = "roUrlEvent"
              code = msg.GetResponseCode()
              
              if code = 200
                JsonResponse = ParseJson(msg.GetString()) 
                ?"APICall::JsonResponse: ";JsonResponse
                responsedata={}
                responsedata["result"] = "success"
                responsedata["data"] = JsonResponse
                return responsedata
              else
             
                responsedata={}
                responsedata["result"] = "failure"
                responsedata["GetResponseCode"] = msg.GetResponseCode()  
                responsedata["message"] = msg.GetFailureReason()  
                ?"APICall::JsonResponse: ";JsonResponse
                return responsedata   
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




