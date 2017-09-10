<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class Fishtank
{
    public static function set($state)
    {
        $file = "mode.txt";

        if( $state == 'auto' )
        {
            if( self::daylight() )
            {
                Storage::disk('fishtank')->put($file, "day\n");
            } else {
                Storage::disk('fishtank')->put($file, "night\n");
            }
        } else {
            Storage::disk('fishtank')->put($file, $state . "\n");
        }
    }

    public static function daylight()
    {
        $latitude = config('fishtank.location.latitude');
        $longitude = config('fishtank.location.longitude');

        $sunrise = date_sunrise(time(), SUNFUNCS_RET_STRING, $latitude, $longitude);
        $sunrise = date_sunset(time(), SUNFUNCS_RET_STRING, $latitude, $longitude);

        return ( time() > $sunrise && time() < $sunset );
    }

    public static function data()
    {
        return [
            'time' => time(),
            'timezone' => config('app.timezone'),
        ];
    }

}

//33
//35
