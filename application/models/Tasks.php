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
    
    public function find($id)
    {
        return $this->db->column('SELECT id FROM tasks WHERE id = :id', [
            'id' => $id,
        ]) != null;
    }
    
    public function setEstimation($id, $estimation)
    {
        $this->db->query('UPDATE tasks SET estimation = :estimation WHERE id = :id', [
            'id' => $id,
            'estimation' => $estimation,
        ]);
    }
}