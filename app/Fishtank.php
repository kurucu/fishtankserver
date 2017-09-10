<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class Fishtank
{
    public static function set($state)
    {
        $file = "mode.txt";
        Storage::disk('fishtank')->put($file, $state . "\n");

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
