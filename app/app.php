<?php

use Czettner\GpsLogger\Devices;
use Czettner\GpsLogger\Log;
use Czettner\GpsLogger\LogException;
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
    $lat = $request->get('lat');
    $lng = $request->get('lng');
    $hash = $request->get('hash');

    $log = new Log($app['db'], $app);
    try {
        $log->logPosition($hash, $lat, $lng);
    } catch (LogException $e) {
        return new Response($e->getMessage(), 503);
    }

    return new Response('SUCCESS', 200);
});

return $app;
