<?php
namespace GpsLogger\Tests;

use Silex\WebTestCase;
use Silex\Provider\DoctrineServiceProvider;

class DevicesTest extends WebTestCase
{
    private $dbFile = __DIR__.'/phpunit.db';
    /**
     * This will run before every test
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../app/app.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        // Modify the database service provider to use SQLite for tests
        $app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_sqlite',
                'path'     => __DIR__.'/phpunit.db',
            ),
        ));

        // Empty database
        if (file_exists($this->dbFile)) {
            unlink($this->dbFile);
        }
        require(__DIR__.'/../install.php');

        // Fill database with dummy data
        $sql = "INSERT INTO `devices` (id, name)
            VALUES (1, 'My Galaxy S3');
        ";

        $app['db']->executeQuery($sql);
        $this->app = $app;

        return $app;
    }

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
                'device' => 1,
                'lat' => 66,
                'lng' => 88.99
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
            'device' => 1,
            'lat' => 66,
            'lng' => 88.99
        ];
        $crawler = $client->request('POST', '/log', $logData);
        $this->assertTrue($client->getResponse()->isOk());
        $crawler = $client->request('POST', '/log', $logData);
        $this->assertFalse($client->getResponse()->isOk());
        $results = $this->app['db']->fetchAssoc('SELECT * FROM `positions`;');
        $this->assertCount(5, $results);
    }
}
