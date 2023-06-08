sub INIT()
    print "API Call INIT: ";m.top.state
    m.top.functionName = "front_screen"
end sub

sub front_screen()
    jsonResponse = APICall(loginguide(), m.top.callType, m.top.data, m.top.headerData)
    ?"personalDeviceName json response: ";jsonResponse
    m.top.response = jsonResponse.jsonResponse
end sub