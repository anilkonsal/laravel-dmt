<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SipService;

class GenerateCSVSips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-sip:csv {--csv-path= : The path of CSV file to read from} {--force-generation=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generates the SIPs from by reading the ItemIDs from a CSV';

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
    public function handle(SipService $sipService)
    {
        $csvPath = $this->option('csv-path');

        if (!(file_exists($csvPath) && is_file($csvPath))) {
            throw new Exception('The CSV file path is not correct! Make sure the file exists.');
        }

        $forceGeneration = $this->option('force-generation');
        if (($handle = fopen($csvPath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $this->line('Starting with itemID: '.$data[0]);
                
                $this->call('generate-sip:standalone', [
                    '--item-id'            =>  $data[0],
                    '--force-generation'   =>  $forceGeneration
                ]);

                $this->call('generate-sip:album', [
                    '--item-id'            =>  $data[0],
                    '--force-generation'   =>  $forceGeneration
                ]);


            }
        }



    }
}
