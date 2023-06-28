Sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
    print "API Call INIT: ";m.top.state
    m.top.functionName = "LogAPI"    
end Sub
    
Sub LogAPI() 

     jsonResponse = APICall(getBaseUrl()+getRoomsUrl(), m.top.callType ,m.top.data,m.top.headerData) 
     ?"GetRoomsTask: response: ";jsonResponse 
     m.top.response = jsonResponse.jsonResponse
     

end sub 