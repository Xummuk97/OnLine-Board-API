<?php

namespace application\models;

use application\core\Model;

class Tasks extends Model
{
    /**
     * Добавление задачи
     * 
     * @param $title (string) - Заголовок задачи
     * @param $description (string) - Описание задачи
     * @return Идентификатор добавленной задачи (int)
     */
    public function add($title, $description)
    {
        $this->db->query('INSERT INTO tasks (title, description) VALUES(:title, :description)', [
            'title' => $title,
            'description' => $description,
        ]);
        
        return $this->db->getInsertId();
    }
    
    /**
     * Поиск задачи
     * 
     * @param $id (int) - идентификатор задачи
     * @return true (bool) - найдена, false (bool) - не найдена
     */
    public function find($id)
    {
        return $this->db->column('SELECT id FROM tasks WHERE id = :id', [
            'id' => $id,
        ]) != null;
    }
    
    /**
     * Установка оценки для задачи
     * 
     * @param $id (int) - идентификатор задачи
     * @param $estimation (string) - оценка задачи
     */
    public function setEstimation($id, $estimation)
    {
        $this->db->query('UPDATE tasks SET estimation = :estimation WHERE id = :id', [
            'id' => $id,
            'estimation' => $estimation,
        ]);
    }
    
    /**
     * Установка спринта в задачу
     * 
     * @param $sprintId (string) - идентификатр спринта
     * @param $taskId (int) - идентификатор задачи
     */
    public function setSprint($sprintId, $taskId)
    {
        $this->db->query('UPDATE tasks SET sprint_id = :sprintId WHERE id = :taskId', [
            'sprintId' => $sprintId,
            'taskId' => $taskId,
        ]);
    }
    
    /**
     * Установка статуса "Закрыт" для задачи
     * 
     * @param $taskId (int) - идентификатор задачи
     */
    public function setClose($taskId)
    {
        $this->db->query('UPDATE tasks SET is_close = 1 WHERE id = :taskId', [
            'taskId' => $taskId,
        ]);
    }
    
    /**
     * Получение всех задач спринта
     * 
     * @param $sprintId (string) - идентификатор спринта
     * @return Все задачи (array)
     */
    public function getFromSprint($sprintId)
    {
        return $this->db->row('SELECT * FROM tasks WHERE sprint_id = :sprintId', [
            'sprintId' => $sprintId,
        ]);
    }
    
    /**
     * Проверка всех задач сприна на то, что они закрыты
     * 
     * @param $sprintId (string) - идентификатор спринта
     * @return true (bool) - не все задачи закрыты, false (bool) - все задачи закрыты
     */
    public function checkAllCloseFromSprint($sprintId)
    {
        return $this->db->row('SELECT is_close, sprint_id FROM tasks WHERE sprint_id = :sprintId AND is_close = 0', [
            'sprintId' => $sprintId,
        ]) != null;
    }
}