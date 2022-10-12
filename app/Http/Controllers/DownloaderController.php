<?php

namespace App\Http\Controllers;

use App\DownloadUrls;
use App\Jobs\DownloadLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class for download controller
 */
class DownloaderController extends Controller
{
    /**
     * Web Request to download url
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function prepare(Request $request)
    {
        $this->validate($request, [
            'url' => 'url'
        ]);
        self::addDownloadJob($request->input('url'));
        return redirect()->route('status');
    }

    /**
     * Web View Status of downloads
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status()
    {
        return view('status', ['downloads' => DownloadUrls::all()->sortByDesc("id")]);
    }

    /**
     *  Post action add new url
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->validate($request, [
                'url' => 'url'
            ]);
            $data = self::addDownloadJob($url = $request->json('url'));
            return response()->json([
                'status' => 'success',
                'message' => $url . ' added to job',
                'data' => [
                    'job' => $data
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    /**
     *  Get downloads by API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloads(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'List of downloads',
            'data' => [
                'downloads' => DownloadUrls::all()->sortByDesc("id")
            ]
        ], 200);
    }

    /**
     *  Get download by ID  API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function downloadByID($id): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Download Info id:'.$id,
            'data' => [
                'info_download' => DownloadUrls::where('id', $id)
                    ->take(1)
                    ->get()
            ]
        ], 200);
    }

    /**
     * Add Download job
     * @param string $url
     * @return DownloadUrls
     */
     public static function addDownloadJob(string $url): DownloadUrls
    {
        $downloadUrl = new DownloadUrls;
        $downloadUrl->url = $url;
        $downloadUrl->save();
        DownloadLink::dispatch($downloadUrl);
        return $downloadUrl;
    }

}

