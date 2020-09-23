<?php

namespace application\libs;

use PDO;

class Db
{

    protected $db;

    public function __construct()
    {
        $db_settings = \application\configs\Db::getSettings();
        $this->db    = new PDO("mysql:host={$db_settings['host']};dbname={$db_settings['dbname']}",
                               $db_settings['user'], $db_settings['pass']);
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            foreach ($params as $key => $value)
            {
                $stmt->bindValue(':' . $key, $value);
            }
        }
        $stmt->execute();
        
        return $stmt;
    }

    public function row($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }

    public function getInsertId()
    {
        return $this->db->lastInsertId();
    }
}
