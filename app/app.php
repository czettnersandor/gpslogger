<?php

use \Czettner\GpsLogger\Devices;

$app = require_once __DIR__.'/bootstrap.php';

$app->get('devices', function () use ($app) {
    $devices = new Devices($app['db']);
    return $app['twig']->render('devices.twig', array(
        'devices' => $devices->getAllDevices(),
    ));
});

$app->post('/log', function (Request $request) {
    $device = $request->get('device');
    $lat = $request->get('lat');
    $lng = $request->get('lng');

    return new Response('Thank you for your feedback!', 201);
});

return $app;