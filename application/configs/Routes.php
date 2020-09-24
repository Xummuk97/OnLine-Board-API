<?php

namespace application\configs;

class Routes
{

    public static function getRoutes()
    {
        return [
            'api/tasks' => [
                'controller' => 'tasks',
                'action' => 'add'
            ],
            
            'api/estimate/task' => [
                'controller' => 'tasks',
                'action' => 'setEstimation'
            ],
            
            'api/sprints' => [
                'controller' => 'sprints',
                'action' => 'add'
            ],
            
            'api/sprints/add-task' => [
                'controller' => 'sprints',
                'action' => 'addTask'
            ],
            
            'api/sprints/start' => [
                'controller' => 'sprints',
                'action' => 'start'
            ],
            
            'api/tasks/close' => [
                'controller' => 'tasks',
                'action' => 'setClose'
            ],
            
            'api/sprints/close' => [
                'controller' => 'sprints',
                'action' => 'setClose'
            ],
        ];
    }

}
