<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SipService;

class GeneratePDFSips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-sip:pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for generating SIPs for the Records with PDf files';

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
         $startGeneration = $this->anticipate('Start Generating? (yes/no)', ['yes','no']);
         $forceGeneration = $this->anticipate('Force Generation? (yes/no)', ['yes','no']);

         if ($startGeneration == 'no') {
             exit;
         }

         if ($forceGeneration == 'no') {
             $forceGeneration = 0;
         }

         $logFileName = 'log-pdfs.html';

         $logFile = public_path().'/downloads/sips/'.$logFileName;
         $logFileUrl = '/downloads/sips/'.$logFileName;
         $zipPath = $sipService->generatePDFSip($logFile, $forceGeneration);

         $domain = 'http://slnsw-dmt-stage.tk';

         if ($zipPath) {
             $this->line("Generated Zip from: $domain". $zipPath);
         }
         $this->line("Log file: $domain". $logFileUrl);

     }
}
