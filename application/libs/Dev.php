<?php

namespace application\libs;

class Dev
{

    public static function displayErrors()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }

    public static function debug($param)
    {
        echo '<pre>';
        var_dump($param);
        echo '</pre>';
        exit;
    }

}
