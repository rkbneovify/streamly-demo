sub INIT()
    print "API Call INIT: ";m.top.state
    m.top.functionName = "qr_login"
end sub

sub qr_login()
    jsonResponse = APICall(getBaseUrl() + qrLogin(), m.top.callType, m.top.data, m.top.headerData)
    ?"LoginTask: ";jsonResponse
    m.top.response = jsonResponse.jsonResponse

end sub