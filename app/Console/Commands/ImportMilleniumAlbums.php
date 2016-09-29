<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
/**
 * This command is used to go through the csv file with millenium records for
 * standalone and album images, parses the links and markes these in the 'item'
 * table as 'm'
 */
class ImportMilleniumAlbums extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:millenium {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to go through the csv file with millenium records for
    standalone and album images, parses the links and markes these in the \'item\'
    table as \'m\'';

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
        $csvPath = $this->argument('path');

        if (!is_readable($csvPath)) {
            throw new \InvalidArgumentException('CSV file not readable');
        }

        $row = 1;

        if (($handle = fopen($csvPath, "r")) !== FALSE) {

            fgetcsv($handle, 1000, ",");

            while (( $data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    if($c == 3) {
                        $links = $data[$c];
                        if (strpos($links, 'albumView.aspx') !== FALSE){

                            $linkArr = explode(';', $links);
                            if (count($linkArr) > 1)
                            {
                                foreach ($linkArr as $link) {
                                    $l = trim($link,'"');
                                    if (strpos($link, '/image/') === FALSE) {
                                        $this->markInDb($l);
                                    } else {
                                        $this->markImageInDb($l);
                                    }
                                }

                            } else {
                                $l = trim($links,'"');
                                //$this->markInDb($l);
                            }

                        } elseif (strpos($links, 'image') !== FALSE){
                            $linkArr = explode(';', $links);
                            if (count($linkArr) > 1)
                            {
                                foreach ($linkArr as $link) {
                                    if (strpos($link, '/image/') !== FALSE) {
                                        $l = trim($link,'"');
                                        $this->markImageInDb($l);
                                    }
                                }

                            } else {
                                $l = trim($links,'"');
                                $this->markImageInDb($l);
                            }
                        }
                    }
                }
            }
        }

    }

    public function markInDb($link)
    {
        $pUrl = parse_url($link);
        if (array_key_exists('query', $pUrl)) {
            $query = $pUrl['query'];
            $queryArr = parse_str($query);
            //echo "$itemID\n";

            \DB::table('item')
                ->where('itemID', $itemID)
                ->update(['acms_mill' => 'm']);



        }
    }

    public function markImageInDb($link)
    {
        $filename = basename($link);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        $digitalId = trim($basename, "rhupt");

        \DB::table('item')
            ->where('itemKey', $digitalId)
            ->where('assetType', 'image')
            ->where('itemType', 'image')
            ->update(['acms_mill' => 'm', 'album_standalone' => 's']);

    }

}
