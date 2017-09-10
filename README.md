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

## Electronics and Wiring

Follow the guide [here](/docs/electronics.md) to wire up your Pi, or get ideas as to how you might do so.

## Install Dependencies

If you're using the AutomationHAT from Pimeroni, then follow the install guide [here](https://github.com/pimoroni/automation-hat).

Install ngingx (a lightweight web server) and PHP.

```bash
sudo nginx apt-get install php7.0 php7.0-dom php7.0-mbstring  php7.0-gd  php7.0-zip php7.0-sqlite3
```

Then install composer, by following the instructions [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).
At the time of writing, this would be done as follows:

```bash
cd ~
wget https://getcomposer.org/installer
mv composer.phar /usr/local/bin/composer
```

You will then need to add the composer path to your $PATH, but editing ~./bashrc, using `nano ~/.bashrc`, and adding the following line:

```
export PATH="/path/to/dir:$PATH"
```

Then type `source ~/.bashrc` to effect the changes.

## Set up the web server

Go to the default web location, set up the folder structure and pull in this repo. Ideally, instead of pulling in the repo with sudo,
you would change your /var/www to be owned by www-data, and then pull it in normally. But the below works if you don't know how to
do that. It's an aquarium, so unless you're putting vital data in your fish tank, this is ok.

```
cd /var/www
sudo mv html html.back
sudo git clone git@github.com:kurucu/fishtankserver.git
sudo chmod 777 -R fishtankserver/storage
sudo chmod 777 -R fishtankserver/logs
```

You will now need to reconfigure ngingx to use the /var/www/fistankserver/public directory, and to use PHP, as follows:

```
sudo nano /etc/nginx/sites-available/default
```

Replace the root directory:

```
    # root /var/www/html
	root /var/www/fishtankserver/public;
```

Add index.php to the allowed index documents:

```
	# Add index.php to the list if you are using PHP
	index index.php index.html index.htm index.nginx-debian.html;
```

And make a few changes in the location area:

```
	location / {
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
	#	try_files $uri $uri/ =404;
		try_files $uri $uri/ /index.php?$query_string;
	}

	# pass PHP scripts to FastCGI server
	#
	location ~ \.php$ {
		include snippets/fastcgi-php.conf;

		# With php-fpm (or other unix sockets):
		fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
    }
```

## Create service

Create a file to watch for the desired state (/etc/fishtank/mode.txt), allow it to be edited by anyone and set a starting value.
Copy the python fishtank service to the same place, and allow it to be executed. This file runs as a service to watch
the /etc/fishtank/mode.txt file, and keep the lights turned to the appropriate settings.

```bash
sudo mkdir /etc/fishtank
sudo touch /etc/fishtank/mode.txt
sudo chmod 777 /etc/fisktank/mode.txt
echo "night" > /etc/fishtank/mode.txt
sudo cp /path/to/cloned/repo/supporting_files/fishtank.py /etc/fishtank/fishtank.py
sudo chmod +x /etc/fishtank/fisktank.py
```

Go and create a service definition file:

```
cd /lib/systemd/system/
sudo nano fishtank.service
```

And paste in the following contents.

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

Press `ctrl-o` (save), `enter` (confirm) and `ctrl-x` (exit).

Now enable the service:

```
sudo chmod 644 /lib/systemd/system/fishtank.service
sudo systemctl daemon-reload
sudo systemctl enable fishtank.service
sudo systemctl start fishtank.service
```

In general:

```
# Check status
sudo systemctl status fishtank.service

# Start service
sudo systemctl start hello.service

# Stop service
sudo systemctl stop hello.service

# Check service's log
sudo journalctl -f -u hello.service
```

You might have guessed that this part came from [HOW TO RUN A SCRIPT AS A SERVICE IN RASPBERRY PI - RASPBIAN JESSIE](http://www.diegoacuna.me/how-to-run-a-script-as-a-service-in-raspberry-pi-raspbian-jessie/).
