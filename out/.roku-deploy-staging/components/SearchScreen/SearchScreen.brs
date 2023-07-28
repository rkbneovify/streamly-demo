sub init()

  m.deviceinfo = getDeviceInfo()
  ?"deviceinfo: ";m.deviceinfo.getDisplaySize()

  setScreenBackGrndimage()



  resultentList()

  setHeader()

  m.launchScreenMode = getUserHomeScreenMode()

  'search result list
  '  m.ChannelsMarkuGrid = m.top.findnode("ChannelsMarkuGrid")

  'spinner
  m.spinner = m.top.CreateChild("BusySpinner")
  m.spinner.visible = false

  m.spinner.translation = [960, 516]
  m.spinner.uri = "pkg:/images/busyspinner_hd.png"

  m.searchStart = m.top.CreateChild("Button")
  ' m.searchStart.focusfootprintBitmapUri ="pkg:/images/Search/mic_focus_icon.png"
  m.searchStart.focusfootprintBitmapUri = "pkg:/images/Search/mic_unfocus_icon.png"
  m.searchStart.translation = [120, 130]
  m.searchStart.showFocusFootprint = true
  m.searchStart.minwidth = 50
  m.searchStart.maxwidth = 50
  m.searchStart.height = 50

  m.searchStart.focusedIconUri = ""
  m.searchStart.iconUri = ""
  m.searchStart.setFocus(false)

  ' m.global.RSG_analytics.trackEvent = {
  '   google: {
  '       ec: "Channel_Search_Screen",
  '       ea: "Launch_complete"
  '   }
  ' }
  allChannelApiCall()

  searchKeyboard()
end sub

sub getChannelDAta()
  ' ? "getChannelDAta;"
  m.TestingTAsk = CreateObject("roSGNode", "TestingTAsk")
  m.TestingTAsk.callType = "GET"
  m.TestingTAsk.observeField("ChannelList", "showResponse")
  m.TestingTAsk.observeField("errorResponse", "errorDialogShow")

  m.TestingTAsk.control = "RUN"
end sub



sub resultentList()
  ?"resultentList" LoadVideo_id()
  m.ChannelsMarkuGrid = m.top.createChild("MarkupGrid")
  m.ChannelsMarkuGrid.id = "ChannelsMarkuGrid"
  m.ChannelsMarkuGrid.vertFocusAnimationStyle = "floatingFocus"
  m.ChannelsMarkuGrid.translation = "[720,200]"
  m.ChannelsMarkuGrid.itemComponentName = "ChannellistMarkuGridComponent"
  m.ChannelsMarkuGrid.itemSpacing = "[ 20, 20 ]"
  ' ?"m.ChannelsMarkuGrid.itemComponentName: "m.ChannelsMarkuGrid.itemComponentName.itemContent.TITLE
  '  m.ChannelsMarkuGrid.rowSpacings = "[ 200, 200 ]"
  '  m.ChannelsMarkuGrid.columnSpacings = "[ 200, 0 ]"
  ' m.ChannelsMarkuGrid.rowHeights =	[ 260, 120, 220 ]
  '  m.ChannelsMarkuGrid.wrapDividerHeight="200"
  m.ChannelsMarkuGrid.numColumns = "3"
  m.ChannelsMarkuGrid.numRows = "3"
  m.ChannelsMarkuGrid.itemSize = "[360,260]"

  m.ChannelsMarkuGrid.drawFocusFeedback = "true"
  m.ChannelsMarkuGrid.visible = false
  m.ChannelsMarkuGrid.observeField("itemSelected", "setChannelUrl")
  'show response text
  m.errorResultLabel = m.top.createChild("Label")
  m.errorResultLabel.text = "Please type some text for search the channels :"
  m.errorResultLabel.translation = [850, 480]
  m.errorResultLabel.visible = false
  
end sub

sub setChannelUrl(event as object)
  ?"setChannelUrl:";event.getRosgnode().content.getchild(event.getRosgnode().itemSelected)
  m.top.playChannel = event.getRosgnode().content.getchild(event.getRosgnode().itemSelected)
  setVideoid1(m.top.playChannel.id.toStr())
 
end sub

sub setHeader()
  ?"setHeader"
  Buttonleft = m.top.CreateChild("Button")
  Buttonleft.translation = [1500, 80]
  Buttonleft.iconUri = ""
  butonPoster = Buttonleft.createChild("Poster")
  butonPoster.uri = "pkg:/images/home/backNavIcon.png"
  butonPoster.height = 56
  butonPoster.width = 56
  butonLabel = Buttonleft.createChild("Label")
  butonLabel.text = "Go back"
  butonLabel.translation = [80, 15]

  LabelNav = m.top.CreateChild("Label")
  LabelNav.text = "SEARCH CHANNELS"
  LabelNav.translation = [100, 80]
end sub

sub searchKeyboard()
  ?"searchKeyboard"
  m.Searchkeyboard = m.top.createChild("MiniKeyboard")
  m.Searchkeyboard.id = "Searchkeyboard"
  m.Searchkeyboard.translation = "[100,200]"
  m.Searchkeyboard.observeField("text", "channelSearchText")

  if getsearch_channel_num() <> ""
    m.Searchkeyboard.setFocus(false)

    resultentList()
    m.ChannelsMarkuGrid.setFocus(true)

  else
    m.Searchkeyboard.setFocus(true)
  end if
  m.Searchkeyboard .maxheight = 300
  m.Searchkeyboard .width = 300

end sub

sub setScreenBackGrndimage()
  ?"setScreenBackGrndimage"
  Poster = m.top.createChild("Poster")
  Poster.id = "Poster"
  Poster.translation = "[0,0]"
  Poster.height = 1080
  Poster.width = 1920

  if m.deviceinfo.getDisplaySize().w = 1920
    Poster.uri = "pkg:/images/homeScreenBckgrnd.png"
  else if m.deviceinfo.getDisplaySize().w = 1280
    Poster.uri = "pkg:/images/home/homeScreenBckgrndhd.png"
  else
    Poster.uri = "pkg:/images/home/homeScreenBckgrndhd.png"
  end if

end sub
sub channelSearchText(event as object)
  ?"channelSearchText; ";event.getData()
  m.inputText = event.getData()
  ' -----------------------------------------------------Use Filter Option here and remove the network call.
  m.spinner.visible = true
  showSearchChannelProgram(m.inputText)

  '   m.errorResultLabel.visible = false

  '   m.spinner.visible = true
  '   m.ChannelSearchTask = CreateObject("roSGNode", "ChannelSearchTask") 'Create XML Parsing task node
  '   m.ChannelSearchTask.callType = "POST"
  '   m.ChannelSearchTask.parameters = { "search": m.inputText }
  '   m.ChannelSearchTask.observeField("response", "responseAPI")
  '   m.ChannelSearchTask.control = "RUN"
  ' else
  '   m.ChannelsMarkuGrid.visible = false
  '   m.errorResultLabel.text = "Please type some text for search the channels :"
  '   m.errorResultLabel.visible = true

end sub
' -----------------------------------------------All channel Api Calling and showing its data------------------------------
sub allChannelApiCall()
  ' ? "getChannelDAta;"
  m.spinner.visible = true
  m.allChannel = CreateObject("roSGNode", "AllChannleTask")
  currentDate = CreateObject("roDateTime")
  param = CreateObject("roAssociativeArray")
  m.allChannel.callType = "GET"
  ' param = CreateObject("roAssociativeArray")
  ' param["hoursoffset"] = timezone
  param["typeurl"] = "manifest"


  ' param["hoursoffset"] = "+05:30"
  ' param["hoursoffset"] = Substitute("{0}:{1}",utcHourOffset,utcMinutesOffset)
  ?"parameter: "param
  m.allChannel.parameters = param

  m.allChannel.observeField("response", "allChannelApiResponse")
  ' m.TestingTAsk.observeField("errorResponse", "errorDialogShow")
  m.allChannel.control = "RUN"

end sub

' creating a function for All Channel Api call response
sub allChannelApiResponse(event as object)

  ' response success type
  ' {
  ' "success": true,
  ' "data": [
  '   {
  '     "id": 25,
  '     "callSign": "CNBCHD",
  '     "image": "CNBC.png",
  '     "url": "https://cdn.streamlytv.com/H-CNBC/index.mpd",
  '     "genrename": [
  '       "Comedy"
  '     ],
  '     "logo": "http://127.0.0.1:8000/uploads/channellogo/CNBC.png"
  '   }]
  videoIdInt = getVideoid1().toInt()
  videoIds = LoadVideo_id()


  ?"all Channel Data -> ": event.getData()
  if(event.getData().success = true and event.getData().response_code = 200)
    m.AllChannelApiResponse = event.getData()
    m.labelContent = createObject("roSGNode", "ContentNode")
    for each channel in event.getData().data
      ?"Search::chanel details :";channel
      channelDetail = m.labelContent.createChild("ContentNode")
      if channel.callSign <> invalid
        channelDetail.title = channel.stationName
      else
        channelDetail.title = "other"
      end if

      if channel.id = 11
        if channel.url <> invalid then channelDetail.url = channel.url
        if channel.id <> invalid then channelDetail.id = channel.id
        if channel.logo <> invalid then channelDetail.HDSMALLICONURL = channel.logo
        if channel.callSign <> invalid then channelDetail.title = "Horse Racing"
      else

        if channel.url <> invalid then channelDetail.url = channel.url
        if channel.id <> invalid then channelDetail.id = channel.id
        if channel.logo <> invalid then channelDetail.HDSMALLICONURL = channel.logo
        if channel.callSign <> invalid then channelDetail.title = channel.stationName
      end if



    end for
    m.ChannelsMarkuGrid.content = m.labelContent

    m.ChannelsMarkuGrid.visible = true
  end if
  m.spinner.visible = false

   for i = 0 to videoIds.count() - 1
    ?"getVideoid().toInt() "videoIdInt
      if videoIds[i] = videoIdInt then
        ?"LoadVideo_id()[i] search channel"i
        setsearch_channel_num(i.toStr())
        m.ChannelsMarkuGrid.jumpToItem = i

        ' m.relatedChannelMarkupList.animateToItem = i - 3
        exit for
      end if
   
  end for
end sub


sub showSearchChannelProgram(searchName as string)
  ' response success type
  ' {
  ' "success": true,
  ' "data": [
  '   {
  '     "id": 25,
  '     "callSign": "CNBCHD",
  '     "image": "CNBC.png",
  '     "url": "https://cdn.streamlytv.com/H-CNBC/index.mpd",
  '     "genrename": [
  '       "Comedy"
  '     ],
  '     "logo": "http://127.0.0.1:8000/uploads/channellogo/CNBC.png"
  '   }]
  ?"search String"; searchName
  m.labelContent = createObject("roSGNode", "ContentNode")
  for each channel in m.AllChannelApiResponse.data

    if(Len(searchName) > 0)
      ' ?"search:"Instr(1, LCase(channel.stationName), searchName)
      if (Instr(1, LCase(channel.stationName), searchName) > 0 or Instr(1, LCase(channel.id.toStr()), searchName))

        channelDetail = m.labelContent.createChild("ContentNode")
        if channel.url <> invalid then channelDetail.url = channel.url
        if channel.id <> invalid then channelDetail.id = channel.id
        if channel.logo <> invalid then channelDetail.HDSMALLICONURL = channel.logo
        if channel.callSign <> invalid then channelDetail.title = channel.stationName
        ?"Search::chanel details :";m.labelContent.getChildCount()
        if m.labelContent.getChildCount() = 1
          m.ChannelsMarkuGrid.numColumns = "1"
        else if m.labelContent.getChildCount() = 2
          m.ChannelsMarkuGrid.numColumns = "2"
        else
          m.ChannelsMarkuGrid.numColumns = "3"
        end if

        ' m.ChannelsMarkuGrid.numColumns = "1"
        ' ?"Search::chanel :"; channelDetail.id

      end if

    else
      channelDetail = m.labelContent.createChild("ContentNode")
      if channel.url <> invalid then channelDetail.url = channel.url
      if channel.id <> invalid then channelDetail.id = channel.id
      if channel.logo <> invalid then channelDetail.HDSMALLICONURL = channel.logo
      if channel.callSign <> invalid then channelDetail.title = channel.stationName

    end if

  end for
  m.ChannelsMarkuGrid.content = m.labelContent
  m.ChannelsMarkuGrid.visible = true
  m.spinner.visible = false
end sub

' sub responseAPI(event as object)
'   ?"SearchScreen::responseAPI: ";event.getData()
'   m.errorResultLabel.visible = false
'   dataJson = event.getData()

'   if dataJson.success = true
'     ?"dataJson:dataJson:  ";dataJson.data[0]
'     '    ?"dataJson:dataJson:  ";dataJson.message.data[0]
'     if dataJson.data[0] <> invalid
'       m.labelContent = createObject("roSGNode", "ContentNode")
'       for each channel in dataJson.data
'         ?"Search::chanel details :";channel
'         channelDetail = m.labelContent.createChild("ContentNode")
'         if channel.callSign <> invalid
'           channelDetail.title = channel.callSign
'         else
'           channelDetail.title = "other"
'         end if
'         if channel.url <> invalid then channelDetail.url = channel.url
'         if channel.id <> invalid then channelDetail.id = channel.id
'         if channel.logo <> invalid then channelDetail.HDSMALLICONURL = channel.logo
'         if channel.callSign <> invalid then channelDetail.title = channel.callSign
'       end for
'       m.ChannelsMarkuGrid.content = m.labelContent
'       m.ChannelsMarkuGrid.visible = true
'     else
'       m.errorResultLabel.text = "No Channels for " + "' " + m.inputText + " '"
'       m.errorResultLabel.visible = true
'       m.ChannelsMarkuGrid.visible = false
'     end if
'   end if

'   '  m.labelContent.createChild("ContentNode").title=event.getData()
'   m.spinner.visible = false
' end sub
function recognizer()
  if m.recognizer = invalid
    m.recognizer = CreateObject("roSGNode", "SpeechRecognizer")
    m.recognizer.delegate = m.top
  end if
  return m.recognizer
end function

function speechRecognizerDidReceiveTranscript(transcript as string)
  ?"speechRecognizerDidReceiveTranscript"
  ?"transcript:"; transcript
  m.spinner.visible = false
  m.lower = LCase(transcript).Replace(".", "")
  m.Searchkeyboard.text = m.lower
  ' m.lower = LCase(transcript)
  ' ?"lower"m.lower
  showSearchChannelProgram(LCase(transcript).Replace(".", ""))

  ' m.infoLb.text = "Hold the OK button to start dictation, release it once you're done."

  m.searchStart.focusfootprintBitmapUri = "pkg:/images/Search/mic_focus_icon.png"
  m.Searchkeyboard.setFocus(false)
  m.top.setFocus(true)

end function

function showProgressBar(value as object)

  if value
    m.spinner.visible = true
  else
    m.spinner.visible = false
  end if

end function

function onKeyEvent(key, press) as boolean
  ?"Search screen onKeyEvent";key ;"has focus";m.searchStart.hasFocus() ;"press ";press
  result = false
  if press
    if key = "OK" and press
      ?"start voice ..."
      recognizer().callFunc("startListening", {})

      return true
    end if
    ?"SearchScreen: ";key
    ?"m.Searchkeyboard.hasFocus(); ";m.Searchkeyboard.hasFocus()
    if (key = "right" and m.ChannelsMarkuGrid.visible = true) then
      m.Searchkeyboard.setFocus(false)
      m.ChannelsMarkuGrid.setFocus(true)
      result = true
    else if key = "left" and m.ChannelsMarkuGrid.hasFocus() then
      m.ChannelsMarkuGrid.setFocus(false)
      m.Searchkeyboard.setFocus(true)
      result = true
    else if key = "back" then
      'm.screen = showHomeScreen()
      ' setUserHomeScreenMode("Home")
      'getUserHomeScreenMode()
      m.top.exitView = true
      result = true

    else if m.top.hasFocus() and key = "down"
      m.searchStart.focusfootprintBitmapUri = "pkg:/images/Search/mic_unfocus_icon.png"
      m.Searchkeyboard.setFocus(true)
    else if key = "up"
      m.searchStart.focusfootprintBitmapUri = "pkg:/images/Search/mic_focus_icon.png"
      m.Searchkeyboard.setFocus(false)
      m.top.setFocus(true)

    end if
    result = true
  end if
  return result
end function

function showHomeScreen() as object
  ? "showHomeScreen"
  if m.screen <> invalid then m.top.removeChild(m.screen)
  ' ShowScreen("HomeScreen")
  HomeScreen = m.top.createChild("HomeScreen")
  HomeScreen.setFocus(true)
  return HomeScreen
end function