<?php

namespace application\configs;

class Db
{

    public static function getSettings()
    {
        return [
            'host'   => 'localhost',
            'dbname' => 'online_board',
            'user'   => 'root',
            'pass'   => ''
        ];
    }

}
