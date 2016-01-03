<?php

use Czettner\GpsLogger\Devices;
use Czettner\GpsLogger\Log;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__.'/bootstrap.php';

$app->get('/devices', function () use ($app) {
    $devices = new Devices($app['db']);
    return $app['twig']->render('devices.twig', array(
        'devices' => $devices->getAllDevices(),
    ));
});

$app->post('/log', function (Request $request) use ($app) {
    $device = $request->get('device');
    $lat = $request->get('lat');
    $lng = $request->get('lng');

    $log = new Log($app['db']);
    $log->logPosition($device, $lat, $lng);

    return new Response('SUCCESS', 200);
});

return $app;
