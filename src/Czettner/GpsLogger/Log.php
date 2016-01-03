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
        // TODO
        return false;
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
