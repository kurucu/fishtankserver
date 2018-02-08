<?php

namespace App;

use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Carbon\Carbon;
use Setting;

class Fishtank
{
    public static function set($state)
    {
        $file = config('fishtank.file');
        $api_state = '';

        if ($state == 'auto') {
            if (self::daylight()) {
                Storage::disk('fishtank')->put($file, "day\n");
                $api_state = 'day';
            } else {
                Storage::disk('fishtank')->put($file, "night\n");
                $api_state = 'night';
            }
        } else {
            Storage::disk('fishtank')->put($file, $state . "\n");
            $api_state = $state;
        }


        /*
        * Tell the server what's going on
        */
        $client = new Client(); //GuzzleHttp\Client
        $result = $client->post('https://iot.elliotali.com/api/status', [
            'form_params' => [
                'id' => env('DEVICE_ID', 1),
                'key' => env('DEVICE_KEY', 'abcde'),
                'status' => $api_state,
            ]
        ]);
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
