<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
                                    if (strpos($link, '/image/') === FALSE) {
                                        $l = trim($link,'"');
                                        $this->markInDb($l);
                                    }
                                }

                            } else {
                                $l = trim($links,'"');
                                $this->markInDb($l);
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
            echo "$itemID\n";
        }
    }
}
