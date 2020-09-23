<?php

namespace application\core;

class View
{

    protected $route;
    public $path;
    public $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path  = $route['controller'] . '/' . $route['action'];
    }

    public function render($title, $vars = [])
    {
        extract($vars);

        $view_path = 'application/views/' . $this->path . '.php';

        if (file_exists($view_path)) {
            ob_start();
            require $view_path;
            $content     = ob_get_clean();
            $layout_path = 'application/views/layouts/' . $this->layout . '.php';

            if (file_exists($layout_path)) {
                require $layout_path;
            } else {
                echo "Не найден шаблон: $layout_path";
            }
        } else {
            echo "Не найден вид: $view_path";
        }
    }

    public function redirect($url)
    {
        header("location: $url");
        exit;
    }

    public static function errorCode($code, $params = [])
    {
        http_response_code($code);
        \application\libs\Dev::debug($params);
    }

}
