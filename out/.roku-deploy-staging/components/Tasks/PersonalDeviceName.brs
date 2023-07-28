sub INIT()
    print "API Call INIT: ";m.top.state
    m.top.functionName = "personal_device_name"
end sub

sub personal_device_name()
    jsonResponse = APICall(getBaseUrl() + updatePersonalDeviceName() + getPersonaldeviceid(), m.top.callType, m.top.data, m.top.headerData)
    ?"personalDeviceName json response: ";jsonResponse
    m.top.response = jsonResponse.jsonResponse
end sub