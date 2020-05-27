' ********** Copyright 2016 Roku Corp.  All Rights Reserved. **********  

' ********** Copyright 2020 Roku Corp.  All Rights Reserved. **********
' ********** Copyright 2019 Roku Corp.  All Rights Reserved. **********

'sub Show(args as Object)
'
'  
''          setAccountToken("")  
'   if getAccountToken() <> Invalid and getAccountToken() <> "" then
'    
'    ' Show first screen
'    
'    m.screentoOpen = CreateObject("roSGNode", "TimeGridView")
'    
'      content = CreateObject("roSGNode", "ContentNode")
'      content.AddFields({
'           HandlerConfigTimeGrid: {
'                   name: "CHRoot"
'                   }
'      })
'     
'     m.screentoOpen.content = content
'     
''    gridConfig = GetButtonBarScreensConfig()["TimeGridView"]
'    
'     m.screentoOpen.ObserveField("ProfileScreen", "openProfile")
' 
'   else
'   
'     ?"expired access token: "
''     gridConfig = GetButtonBarScreensConfig()["LoginScreen"]
'     m.screentoOpen = CreateObject("roSGNode", "LoginScreen")
'     m.screentoOpen.ObserveField("closeChannel", "closeChannel")
''     m.top.allowCloseChannelOnLastView= true
'   endif
'    
'     m.top.ComponentController.CallFunc("show", {
'        view: m.screentoOpen
'    
'     })
'    m.top.componentController.selectStack = "default" 
''    m.top.ComponentController.allowCloseChannelOnLastView =true 
'    m.top.signalBeacon("AppLaunchComplete")
'    
''    m.top.signalBeacon("AppLaunchComplete")
'    
'end sub
'sub closeChannel()
'?"closeChannel"
'm.screentoOpen.close = true
''    m.top.getScene().exitChannel = true
'     
'end sub
'  
'sub openProfile(event as Object)
'    timerGrid = event.GetRoSGNode()
''              = timerGrid.getData()
'  ?"openProfile; "
'    
'     if timerGrid.getData() then
'      ShowProfileSettingView()
'     end if
'     
'end sub
'sub OnGridItemSelected(event as Object)
'    grid = event.GetRoSGNode()
'    selectedIndex = event.GetData()
'    rowContent = grid.content.GetChild(selectedIndex[0])
'    detailsView = ShowDetailsView(rowContent, selectedIndex[1])
'    detailsView.ObserveField("wasClosed", "OnDetailsWasClosed")
'end sub
'
'sub OnDetailsWasClosed(event as Object)
'    details = event.GetRoSGNode()
'    m.grid.jumpToRowItem = [m.grid.rowItemFocused[0], details.itemFocused]
'end sub
'




















'
'
'sub Show(args as Object)
'
'   ' Add more stacks for each button
''    m.top.componentController.addStack = "stack_1"
''    m.top.componentController.addStack = "stack_2"
''    m.top.componentController.addStack = "stack_3"
''    m.top.componentController.selectStack = "default"   ' default stack is created automatically
'
''        setAccountToken("")  
'   if getAccountToken() <> Invalid and getAccountToken() <> ""
'    
'    ' Show first screen
'     gridConfig = GetButtonBarScreensConfig()["TimeGridView"]
'     
'   else
'    
'     ?"expired access token: "
'     gridConfig = GetButtonBarScreensConfig()["LoginScreen"]
'   endif
'   
'   ShowNewScreenFromConfig(gridConfig)
'   
'
'    
'end sub
'
'sub OnButtonBarItemSelected(event as Object)
'    itemSelected = event.GetData()
'    RetrieveScreenFromItem(itemSelected)
'end sub
'
'sub RetrieveScreenFromItem(item as Object)
''    button = m.top.buttonBar.content.GetChild(item)
'    screenConfig = GetButtonBarScreensConfig()[item]
'    activeStack = m.top.componentController.activeStack
'    activeView = m.top.componentController.currentView
'
'    if screenConfig.stackName = activeStack
'        ' user selected the active stack, thus move focus back on view
'        ' to prevent view reloading
'        if activeView <> invalid then activeView.SetFocus(true)
'    else
'        ' Select certain stack
'        m.top.componentController.selectStack = screenConfig.stackName
'
'        if m.top.ComponentController.currentView = invalid ' this stack hasn't been used yet
'            ShowNewScreenFromConfig(screenConfig)
'        else
'            ' view has been already shown, therefore just move focus on it
'            currentView = m.top.componentController.currentView
'            if currentView <> invalid then currentView.SetFocus(true)
'        end if
'    end if
'end sub
'
'
'' Set up and show a new view
'sub ShowNewScreenFromConfig(config as Object)
'    newScreen = CreateObject("roSGNode", config.screenName)
'    content = CreateObject("roSGNode", "ContentNode")
'
'    content.AddFields(config.handlerConfig)
'    if config.fieldsToSet <> invalid
'        newScreen.SetFields(config.fieldsToSet)
'    end if
''    newScreen.content = content
'
'    if config.screenName = "LoginScreen"
'        ShowLoginView(newScreen)
'    else if config.screenName = "TimeGridView"
'        ShowTimeGridView(newScreen)
'    else if config.screenName = "VideoPlayer"
'        ShowVideoPlayer(newScreen)
'    else if config.screenName = "ProfileSettingScreen"
'        ShowProfileSettingView(newScreen)        
'    end if
'
'end sub
'
'' Retrieve config for corresponding stack and view using button ID
'function GetButtonBarScreensConfig() as Object
'    config = {
'        LoginScreen: {
'            stackName: "stack_1"
'            screenName: "LoginScreen"
'            handlerConfig: {
''                HandlerConfigGrid: {
''                    name: "GridHandler"
''                    fields: {
''                        contentType: "movies"
''                    }
''                }
'            }
'        }
'        TimeGridView: {
'            stackName: "default"
'            screenName: "TimeGridView"
'            handlerConfig: {
'                   HandlerConfigTimeGrid: {
'                   name: "CHRoot"
'                   }
'            }
'       }
'        ProfileSettingScreen: {
'            stackName: "stack_2"
'            screenName: "ProfileSettingScreen"
'        }
''        VideoPlayer: {
''            stackName: "stack_3"
''            screenName: "VideoPlayer"
''        }
'    }
'    return config
'end function

'function onKeyEvent(key,press)
'?"Main :key:";key
'?"Main :press:";press
'if press
'    if key = "options"
''    
''    gridConfig = GetButtonBarScreensConfig()["TimeGridView"]
'    activeStack = m.top.componentController.activeStack
'    activeView = m.top.componentController.currentView
''
'   ?"activeStack: ";activeStack
'   ?"activeView: ";activeView
'''   ?"gridConfig.stackName: ";gridConfig.stackName
''   
''    if "stack_1" = activeStack
''            ' user selected the active stack, thus move focus back on view
''        ' to prevent view reloading
''        if activeView <> invalid then activeView.SetFocus(true)
''    else    
'''       RetrieveScreenFromItem("TimeGridView")  
'      if  activeStack = "default"
'        gridConfig = GetButtonBarScreensConfig()["ProfileSettingScreen"] 
'        ShowNewScreenFromConfig(gridConfig)
'      end if   
'    end if       
'   end if 
'end if
'
'end function        
'


sub init()
    
'        setAccountToken("")  
   if getAccountToken() <> Invalid and getAccountToken() <> ""
            
      ?"login successfully: accessToken: ";getAccountToken()
       HomeScreen = m.top.createChild("HomeScreen")  
       HomeScreen.setFocus(true)
   else
   
      LoginScreen = m.top.createChild("LoginScreen")
      LoginScreen.observeField("dialod","showDialog")
      
      LoginScreen.setFocus(true)
'      LoginScreen.id ="LoginScreen"
'       SearchScreen.translation="[0,0]"
'        m.deviceinfo = getDeviceInfo()
      ?"expired access token: "
'       SearchScreen = m.top.createChild("SearchScreen")
'       SearchScreen.id ="SearchScreen"
'       SearchScreen.translation="[0,0]"
'       SearchScreen.height = m.deviceinfo.getDisplaySize().h
'       SearchScreen.width =m.deviceinfo.getDisplaySize().w


   end if  
    
End sub

'sub onShowProfile(event as Object)
'?"onShowProfile: ";
'
'         ProfileSettingScreen = CreateObject("roSGNode", "ProfileSettingScreen")
''           ProfileSettingScreen.observeField("wasClosed", "onPofileFinished")
'           m.top.ComponentController.callFunc("show", {
'           view: ProfileSettingScreen
'          })
'end sub
'sub onLoginFinished(event as Object)
'    loginView = event.getRosgNode()
'    if loginView.isSuccess then
''       ShowVideoPlayer()
'       HomeScreen = CreateObject("roSGNode", "HomeScreen")
'       HomeScreen.observeField("wasClosed", "onHomeFinished")
'       m.top.ComponentController.callFunc("show", {
'          view: HomeScreen
'       })
'    else
'        'do your logic
'    end if
'end sub
''
'sub onHomeFinished(event as Object)
'    homeView = event.getRosgNode()
'    if homeView.isSuccess then
''       ShowVideoPlayer()
'       LoginScreen = CreateObject("roSGNode", "LoginScreen")
'       LoginScreen.observeField("wasClosed", "onLoginFinished")
'       m.top.ComponentController.callFunc("show", {
'         view: LoginScreen
'       })
'    else
'        'do your logic
'    end if
'end sub
''
'
'sub logout()
'?"logout::expired access token: "
'      
'      m.HomeScreen.visible = false
'      m.ProfileSettingScreen.visible = false
'      m.LoginScreen.visible = true 
'      
'end sub
'sub focusSet()
'?"focusSet: ";
'
'  if m.ProfileSettingScreen.visible = false and  m.ProfileSettingScreen.findNode("ProfileCategory").hasFocus()    
'     m.HomeScreen.visible = true
'     m.HomeScreen.findNode("timeGrid").setFocus(true)
'          
'  end if
'end sub
'
'sub closeScreen()
'?"MainScreeen::closeScreen: ";
'  
'  if m.LoginScreen.visible = false
' 
'   ?"if close"
'   m.HomeScreen.visible = true
'   m.HomeScreen.setFocus(true) 
'  
'  else if m.LoginScreen.visible = true
' 
'   ?"else login screen open"
'      
'      m.HomeScreen.visible = false
'      m.HomeScreen.findNode("myVideo").visible= false
'      m.HomeScreen.findNode("ChannelsGroup").visible= false
'      m.LoginScreen.visible = true
'      
''      m.LoginScreen.findNode("UsernamEditText").setFocus(true)
'  
'  end if
''  
'end sub
sub showDialog()

 ?"MainScreen: showDialog: ";
 m.top.dialog = m.LoginScreen.dialog
 
end sub
'
'
'sub openScreen(event as object) 
'?"openScreen: ";event
'  
' if event.getData()=true
' 
'      m.HomeScreen.visible = false
'      m.HomeScreen.setFocus(false)
'      m.ProfileSettingScreen.visible=true
'      m.ProfileSettingScreen.setFocus(true)
' 
' end if
'end sub
''
'function onKeyEvent(key as String, press as Boolean) as Boolean
' ?"MainScree::key: ";key
'    result = false
'    
'    return result 
'end function