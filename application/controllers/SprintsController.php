<?php

namespace application\controllers;

use application\core\Controller;
use application\libs\Valid;
use application\libs\Utils;

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
            $this->sendResponseGlobalError(400, 'Спринт с указанным идентификатором уже есть');
        }
        
        $this->models['Sprints']->add($id);
        
        $this->sendResponse(200, [
            'Id' => $id,
        ]);
    }
    
    public function startAction()
    {
        $this->headerAPI();
        
        $sprintId = Valid::postVariable('sprintId');
        
        $errors = [];
        
        if (empty($sprintId))
        {
            $errors[] = [ 'sprintId' => 'Укажите идентификатор спринта' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        if (!$this->models['Sprints']->find($sprintId))
        {
            $this->sendResponseGlobalError(400, 'Спринт с указанным идентификатором не найден');
        }
        
        $tasks = $this->models['Tasks']->getFromSprint($sprintId);
        $task_time = 0;
        
        foreach ($tasks as $task)
        {
            if ($task['estimation'] == '-')
            {
                $this->sendResponseGlobalError(400, 'В спринте есть неоцененные задачи');
            }
            
            $task_time += Utils::fmtTimeToInt($task['estimation']);
        }
        
        $this->models['Sprints']->setWork($sprintId);
        
        if ($this->models['Sprints']->checkNoStart())
        {
            $this->sendResponseGlobalError(400, 'Есть уже запущенный спринт');
        }
        
        $current_time = time();
        $date = explode('-', $sprintId);
        $year = $date[0];
        $week = $date[1];
        
        if ($week < 10)
        {
            $week = '0' . $week;
        }
        
        $date = '20' . $year . 'W' . $week;
        
        $sprint_time = strtotime($date);
        
        if ($sprint_time - 604800 > $current_time)
        {
            $this->sendResponseGlobalError(400, 'Попытка запустить спринт за 7 дней или больше до даты его начала');
        }
        
        if ($task_time > 144000)
        {
            $this->sendResponseGlobalError(400, 'Суммарная оценка в задаче > 40 часов');
        }
        
        $this->models['Sprints']->setStart($sprintId);
        
        $this->sendResponse(200);
    }
    
    public function addTaskAction()
    {
        $this->headerAPI();
        
        $sprintId = Valid::postVariable('sprintId');
        $taskId = Valid::postVariable('taskId');
        
        $errors = [];
        
        if (empty($sprintId))
        {
            $errors[] = [ 'sprintId' => 'Укажите идентификатор спринта' ];
        }
        
        if (empty($taskId))
        {
            $errors[] = [ 'taskId' => 'Укажите идентификатор задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        if (!$this->models['Sprints']->find($sprintId))
        {
            $this->sendResponseGlobalError(400, 'Спринт с указанным идентификатором не найден');
        }
        
        $taskId = str_replace('TASK-', '', $taskId);
        
        if (!$this->models['Tasks']->find($taskId))
        {
            $this->sendResponseGlobalError(400, 'Задача с указанным идентификатором не найдена');
        }
        
        $this->models['Tasks']->setSprint($sprintId, $taskId);
        
        $this->sendResponse(200);
    }
}