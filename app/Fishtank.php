<?php

namespace App;

use \PiPHP\GPIO\GPIO;
use \PiPHP\GPIO\Pin\PinInterface;

class Fishtank
{
    public static function set($state)
    {
        $gpio = new GPIO();

        $pin_day = $gpio->getOutputPin(13);
        $pin_night = $gpio->getOutputPin(19);

        switch($state)
        {
            case 'day':
                $pin_day->setValue(PinInterface::VALUE_HIGH);
                $pin_night->setValue(PinInterface::VALUE_HIGH);
                break;

            case 'night':
                $pin_day->setValue(PinInterface::VALUE_LOW);
                $pin_night->setValue(PinInterface::VALUE_LOW);
                break;

            case 'off':
                $pin_day->setValue(PinInterface::VALUE_LOW);
                $pin_night->setValue(PinInterface::VALUE_HIGH);
                break;
            default:
            //auto
        }
    }

    public static function set_day()
    {
        $gpio = new GPIO();

        $pin_day = $gpio->getOutputPin(33);
        $pin_night = $gpio->getOutputPin(35);
    }

}

//33
//35
