<?php
namespace GpsLogger\Tests;

use Silex\Provider\DoctrineServiceProvider;

require_once('AbstractWebTestCase.php');

class DevicesTest extends AbstractWebTestCase
{

    /**
     * devices
     */
    public function testDevices()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/devices');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Devices")'));
        $this->assertCount(1, $crawler->filter('li:contains("My Galaxy S3")'));
        $this->assertCount(1, $crawler->filter('li > span:contains("mydevicehash")'));
    }
}
