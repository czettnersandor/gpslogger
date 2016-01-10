<?php
/**
 * Install and upgrade script.
 *
 * Run this when you run this application first time or when upgrade to a new
 * version.
 */

// TODO: implement.

if (!isset($app)) {
    $app = require(__DIR__.'/app/app.php');
}

$sql = "CREATE TABLE IF NOT EXISTS `devices` (
   `id` INTEGER PRIMARY KEY,
   `name` TEXT NOT NULL,
   `hash` TEXT NOT NULL
)
";

$app['db']->executeQuery($sql);

$sql = "CREATE TABLE IF NOT EXISTS `positions` (
   `id` INTEGER PRIMARY KEY,
   `device_id` INT     NOT NULL,
   `timestamp` INT     NOT NULL,
   `lat`       INT     NOT NULL,
   `lng`       INT     NOT NULL
)
";

$app['db']->executeQuery($sql);
