<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$config = require_once __DIR__.'/../config/config.php';

$app['debug'] = $config['misc']['debug'];

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $config['db'],
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

return $app;