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
    
    public function checkNoStart()
    {
        return $this->db->column('SELECT is_start FROM sprints WHERE is_start = 1') != null;
    }
    
    public function setWork($id)
    {
        $this->db->query('UPDATE sprints SET is_work = 1 WHERE id = :id', [
            'id' => $id,
        ]);
    }
    
    public function setStart($id)
    {
        $this->db->query('UPDATE sprints SET is_start = 1 WHERE id = :id', [
            'id' => $id,
        ]);
    }
    
    public function setClose($id)
    {
        $this->db->query('UPDATE sprints SET is_start = 0, is_close = 1 WHERE id = :id', [
            'id' => $id,
        ]);
    }
}