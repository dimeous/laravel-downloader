<?php

namespace App\Console\Commands;

use App\DownloadUrls;
use App\Http\Controllers\DownloaderController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class DownloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download {--list} {--url=} {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download job command';

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
     * @return int
     */
    public function handle(): int
    {
        //add url
        if ($url = $this->option('url')) {
            $validation = Validator::make(['url'=>$url],[
                'url' => 'url'
            ]);
            if ($validation->fails()) {
                $this->error($validation->errors());
            }else {
                $result = DownloaderController::addDownloadJob($url);
                $this->info('add url:' . $url);
                $this->info($result);
            }
        }

        //list downloads
        if ( $this->option('list')) {
            $this->info('List downloads');
            $this->info(DownloadUrls::all()->sortByDesc("id"));
        }

        //info about download by ID
        if ( $id = $this->option('id')) {
            $this->info('info about download by ID:'.$id);
            $this->info(DownloadUrls::where('id', $id)
                ->take(1)
                ->get());
        }

        return 0;
    }
}
