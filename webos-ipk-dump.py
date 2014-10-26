#-------------------------------------------------------------------------------
# Name:        webos-ipk-dump
# Purpose:     save all your devices' apps (ipks)
#
# Author:      PrplHaz4
#
# Created:     24/10/2014
# Copyright:   (c) PrplHaz4 2014
# Licence:     Not responsible if something blows up
#-------------------------------------------------------------------------------

import os
import sys
import json
import urllib.request
import shutil
import codecs

global token, deviceId, inputFile

def main():
    print("--------------------------")
    if not os.path.exists(inputFile):
        print(inputFile + " must exist in this directory!")
        sys.exit(0)
    apps = readIpkJson(inputFile)
    if not os.path.exists('.\ipk'):
        os.mkdir('.\ipk')
    for app in apps:
        ipkUrl = apps[app]['appLocation']
        downloadIpk(token, deviceId, ipkUrl)
    pass

def getAppFilename(ipkUrl):
    return os.path.split(ipkUrl)[1]

def getRequestHeaders(token, deviceId):
    return {"Auth-Token":token, \
        "Device-Id":deviceId \
    }

def downloadIpk(token, deviceId, ipkUrl):
    appFilename = getAppFilename(ipkUrl)
    headers = getRequestHeaders(token, deviceId)

    print(appFilename, end='')
    if not os.path.exists(".\\ipk\\" + appFilename):
        r = urllib.request.Request(ipkUrl, None, headers)
        try:
            with urllib.request.urlopen(r, None) as response, open(".\\ipk\\" + appFilename, 'wb') as out_file:
                shutil.copyfileobj(response, out_file)
            print(" ....saved!")
        except urllib.error.HTTPError:
            print(" ....Error downloading file")
    else:
        print(" ...already exists.")

def readIpkJson(inputFilename):
    try:
        f = json.load(codecs.open(inputFilename, 'r', 'utf8'))
    except:
        e = sys.exc_info()[0]
        print(e)
        print("...trying UTF-16...")
        try:
            f = json.load(codecs.open(inputFilename, 'r', 'utf16'))
        except:
            e = sys.exc_info()[0]
            print(e)
    return f

if __name__ == '__main__':
    if len(sys.argv) < 3:
        print("\nEmail these to yourself using Impostah from your webOS device.")
        token = input("  Your Palm Profile token >> ")
        deviceId = input("  Your Device Profile ndUid >> ")
        inputFile = input("  Installed Apps json [ipkdump.json] >> ")
        if token == "":
            print("Need to enter a token!")
            sys.exit(0)
        if deviceId == "":
            print("Need to enter a device ID!")
            sys.exit(0)
        if(inputFile) == "":
            inputFile = "ipkdump.json"
    else:
        token = sys.argv[1]
        deviceId = sys.argv[2]
        try:
            inputFile = sys.argv[3]
        except IndexError:
            inputFile = "ipkdump.json"
    main()
