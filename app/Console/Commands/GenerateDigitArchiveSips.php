<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SipService;

class GenerateDigitArchiveSips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-sip:digit-archive  {--force-generation=0}';

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
        $forceGeneration = $this->option('force-generation');

        \DB::table('missing_files_on_permanent_storage')
                ->distinct('item_id')->chunk(100, function ($missingRows) use ($forceGeneration){
                    foreach ($missingRows as $missingRow) {
                        $this->line('Starting with item ID: '.$missingRow->item_id);                
                        // $this->call('generate-sip:digit-archive-item', [
                        //     '--item-id'            =>  $missingRow->item_id,
                        //     '--force-generation'   =>  $forceGeneration
                        // ]);
                    }
            
        });
    }
}
