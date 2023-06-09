

''Access Token
function getAccountToken() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("accountToken")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setAccountToken(accountToken as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("accountToken", accountToken)
end function

function getVideourl() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("videourl")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setVideourl(videourl as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("videourl", videourl)
end function

function getVideoscreen() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("videoscreen")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setVideoscreen(videoscreen as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("videoscreen", videoscreen)
end function

function deleteVideoscreen() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Delete("videoscreen")
end function

function getVideoid() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("videoid")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setVideoid(videoid as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("videoid", videoid)
end function

function getnextprev() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("nextprev")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setnextprev(key as String, array as Object) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    ' sec.Write("nextprev", array.ToString())
end function

function getLogin() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("login")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setLogin(login as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("login", login)
end function

function deleteLogin() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Delete("login")
end function

''Access Token
function getRegister() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("register")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setRegister(register as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("register", register)
end function

function deleteRegister() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Delete("register")
end function

function getVide_val() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("videval")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setVide_val(videval as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("videval", videval)
end function

function deleteVide_val() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Delete("videval")
end function

sub SaveVideo_back(settings as object)
    SaveVideo_backitem("Video_back", FormatJSON({ settings: settings }, 1))
end sub

function LoadVideo_back() as dynamic
    json = GetVideo_backitem("Video_back")
    if json <> ""
        obj = ParseJSON(json)
        if obj <> invalid
            return obj.settings
        end if
    end if
    return invalid
end function

function GetVideo_backitem(key as string, default = "") as string
    sec = CreateObject("roRegistrySection", "Backitem")
    if sec.Exists(key)
        return sec.Read(key)
    end if
    return default
end function

sub SaveVideo_backitem(key as string, value as string)
    sec = CreateObject("roRegistrySection", "Backitem")
    sec.Write(key, value)
    ' sec.Flush()
end sub

sub SaveVideo_id(settings as object)
    SaveVideo_iditem("Video_id", FormatJSON({ settings: settings }, 1))
end sub

function LoadVideo_id() as dynamic
    json = GetVideo_iditem("Video_id")
    if json <> ""
        obj = ParseJSON(json)
        if obj <> invalid
            return obj.settings
        end if
    end if
    return invalid
end function

function GetVideo_iditem(key as string, default = "") as string
    sec = CreateObject("roRegistrySection", "Backitem")
    if sec.Exists(key)
        return sec.Read(key)
    end if
    return default
end function

sub SaveVideo_iditem(key as string, value as string)
    sec = CreateObject("roRegistrySection", "Backitem")
    sec.Write(key, value)
    ' sec.Flush()
end sub

sub SaveVideo_id_drawyer(settings as object)
    SaveVideo_iditem_drawyer("Video_id", FormatJSON({ settings: settings }, 1))
end sub

function LoadVideo_id_drawyer() as dynamic
    json = GetVideo_iditem_drawyer("Video_id")
    if json <> ""
        obj = ParseJSON(json)
        if obj <> invalid
            return obj.settings
        end if
    end if
    return invalid
end function

function GetVideo_iditem_drawyer(key as string, default = "") as string
    sec = CreateObject("roRegistrySection", "Backitem")
    if sec.Exists(key)
        return sec.Read(key)
    end if
    return default
end function

sub SaveVideo_iditem_drawyer(key as string, value as string)
    sec = CreateObject("roRegistrySection", "Backitem")
    sec.Write(key, value)
    ' sec.Flush()
end sub

''Home Timezone
function getHomeTimezone() as dynamic
    sec = CreateObject("roRegistrySection", "HomeTimezone")
    return sec.Read("homeTimezone")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setHomeTimezone(homeTimezone as string) as dynamic
    sec = CreateObject("roRegistrySection", "HomeTimezone")
    sec.Write("homeTimezone", homeTimezone)
end function

''Current Timezone
function getCurrentTimezone() as dynamic
    sec = CreateObject("roRegistrySection", "CurrentTimezone")
    return sec.Read("currentTimezone")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setCurrentTimezone(currentTimezone as string) as dynamic
    sec = CreateObject("roRegistrySection", "CurrentTimezone")
    sec.Write("currentTimezone", currentTimezone)
end function

function getHome() as dynamic
    sec = CreateObject("roRegistrySection", "Home")
    return sec.Read("Home")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setHome(Home as string) as dynamic
    sec = CreateObject("roRegistrySection", "Home")
    sec.Write("Home", Home)
end function

function getProfilescreen() as dynamic
    sec = CreateObject("roRegistrySection", "Profile")
    return sec.Read("Profile")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setProfilescreen(Home as string) as dynamic
    sec = CreateObject("roRegistrySection", "Profile")
    sec.Write("Profile", Home)
end function

function deleteProfilescreen(Home as string) as dynamic
    sec = CreateObject("roRegistrySection", "Profile")
    sec.Delete("Profile", Home)
end function

function getUserCredential() as dynamic
    sec = CreateObject("roRegistrySection", "Userid")
    return sec.Read("Userid")
    ' return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvc3RyZWFtbHl0di5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE2MDczNjIzODcsImV4cCI6MTYwOTk5MDM4NywibmJmIjoxNjA3MzYyMzg3LCJqdGkiOiJpbFdZNkE0MnA1QUVxQjViIiwic3ViIjoxMiwicHJ2IjoiODdlMGFmMWVmOWZkMTU4MTJmZGVjOTcxNTNhMTRlMGIwNDc1NDZhYSIsIm5hbWUiOiJzaGFoamFkIiwicm9sZXMiOlt7ImlkIjozLCJuYW1lIjoidXNlciIsImRpc3BsYXlfbmFtZSI6InVzZXIiLCJkZXNjcmlwdGlvbiI6Im5vdCBhZG1pbiBwZXJtaXNpb24iLCJjcmVhdGVkX2F0IjpudWxsLCJ1cGRhdGVkX2F0IjpudWxsLCJwaXZvdCI6eyJ1c2VyX2lkIjoxMiwicm9sZV9pZCI6M319XX0.yAdxTXaoMhBw9NN7zajfUuQZ9_bBgdvwW7hbHgcQF7c"
end function

function setUserCredential(Userid as string) as dynamic
    sec = CreateObject("roRegistrySection", "Userid")
    sec.Write("Userid", Userid)
end function

' setThe Session name
function getPersonaldeviceid() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("personal_devic_id")
end function

function deleteUser() as object
    return "api/auth/userdelete/"
end function

' Set the room Selection
function setRoomSelectionFlag(isRoomSelect as boolean)
    roomSelect = ""
    if isRoomSelect
        roomSelect = "true"
    else
        roomSelect = "false"
    end if
    sec = CreateObject("roRegistrySection", "RoomSelectionFlag")
    sec.Write("RoomSelectionFlag", roomSelect)
end function

' Get the room Selection flag
function getRoomSelectionFlag() as dynamic
    sec = CreateObject("roRegistrySection", "RoomSelectionFlag")
    return sec.Read("RoomSelectionFlag")
end function

' Set data where user need to go when launch app.
function setUserHomeScreenMode(mode as string)
    sec = CreateObject("roRegistrySection", "LanuchScreenType")
    sec.Write("LanuchScreenType", mode)
end function

' Get data where user need to go when launch app.
function getUserHomeScreenMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchScreenType")
    return sec.Read("LanuchScreenType")
end function

function setFrontScreenMode(front as string)
    sec = CreateObject("roRegistrySection", "FrontType")
    sec.Write("FrontType", front)
end function

function deleteFront() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Delete("FrontType")
end function

' Get data where user need to go when launch app.
function getFrontScreenMode() as dynamic
    sec = CreateObject("roRegistrySection", "FrontType")
    return sec.Read("FrontType")
end function

function setDeviceMode(device as string)
    sec = CreateObject("roRegistrySection", "LanuchDeviceType")
    sec.Write("LanuchDeviceType", device)
end function

' Get data where user need to go when launch app.
function getDeviceMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchDeviceType")
    return sec.Read("LanuchDeviceType")
end function

function setGuideMode(guide as string)
    sec = CreateObject("roRegistrySection", "LanuchGuideType")
    sec.Write("LanuchDeviceType", guide)
end function

' Get data where user need to go when launch app.
function getGuideMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchGuideType")
    return sec.Read("LanuchDeviceType")
end function

function setCheckMode(check as string)
    sec = CreateObject("roRegistrySection", "LanuchCheckType")
    sec.Write("LanuchCheckType", check)
end function

' Get data where user need to go when launch app.
function getCheckMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchCheckType")
    return sec.Read("LanuchCheckType")
end function

function deleteCheckMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchCheckType")
    return sec.Delete("LanuchCheckType")
end function

function setHelpMode(help as string)
    sec = CreateObject("roRegistrySection", "LanuchHelpType")
    sec.Write("LanuchHelpType", help)
end function

' Get data where user need to go when launch app.
function getHelpMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchHelpType")
    return sec.Read("LanuchHelpType")
end function

function setDataMode(data as string)
    sec = CreateObject("roRegistrySection", "LanuchDataType")
    sec.Write("LanuchDataType", data)
end function

' Get data where user need to go when launch app.
function getDataMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchDataType")
    return sec.Read("LanuchDataType")
end function

function deleteDataMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchDataType")
    return sec.Delete("LanuchDataType")
end function

function setControlMode(control as string)
    sec = CreateObject("roRegistrySection", "LanuchControlType")
    sec.Write("LanuchControlType", control)
end function

' Get data where user need to go when launch app.
function getControlMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchControlType")
    return sec.Read("LanuchControlType")
end function

function deleteControlMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchControlType")
    return sec.Delete("LanuchControlType")
end function

function setLogoutMode(logout as string)
    sec = CreateObject("roRegistrySection", "LanuchLogoutType")
    sec.Write("LanuchLogoutType", logout)
end function

' Get data where user need to go when launch app.
function getLogoutMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchLogoutType")
    return sec.Read("LanuchLogoutType")
end function

function deleteLogoutMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchLogoutType")
    return sec.Delete("LanuchLogoutType")
end function

function setSettingMode(setting as string)
    sec = CreateObject("roRegistrySection", "LanuchSettingType")
    sec.Write("LanuchSettingType", setting)
end function

' Get data where user need to go when launch app.
function getSettingMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchSettingType")
    return sec.Read("LanuchSettingType")
end function

function deleteSettingMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchSettingType")
    return sec.Delete("LanuchSettingType")
end function

function deleteHelpMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchHelpType")
    return sec.Delete("LanuchHelpType")
end function


function setUserPersonalScreenMode(mode as string)
    sec = CreateObject("roRegistrySection", "LanuchScreenType")
    sec.Write("LanuchScreenType", mode)
end function

' Get data where user need to go when launch app.
function getUserPersonalScreenMode() as dynamic
    sec = CreateObject("roRegistrySection", "LanuchScreenType")
    return sec.Read("LanuchScreenType")
end function

function setPersonaldeviceid(personalDeviceID as string)
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("personal_devic_id", personalDeviceID)
end function

' set the personalDeviceName
function getThePersonalDeviceName() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("PersonalDeviceName")
end function

function setThePersonalDeviceName(personalDeviceName as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("PersonalDeviceName", personalDeviceName)
end function

' set the sessionid
function getSessionDeviceLogout() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("SessionDeviceLogout")
end function

function setSessionDeviceLogout(sessionDeviceLogout as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("SessionDeviceLogout", sessionDeviceLogout)
end function


function setDeviceType(deviceType as string)
    rsAuthentication = CreateObject("roRegistrySection", "Authentication")
    rsAuthentication.Write("device_type", deviceType)
end function

function setUserType(userType as string)
    rsAuthentication = CreateObject("roRegistrySection", "Authentication")
    rsAuthentication.Write("user_type", userType)
end function

function getDeviceType() as dynamic
    rsAuthentication = CreateObject("roRegistrySection", "Authentication")
    return rsAuthentication.Read("device_type")
end function

function getUserType() as dynamic
    rsAuthentication = CreateObject("roRegistrySection", "Authentication")
    return rsAuthentication.Read("user_type")
end function

function setPersonaldevicename_popup(personaldevicename_popup as boolean)
    personalPopup = ""
    if personaldevicename_popup
        personalPopup = "true"
    else
        personalPopup = "false"
    end if

    rsAuthentication = CreateObject("roRegistrySection", "Authentication")
    rsAuthentication.Write("Personaldevicename_popup", personalPopup)
end function

function getPersonaldevicename_popup() as dynamic
    rsAuthentication = CreateObject("roRegistrySection", "Authentication")
    return rsAuthentication.Read("Personaldevicename_popup")
end function


'lat long

function getLongitude() as dynamic
    sec = CreateObject("roRegistrySection", "latlong")
    return sec.Read("Longitude")
end function

function setLatitude(Latitude as string) as dynamic
    sec = CreateObject("roRegistrySection", "latlong")
    sec.Write("Latitude", Latitude)
end function

function getLatitude() as dynamic
    sec = CreateObject("roRegistrySection", "latlong")
    return sec.Read("Latitude")
end function

function setLongitude(Longitude as string) as dynamic
    sec = CreateObject("roRegistrySection", "latlong")
    sec.Write("accountToken", Longitude)
end function



function getUserName() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("UserName")
end function

function setUserName(userName as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("UserName", userName)
end function

function getUserPassword() as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    return sec.Read("UserPassword")
end function

function setUserPassword(UserPassword as string) as dynamic
    sec = CreateObject("roRegistrySection", "Authentication")
    sec.Write("UserPassword", UserPassword)
end function

function getFont(fontSize as integer, fontUri as string) as object
    Font = CreateObject("roSGNode", "Font")
    Font.uri = fontUri
    Font.size = fontSize
    return Font
end function


function APICallWithHeader(url as string, callType as string, data as object, parameters as object, headers as object) as object
    requestObject = CreateObject("roUrlTransfer")

    ' ?"parameters tessting:";parameters
    urlParameters = ""
    if parameters <> invalid and parameters.Count() > 0
        for each parameter in parameters.Keys()
            urlParameters = (parameter).toStr() + "=" + (parameters[parameter]).toStr() + "&" + urlParameters
        end for
    end if

    if urlParameters <> ""
        url = url + "?" + urlParameters
        requestObject.SetUrl(url)
    else
        requestObject.SetUrl(url)
    end if


    ' for each key in headers.Keys()

    '    if key<>invalid
    requestObject.setHeaders(headers)
    '    end if
    ' end for

    ' ? "request url:"; url
    requestObject.addHeader("Content-Type", "application/json")
    ' requestObject.addHeader("Authorization", "Bearer " + getAccountToken())
    ' requestObject.addHeader("user-Agent", "apple")
    requestObject.addHeader("User-Agent", "Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1")

    requestObject.setCertificatesFile("common:/certs/ca-bundle.crt")
    requestObject.initClientCertificates()
    requestObject.enableEncodings(true)
    requestObject.RetainBodyOnError(true)
    requestObject.SetRequest(callType)
    port = CreateObject("roMessagePort")
    requestObject.setMessagePort(port)
    '    requestObject.addHeader("Content-Type","application/json")
    if callType = "POST"
        ' ? "APICall:::POST CALL"
        requestObject.SetRequest(callType)
        body = FormatJson(data)

        req = requestObject.AsyncPostFromString(body)
    else if callType = "GET"
        ' ? "APICall:::GET CALL"
        requestObject.SetRequest(callType)
        req = requestObject.AsyncGetToString()
    end if

    if req
        while true
            msg = Wait(0, port)
            if Type(msg) = "roUrlEvent"

                responseJson = {}

                if msg.GetResponseCode() = 200


                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["data"] = ParseJson(msg.GetString())

                    ' ?"APICall::JsonResponse: ";JsonResponse

                    return responseJson
                else
                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["data"] = ParseJson(msg.GetString())
                    responseJson["Failure"] = msg.GetFailureReason()

                    ?"APICall::JsonResponse: ";responseJson

                    return responseJson
                end if
            else
                ? "APICall::ResponseCode: "; msg.GetResponseCode()
                ? "APICall::FailureReason: "; msg.GetFailureReason()
            end if
        end while
    else
        ? "req: "; req
    end if
end function

function APICall(url as string, callType as string, data as object, parameters as object) as object
    ' ? "APICall:"
    requestObject = CreateObject("roUrlTransfer")

    ?"api call url :";url
    ?"calltype "callType
    ' ?"parameters tessting:";parameters
    urlParameters = ""
    if parameters <> invalid and parameters.Count() > 0
        for each parameter in parameters.Keys()
            urlParameters = (parameter).toStr() + "=" + (parameters[parameter]).toStr() + "&" + urlParameters
        end for
    end if

    ' ? "request url:"; url
    ' ?"method :";callType
    requestObject.addHeader("Content-Type", "application/json")
    requestObject.addHeader("Accept", "application/json")
    requestObject.addHeader("Authorization", "Bearer " + getAccountToken())
    ' requestObject.addHeader("User-Agent", "Roku/DVP-9.30 (509.30E04194A)")
    ' requestObject.addHeader("User-Agent", "Mozilla/5.0 (iPhone; CPU iPhone OS 12_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0 Mobile/15E148 Safari/604.1")
    ' requestObject.addHeader("user-Agent", "apple")
    requestObject.addHeader("User-Agent", "Android")

    requestObject.setCertificatesFile("common:/certs/ca-bundle.crt")
    requestObject.initClientCertificates()
    requestObject.enableEncodings(true)
    requestObject.RetainBodyOnError(true)
    requestObject.SetRequest(callType)
    if urlParameters <> ""
        url = url + "?" + urlParameters
        requestObject.SetUrl(url)
    else
        requestObject.SetUrl(url)
    end if
    port = CreateObject("roMessagePort")
    requestObject.setMessagePort(port)
    '    requestObject.addHeader("Content-Type","application/json")
    if callType = "POST"
        ' ? "APICall:::POST CALL"
        requestObject.SetRequest(callType)
        body = FormatJson(data)

        req = requestObject.AsyncPostFromString(body)
    else if callType = "GET"
        ' ? "APICall:::GET CALL"
        requestObject.SetRequest(callType)
        req = requestObject.AsyncGetToString()
    else if callType = "PUT"
        ' ? "APICall:::PUT CALL"
        requestObject.SetRequest(callType)
        body = FormatJson(data)

        req = requestObject.AsyncPostFromString(body)
    else if callType = "DELETE"
        ' ? "APICall:::DELETE CALL"
        requestObject.SetRequest(callType)
        body = FormatJson(data)

        req = requestObject.AsyncPostFromString(body)
    end if

    if req
        while true
            msg = Wait(0, port)
            if Type(msg) = "roUrlEvent"

                responseJson = {}

                if msg.GetResponseCode() = 200

                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["jsonResponse"] = ParseJson(msg.GetString())

                    ' ?"APICall::JsonResponse: ";JsonResponse

                    return responseJson
                else
                    responseJson["responseCode"] = msg.GetResponseCode()
                    responseJson["jsonResponse"] = ParseJson(msg.GetString())
                    responseJson["Failure"] = msg.GetFailureReason()

                    ' ?"APICall::JsonResponse: ";responseJson

                    return responseJson
                end if
            else
                ? "APICall::ResponseCode: "; msg.GetResponseCode()
                ? "APICall::FailureReason: "; msg.GetFailureReason()
            end if
        end while
    else
        ? "req: "; req
    end if
end function

function getBaseUrl()
    ' return "http://100.26.190.92/"
    ' return "https://b86ed54d.ngrok.io/"
    return "https://streamlytv.com/"
end function

function drmkeyUrl()
    return "api/auth/getdrmkey"
end function

function profileUrl()
    ' return "api/auth/profile"
    return "api/auth/tvprofile"
end function

function qeCodeUrl()
    ' return "api/login/create/qrcode"
    return "api/login/create/qrcode"
end function
function updatePersonalDeviceName()
    ' return "api/login/create/qrcode"
    return "api/auth/personaldevicename/"
end function

function loginguide()
    ' return "api/login/create/qrcode"
    return "https://streamlytv.com/api/loginguide"
end function

function checkStatus()
    ' return "api/login/create/qrcode"
    return "/api/login/scan/qrcode"
end function

function qrLogin()
    return "/api/login/entry/login"
end function

function logoutUrl()
    return "api/auth/logout"
end function

function termOfUse()
    return "api/auth/termofuse"
end function


function loginUrl()
    return "api/auth/login"
end function

function DeviceUrl()
    return "api/auth/same-device-id-logout"
end function

function passcodeUrl()
    return "api/auth/login-with-passcode"
end function
function sameDeviceLogoutUrl()
    return "api/auth/same-device-id-logout"
end function

function syncSessionWithRoom()
    return "api/auth/sync-session-with-room"
end function

function getRoomsUrl()
    return "api/auth/get-hotelrooms"
end function


function connectedDeviceUrl() as string
    return "api/auth/connecteddevice"
end function

function getChannelListUrl() as string
    return "api/auth/channellist?typeurl=manifest"
end function

function getChannelGenerList() as string
    return "api/auth/channelgenre"
end function

function getchannelSearchUrl() as string
    return "api/auth/search"
end function

function getchannelschdeuleUrl() as string
    return "api/auth/channeldayschdeuletzwithoffset"
end function

function getChannelDaySheduler() as string
    return "api/auth/channeldayschdeuletzwithoffset"
end function

function getsupportUrl() as string
    return "api/auth/support"
end function

function getprivacypolUrl() as string
    return "api/auth/privacypol"
end function

function getBaseUrlTemp() as string
    return "http://ab8c26b210d7.ngrok.io/"
end function

function getDeviceInfo() as object
    ? "getDeviceInfo"
    return CreateObject("roDeviceInfo")
end function

function getRecentUrl() as object
    return "api/auth/my_recents"
end function

function getFavouriteList() as object
    return "api/auth/my_favorites"
end function
function addRecentUrl() as object
    return "api/auth/addtorecent"
end function

function LinkNQRCode() as object
    return "api/qrcode"
end function

function openApi() as object
    ' return "http://ip-api.com/json/137.151.1.0"
    return "http://ip-api.com/json"
end function

function geofence() as object
    return "api/auth/geofence"
    ' return "http://ip-api.com/json"
end function
function allowLocatioPlay() as object
    return "api/auth/outsidelocationplay"
    ' return "http://ip-api.com/json"
end function


function ga() as object
    return "api/auth/ga"
end function

function GADUration() as object
    return "api/auth/ga/duration"
end function

function playererror() as object
    return "api/auth/ga/playererror"
end function

function qrlogin1() as object
    return "api/auth/qrvisibility"
end function

function popupDialog(node as object, title as string, message as string) as object

    m.dialogHeight = 350
    m.dialogWidth = 1223
    m.background = node.createChild("Rectangle")
    m.SupportDialog = node.createChild("Poster")
    m.background.height = 1080
    m.background.width = 1920
    m.background.color = "#000000"
    m.background.opacity = ".4"
    m.SupportDialog.visible = true
    m.SupportDialog.width = m.dialogWidth
    m.SupportDialog.height = m.dialogHeight
    m.SupportDialog.uri = "pkg:/images/profile/profile_dialog.png"
    m.SupportDialog.translation = [(1920 - m.dialogWidth) / 2, (1080 - m.dialogHeight) / 2]

    m.LayoutGroup = m.SupportDialog.createChild("LayoutGroup")
    m.LayoutGroup.translation = [0, 50]
    m.LayoutGroup.layoutDirection = "vert"
    m.LayoutGroup.itemSpacings = [0]
    m.TextLabel = m.LayoutGroup.createChild("Label")
    m.TextLabel.width = m.dialogWidth
    '     * (83.6/100)
    m.TextLabel.font = getFont(35, "pkg:/fonts/FontsFree-Net-HelveticaNeueBold.ttf")
    m.TextLabel.height = 50
    m.TextLabel.horizAlign = "Center"
    m.TextLabel.vertAlign = "Center"
    m.TextLabel.text = title

    m.contactNumber = m.LayoutGroup.createChild("Label")

    m.contactNumber.translation = [0, 0]
    m.contactNumber.width = m.dialogWidth
    m.contactNumber.height = m.dialogHeight * (25 / 100)
    '    m.contactNumber.translation = [0, 150]
    '    m.contactNumber.font = "font:SmallBoldSystemFont"
    m.contactNumber.horizAlign = "Center"
    m.contactNumber.vertAlign = "Center"
    m.contactNumber.font = getFont(30, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.contactNumber.text = message
    m.contactNumber.numlines = 2

    m.HelpButton = m.SupportDialog.createChild("Button")
    ' m.HelpButton.observeField("buttonSelected", "closeDialog")

    '                 m.OkButton.translation= [466,429]
    m.HelpButton.minWidth = "280"
    m.HelpButton.maxWidth = "280"
    m.HelpButton.focusedTextColor = "#FFFFFF"
    m.HelpButton.height = "84"
    m.HelpButton.focusBitmapUri = "pkg:/images/Login/buttonFoucs.png"
    m.HelpButton.showFocusFootprint = "true"
    m.HelpButton.iconUri = ""
    m.HelpButton.focusedIconUri = ""
    okLabel = m.HelpButton.createChild("Label")
    okLabel.horizAlign = "center"
    okLabel.height = "84"
    okLabel.width = "280"
    okLabel.vertAlign = "center"
    okLabel.text = "OK"
    okLabel.font = getFont(36, "pkg:/fonts/HelveticaNeueMedium.ttf")
    m.HelpButton.translation = [(m.dialogWidth - m.HelpButton.boundingRect().width) / 2, m.dialogHeight * (65 / 100)]
    return m.HelpButton
end function

function closeErrorDialogShow(node as object)

    if m.background <> invalid and m.SupportDialog <> invalid and node <> invalid
        ?"closeError: "m.background
        ?"m.support: "m.SupportDialog
        ?"m.node: "node
        node.removeChild(m.background)
        node.removeChild(m.SupportDialog)
    end if

end function