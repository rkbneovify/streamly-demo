sub init()
    
    m.top.functionName = "getlinkNQRcode"
    
end sub

sub getlinkNQRcode()
    
    m.top.data = []
    m.top.parameters = []
    m.top.method = "GET"
    jsonResponse = APICall(getBaseUrl()+LinkNQRCode(), m.top.method ,m.top.data,m.top.parameters)  
    
    m.top.response = jsonResponse

end sub