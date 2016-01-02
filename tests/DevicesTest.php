<?php
namespace GpsLogger\Tests;

require_once __DIR__.'/../vendor/autoload.php';

use Silex\WebTestCase;

class DevicesTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../app/app.php';
        $app['debug'] = true;
        return $app;
    }

    public function testDevices()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/devices');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Devices")'));
    }
}
