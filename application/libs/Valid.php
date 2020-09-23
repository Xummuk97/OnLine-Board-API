<?php

namespace application\libs;

class Valid
{
    public static function postVariable($name)
    {
        return isset($_POST[$name]) ? trim(htmlspecialchars($_POST[$name])) : '';
    }
}