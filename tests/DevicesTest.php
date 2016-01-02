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

        return $app;
    }

    /**
     * /devices
     */
    public function testDevices()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/devices');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h1:contains("Devices")'));
    }
}
