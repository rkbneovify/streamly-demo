sub init()
 ?"Error Dialog:"
 m.backgroungPoster = m.top.findNode("backgroungPoster")

 m.buttonClick = m.top.findNode("buttonClick")
 m.messageBody = m.top.findNode("messageBody")
 ?"m.buttonClick.getChild:"m.buttonClick.getChild(2)
 m.buttonClick.getChild(2).vertAlign ="center"
 m.buttonClick.getChild(2).horizAlign ="center"
' m.label = m.buttonClick.getChild(2)
'  m.buttonClick.getChild(2)["vertAlign"] ="center"
 
 
 
' CreateObject("roSGNode", "Dialog")
 
 
 
' m.ProfileTask = CreateObject("roSGNode", "TrackerTask")
'    m.ProfileTask.control="RUN"
 
 m.deviceInfo = getDeviceInfo()
 
' listeners()
 
end sub
sub setDialogMessage(event as object )
 if event.getData().text <> invalid                   <> invalid  m.messageBody.text = event.getData().text
'  centerx = ( m.deviceInfo.w - m.backgroungPoster.bitmapWidth) / 2
' centery = ( m.deviceInfo.h - m.backgroungPoster.bitmapHeight) / 2
' m.backgroungPoster.translation = [ centerx, centery ]
end sub
'sub listeners()
'  ?"listeners";
'  m.top.dialogParentcolor.observeField("","setDialogParentBackground")
'  m.top.dialogUri.observeField("","")
'  m.top.dialogText.observeField("","")
'  m.top.DialogButton.observeField("","")
'end sub

sub setDialogParentBackground()
 ?"setDialogParentBackground: "
end sub

sub setDialogUri(event as object)
 ?"setDialogUri: "
 
  posterUri = event.getData()
 
 if posterUri.uri                   <> invalid  m.backgroungPoster.uri = posterUri.uri 
' if posterUri.width                 <> invalid  m.backgroungPoster.width = posterUri.width 
' if posterUri.height                <> invalid  m.backgroungPoster.height = posterUri.height
' if posterUri.translation           <> invalid  m.backgroungPoster.translation = posterUri.translation
  
end sub

sub setDialogText(event as object)
 
 textData = event.getData()
 ?"setDialogText: textData: ";textData
' if textData.text <> invalid m.dialogText.text = textData.text
 
'       movieTitlerect =   m.dialogText.boundingRect()

'       centerx = (m.deviceInfo.getDisplaySize().w- movieTitlerect.width)/2
'       centery = (m.deviceInfo.getDisplaySize().h - movieTitlerect.height)/2
       
'       m.dialogText.translation = [ 500, 500 ]
       
       
end sub
 
                
sub setDialogButton(event as object)
 
'  buttondata=event.getData()
'   ?"setDialogButton: buttondata:"buttondata;
''  if buttondata                  <> invalid m.buttonClick.addFields(buttondata)
'  if buttondata.height                   <> invalid m.buttonClick = buttondata.height
'  if buttondata.width                    <> invalid m.buttonClick.minWidth = buttondata.width
'  if buttondata.width                    <> invalid m.buttonClick.maxWidth = buttondata.width
'  if buttondata.translation              <> invalid m.buttonClick.translation = buttondata.translation
'  if buttondata.focusedTextColor         <> invalid m.buttonClick.focusedTextColor = buttondata.focusedTextColor
'  if buttondata.focusFootprintBitmapUri  <> invalid m.buttonClick.focusFootprintBitmapUri = buttondata.focusFootprintBitmapUri
'  if buttondata.focusBitmapUri           <> invalid m.buttonClick.focusBitmapUri = buttondata.focusBitmapUri
'  if buttondata.BitmapUri                <> invalid m.buttonClick.BitmapUri = buttondata.BitmapUri
'  if buttondata.showFocusFootprint       <> invalid m.buttonClick.showFocusFootprint = buttondata.showFocusFootprint
'  if buttondata.iconUri                  <> invalid m.buttonClick.iconUri = buttondata.iconUri
'  if buttondata.focusedIconUri           <> invalid m.buttonClick.focusedIconUri = buttondata.focusedIconUri
'  
  
'  m.buttonClick.translation = [ 200, 200 ]
'  if buttondata.text                     <> invalid m.buttonClick.text = buttondata.text
 
end sub