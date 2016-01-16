<?php
namespace Czettner\GpsLoggerTests;

use Silex\Provider\DoctrineServiceProvider;

class HistoryTest extends AbstractWebTestCase
{

    /**
     * devices
     */
    public function testRetrieveHistory()
    {
        $client = $this->createClient();
        $time = time();
        $crawler = $client->request(
            'POST',
            '/log',
            [
                'lat' => 66.11,
                'lng' => 88.99,
                'timestamp' => $time - 15,
                'hash' => 'mydevicehash',
            ]
        );
        $crawler = $client->request(
            'POST',
            '/log',
            [
                'lat' => 66.22,
                'lng' => 88.99,
                'timestamp' => $time,
                'hash' => 'mydevicehash',
            ]
        );

        $this->assertTrue($client->getResponse()->isOk());

        $crawler = $client->request(
            'GET',
            '/history/mydevicehash'
        );

        $expectedData = '[{"id":"1","device_id":"1","timestamp":"'.
            ($time - 15).
            '","lat":"66.11","lng":"88.99"},{"id":"2","device_id":"1","timestamp":"'.
            $time.'","lat":"66.22","lng":"88.99"}]';

        $this->assertEquals($client->getResponse()->getContent(), $expectedData);
    }
}
