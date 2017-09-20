# Electronics

## Shopping List

This list will need to be tailored to your application, but gives you a good idea of what you can do. This is based upon my use within an [Askoll Pure M](http://aquarium.askoll.com/en/aquarium-and-accessories/aquarium-kit-stand/pure/pure-m) Aquarium, which requires 1200 lumens during the day, equating to 14.4W of LEDs plus the overheads for the Pi and HAT.

  * 12v LED driver - e.g. a [20w waterproof 12v LED driver](https://www.wholesaleledlights.co.uk/20w-waterproof-led-driver.html)
  * 12v to 5v regulator - e.g. [A Buck driver with microUSB output](https://www.amazon.co.uk/GEREE-Converter-Module-Output-Adapter/dp/B01KX00QUU/ref=sr_1_1?ie=UTF8&qid=1505125137&sr=8-1&keywords=12v+5v+micro+usb)
  * 1200 lumens of LEDs - e.g. [1.5m of 9.6w/m LED strip at 800 lumens/m](https://www.wholesaleledlights.co.uk/1m-single-colour-led-strip-lights-120-led-m-3528-3-5-x-2-8mm-9-6-watts-per-metre-800-lumens.html)
  * Veroboard
  * Raspberry Pi
  * [Edimax USB Wifi dongle](https://www.amazon.co.uk/Edimax-EW-7811Un-N150-Wireless-Adapter/dp/B003MTTJOY/ref=sr_1_1?s=computers&ie=UTF8&qid=1505125313&sr=1-1&keywords=edimax+raspberry+pi)
  * [ Pimorino AutomationHAT](https://www.google.co.uk/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&ved=0ahUKEwjgmeHs9JzWAhUhKMAKHbgZB0sQFggwMAA&url=https%3A%2F%2Fshop.pimoroni.com%2Fproducts%2Fautomation-hat&usg=AFQjCNHEYd_g1AvKAkFRNCZIh8AnILB3ZQ)
  * 2 x 5mm 3.3v Blue LEDs
  * 1 x 220 Ohm resistor
  * Wire
  * Solder
  * Stand-offs etc
  * Perspex sheet
  * Glue gun and glue (Hot Glue in the USA)
  * Silver card

## Method

### Lighting

Cut a perspex sheet to fit the bottom of the clear window. Take it out, turn it over and cut and glue the silver card to fit. Drill two 5mm holes in the centre and pop and glue the blue LEDs through. On the back, solder the 220 Ohm resistor between the LEDs (taking care to wire one cathode to one anode via the resistor).

Cut the LED strips into lengths that are slightly shorter than the perspex, ensuring to cut where indicated. Peel off their sticky backing and stick them to the silver card. Solder wires between the LED strips so that they are powered in parallel. From one of the strips, send two wires through a 5mm hole drilled in the perspex.

### Power and Control

In the white side of the lighting box (or elsewhere according to your aquarium design) take out the existing control systems and trim back plastic fittings. Keep the mains cable and grommet as-is.

Fit the mains cable to the 12v adaptor, and glue/screw it in place. Then take two wires out to the veroboard and solder as follows:

One 12v rail from the power adaptor.
One Ground/0v rail from the power adaptor.
One day rail.
One night rail.

Solder the 12v-5v Buck adaptor to the 12v and 0v veroboard rail. Connect the Micro USB connnector to the Pi.

Solder a line to the 12v rail and run it to the day rail via Relay 1 on the Automation HAT (I chose normally open/NO).

Solder a line to the 12v rail and run it to the night rail via Relay 2 on the Automation HAT (I chose normally closed/NC).

Solder the positive line from the LED strips to the day rail; and the negative line to the 0v rail.

Solder the positive line from the blue LEDs to the night rail; and the negative line to the 0v rail.

Plug the WiFi adaptor into the Raspberry Pi.

Plug the Automation HAT into the Raspberry Pi.

Find homes for everything and screw / glue them down.
