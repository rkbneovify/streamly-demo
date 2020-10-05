Sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
    print "API Call INIT: ";m.top.state
    m.top.functionName = "getChannel"    
end Sub


sub getChannel()
 ?"getProfile: ";
   
       m.top.data={}
       ?"m.top.callto: ";m.top.callto
        m.top.data ={}
        m.top.headerData={}
        jsonResponse = APICall(getBaseUrl()+getchannelSearchUrl(), m.top.callType ,m.top.data,m.top.parameters)     
        ?"getSearch Result Task: jsonResponse: ";jsonResponse
        m.top.response = jsonResponse.jsonResponse 
    
 end sub       