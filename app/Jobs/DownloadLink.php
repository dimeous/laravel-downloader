<?php

namespace App\Jobs;

use App\DownloadUrls;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Class implements job to save file to storage
 */
class DownloadLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected DownloadUrls $downloadUrl;

    /**
     * Create a new job instance of download.
     *
     * @return void
     */
    public function __construct(DownloadUrls $downloadUrl)
    {
        $this->downloadUrl = $downloadUrl;
    }

    /**
     * Execute the job save url to file and link to database.
     *
     * @return void
     * @throws Throwable
     */
    public function handle()
    {
        try {
            $url = $this->downloadUrl->url;
            $this->downloadUrl->status = 'downloading';
            $contents = file_get_contents($url);
            if (!$contents) {
                $this->downloadUrl->status = 'error';
            } else {
                $name = preg_replace('/[^a-zA-Z0-9\']/', '_', $url);
                $name = str_replace("'", '', $name);
                $name = $this->downloadUrl->id . '_' . $name;
                $name = substr( $name, 0, 128);
                $this->downloadUrl->status = 'downloading';
                $file_full_path = 'public/downloads/';
                Storage::put(  $file_full_path .$name, $contents);
                $file_url = Storage::url($file_full_path . $name);
                $this->downloadUrl->resource_url = $file_url;
                $this->downloadUrl->status = 'complete';
            }
            $this->downloadUrl->save();
        } catch (Throwable $exception) {
            $this->downloadUrl->status = 'error';
            $this->downloadUrl->save();
            logger(sprintf('Could not download url id %d with url %s', $this->downloadUrl->id, $this->downloadUrl->url));
        }
    }

}
