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
        ];
    }

}
