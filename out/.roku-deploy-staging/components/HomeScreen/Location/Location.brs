sub Init()

    m.top.functionName = "LocationApicall"    
end sub    

sub LocationApicall()


    if m.top.callTO = "locationAllow" then
        ?"m.top.bodyData";m.top.bodyData
        ?"m.top.callType";m.top.callType
        ?"Req URL:";getBaseUrl() + allowLocatioPlay()
        allowLocationResponse = APICall(getBaseUrl() + allowLocatioPlay() , m.top.callType, m.top.bodyData, m.top.parameters)
        m.top.apiResponse = allowLocationResponse
    else if  m.top.callTO = "checkLocation" then

        latlongResponse = APICall(openApi() , m.top.callType, m.top.bodyData, m.top.parameters)
        ?"apicall.response:";latlongResponse.jsonResponse
    
        if latlongResponse.jsonResponse<>invalid and latlongResponse.jsonResponse.lat<>invalid and latlongResponse.jsonResponse.lon<>invalid then
    
            ?"addrecentResponse.jsonResponse.lat";latlongResponse.jsonResponse.lat
            ?"addrecentResponse.jsonResponse.lon";latlongResponse.jsonResponse.lon
            longitude = (latlongResponse.jsonResponse.lon).toStr()
            latitude = (latlongResponse.jsonResponse.lat).toStr()
            setLongitude(longitude)
            setLatitude(latitude)
            ?"get latitude fromn device persitance:";getLatitude()
            
            m.top.bodyData = {
                                latitude:latlongResponse.jsonResponse.lat,
                                longitude:latlongResponse.jsonResponse.lon
                             }
        areaCheckResponse = APICall( getBaseUrl() + geofence(), "POST", m.top.bodyData, m.top.parameters)
        ' ?"areaCheckResponse:jsonResponse: ";areaCheckResponse.jsonResponse
            m.top.apiResponse = areaCheckResponse.jsonResponse
    
        else
            
        end if
    end if

   
end sub