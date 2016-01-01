<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Czettner\GpsLogger\Model;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('devices', function () use ($app) {
    $model = new Model();
    return 'Hello '.$app->escape($device);
});

$app->post('/log', function (Request $request) {
    $device = $request->get('device');
    $lat = $request->get('lat');
    $lng = $request->get('lng');

    return new Response('Thank you for your feedback!', 201);
});

$app->run();
