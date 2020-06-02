Sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
    print "API Call INIT: ";m.top.state
    m.top.functionName = "ChannnleAPI"    
end Sub
    
Sub ChannnleAPI() 

     m.top.callType = "GET"
     
     jsonResponse = APICall(getBaseUrl() + getChannelListUrl() , m.top.callType ,m.top.data,m.top.parameters) 
     ?"ChannnleAPI: jsonResponse";jsonResponse.data 
     ?"ChannnleAPI: jsonResponse.Count(): ";jsonResponse.data.Count()
     m.top.ChannelData = jsonResponse.data 
     
'      if jsonResponse.success = true
'      
'            channels = jsonResponse.data  
'                         
'            content = CreateObject("roSGNode", "ContentNode")
'            currentTime =  CreateObject("roDateTime")
'            t = currentTime.AsSeconds()
'            t = t - (t mod 1800) ' RDE-2665 - TimeGrid works best when contentStartTime is set to a 30m mark
''            m.timeGride.contentStartTime = t
''            m.timeGride.leftEdgeTargetTime = currentTime.AsSeconds()
'            for each channel in channels
'              ?"channel: ";channel
'              channelNode = content.createChild("ContentNode")
'              channelNode.TITLE = channel.StationName
'              channelNode.HDSMALLICONURL = "pkg:/images/mtv.png"
'              
'              for i=0 to 10
'                 programNode = channelNode.createChild("ContentNode") 
'                 programNode.TITLE = channel.StationName
'                 programNode.PLAYSTART = t
'                 programNode.PLAYDURATION   = 300+t
'                 programNode.ID             = channel.id
''                 if channel.callSign<>invalid then programNode.genre          = channel.callSign
'                 
'                
'                 programNode.URL = channel.url
'                 
''                programNode.addFields(programAsositiveArray)
'              end for
'               
''              channelNode.addFields(channelAsositiveArray)
'              
'            end for
'       
'            m.top.ChannelData = content  
'      end if
     
'     m.top.response = jsonResponse
     

end sub 
