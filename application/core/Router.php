<?php

namespace application\core;

class Router
{

    protected $routes = [];
    protected $params = [];

    public function __construct()
    {
        $routes_date = \application\configs\Routes::getRoutes();

        foreach ($routes_date as $key => $value)
        {
            $this->add($key, $value);
        }
    }

    public function add($route, $params)
    {
        $route                = "#^$route$#";
        $this->routes[$route] = $params;
    }

    public function match()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        foreach ($this->routes as $route => $params)
        {
            if (preg_match($route, $uri, $matches)) {
                array_shift($matches);
                $params['matches'] = $matches;
                $this->params      = $params;
                return true;
            }
        }

        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = 'application\controllers\\'
                    . ucfirst($this->params['controller'])
                    . 'Controller';

            if (class_exists($path)) {
                $action = $this->params['action'] . 'Action';

                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);

                    call_user_func_array([$controller, $action],
                                         $this->params['matches']);
                } else {
                    View::errorCode(404, ['error' => 'Не найден метод (Action): ' . $action]);
                }
            } else {
                View::errorCode(404, ['error' => 'Не найден класс (Файл, где он расположен): ' . $path]);
            }
        } else {
            View::errorCode(404, ['error' => 'Не найден маршрут']);
        }
    }

}
