Sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
    print "API Call INIT: ";m.top.state
    m.top.functionName = "ChannnleAPI"    
end Sub
    
Sub ChannnleAPI() 

     m.top.callType = "GET"
     
     jsonResponse = APICall(getBaseUrl() + getChannelListUrl() , m.top.callType ,m.top.data,m.top.parameters) 
     ?"ChannnleAPI: jsonResponse";jsonResponse.data 
     ?"ChannnleAPI: jsonResponse.Count(): ";jsonResponse.data.Count()
'     m.top.categoryChannels
    channles =jsonResponse.data
    categoryChannels = createObject("roAssociativeArray")
    
    categoryChannels["ALL"] = channles    
     
     for i=0 to channles.Count()
      
      if channles[i]<>invalid
        ?"channles[i].genrename ;";channles[i].genrename
          
         if channles[i].genrename <> invalid 
           for each genre in channles[i].genrename
              
              if categoryChannels[genre]<>invalid 
                  arrayValue =  categoryChannels[genre] 
                  arrayValue [arrayValue.count()] = channles[i]     
                  categoryChannels[genre] = arrayValue
              else
                 categoryChannels[genre] = [ channles[i] ]
              end if
          
            end for
          
             
             
          end if
      end if
      
      
     end for
     
      for each category in categoryChannels
        ?"category;tAsk ";category
'        ?"categoryChannels[category].Count() ";categoryChannels[category].Count()
'        Family, Sports ,Kids , (Movies,Family,Comedy)
      end for
'     m.top.ChannelData = jsonResponse.data  

     m.top.ChannelData = categoryChannels
end sub 
