#!/bin/bash
timestamp=$(date +%F_%T)
# Configure parameters to match your setup
reolinkUSER="USERNAME"
reolinkPASSWORD="PASSWORD"
reolinkHOST="HOSTIP"
# Default RTSP port 554
reolinkPORT=554
reolinkPRESET=1

sftpHOST="SFTPHOST"
sftpPATH=SFTPPATH
sftpUSER=SFTPUSER

localUSER="LOCALUSER"
localPATH="/home/$localUSER/reolinkPHP"

/usr/bin/php "$localPATH"/Control.php " $reolinkPRESET"
sleep 20
ffmpeg -y -i "rtsp://$reolinkUSER:$reolinkPASSWORD@$reolinkHOST:$reolinkPORT" -vframes 1 $localPATH/images/img-"$timestamp".jpg

# Uncomment the following and configure to upload the image with sftp
# This assumes you have setup a SSH KEY pair for the upstream server

#sftp "$sftpUSER@$sftpHOST:$sftpPATH" <<< $"put  $localPATH/images/img-$timestamp.jpg"
