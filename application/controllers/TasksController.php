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
    
    public function setEstimationAction()
    {
        $this->headerAPI();
        
        $id = Valid::postVariable('id');
        $estimation = Valid::postVariable('estimation');
        
        $errors = [];
        
        if (empty($id))
        {
            $errors[] = [ 'id' => 'Укажите идентификатор задачи' ];
        }
        
        if (empty($estimation))
        {
            $errors[] = [ 'estimation' => 'Укажите оценку задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        str_replace('TASK-', '', $id);
        
        if (!$this->models['Tasks']->find($id))
        {
            $this->sendResponseGlobalError(400, 'Не найдена запись по указанному идентификатору задачи');
        }
        
        $this->models['Tasks']->setEstimation($id, $estimation);
        
        $this->sendResponse(200);
    }
    
    public function setCloseAction()
    {
        $this->headerAPI();
        
        $taskId = Valid::postVariable('taskId');
        
        $errors = [];
        
        if (empty($taskId))
        {
            $errors[] = [ 'taskId' => 'Укажите идентификатор задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        $taskId = str_replace('TASK-', '', $taskId);
        
        if (!$this->models['Tasks']->find($taskId))
        {
            $this->sendResponseGlobalError(400, 'Не найдена запись по указанному идентификатору задачи');
        }
        
        $this->models['Tasks']->setClose($taskId);
        
        $this->sendResponse(200);
        
    }
}