#!/usr/bin/env python

import time

import automationhat

if automationhat.is_automation_hat():
    automationhat.light.power.write(1)

while True:

    # Open the mode file to find out which lighting mode is required
	data = open('/etc/fishtank/mode.txt', 'r')
    # Read the first line and close the file
	mode = data.readline()[:-1]
	data.close()

    # Note, that I use the night lights as normally closed (on); so the pin needs power (on) to turn off the lights
    #       the day lights work in a more intuitive way
    # This is so that, without software working or if the Pi breaks, the normal positions of the relays will assume
    # a "night" mode. You can change your pins and preferences, and only need to make the changes here.

    # If the mode required is "day",
	if mode == 'day':
		automationhat.relay.one.on()
		if automationhat.is_automation_hat():
			automationhat.relay.two.on()
	elif mode == 'night':
		automationhat.relay.one.off()
		if automationhat.is_automation_hat():
			automationhat.relay.two.off()
	elif mode == 'off':
		automationhat.relay.one.off()
		if automationhat.is_automation_hat():
			automationhat.relay.two.on()

    # Wait 10 seconds and go around again. This is what causes the 10 second delay to your tank noticing your demands
    # Chosen to lower impatience, but not to be too demanding. I've no idea if going round faster would be a problem.
	time.sleep(10)
