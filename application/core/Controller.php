<?php

namespace application\core;

use application\core\View;

abstract class Controller
{

    protected $route;
    protected $models = [];
    public $view;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view  = new View($route);
        $this->onCreate();
    }

    abstract public function onCreate();

    public function loadModels($models)
    {
        foreach ($models as $model)
        {
            $this->loadModel($model);
        }
    }
    
    public function loadModel($name)
    {
        $model_path = 'application\models\\' . ucfirst($name);

        if (class_exists($model_path)) {
            $this->models[$name] = new $model_path;
        }
    }

    public function headerAPI()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
    }

    public function sendResponseFieldErrors($code, $errors)
    {
        if (!empty($errors))
        {
            http_response_code($code);
            
            echo json_encode([ 
                'Errors' => [
                    'Fields' => $errors
                ],
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    public function sendResponseGlobalError($code, $error)
    {
        http_response_code($code);
        
        echo json_encode([ 
            'Errors' => [
                'Global' => $error
            ],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function sendResponse($code, $response = [])
    {
        # Выводим ответ
        http_response_code($code);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function checkEmptyVariable($array)
    {
        $errors = [];
        
        foreach ($array as $key => $element)
        {
            if (empty($element['value']))
            {
                $errors[] = [ $key => $element['error'] ];
            }
        }
        
        $this->sendResponseFieldErrors(400, $errors);
    }
}
