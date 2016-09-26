<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TraversePath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traverse:path {path : Absolute Path of directory to be traversed}
    {--table= : Table where the traversed path will be stored}
    {--csv-path= : Path of the CSV file in which the traversed path will be stored}
    {--truncate-table : If the table should be truncated before filling up. Should be used with --table switch}
    {--show-output : Should the output be displayed on screen}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for traversing a file system path and fill the database';

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
        $path = $this->argument('path');
        $table = $this->option('table');
        $csvPath = $this->option('csv-path');
        $showOutput = $this->option('show-output');
        $truncateTable = $this->option('truncate-table');

        if (!file_exists($path)) {
            throw new \InvalidArgumentException('Path does not exist!');
        }
        if (!is_readable($path)) {
            throw new \InvalidArgumentException('Path not readable!');
        }
        if (!$table && !$csvPath) {
            throw new \InvalidArgumentException('Either a Table name or a CSV file path is required!');
        }

        if ($csvPath) {
            if(!is_writable(dirname($csvPath))) {
                throw new \InvalidArgumentException('CSV Path is not writable! Path: '.$csvPath);
            }
        }

        if ($truncateTable) {
            \DB::table($table)->truncate();
        }

        if ($csvPath) {
            $fp = fopen($csvPath, 'w');
        }

        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path,\RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){

            if (!$object->isDir()) {

                if ($showOutput) {
                    echo "$name\n";
                }

                $fileArray = [
                    'file_path'     =>  $name,
                    'file_name'     =>  basename($name)
                ];


                if (!empty($table)) {
                    echo "here";
                    \DB::table($table)->insert($fileArray);
                }

                if ($csvPath) {
                    fputcsv($fp, $fileArray);
                }

            }
        }
        if ($csvPath) {
            fclose($fp);
        }
    }
}
