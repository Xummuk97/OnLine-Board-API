<?php

namespace application\models;

use application\core\Model;

class Sprints extends Model
{
    public function find($id)
    {
        return $this->db->column('SELECT id FROM sprints WHERE id = :id', [
            'id' => $id,
        ]) != null;
    }
    
    public function add($id)
    {
        $this->db->query('INSERT INTO sprints (id) VALUES(:id)', compact('id'));
    }
}