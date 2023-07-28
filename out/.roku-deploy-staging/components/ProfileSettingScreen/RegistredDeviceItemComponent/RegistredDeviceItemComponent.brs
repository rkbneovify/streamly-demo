sub init()
  m.device_image_poster = m.top.findNode("device_image")
  m.device_id_lable = m.top.findNode("device_id_lable")
  m.device_id_lable.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.device_name_per = m.top.findNode("device_name_per")
  m.device_name_per.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.room_number_lable = m.top.findNode("room_number_lable")
  m.room_number_lable.visible = false
  m.room_number_lable.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.room_number = m.top.findNode("room_number")
  m.room_number.visible = false
  m.room_number.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.device_name_lable = m.top.findNode("device_name_lable")
  m.device_name_lable.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.device_type = m.top.findNode("device_type")
  m.device_type.font = getFont(18, "pkg:/fonts/HelveticaNeueMedium.ttf")
  m.device_name = m.top.findNode("device_name")
  ' m.device_type = m.top.findNode("device_type")
  m.device_name.font = getFont(25, "pkg:/fonts/HelveticaNeueMedium.ttf")
  ' m.device_type.font = getFont(23, "pkg:/fonts/Helvetica-Neue-Light_22503.ttf")
  m.text_group_constainer = m.top.findNode("text_group_constainer")
  m.itemcursor = m.top.findNode("itemcursor")
  m.currentfocus = m.top.findNode("currentfocus")
end sub

sub showcontent()
  ? "itemContent " ; m.top.itemContent.Keys()
  ' for each userdetail in m.top.itemContent.Keys()
  device_name = m.top.itemContent.device_name
  device_image = m.top.itemContent.device_img
  ? "personal_device_name " ; m.top.itemContent.personaldevicename
  if(m.top.itemContent.personaldevicename <> invalid)
    device_name_per = m.top.itemContent.personaldevicename
  else
    device_name_per = "unknown"
  end if
  room_number = m.top.itemContent.hotel_room
  arr = m.top.itemContent.Keys()
  for each entry in arr
    if entry = "hotel_room"
      m.room_number_lable.visible = true
      m.room_number.visible = true
      if(m.top.itemContent.hotel_room <> invalid)
        room_number = m.top.itemContent.hotel_room
      else 
        room_number = "Unknown"
      end if 
    end if
  end for
  device_type = m.top.itemContent.device_type
  ' device_type = m.top.itemContent.device_type
  ? "device_image " ; device_image
  m.device_image_poster.uri = device_image
  m.device_name.text = device_name
  m.device_name_per.text = device_name_per
  m.device_type.text = device_type
  m.room_number.text = room_number
  ' m.device_type.text = device_type
  ' end for
  '       m.labelvalue.text = m.top.itemContent.textValue
  ' m.underline. width = m.labelvalue.boundingRect().width
  ' m.underline. height = 2

end sub


sub updateLayout()
  
  if m.top.height > 0 and m.top.width > 0 then
  end if
end sub



sub showfocus()
     
  ?"showfocus:";m.top.focusPercent
    m.currentfocus.opacity = m.top.focusPercent           

end sub