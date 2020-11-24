sub Init()

    m.top.functionName = "LocationApicall"    
end sub    

sub LocationApicall()

    latlongResponse = APICall(openApi() , m.top.callType, m.top.bodyData, m.top.parameters)
    ?"apicall.response:";latlongResponse.jsonResponse

    if latlongResponse.jsonResponse<>invalid and latlongResponse.jsonResponse.lat<>invalid and latlongResponse.jsonResponse.lon<>invalid then

        ?"addrecentResponse.jsonResponse.lat";latlongResponse.jsonResponse.lat
        ?"addrecentResponse.jsonResponse.lon";latlongResponse.jsonResponse.lon

        m.top.bodyData = {
                            latitude:latlongResponse.jsonResponse.lat,
                            longitude:latlongResponse.jsonResponse.lon
                         }
    areaCheckResponse = APICall( getBaseUrl() + geofence(), "POST", m.top.bodyData, m.top.parameters)
    ?"areaCheckResponse:jsonResponse: "areaCheckResponse.jsonResponse

        m.top.apiResponse = areaCheckResponse.jsonResponse

    else
        
    end if
end sub