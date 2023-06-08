sub init() ' This function retrieves and parses the feed and stores each content item in a ContentNode
    ? "API Call INIT: "; m.top.state
    m.top.functionName = "ChannnleAPI"
end sub

' This api get categories.
sub ChannnleAPI()
    m.top.callType = "GET"
    jsonResponse = APICall(getBaseUrl() + getChannelGenerList(), m.top.callType, m.top.data, m.top.parameters)
    ? "ChannnleAPI: jsonResponse"; jsonResponse
    '    ? "ChannnleAPI: jsonResponse.Count(): "; jsonResponse.data.Count()
    '     m.top.categoryChannels
    if jsonResponse.responseCode = 200
        ' channles = jsonResponse.jsonResponse.data.data
        categoryChannels = CreateObject("roAssociativeArray")
        categoriesList = []
        categoriesList.push("All")
        categoriesList.push("Recent")
        categoriesList.push("Others")
        categoriesList.push("Sports")
        categoriesList.push("Movies")
        categoriesList.push("Family")
        categoriesList.push("Kids")
        categoriesList.push("News")
        categoriesList.push("Comedy")
        ' categoriesList.push("MyChannel")

        ' categoriesList.append(jsonResponse.jsonResponse.allGenre)

        ' categoryChannels["ALL"] = channles
        ' categoryChannels["RECENT"] = []

        ' for i = 0 to channles.data.Count()
        '     if channles[i] <> invalid
        '         ' ? "channles[i].genrename ;"; channles[i].genrename
        '         if channles[i].genrename <> invalid
        '             for each genre in channles[i].genrename
        '                 genre = UCase(genre)
        '                 if categoryChannels[genre] <> invalid
        '                     arrayValue = categoryChannels[genre]
        '                     arrayValue[arrayValue.count()] = channles[i]
        '                     categoryChannels[genre] = arrayValue
        '                 else
        '                     categoryChannels[genre] = [channles[i]]
        '                 end if
        '             end for
        '         end if
        '     end if
        ' end for

        '    callSign: "AETVP"
        '    genrename: <Component: roArray>
        '    id: 88
        '    image: "AETV.png"
        '    logo: "http://streamlytv.com/uploads/channellogo/AETV.png"
        '    url: "http://cdn.streamlytv.com/live/ANE/interlink.m3u8"
        '  ?"categoryChannels[ALL][0]";categoryChannels["ALL"][0]
        ' recentResponse = APICall(getBaseUrl() + getRecentUrl(), "GET", invalid, invalid)
        ' ' ? "recentResponse:"; recentResponse.data
        ' categoryChannels["RECENT"] = invalid
        ' if (recentResponse.response_code = 200 and recentResponse.data <> invalid and recentResponse.data.Count() > 0)
        '     categoryChannels["RECENT"] = recentResponse.data
        ' end if
        m.top.CategoriesList = categoriesList
        ' m.top.ChannelData = categoryChannels

    else
        if jsonResponse.responseCode = 401 or  jsonResponse.responseCode = 429 or  jsonResponse.responseCode = 404 or  jsonResponse.responseCode = 402
            m.top.errorMessage = jsonResponse
            ?"json "jsonResponse.jsonResponse
        end if    
    end if

end sub
