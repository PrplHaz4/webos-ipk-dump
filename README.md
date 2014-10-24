webos-ipk-dump
==============

PHP script that uses your palm profile to download and tar IPKs for your installed apps.

### Requirements (Windows EXE)###

* WebOS access token (* Palm Profile)
* WebOS device ndUid (* Device Profile)
* WebOS Installed Apps list saved as .json file (* Installed Apps)
* free disk space

### Instructions (Windows EXE)###

1. Download webos-ipk-dump.zip
2. Extract zip to c:\tmp
3. Save "Installed Apps" (from the email you sent yourself above) as "ipkdump.json"
4. Place ipkdump.json into c:\tmp directory (with "webos-ipk-dump.exe")
5. Have your "token" and "ndUid" ready!
6. Run webos-ipk-dump.exe from command line

### Requirements (PHP 5.3+)###

* Ability to run PHP on the command line
* wget
* tar
* WebOS access token (* Palm Profile)
* WebOS device ndUid (* Device Profile)
* WebOS Installed Apps list saved as .json file (* Installed Apps)
* free disk space


*You can get these 3 things from by using Impostah's (on your WebOS device) email feature

==============

Inspired by this post from GMMan:
http://forums.webosnation.com/3378367-post15.html

In case you don't want to uninstall and reinstall all your apps to download the IPKs, you can add a couple of headers and download them through your desktop browser. Use Preware to find the app ID (or browse through your applications folder), and use Impostah to find the IPK URL. Find an extension for your browser that allows you to add HTTP headers, and insert the following headers:
````
Auth-Token: <your auth token here>
Device-Id: <your nduid/imei here>
````
Get those two pieces of info the same way as I described in the country changer app. (Maybe it'll work for apps App Tuckerbox can't get on phones.)
