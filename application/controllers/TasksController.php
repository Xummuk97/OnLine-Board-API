<?php

namespace application\controllers;

use application\core\Controller;
use application\libs\Valid;

class TasksController extends Controller 
{
    public function onCreate()
    {
        $this->loadModels([ 'Tasks' ]);
    }
    
    public function addAction()
    {
        $this->headerAPI();
        
        $data = $_POST;
        
        $title = Valid::postVariable('title');
        $description = Valid::postVariable('description');
        
        $errors = [];
        
        if (empty($title))
        {
            $errors[] = [ 'title' => 'Укажите заголовок задачи' ];
        }
        
        if (empty($description))
        {
            $errors[] = [ 'description' => 'Укажите описание задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        $insert_id = $this->models['Tasks']->add($title, $description);
        
        $this->sendResponse(200, [
            'id' => 'TASK-' . $insert_id
        ]);
    }
}