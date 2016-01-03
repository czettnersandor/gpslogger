<?php
namespace Czettner\GpsLogger;

class Log
{
    protected $db;

    public function __construct(\Doctrine\DBAL\Connection $db)
    {
        $this->db = $db;
    }

    public function isAlreadyExist($deviceId, $timestamp, $lat, $lng)
    {
        $count = $this->db->fetchAssoc('SELECT COUNT(*) AS count FROM positions WHERE timestamp = ? AND device_id = ?', [$timestamp, $deviceId]);
        return ($count['count'] != 0);
    }

    public function logPosition($deviceId, $lat, $lng)
    {
        $timestamp = time();
        if ($this->isAlreadyExist($deviceId, $timestamp, $lat, $lng)) {
            throw new LogException("Position already exists.", 1);
        }
        return $this->db->insert('positions', [
            'device_id' => $deviceId,
            'timestamp' => $timestamp,
            'lat' => $lat,
            'lng' => $lng
        ]);
    }
}
