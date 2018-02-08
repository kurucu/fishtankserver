<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Fishtank;
use Setting;

class GetCommands extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'commands:process';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Download and process any commands';

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
        $client = new Client(); //GuzzleHttp\Client
        $response = $client->post('https://iot.elliotali.com/api/get_commands', [
            'form_params' => [
                'id' => env('DEVICE_ID', 1),
                'key' => env('DEVICE_KEY', 'abcde'),
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody());
            foreach ($data as $command) {
                $state = $command->command;
                if (!in_array($state, ['day', 'night', 'auto', 'off'])) {
                    $state = 'auto';
                }

                Setting::set('state', $state);
                Setting::save();

                Fishtank::set($state);
            }
        }
    }
}
