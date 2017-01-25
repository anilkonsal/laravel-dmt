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
    protected $description = 'This command generates the SIPs for millenium records by reading an XML file containing MARCs';

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

        $records = $xml->xpath('//record');

        $totalRecords = count($records);

       


        for($i=1; $i<= $totalRecords; $i++) {
          
            echo "i: $i\n";
            $recordXml = $xml->xpath('//record['.$i.']')[0];

            $imgUrlNode = $xml->xpath("//record[".$i."]/datafield[@tag=856]/subfield[@code='u']");
            if (!$imgUrlNode) {
                $imgUrlNode = $xml->xpath("//record[".$i."]/datafield[@tag=856]/subfield")[0];
                $imgUrl = $imgUrlNode->__toString();
            } else {
                $imgUrl = $imgUrlNode[0]->__toString();
            }
            

            
            /*
            If 'album' word is found in the url, then this is an Album record, else
            this is a standalone record.
            */
            if (strpos($imgUrl, 'album') === false) {
                $path = parse_url($imgUrl, PHP_URL_PATH);
                if(preg_match('/(\d+)\/(\d+)\/(\w+)/', $path, $matches)) {
                    list($fullMatch, $folder1, $folder2, $imageName) = $matches;    
                    $sipService->generateMilleniumStandAloneSip($folder1, $folder2, $imageName, $recordXml, $forceGeneration);
                }
            } else {
                parse_str(parse_url($imgUrl, PHP_URL_QUERY), $arguments);
                if (!empty($arguments)) {
                    if (array_key_exists('itemID', $arguments)) {
                        $itemId = $arguments['itemID'];
                    } elseif (array_key_exists('itemid', $arguments)) {
                        $itemId = $arguments['itemid'];
                    }
                    $sipService->generateMilleniumAlbumSip($itemId, $recordXml, $forceGeneration);
                }
                
            }
        }

    }
}
