Sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
    print "API Call INIT: ";m.top.state
    m.top.functionName = "ChannnleAPI"    
end Sub

sub ChannnleAPI()
?"ChannnleAPI"
?"API start call"

        m.top.callType="POST"      
       programData = APICall( getBaseUrl() + getchannelschdeuleUrl() , m.top.callType ,m.top.data,m.top.parameters)
  ?"programData: ";programData
'sleep(4000)
?"API End call";   

  if programData.response_code = 200
    
    programdata = programData.data[0].programs      
    
    content = m.top.content
    channleNode = content.getChild(m.top.id.ToInt())
  '  ?"programdata: count: ";programdata.Count()
    length =  programdata.Count()
    timemin =0
    for i=0 to length-1
       
    prograom = programdata[i]
'   ?"prograom ;";prograom
    if timemin <= prograom.start
       singleProgramNode = channleNode.createChild("ContentNode")  
       if prograom.title<>invalid then singleProgramNode.TITLE  = prograom.title
       if prograom.start<>invalid then singleProgramNode.PLAYSTART  = prograom.start
       if prograom["end"]<>invalid then 
        singleProgramNode.PLAYDURATION  = prograom["end"]- prograom["start"] 
        timemin = prograom["end"] 
       end if      
    end if       
    end for
    
'    m.top.programData = programData  
  end if
   m.top.programData = [] 

end sub
 