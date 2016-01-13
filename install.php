<?php
/**
 * Install and upgrade script.
 *
 * Run this when you run this application first time or when upgrade to a new
 * version.
 */

// TODO: use DBAL because AUTOINCREMENT is the syntax in SQLite
// and AUTO_INCREMENT in MySQL.

if (!isset($app)) {
    $app = require(__DIR__.'/app/app.php');
}

$sql = "CREATE TABLE IF NOT EXISTS `devices` (
   `id` INTEGER PRIMARY KEY AUTOINCREMENT,
   `name` TEXT NOT NULL,
   `hash` TEXT NOT NULL
)
";

$app['db']->executeQuery($sql);

$sql = "CREATE TABLE IF NOT EXISTS `positions` (
   `id` INTEGER PRIMARY KEY AUTOINCREMENT,
   `device_id` INT     NOT NULL,
   `timestamp` INT     NOT NULL,
   `lat`       FLOAT   NOT NULL,
   `lng`       FLOAT   NOT NULL
)
";

$app['db']->executeQuery($sql);
