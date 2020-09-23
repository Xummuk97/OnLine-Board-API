<?php

namespace application\models;

use application\core\Model;

class Tasks extends Model
{
    public function add($title, $description)
    {
        $this->db->query('INSERT INTO tasks (title, description) VALUES(:title, :description)', [
            'title' => $title,
            'description' => $description,
        ]);
        
        return $this->db->getInsertId();
    }
}