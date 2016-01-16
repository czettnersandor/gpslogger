<?php

use Czettner\GpsLogger\Devices;
use Czettner\GpsLogger\Log;
use Czettner\GpsLogger\History;
use Czettner\GpsLogger\LogException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__.'/bootstrap.php';

$app->before(function () use ($app) {
    $app['twig']->addGlobal('html', $app['twig']->loadTemplate('html.twig'));
});

$app->get('/devices', function () use ($app) {
    $devices = new Devices($app['db']);
    return $app['twig']->render('devices.twig', array(
        'devices' => $devices->getAllDevices(),
    ));
});

$app->get('/map', function () use ($app) {
    $devices = new Devices($app['db']);
    return $app['twig']->render('map.twig', array(
        'history' => $devices->getAllDevices(),
    ));
});

$app->get('/history/{hash}', function ($hash) use ($app) {
    $log = new Log($app['db'], $app);
    $deviceId = $log->getIdFromHash($hash);
    $history = new History($app['db']);
    return new Response(json_encode($history->getLast24h($deviceId)), 200);
});

$app->post('/log', function (Request $request) use ($app) {
    $lat = $request->get('lat');
    $lng = $request->get('lng');
    $timestamp = $request->get('timestamp');
    $hash = $request->get('hash');

    $log = new Log($app['db'], $app);
    try {
        $log->logPosition($hash, $lat, $lng, $timestamp);
    } catch (LogException $e) {
        return new Response($e->getMessage(), 503);
    }

    return new Response('SUCCESS', 200);
});

return $app;
