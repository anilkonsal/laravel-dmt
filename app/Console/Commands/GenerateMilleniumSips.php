<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SipService;

class GenerateMilleniumSips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-millenium-sip {--xml-path=} {--force-generation=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generates the SIPs for millenium records.';

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
        $xmlPath = $this->option('xml-path');
        
        if (!is_readable($xmlPath)) {
            throw new \InvalidArgumentException('xml-path is not not readable!');
        }

        $forceGeneration = $this->option('force-generation');

        $xml = simplexml_load_file($xmlPath);

        $index = 6;

        $imgUrl = $xml->xpath("//record[".$index."]/datafield[@tag=856]")[0]->subfield[1]->__toString();
        $recordXml = $xml->xpath('//record['.$index.']')[0];

        /*
        If 'album' word is found in the url, then this is an Album record, else
        this is a standalone record.
        */
        if (strpos($imgUrl, 'album') === false) {
            $path = parse_url($imgUrl, PHP_URL_PATH);
            
            if(preg_match('/(\d+)\/(\d+)\/(\w+)/', $path, $matches)) {
                list($fullMatch, $folder1, $folder2, $imageName) = $matches;    
                $sipService->generateMilleniumStandAloneSip($folder1, $folder2, $imageName, $recordXml);
            }

        } else {
            // http://acms.sl.nsw.gov.au/album/albumview.aspx?itemID=1025703&acmsid=0
            parse_str(parse_url($imgUrl, PHP_URL_QUERY), $arguments);
            if (!empty($arguments)) {
                $itemId = $arguments['itemID'];
                $sipService->generateMilleniumAlbumSip($itemId, $recordXml);
            }
            
        }

    }
}
