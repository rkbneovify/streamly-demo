sub INIT()
    print "API Call INIT: ";m.top.state
    m.top.functionName = "check_for_bar_code"
end sub

sub check_for_bar_code()
    jsonResponse = APICall(getBaseUrl() + checkStatus(), m.top.callType, m.top.data, m.top.headerData)
    Sleep(5000)
    ?"LoginTask: ";jsonResponse
    m.top.response = jsonResponse.jsonResponse

end sub