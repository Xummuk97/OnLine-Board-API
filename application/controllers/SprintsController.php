<?php

namespace application\controllers;

use application\core\Controller;
use application\libs\Valid;

class SprintsController extends Controller 
{
    public function onCreate()
    {
        $this->loadModels([ 'Tasks', 'Sprints' ]);
    }
    
    public function addAction()
    {
        $this->headerAPI();
        
        $week = Valid::postVariable('Week');
        $year = Valid::postVariable('Year');
        
        $errors = [];
        
        if (empty($week))
        {
            $errors[] = [ 'Week' => 'Укажите неделю спринта' ];
        }
        
        if (empty($year))
        {
            $errors[] = [ 'Year' => 'Укажите год спринта' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        $id = substr($year, 2) . '-' . $week;
        
        if ($this->models['Sprints']->find($id))
        {
            $this->sendResponseGlobalError(400, 'Запись с указанным идентификатором спринта уже есть');
        }
        
        $this->models['Sprints']->add($id);
        
        $this->sendResponse(200, [
            'Id' => $id,
        ]);
    }
}