Public Function GetIP
   ComputerName="."
    Dim objWMIService,colItems,objItem,objAddress
    Set objWMIService = GetObject("winmgmts:\\" & ComputerName & "\root\cimv2")
    Set colItems = objWMIService.ExecQuery("Select * From Win32_NetworkAdapterConfiguration Where IPEnabled = True")
    For Each objItem in colItems
        For Each objAddress in objItem.IPAddress
            If objAddress <> "" and (InStr(objAddress, "192.168.50") > 0 or InStr(objAddress, "192.168.10") > 0) then
                GetIP = objAddress
                Exit Function
            End If
        Next
    Next
End Function
set ws = CreateObject("Wscript.Shell")
ws.Run "cmd /c " & WScript.Arguments(0) & "\php.exe -c " & WScript.Arguments(0) & "\php.ini -S " & GetIP() & ":1080 -t " & WScript.Arguments(1), vbhidden
