sub init() ' This function retrieves and parses the feed and stores each content item in a ContentNode
    ? "API Call INIT: "; m.top.state
    m.top.functionName = "ChannnleAPI"
end sub

sub ChannnleAPI()
    m.top.callType = "GET"

    jsonResponse = APICall(getBaseUrl() + getChannelListUrl(), m.top.callType, m.top.data, m.top.parameters)
    ? "ChannnleAPI: jsonResponse"; jsonResponse.data
'    ? "ChannnleAPI: jsonResponse.Count(): "; jsonResponse.data.Count()
'     m.top.categoryChannels
    channles = jsonResponse.data
    categoryChannels = CreateObject("roAssociativeArray")

    categoryChannels["ALL"] = channles
    categoryChannels["Recent"] = []

    for i = 0 to channles.Count()
        if channles[i] <> invalid
            ? "channles[i].genrename ;"; channles[i].genrename
            if channles[i].genrename <> invalid
                for each genre in channles[i].genrename
                    if categoryChannels[genre] <> invalid
                        arrayValue =  categoryChannels[genre]
                        arrayValue[arrayValue.count()] = channles[i]
                        categoryChannels[genre] = arrayValue
                    else
                        categoryChannels[genre] = [channles[i]]
                    end if
                end for
            end if
        end if
    end for
    
'    callSign: "AETVP"
'    genrename: <Component: roArray>
'    id: 88
'    image: "AETV.png"
'    logo: "http://streamlytv.com/uploads/channellogo/AETV.png"
'    url: "http://cdn.streamlytv.com/live/ANE/interlink.m3u8"
    
     
    
     ?"categoryChannels[ALL][0]";categoryChannels["ALL"][0]
    recentResponse = APICall(getBaseUrl() + getRecentUrl(), "GET", invalid, invalid)
    ? "recentResponse:"; recentResponse.data
    categoryChannels["Recent"]=invalid
    if (recentResponse.response_code = 200 and recentResponse.data <> invalid and recentResponse.data.Count() > 0)
        categoryChannels["Recent"] = recentResponse.data
    end if

    m.top.ChannelData = categoryChannels
end sub
