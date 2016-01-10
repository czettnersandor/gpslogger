<?php
namespace GpsLogger\Tests;

use Silex\WebTestCase;
use Silex\Provider\DoctrineServiceProvider;

class AbstractWebTestCase extends WebTestCase
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
        $sql = "INSERT INTO `devices` (id, name, hash)
            VALUES (1, 'My Galaxy S3', 'mydevicehash');
        ";

        $app['db']->executeQuery($sql);
        $this->app = $app;

        return $app;
    }
}
