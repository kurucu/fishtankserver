# fishtankserver

This is a Laravel 5.5 based fishtank app for the Raspberry Pi. It checks for the sunrise and sunset times in your local
area to determine whether there is daylight, and if so turns on the tank lights. The tank light mode (day, night or off)
can be overridden through a browser on the local network; or by writing to a file on the Raspberry Pi.

## Overview

I have developed this with a Raspberry Pi B+, but I think any should work, and a Pimerono AutomationHAT.
If you tinker with the settings and python file, you should find that any (cheaper) relay HAT should work
fine too.

The project runs a basic service, written in python, that checks on a `mode` file for how the tank lighting should be set.

It also provides a Laravel project which does two things:
  * It provides a front end for the user, allowing a specific mode to be set or overridden
  * It provides a regular task to check whether the sun is up, and to turn on the tank lights automatically
  
# Installation Method

install composer
install laravel
path and source path

install php7.0- dom; mastring, gd, zip

install nginx

install sqlite and php sqlite

chmod storage

configure nginx (use default, and public)

```
cd /lib/systemd/system/
sudo nano hello.service
```

Use `sudo nano /lib/systemd/system/fishtank.service` to create the file as follows:

```
[Unit]
Description=Fishtank Service
After=multi-user.target
 
[Service]
Type=simple
ExecStart=/usr/bin/python /etc/fishtank/fishtank.py
Restart=on-abort
 
[Install]
WantedBy=multi-user.target
```
