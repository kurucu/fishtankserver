<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Fishtank;
use Setting;

class FishtankController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function get_state()
    {
        return response()->json([
            'data' => Fishtank::data(),
        ]);
    }

    public function set_state($state = 'auto')
    {
        if (!in_array($state, ['day', 'night', 'auto', 'off'])) {
            $state = 'auto';
        }

        Setting::set('state', $state);
        Fishtank::set($state);

        return response()->json([
            'requested_state' => $state,
            'data' => Fishtank::data(),
        ]);
        ;
    }
}
