sub init() ' This function retrieves and parses the feed and stores each content item in a ContentNode
    ? "API Call INIT: "; m.top.state
    m.top.functionName = "recentApicall"
end sub


sub recentApicall()
?"recentApicall:";m.top.data    

 addrecentResponse = APICall(getBaseUrl() + m.top.url, m.top.callType, m.top.bodyData, m.top.parameters)
 
 ?"apicall.response:";addrecentResponse.jsonResponse
 m.top.apiResponse = addrecentResponse.jsonResponse
end sub
  
  