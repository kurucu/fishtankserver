<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Fishtank;
use Setting;

class FishtankController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function set_state($state = 'auto')
    {
        if( !in_array($state, ['day', 'night', 'auto', 'off']))
        {
            $state = 'auto';
        }

        Setting::set('state', $state);
        Fishtank::set($state);

        return response()->json([
            'requested_state' => $state
        ]);;
    }

}
