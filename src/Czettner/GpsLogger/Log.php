<?php
namespace Czettner\GpsLogger;

class Log
{
    protected $db;

    public function __construct(\Doctrine\DBAL\Connection $db)
    {
        $this->db = $db;
    }

    public function isAlreadyExist($deviceId, $timestamp)
    {
        $count = $this->db->fetchAssoc('SELECT COUNT(*) AS count FROM positions WHERE timestamp = ? AND device_id = ?', [$timestamp, $deviceId]);
        return ($count['count'] != 0);
    }

    protected function getIdFromHash($hash)
    {
        $res = $this->db->fetchAssoc('SELECT id FROM devices WHERE hash = ?;', [$hash]);
        return $res['id'];
    }

    public function logPosition($hash, $lat, $lng)
    {
        $timestamp = time();
        $deviceId = $this->getIdFromHash($hash);
        if ($this->isAlreadyExist($deviceId, $timestamp)) {
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
