<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\SipService;

class GenerateRootAcmsCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-csv:root {--chunk=1000 : Number of rows to include in a CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This console command will retrieve all the ACMS Collection rows which do not have'.
                            ' entry as ItemID in the collection table, so basically all the root level ACMS rows.'.
                            'The CSV will be chunked by 1000 rows per CSV, You can override this number by specifying'.
                            '--chunk=xxxx option';

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
        $chunk = $this->option('chunk');

        $path = $sipService->generateRootAcmsCSVs($chunk);
        $this->line('Generated CSVs in: '. $path);
    }
}
