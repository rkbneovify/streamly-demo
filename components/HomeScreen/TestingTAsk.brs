sub INIT() 'This function retrieves and parses the feed and stores each content item in a ContentNode
  ' print "API Call INIT: ";m.top.state
  m.top.functionName = "ChannnlePaginationAPI"
end sub

sub ChannnleAPI()
  ' ?"ChannnleAPI"
  ' ?"API start call"

  m.top.callType = "POST"
  ' programData = APICall(getBaseUrl() + getChannelDaySheduler(), m.top.callType, m.top.data, m.top.parameters)
  programData = APICall(getBaseUrl() + getchannelschdeuleUrl(), m.top.callType, m.top.data, m.top.parameters)
  '  headers ={"User-Agent": "Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19"}
  '  programData = APICallWithHeader( getBaseUrl() + getchannelschdeuleUrl() , m.top.callType ,m.top.data,m.top.parameters,headers)
  ' ?"pr-ogramData: ";programData.jsonResponse
  ' sleep(4000)
  ' ?"API End call";
  if programData.responseCode = 200
    ?"programmData: JesonResponse"; programData.jsonResponse
    ?"programmData: JsonData array"; programData.jsonResponse.data[0]
    ?"programmData: JsonData array"; programData.jsonResponse.data[1]
    ?"programmData: Programs"; programData.jsonResponse.data[0].programs
    ?"programmData: Programs array data"; programData.jsonResponse.data[0].programs[0]
    programdata = programData.jsonResponse.data[0].programs
    content = m.top.content
    channleNode = content.getChild(m.top.id.ToInt())

    field = {}
    field.channelfocus = false
    channleNode.addFields(field)

    '  ?"programdata: count: ";programdata.Count()
    length = programdata.Count()
    timemin = 0


    for i = 0 to length - 1

      prograom = programdata[i]
      '   ?"prograom ;";prograom
      if timemin <= prograom.start
        singleProgramNode = channleNode.createChild("ContentNode")
        ' ?"prograom.title";prograom.title
        ' ?"prograom.description";prograom.description
        if prograom.title <> invalid then singleProgramNode.TITLE = prograom.title
        if prograom.start <> invalid then singleProgramNode.PLAYSTART = prograom.start
        if prograom.description <> invalid then singleProgramNode.Description = prograom.description
        if prograom["end"] <> invalid then
          singleProgramNode.PLAYDURATION = prograom["end"] - prograom["start"]
          timemin = prograom["end"]
        end if
      end if
    end for

    '    m.top.programData = programData
  end if
  m.top.programData = []
end sub

sub ChannnlePaginationAPI()
  ' ?"ChannnleAPI"
  ' ?"API start call"

  m.top.callType = "GET"
  programData = APICall(getBaseUrl() + getChannelDaySheduler(), m.top.callType, m.top.data, m.top.parameters)
  '  headers ={"User-Agent": "Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19"}
  '  programData = APICallWithHeader( getBaseUrl() + getchannelschdeuleUrl() , m.top.callType ,m.top.data,m.top.parameters,headers)
  ' ?"pr-ogramData: ";programData.jsonResponse
  'sleep(4000)
  ' ?"API End call";
  if programData.responseCode = 200
    ?"programmData: JesonResponse"; programData.jsonResponse
    ChannelList = programData.jsonResponse.data
    m.top.ChannelList = ChannelList
  else
    m.top.errorResponse = programData
  end if
  ' m.top.programData = []


end sub