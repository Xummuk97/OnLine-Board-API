<?php

namespace application\libs;

class Valid
{
    public static function normalStrFromPOST($name)
    {
        return isset($_POST[$name]) ? trim(htmlspecialchars($_POST[$name])) : '';
    }
}