' ********** Copyright 2016-2019 Roku Corp.  All Rights Reserved. **********

Function init()
    'set video node'
    m.ExtVideo = m.top
    m.top.observeField("state", "handleStateChange")
    m.top.observeField("bufferingStatus", "handleBufferingStatus")
'    m.top.observeField("position", "handlePosition")

    initProgressBar()
    initThumbnails()       ' hostPefix and trickInterval are passed in from interface fields'

    initLoadingBar()

    m.pressmenuButton.Font.size="20"
     m.trickInterval=0
end Function

'sub handlePosition(event as object)
'?"handlePosition: ";event.getData()
'?"handlePosition: state";event.getRoSGNode().state
'end sub

Function handleStateChange(msg)
?"handleStateChange: msg.getData(): ";msg.getData() 
 
  if type(msg) = "roSGNodeEvent" and msg.getField() = "state"
  
      
      if   msg.getField() = "state" and msg.getData()="buffering"  
        m.playPause.visible =false  
        m.headerIcons.visible=false
     
      else if   msg.getField() = "state" and msg.getData()="stop"        
       m.playPause.uri = "pkg:/images/home/playicon.png"
       m.playPause.visible =true
        
      end if
      state = msg.getData()
      if state = "finished"
'          m.top.getScene().exitApp = true
'      else if m.ExtVideo.thumbnailHostPrefix <> ""
'          ' This is to take in the video node thumbnails and content duration
'          ' Is there a better way? '
'          m.hostPrefix = m.ExtVideo.thumbnailHostPrefix
'          m.trickInterval = m.ExtVideo.thumbnailIntervalInSecs
      else
          m.hostPrefix = invalid
          m.trickInterval = 5
      end if
      m.videoDuration = m.top.duration
  end if
end Function

Function formatNumberString(number as Integer) as String
  numberText = number.toStr()
  if number <= 0
    numberText = "00"   ' min number is 0'
  else if number < 10
    numberText = "0" + numberText
  else if number > 99
    numberText = "99"   ' max number - saturates at 99'
  end if
  return numberText
end Function

Function setTimeText(timeInSeconds) as String
  hoursText = formatNumberString( Fix(timeInSeconds / 3600) )
  minutesText = formatNumberString( Fix(timeInSeconds / 60) )
  secondsText = formatNumberString( Fix(timeInSeconds MOD 60) )

  timeText = minutesText + ":" + secondsText
  if hoursText <> "00"
    timeText = hoursText + ":" + timeText
  end if

  return timeText
end Function
sub showHideThumbnail()
?"m.top.thumbnailImage: ";m.top.thumbnailImage
 if m.top.thumbnailImage <>""
     
     m.thumbnailVideoImage.uri = m.top.thumbnailImage
     m.thumbnailVideoImage.visible = true
     m.opcitythumpnail.visible = true
      
 else

     m.thumbnailVideoImage.visible = false
     m.opcitythumpnail.visible = false

 end if
 
end sub
' *** Progress Bar Section *** '
Function initProgressBar()
  'progress bar nodes'
  
  m.pressmenuButton = m.top.findNode("pressmenuButton")
  m.playPause = m.top.findNode("playPause")
  m.thumbnailVideoImage = m.top.findNode("thumbnailVideoImage")
  m.opcitythumpnail = m.top.findNode("opcitythumpnail")
  
  m.headerIcons = m.top.findNode("headerIcons")
  m.headerIcons.visible=true
  
  m.progress = m.top.findNode("progress")
  m.progress.visible = true

  m.progressWidth = m.top.findNode("outlineRect").width
  m.progressRect = m.top.findNode("progressRect")
  m.currentseek = m.top.findNode("currentseek")
  
'  m.leftProgressLabel = m.top.findNode("leftProgressLabel")
'  m.rightProgressLabel = m.top.findNode("rightProgressLabel")
  m.progressMode = m.top.findNode("progressMode")
end Function

Function showProgressBar(position)
 m.playPause.uri = "pkg:/images/home/playicon.png"   
'        m.playPause.visible =true

    m.progress.visible = true
    m.playPause.visible =true
    m.headerIcons.visible = true
if position <> 0 and m.videoDuration <>0
    
    m.progressRect.width = position * m.progressWidth / m.videoDuration
    leftPositionSeconds = position * 100 / 100
    m.currentseek.translation = [m.progressRect.width-20,-12]
    rightPositionSeconds = m.videoDuration - leftPositionSeconds
'    m.leftProgressLabel.text = setTimeText(leftPositionSeconds)
'    m.rightProgressLabel.text = setTimeText(rightPositionSeconds)
    setProgressMode()
end if
   
end Function

Function hideProgressBar()
    m.playPause.uri = "pkg:/images/home/pauseicon100.png" 
    m.progress.visible = false
    m.playPause.visible =false
    m.headerIcons.visible = false
end Function

Function setProgressMode()
  if m.trickPlaySpeed = 0
    m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_PAUSE_HD.png"
  else if m.trickplayDirection = "forward"
    if m.trickplaySpeed = 1
      m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_FWDx1_HD.png"
    else if m.trickplaySpeed = 2
      m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_FWDx2_HD.png"
    else
      m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_FWDx3_HD.png"
    end if
  else if m.trickplayDirection = "reverse"
    if m.trickplaySpeed = 1
      m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_REWx1_HD.png"
    else if m.trickplaySpeed = 2
      m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_REWx2_HD.png"
    else
      m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_REWx3_HD.png"
    end if
  end if
end Function

Function setProgressModePlay()
  m.progressMode.uri = "pkg:/images/TrickPlay_ButtonMode_PLAY_HD.png"
end Function

' *** Thumbnails Section *** '
Function initThumbnails()
 
  m.trickPosition = 0
    ' Timer is used during ffwd/rew, thumbnails move when the fire event hits'
  m.trickPlayTimer = createObject("roSGNode", "Timer")
  m.trickPlayTimer.duration = 1
  m.trickPlayTimer.repeat = True
  m.trickPlayTimer.observeField("fire", "handleTrickPlayTimer")

  resetSeekLogic()
end Function

Function resetSeekLogic()
  ' ffwd/rew happen from a paused state, i.e. m.trickplayDirection = "paused"
  m.trickplayDirection = "paused"
  m.trickPlaySpeed = 0
  m.trickPlayTimer.duration = 1
end Function

Function isSeeking() as boolean
  return m.trickplayDirection <> "paused"
end Function

Function startSeeking(key)  ' key is either "fastforward" or "rewind"
  if m.trickplayDirection = "paused"
      m.trickplaySpeed = 1
      m.trickPlayTimer.duration = 1 / m.trickPlaySpeed
      ' set the trickplay direction'
      if key = "fastforward" or key = "forward"
        m.trickplayDirection = "forward"
      else
        m.trickplayDirection = "reverse"
      end if

      m.trickPlayTimer.control = "START"
  else if key = "fastforward" and m.trickplayDirection <> "forward" or key = "rewind" and m.trickplayDirection <> "reverse"
      pauseSeeking(key, m.trickPosition * m.trickInterval)
  else
      ?" set the trick play speed"
      ?"m.trickPlaySpeedplay speed:: ";m.trickPlaySpeed 
      m.trickPlaySpeed = m.trickPlaySpeed+ 1
      ?"m.trickPlaySpeed += 1: ";m.trickPlaySpeed 
      if m.trickPlaySpeed > 3 ' saturate trickplay speed at 3x'          
          m.trickPlaySpeed = 1
      end if
      
      m.trickPlayTimer.control = "STOP"
      m.trickPlayTimer.duration = 1 / m.trickPlaySpeed
      m.trickPlayTimer.control = "START"
    
  end if
  print "trickPlaySpeed= "; m.trickPlaySpeed
end Function

Function pauseSeeking(key, position)  ' key is either "left" or "right"
if m.trickPosition<>invalid and m.videoDuration<>invalid and m.trickInterval<>invalid


  m.trickPlayTimer.control = "STOP"
  m.trickPlaySpeed = 0
  if m.trickplayDirection = "paused"
'    showThumbnails(position)
    if key = "right"
      m.trickplayDirection = "forward"
    else
      m.trickplayDirection = "reverse"
    end if
  else if key = "right" or key = "fastforward"    ' shift thumbnails to the left - forward
    if m.trickPosition < Fix((m.videoDuration / m.trickInterval) + 1)
        m.trickPosition += 1
'        shiftThumbnailsLeft()
    end if
    m.trickplayDirection = "forward"
  else if key = "left" or key = "rewind"    ' shift thumbnails to the right - reverse
    if m.trickPosition > 0
        m.trickPosition -= 1
'        shiftThumbnailsRight()
    end if
    m.trickplayDirection = "reverse"
  end if
'  position = m.trickPosition * m.trickInterval
  showProgressBar(position)

end if
end Function

Function endSeeking()
  m.trickPlayTimer.control = "STOP"
  ' is there a better way to force seek position?'
  m.ExtVideo.control = "stop"
  m.ExtVideo.control = "play"
'  ?"m.trickPosition * m.trickInterval: ";m.trickPosition * m.trickInterval
'  ?"Cdbl(m.trickPosition * m.trickInterval)";Cdbl(m.trickPosition * m.trickInterval)
  seekPosition = Cdbl(m.trickPosition * m.trickInterval)

  m.ExtVideo.seek = seekPosition
  resetSeekLogic()
  setProgressModePlay()
end Function

Function handleTrickPlayTimer() as Void
    if m.trickplayDirection = "forward"
        if m.trickPosition * m.trickInterval + m.trickInterval >= m.videoDuration ' handle fast foward overflow
'            shiftThumbnailsLeft()
            showProgressBar(m.videoDuration)
            pauseSeeking("right", m.videoDuration)
            return
        else if m.trickPosition < Fix((m.videoDuration / m.trickInterval) + 1)
            m.trickPosition += 1
'            shiftThumbnailsLeft()
        end if
    else if m.trickplayDirection = "reverse"
        if m.trickPosition > 0
            m.trickPosition -= 1
'            shiftThumbnailsRight()
        else
            pauseSeeking("left", 0)
        end if
    end if
    showProgressBar(m.trickPosition * m.trickInterval)
end Function

' *** Loading Bar Section *** '
Function initLoadingBar()
  m.loadingPercentage = 0
  m.loading = m.top.findNode("loading")
  m.loading.visible = false
  m.loadingWidth = m.top.findNode("loadOutlineRect").width
  m.loadingProgressRect = m.top.findNode("loadProgressRect")
end Function

Function showLoadingBar()
  m.loading.visible = true
  m.loadingProgressRect.width = m.loadingPercentage * m.loadingWidth / 100
end Function

Function hideLoadingBar()
  m.loading.visible = false
  m.loadingPercentage = 0
end Function

Function handleBufferingStatus(msg)
  bufferingStatus = msg.getData()
  if bufferingStatus <> invalid
    m.loadingPercentage = bufferingStatus.percentage
    print "buffering - percentage= "; m.loadingPercentage
    showLoadingBar()
    if m.loadingPercentage = 100
      hideLoadingBar()
      hideProgressBar()
'      hideThumbnails()
    end if
  end if
end Function


Function onKeyEvent(key as String, press as Boolean) as Boolean  'Maps back button to leave video
   ? "onKeyEvent: "; key
    handled = false
    if press
        if key = "fastforward" or key = "rewind"
          if not isSeeking()
            position = m.ExtVideo.position
          else
            position = m.trickPosition * m.trickInterval
            if key = "rewind" then position -= m.trickInterval
          end if

          if m.trickPlaySpeed = 0 then m.initPos = position

          if m.videoDuration<>invalid and position < m.videoDuration ' block additional fast forwards from going off the progress bar
            showProgressBar(position)
            startSeeking(key)
'            showThumbnails(position)
          end if

        else if key = "left" or key = "right"
          if not isSeeking()
            position = m.ExtVideo.position
          else
          ?"m.trickPosition; ";m.trickPosition
          ?"m.trickInterval; ";m.trickInterval
          
            position = m.trickPosition * m.trickInterval
          end if
          ?"position:ckeing ;";position
          pauseSeeking(key, position)
        else if key = "play" or key = "OK"
            if m.ExtVideo.state = "playing"
                m.initPos = m.ExtVideo.position
                m.trickPosition = m.ExtVideo.position
                showProgressBar(m.ExtVideo.position)
                m.ExtVideo.control = "pause"
                m.trickPlayDirection = "paused"
            else
                if isSeeking()
                    showLoadingBar()
                    endSeeking()
                else
                    m.ExtVideo.control = "resume"
                    hideProgressBar()
                end if
            end if
        end if
    end if

    return handled
end Function
