<?php

spl_autoload_register(function($class)
{
    $path = str_replace('\\', '/', $class . '.php');
    
    if (file_exists($path))
    {
        require_once $path;
    }
});

application\libs\Dev::displayErrors();

session_start();

$router = new application\core\Router;
$router->run();