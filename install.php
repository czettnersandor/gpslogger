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

$schema = new \Doctrine\DBAL\Schema\Schema();

$devicesTable = $schema->createTable("devices");
$devicesTable->addColumn("id", "integer", ["unsigned" => true, 'autoincrement' => true]);
$devicesTable->addColumn("name", "string", ["length" => 128]);
$devicesTable->addColumn("hash", "string", ["length" => 256]);
$devicesTable->setPrimaryKey(array("id"));
$devicesTable->addUniqueIndex(array("hash"));

$posTable = $schema->createTable("positions");
$posTable->addColumn("id", "integer", ["unsigned" => true, 'autoincrement' => true]);
$posTable->addColumn("device_id", "integer", ["unsigned" => true]);
$posTable->addColumn("timestamp", "integer", ["unsigned" => true]);
$posTable->addColumn("lat", "float", []);
$posTable->addColumn("lng", "float", []);
$posTable->setPrimaryKey(array("id"));

$platform = $app['db']->getDatabasePlatform();
$queries = $schema->toSql($platform);

foreach ($queries as $query) {
    $app['db']->executeQuery($query);
}
