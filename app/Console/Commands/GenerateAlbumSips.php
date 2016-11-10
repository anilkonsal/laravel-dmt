<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SipService;

class GenerateAlbumSips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-sip:album {--item-id=} {--force-generation=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $itemId = $this->option('item-id');
        if (empty($itemId)) {
            $itemId = $this->ask('What is the item ID?');
        }

        $forceGeneration = $this->option('force-generation');
        if (is_null($forceGeneration)) {
            $forceGeneration = $this->anticipate('Force Generate? (yes/no)', ['yes','no']);
        }

        if ($forceGeneration == 'no') {
            $forceGeneration = 0;
        }

        if (empty($itemId)) {
            throw new \InvalidArgumentException( 'Please provide the item ID', '400');
        }


        $logFileName = 'log-'.$itemId.'-album.html';

        $logFile = public_path().'/downloads/sips/'.$logFileName;
        $logFileUrl = '/downloads/sips/'.$logFileName;
        $zipPath = $sipService->generateAlbumSip($itemId, $logFile, $forceGeneration);

        $domain = 'http://slnsw-dmt-stage.tk';

        if ($zipPath) {
            $this->line("Generated Zip from: $domain". $zipPath);
        }

        if (file_exists($logFile)) {
            $this->line("Log file: $domain". $logFileUrl);
        }

    }
}
