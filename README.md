# Reolink API Connector using GuzzleHTTP

Tested on a Reolink E1 Outdoor with firmware v3.1.0.804_22011506

Image downloader script will (probably) work on any camera, control only on PTZ enabled cameras.

## Source for api usage 

I used this documentation of the Reolink API https://docs.google.com/document/d/10Ec4sRt8S4RC1L7w662UVTCHuihktO2d/edit to create this project.

## Requirements

PHP8  
GuzzleHTTP  
ffmpeg for Image saving  

## Installation

Make a copy of config.ini.template as config.ini and insert username, password and hostname.  
Hostname should be a LAN adress of the camera, I have not tested this over WAN.

Configure Parameters in ptzGetSnapShot.php

Run composer update to install GuzzleHTTP

## Usage

### WARN 
Due to Reolink using a self-signed certificate for HTTPS we have to set verify = false when sending requests with HTTPS.   
As long as you run this in a contained network this shouldn't be an issue.  
You can fix this by getting a valid certificate for your camera with LetsEncrypt or similar.


### Control.php

Using Control.php moves the camera to a predefined PTZ Preset saved on the camera.  
This can be used in conjunction with a bash script to upload or save the image after moving.  
Useful if you have a set guard point and want to move it to another point every X minutes to take a snapshot.


### GetPresets.php

Returns all enabled presets from the camera. 

### RunCommand.php 

Generic Run Command which takes the command name and an array with the configuration. See example in RunCommand.php
Return type can be selected, defaults to statusCode.



### ptzGetSnapshot.sh Bash script

Included is a bash script template that moves the camera to a predefined preset and sleeps for 20 seconds before taking a snapshot and saving it to disk.  

Intended use for this is to move the camera to another preset than your default Guard Point and take an image.

This script requires FFMPEG to work. 

Included also an upload with sftp to remote server, this requires that you have set up a keypair for authentication.
