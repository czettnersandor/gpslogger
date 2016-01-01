<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Czettner\GpsLogger\Devices;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$dbConfig = include(__DIR__.'/../config/config.php');

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $dbConfig,
));

$app['debug'] = true;

$app->get('devices', function () use ($app) {
    $devices = new Devices($app['db']);

    var_dump($devices->getAllDevices());
    return 'Hello '.$app->escape($device);
});

$app->post('/log', function (Request $request) {
    $device = $request->get('device');
    $lat = $request->get('lat');
    $lng = $request->get('lng');

    return new Response('Thank you for your feedback!', 201);
});

$app->run();
