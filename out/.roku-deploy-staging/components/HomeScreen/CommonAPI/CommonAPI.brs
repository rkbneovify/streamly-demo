sub init() 
    ?"CommonAPI Init"
    ?"ApiType:" m.top.ApiType
    m.top.functionname = "callAllAPI"
end sub

sub callAllAPI()
    RecentGetChannelList()
    MyGetChannelList()
end sub

'This api is used for get recent channel data from api.
sub RecentGetChannelList()
    jsonResponse = APICall(getBaseUrl() + getRecentUrl(), "GET", invalid, invalid)
    ?"Recent list:" jsonResponse.jsonResponse.data
    if jsonResponse.responseCode = 200
        m.top.RecentChannelData = jsonResponse.jsonResponse.data
    else
        if jsonResponse.responseCode = 401 or  jsonResponse.responseCode = 429 or  jsonResponse.responseCode = 404
            m.top.errorMessage = jsonResponse
        end if  
    end if
end sub

'This api is used for get recent channel data from api.
sub MyGetChannelList()
    jsonResponse = APICall(getBaseUrl() + getFavouriteList(), "GET", invalid, invalid)
    ?"MyChannel list:" jsonResponse.jsonResponse.data
    if jsonResponse.responseCode = 200
        m.top.MyChannelData = jsonResponse.jsonResponse.data
    else
        if jsonResponse.responseCode = 401 or  jsonResponse.responseCode = 429 or  jsonResponse.responseCode = 404
            m.top.errorMessage = jsonResponse
        end if  
    end if
end sub