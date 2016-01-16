<?php
namespace Czettner\GpsLogger;

class Log
{
    protected $db;
    protected $app;

    public function __construct(\Doctrine\DBAL\Connection $db, \Silex\Application $app)
    {
        $this->db = $db;
        $this->app = $app;
    }

    public function isAlreadyExist($deviceId, $timestamp)
    {
        $count = $this->db->fetchAssoc('SELECT COUNT(*) AS count FROM positions WHERE timestamp = ? AND device_id = ?', [$timestamp, $deviceId]);
        return ($count['count'] != 0);
    }

    public function getIdFromHash($hash)
    {
        $res = $this->db->fetchAssoc('SELECT id FROM devices WHERE hash = ?;', [$hash]);
        return $res['id'];
    }

    public function logPosition($hash, $lat, $lng, $timestamp)
    {
        $deviceId = $this->getIdFromHash($hash);
        if ($this->isAlreadyExist($deviceId, $timestamp)) {
            throw new LogException("Position already exists.", 1);
        }
        $this->app['monolog']->addInfo(sprintf("Position logged: %d: (%d) lat:%f, lng:%f.", $deviceId, $timestamp, $lat, $lng));
        return $this->db->insert('positions', [
            'device_id' => $deviceId,
            'timestamp' => $timestamp,
            'lat' => $lat,
            'lng' => $lng
        ]);
    }
}
