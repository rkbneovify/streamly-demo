sub init()

m.ProfileCategory = m.top.findNode("ProfileCategory")
m.Dialog = m.top.findNode("Dialog")
m.ok = m.top.findNode("ok")


m.ProfileCategory.observeField("itemSelected","initDialogMrkupWithData")

m.dialogOptions = m.top.findNode("dialogOptions")

initMrkupWithData()

m.top.observeField("visible","setfocus")


end sub
sub initMrkupWithData()
?"m.ChannelCategoryMarkuGrid:init()"
 
 marupContent = createObject("roSGNode", "ContentNode")
 
 data = marupContent.createChild("ContentNode")
 data.text = "Account"
 data.SDPosterUrl = "pkg:/images/profile/profileicon.png"

 data = marupContent.createChild("ContentNode")
 data.text = "Registered Devices"
 data.SDPosterUrl = "pkg:/images/profile/devicesicon.png"
  
 data = marupContent.createChild("ContentNode")
 data.text = "Support"
 data.SDPosterUrl = "pkg:/images/profile/supporticon.png"
 
 data = marupContent.createChild("ContentNode")
 data.text = "Privacy & Policy"
 data.SDPosterUrl = "pkg:/images/profile/privicypolicyicon.png"
 
 data = marupContent.createChild("ContentNode")
 data.text = "Sign out"
 data.SDPosterUrl = "pkg:/images/profile/logouticon.png"
 
 m.ProfileCategory.content = marupContent
 

 
 end sub
 
 sub initDialogMrkupWithData()
?"m.ChannelCategoryMarkuGrid:init()"
 
 marupContent = createObject("roSGNode", "ContentNode")
 
 data = marupContent.createChild("ContentNode")
 
 assoArray = createObject("roAssociativeArray")
 assoArray.textName = "Name"
 assoArray.textValue = "Zondo"
 data.addFields(assoArray)
 

 data = marupContent.createChild("ContentNode")
 assoArray.textName = "DOB"
 assoArray.textValue = "14/04/2020"
 data.addFields(assoArray)
   
 data = marupContent.createChild("ContentNode")
 assoArray.textName = "Gender"
 assoArray.textValue = "Male"
 data.addFields(assoArray)
 
 data = marupContent.createChild("ContentNode")
 assoArray.textName = "country"
 assoArray.textValue = "USA"
 data.addFields(assoArray)
 
 data = marupContent.createChild("ContentNode")
 assoArray.textName = "Address"
 assoArray.textValue = "California"
 data.addFields(assoArray)
 
 data = marupContent.createChild("ContentNode")
 assoArray.textName = "zipcode"
 assoArray.textValue = "1234"
 data.addFields(assoArray)
 
 data = marupContent.createChild("ContentNode")
 assoArray.textName = "Contact"
 assoArray.textValue = "1331212"
 data.addFields(assoArray)
 
 m.dialogOptions.content = marupContent
 m.dialogOptions.visible=true
 m.Dialog.visible =true
 m.dialogOptions.setFocus(false)
 m.ok.setFocus(true)
 m.ProfileCategory.setFocus(false)
 end sub
 
 
sub setfocus()
  ?"ProfileScreen: ";m.top.visible
  if m.top.visible  = true
    m.ProfileCategory.setFocus(true)
  end if
 
end sub 
 
function onKeyEvent(key as String, press as Boolean) as Boolean
    ?"key: ";key
    ?"press: ";press
    result = false
    
    if press
     
     if m.ok.hasFocus() and key = "back" 
        
        m.ok.setFocus(false)
        m.ProfileCategory.setFocus(true)
        
        m.Dialog.visible=false
        return true
     else if m.ProfileCategory.hasFocus() and key = "back" 
      m.top.visible = false
      return true
     end if 
    end if
    
end function     