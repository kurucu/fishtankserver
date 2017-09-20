# Fish Tank Server

This project helps you set up a controller for your aquarium lights based on a Raspberry Pi. The project currently has the following features:

  * Automatically switches the lights between day and night mode at sunrise and sunset, accordingly (Auto mode).
  * Allows you to visit a web application on your phone to turn the lights manually to Day/Night/Off or back to Auto.
  * Uses Avahi (Bonjour to Apple users) to allow auto-discovery

There are more ideas in the pipeline, and the code is easily extended. I welcome pull requests to add/modify features and/or the documentation.

## Technical Overview

The project is based on the following technologies and designs:

  * A Raspberry Pi (any should do, I use a B+) with a USB WiFi dongle
  * A [Pimeroni AutomationHAT](https://shop.pimoroni.com/products/automation-hat) (with some tweaking of the python script, any relay or control board should be fine)
  * [Raspbian Stretch Lite](https://www.raspberrypi.org/downloads/raspbian/) (CLI Only, the Raspberry Pi hides in the aquarium and controls the lighting relays, so why not)
  * A python script which runs as service, and controls the GPIO
  * [Laravel 5.5](https://laravel.com/) for the web application, which lets the python script know what to do
  * Avahi to enable auto-discovery

The project runs a basic service, written in python, that checks a mode file (default is `/etc/fishtank/mode.txt`) every 10 seconds. The mode file can contain either "day", "night" or "off", and the python script will alter the tank lighting accordingly.

The laravel web framwork does two things. Firstly, using its scheduler (run from the CLI, via Cron) it will check to see whether there is daylight (i.e. we are between sunrise and sunset). If the user has selected Auto, it updates the mode file with day/night accordingly; otherwise it leaves the file alone.

Secondly, it offers a web interface, accessible from any computer on the local network which provides:

  * An HTML page with control buttons and feedback elements
  * An API to find out information about the tank and to change modes (all used by the web page itself)

# Installation Method

## Set Up the Pi

You need to enable SSH on your Pi, either by connecting a monitor and keyboard and running "sudo raspi-config"; or by saving an empty file called "ssh" in the /boot partition before putting the SD card in the Pi.

Then, plug in the EdiMax Wifi dongle, and configure your Wifi as detailed [here](https://www.raspberrypi.org/documentation/configuration/wireless/wireless-cli.md). Make sure the connection is working, and then disconnect the ethernet/keyboard and monitor. Perhaps connect via SSH one last time (via wifi) to make sure.

While you might like to make the IP address static, you can also rely on Avahi (included with Raspbian) and then find your Pi automatically at `raspberrypi.local.`; or a hostname of your choosing.

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

```bash
export PATH="/path/to/dir:$PATH"
```

Then type `source ~/.bashrc` to effect the changes.

## Set up the web server

Go to the default web location, set up the folder structure and pull in this repo. Ideally, instead of pulling in the repo with sudo,
you would change your /var/www to be owned by www-data, and then pull it in normally. But the below works if you don't know how to
do that. It's an aquarium, so unless you're putting vital data in your fish tank, this is ok.

```bash
cd /var/www
sudo mv html html.back
sudo git clone git@github.com:kurucu/fishtankserver.git
sudo chmod 777 -R fishtankserver/storage
sudo chmod 777 -R fishtankserver/logs
# Create a database file
sudo touch database/database.sqlite
# Update the composer dependencies - this may take a while on a pi
composer update
# Migrate the database
php artisan migrate
php artisan migrate --path=vendor/anlutro/l4-settings/src/migrations
```

You will now need to reconfigure ngingx to use the /var/www/fistankserver/public directory, and to use PHP, as follows:

```bash
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

```bash
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

```bash
sudo chmod 644 /lib/systemd/system/fishtank.service
sudo systemctl daemon-reload
sudo systemctl enable fishtank.service
sudo systemctl start fishtank.service
```

In general:

```bash
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
