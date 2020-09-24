<?php

namespace application\models;

use application\core\Model;

class Sprints extends Model
{
    /**
     * Поиск спринта
     * 
     * @param $id (string) - идентификатор спринта
     * @return true (bool) - найден, false (bool) - не найден
     */
    public function find($id)
    {
        return $this->db->column('SELECT id FROM sprints WHERE id = :id', [
            'id' => $id,
        ]) != null;
    }
    
    /**
     * Добавление спринта
     * 
     * @param $id (string) - идентификатор спринта
     */
    public function add($id)
    {
        $this->db->query('INSERT INTO sprints (id) VALUES(:id)', compact('id'));
    }
    
    /**
     * Проверка, есть ли уже запущенные спринты
     * 
     * @return true (bool) - есть, false (bool) - нет
     */
    public function checkNoStart()
    {
        return $this->db->column('SELECT is_start FROM sprints WHERE is_start = 1') != null;
    }
    
    /**
     * Установка статуса "В работе" для спринта
     * 
     * @param $id (string) - идентификатор спринта
     */
    public function setWork($id)
    {
        $this->db->query('UPDATE sprints SET is_work = 1 WHERE id = :id', [
            'id' => $id,
        ]);
    }
    
    /**
     * Установка статуса "Старт" для спринта
     * 
     * @param $id (string) - идентификатор спринта
     */
    public function setStart($id)
    {
        $this->db->query('UPDATE sprints SET is_start = 1 WHERE id = :id', [
            'id' => $id,
        ]);
    }
    
    /**
     * Установка статуса "Закрыт", а также убирает статус "Старт"
     * 
     * @param $id (string) - идентификатор спринта
     */
    public function setClose($id)
    {
        $this->db->query('UPDATE sprints SET is_start = 0, is_close = 1 WHERE id = :id', [
            'id' => $id,
        ]);
    }
}