<?php

namespace App;

use \PiPHP\GPIO\GPIO;
use \PiPHP\GPIO\Pin\PinInterface;

class Fishtank
{
    public static function set($state)
    {
        $file = "/etc/fishtank/mode.txt";

        switch($state)
        {
            case 'day':
                file_put_contents($file, 'day');
                break;

            case 'night':
                file_put_contents($file, 'night');
                break;

            case 'off':
                file_put_contents($file, 'off');
                break;
            default:
            //auto
        }
    }

}

//33
//35
