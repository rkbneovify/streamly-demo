Function getAccountToken() as dynamic
 sec = CreateObject("roRegistrySection", "Authentication")
 return sec.Read("accountToken")
end Function

Function setAccountToken(accountToken as String) as dynamic
  sec = CreateObject("roRegistrySection", "Authentication")
  sec.Write("accountToken",accountToken)
end Function






