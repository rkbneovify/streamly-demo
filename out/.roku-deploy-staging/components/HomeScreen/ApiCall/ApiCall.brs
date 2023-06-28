sub init() ' This function retrieves and parses the feed and stores each content item in a ContentNode
    m.top.functionName = "recentApicall"
end sub


sub recentApicall()
?"recentApicall:data:";m.top.bodyData    
?"recentApicall:url:";m.top.url    
?"recentApicall:callType:";m.top.callType    
?"recentApicall:parameters:";m.top.parameters  
?"recentApicall:data:errorcode"m.top.bodyData.errcode



 addrecentResponse = APICall(getBaseUrl() + m.top.url, m.top.callType, m.top.bodyData, m.top.parameters)
 
 ?"apicall.response:";addrecentResponse.jsonResponse
 ?"apicall.url:";getBaseUrl() + m.top.url
 m.top.apiResponseData = addrecentResponse.jsonResponse
end sub
  
  