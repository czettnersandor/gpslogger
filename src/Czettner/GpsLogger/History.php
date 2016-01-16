<?php
namespace Czettner\GpsLogger;

class History
{
    protected $db;

    public function __construct(\Doctrine\DBAL\Connection $db)
    {
        $this->db = $db;
    }

    public function getLast24h($deviceId)
    {
        return $this->db->fetchAll(
            'SELECT * FROM `positions` WHERE `device_id` = ? AND `timestamp` > ?;',
            [$deviceId, time() - 24 * 60 * 60]
        );
    }
}
