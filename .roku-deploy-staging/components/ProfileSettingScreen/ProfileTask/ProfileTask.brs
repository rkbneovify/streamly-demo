sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
       print "API Call INIT: ";m.top.state
       m.top.functionName = "getProfile"
end sub


sub getProfile()
       ?"getProfile: ";

       m.top.data = {}
       ?"m.top.callto: ";m.top.callto
       ?"m.top.data: ";m.top.data
       ?"m.top.parameters: ";m.top.parameters
       ?"m.top.callType: ";m.top.callType



       if m.top.callto = "Account"

              jsonResponse = APICall(getBaseUrl() + profileUrl(), m.top.callType, m.top.data, m.top.parameters)
              ?"getProfile: jsonResponse: ";jsonResponse
              responseAPiMethod(jsonResponse)

       else if m.top.callto = "Registered Devices"
              ?"getProfile:url req.:";getBaseUrl() + connectedDeviceUrl()
              jsonResponse = APICall(getBaseUrl() + connectedDeviceUrl(), m.top.callType, m.top.data, m.top.parameters)
              ?"getProfile: jsonResponse: ";jsonResponse
              responseAPiMethod(jsonResponse)

              '   m.top.response = jsonResponse.jsonResponse
       else if m.top.callto = "Support"

              jsonResponse = APICall(getBaseUrl() + getsupportUrl(), m.top.callType, m.top.data, m.top.parameters)
              ?"getProfile: jsonResponse: ";jsonResponse
              responseAPiMethod(jsonResponse)

              '   m.top.response = jsonResponse.jsonResponse
       else if m.top.callto = "Privacy & Policy"

              jsonResponse = APICall(getBaseUrl() + getprivacypolUrl(), m.top.callType, m.top.data, m.top.parameters)
              ?"getProfile: jsonResponse: ";jsonResponse
              responseAPiMethod(jsonResponse)

              '   m.top.response = jsonResponse.jsonResponse
       else if m.top.callto = "Sign out"
              '         m.top.response = jsonResponse
              jsonResponse = APICall(getBaseUrl() + logoutUrl(), m.top.callType, m.top.data, m.top.parameters)
              ?"getProfile: jsonResponse: ";jsonResponse
              '   m.top.response = jsonResponse.jsonResponse
              responseAPiMethod(jsonResponse)

       else if m.top.callto = "termofuse"

              jsonResponse = APICall(getBaseUrl() + termOfUse(), m.top.callType, m.top.data, m.top.parameters)

              ?"getProfile: jsonResponse: ";jsonResponse
              responseAPiMethod(jsonResponse)

              '   m.top.response = jsonResponse.jsonResponse
       end if



end sub

function responseAPiMethod(jsonResponse as object)
?"responseAPiMethod:"
       if jsonResponse.responseCode = 200
              m.top.response = jsonResponse.jsonResponse
       else
              m.top.errorMessage = jsonResponse
       end if
end function
