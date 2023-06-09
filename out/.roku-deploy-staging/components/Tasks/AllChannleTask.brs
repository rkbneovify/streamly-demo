sub INIT()
    print "API Call INIT: ";m.top.state
    m.top.functionName = "call_all_channle_api"
end sub

sub call_all_channle_api()
    jsonResponse = APICall(getBaseUrl() + getChannelListUrl(), m.top.callType, m.top.data, m.top.headerData)
    ?"call_all_channle_api: ";jsonResponse.jsonResponse
    m.top.response = jsonResponse.jsonResponse

end sub