<?php

namespace Tests\Feature;

use App\DownloadUrls;
use App\Jobs\DownloadLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Tests\TestCase;

class DownloadUrlTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test a home
     *
     * @return void
     */
    public function test_a_home_request()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test a status
     *
     * @return void
     */
    public function test_a_status()
    {
        $response = $this->get('/status');

        $response->assertStatus(200);
    }


    /**
     * Test add url by api and job dispatched
     * @test
     */
    public function ensure_the_url_download_job_is_dispatched()
    {
        Bus::fake();

        $url = $this->faker->url;
        $response = $this->post(route('prepare'),['url'=>$url]);
        $download = DB::table('download_urls')->where('url', $url)->first();

        $response->assertRedirect(route('status', ['downloads' => DownloadUrls::all()->sortByDesc("id")]));
        $response->assertSessionHasNoErrors();

        Bus::assertDispatched(DownloadLink::class, function ($job) use ($download) {
            return $this->getPrivateProperty($job, 'downloadUrl')->id === $download->id;
        });
    }

    protected function getPrivateProperty(object $obj, string $property)
    {
        $reflection = new ReflectionClass($obj);
        $privateProperty = $reflection->getProperty($property);
        $privateProperty->setAccessible(true);

        return $privateProperty->getValue($obj);
    }

}
