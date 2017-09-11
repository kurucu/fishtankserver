<?php

namespace App;

use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Setting;

class Fishtank
{
    public static function set($state)
    {
        $file = config('fishtank.file');

        if ($state == 'auto') {
            if (self::daylight()) {
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
        $now = Carbon::now();

        $sunrise = self::sunrise();
        $sunset = self::sunset();

        return ($now->gt($sunrise) && $now->lt($sunset));
    }

    public static function sunrise()
    {
        $latitude = config('fishtank.location.latitude');
        $longitude = config('fishtank.location.longitude');
        return new Carbon(date_sunrise(time(), SUNFUNCS_RET_STRING, $latitude, $longitude));
    }

    public static function sunset()
    {
        $latitude = config('fishtank.location.latitude');
        $longitude = config('fishtank.location.longitude');
        return new Carbon(date_sunset(time(), SUNFUNCS_RET_STRING, $latitude, $longitude));
    }

    public static function data()
    {
        $fishtank_setting = Storage::disk('fishtank')->get(config('fishtank.file'));
        //the file has a new line at the end
        $fishtank_setting = trim($fishtank_setting);

        return [
            'time' => Carbon::now()->toDayDateTimeString(),
            'timezone' => config('app.timezone'),
            'daylight' => self::daylight(),
            'sunrise' => self::sunrise()->format('h:i A'),
            'sunset' => self::sunset()->format('h:i A'),
            'setting' => Setting::get('state', 'auto'),
            'actual' => $fishtank_setting,
        ];
    }
}
