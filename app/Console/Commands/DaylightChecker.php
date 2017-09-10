<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Fishtank;
use Setting;

class DaylightChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daylight:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Confirms whether there is daylight, and changes the tank lights if appropriate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $daylight = Fishtank::daylight();
        $desired_mode = Setting::get('state', 'auto');

        if($desired_mode != 'auto'){
            Fishtank::set($desired_mode);
        } else {
            if($daylight == true)
            {
                Fishtank::set('day');
            } else {
                Fishtank::set('night');
            }
        }
    }
}
