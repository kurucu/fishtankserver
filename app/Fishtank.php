<?php

namespace App;

use \PiPHP\GPIO\GPIO;
use \PiPHP\GPIO\Pin\PinInterface;

class Fishtank
{
    public static function set($state)
    {
        $file = "/etc/fishtank/mode.txt";
        Storage::disk('fishtank')->put('mode.txt', $state);

        switch($state)
        {
            case 'day':
                break;

            case 'night':
                break;

            case 'off':
                break;
            default:
            //auto
        }
    }

}

//33
//35
