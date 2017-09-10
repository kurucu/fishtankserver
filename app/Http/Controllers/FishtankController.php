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

    public function set_state(Request $request)
    {
        $request->validate([
            'state' => [
                'required',
                'string',
                Rule::in(['day', 'night', 'auto', 'off']),
            ],
        ]);

        $state = $request->input('state');

        //Setting::set('state', $state );
        Fishtank::set($state);

        return true;
    }

    public function day()
    {

    }
}
