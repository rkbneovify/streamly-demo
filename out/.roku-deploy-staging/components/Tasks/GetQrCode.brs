sub INIT()
    print "API Call INIT: ";m.top.state
    m.top.functionName = "GetQrCodeApi"
end sub

sub GetQrCodeApi()
    jsonResponse = APICall(getBaseUrl() + qeCodeUrl(), m.top.callType, m.top.data, m.top.headerData)
    ?"LoginTask: ";jsonResponse
    m.top.response = jsonResponse.jsonResponse

end sub