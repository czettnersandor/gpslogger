<?php
namespace Czettner\GpsLogger;

class Devices
{
    protected $db;

    public function __construct(\Doctrine\DBAL\Connection $db)
    {
        $this->db = $db;
    }

    public function getAllDevices()
    {
        // TODO
        return $this->db->fetchAll('SELECT * FROM `devices`;');
    }
}
