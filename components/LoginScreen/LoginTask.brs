Sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
    print "API Call INIT: ";m.top.state
    m.top.functionName = "LogAPI"    
end Sub
    
Sub LogAPI() 

    requestforLogin = CreateObject("roUrlTransfer")
    requestforLogin.SetUrl("https://b992c94f.ngrok.io/api/auth/login")
    requestforLogin.addHeader("Content-Type","application/x-www-form-urlencoded")
'    url.addHeader("Content-Type","application/json")
    requestforLogin.setCertificatesFile("common:/certs/ca-bundle.crt")
    requestforLogin.initClientCertificates()
    requestforLogin.enableEncodings(true)
    requestforLogin.RetainBodyOnError(true)
    
    requestforLogin.SetRequest("POST")
    port = CreateObject("roMessagePort")
    requestforLogin.setMessagePort(port)


    if requestforLogin.AsyncPostFromString(m.top.data)
       while true
         msg = wait(0, port)
         if type(msg) = "roUrlEvent"
          
          code = msg.GetResponseCode()
          if code = 200 or code = 201 or code = 202 or code = 203 or code = 204
            responsejson = ParseJson(msg.GetString())
            ?"requestforLogin: responsejson: ";responsejson
            
            if responsejson.success =true
              
              m.top.response = responsejson
            else
              error={} 
              error["success"] = false
              error["msg"] = responsejson.msg
              m.top.response = error
            end if
            
           
          
          else
           ?"GetResponseCode: ";msg.GetResponseCode()
           ?"GetFailureReason: ";msg.GetFailureReason()
           error={} 
           error["success"] = false
           error["responseCode"] = msg.GetResponseCode()
           error["message"] = msg.GetFailureReason()
           
           m.top.response = error
          end if
          
            exit while 
        end if
       end while        
    end if     

end sub 






