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
        ];
    }

}
