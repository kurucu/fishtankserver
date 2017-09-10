<?php

namespace App;

use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class Fishtank
{
    public static function set($state)
    {
        if( $state == 'auto' )
        {
            if( self::daylight() )
            {
                Storage::disk('fishtank')->put($file, "day\n");
            } else {
                Storage::disk('fishtank')->put($file, "night\n");
            }
        } else {
            Storage::disk('fishtank')->put( config('fishtank.file') , $state . "\n");
        }
    }

    public static function daylight()
    {
        $latitude = config('fishtank.location.latitude');
        $longitude = config('fishtank.location.longitude');

        $now = Carbon::now();

        $sunrise = new Carbon(date_sunrise(time(), SUNFUNCS_RET_STRING, $latitude, $longitude));
        $sunset = new Carbon(date_sunset(time(), SUNFUNCS_RET_STRING, $latitude, $longitude));

        return ( $now->gt($sunrise) && $now->lt($sunset) );
    }

    public static function data()
    {
        return [
            'time' => Carbon::now(),
            'timezone' => config('app.timezone'),
            'daylight' => self::daylight(),
            'setting' => Setting::get('state', 'auto'),
            'actual' => Storage::disk('fishtank')->get( config('fishtank.file') ),
        ];
    }

}
