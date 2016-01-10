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

    /**
     * log
     */
    public function testLogPosition()
    {
        $client = $this->createClient();
        $crawler = $client->request(
            'POST',
            '/log',
            [
                'lat' => 66,
                'lng' => 88.99,
                'timestamp' => 123456,
                'hash' => 'mydevicehash',
            ]
        );
        $this->assertTrue($client->getResponse()->isOk());

        $results = $this->app['db']->fetchAssoc('SELECT * FROM `positions`;');
        $this->assertCount(5, $results);
        $this->assertEquals($results['lat'], 66);
        $this->assertEquals($results['lng'], 88.99);
    }

    public function testLogDuplicates()
    {
        $client = $this->createClient();
        $logData = [
            'lat' => 66,
            'lng' => 88.99,
            'timestamp' => 123456,
            'hash' => 'mydevicehash',
        ];
        $crawler = $client->request('POST', '/log', $logData);
        $this->assertTrue($client->getResponse()->isOk());
        $crawler = $client->request('POST', '/log', $logData);
        $this->assertFalse($client->getResponse()->isOk());
        $results = $this->app['db']->fetchAssoc('SELECT * FROM `positions`;');
        $this->assertCount(5, $results);
    }
}
