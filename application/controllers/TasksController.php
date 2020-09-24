<?php

namespace application\controllers;

use application\core\Controller;
use application\libs\Valid;

class TasksController extends Controller 
{
    /**
     * Создание контроллера
     * 
     * Загружает модели
     */
    public function onCreate()
    {
        $this->loadModels([ 'Tasks' ]);
    }
    
    /**
     * Добавление задачи
     * 
     * @param title (string) - Заголовок задачи
     * @param description (string) - Описание задачи
     * 
     * @response id (string) - Идентификатор задачи
     */
    public function addAction()
    {
        # Устанавливаем страницу в JSON формат
        $this->headerAPI();
        
        # Получаем данные из POST
        $title = Valid::postVariable('title');
        $description = Valid::postVariable('description');
        
        # Проводим валидацию данных
        $errors = [];
        
        if (empty($title))
        {
            $errors[] = [ 'title' => 'Укажите заголовок задачи' ];
        }
        
        if (empty($description))
        {
            $errors[] = [ 'description' => 'Укажите описание задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        # Добавляем задачу
        # Получаем индекс добавленной задачи
        $insert_id = $this->models['Tasks']->add($title, $description);
        
        # Отправляем ответ
        $this->sendResponse(200, [
            'id' => 'TASK-' . $insert_id
        ]);
    }
    
    /**
     * Установка оценки задачи
     * 
     * @param id (string) - идентификатор задачи
     * @param estimation (string) - оценка задачи
     */
    public function setEstimationAction()
    {
        # Устанавливаем страницу в JSON формат
        $this->headerAPI();
        
        # Получаем данные из POST
        $id = Valid::postVariable('id');
        $estimation = Valid::postVariable('estimation');
        
        # Проводим валидацию данных
        $errors = [];
        
        if (empty($id))
        {
            $errors[] = [ 'id' => 'Укажите идентификатор задачи' ];
        }
        
        if (empty($estimation))
        {
            $errors[] = [ 'estimation' => 'Укажите оценку задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        # Удаляем из идентификатора задачи 'TASK-'
        str_replace('TASK-', '', $id);
        
        # Ищем задачу по идентификатору
        if (!$this->models['Tasks']->find($id))
        {
            $this->sendResponseGlobalError(400, 'Не найдена запись по указанному идентификатору задачи');
        }
        
        # Установка для задачи оценки
        $this->models['Tasks']->setEstimation($id, $estimation);
        
        # Ответ - OK
        $this->sendResponse(200);
    }
    
    /**
     * Закрытие задачи
     * 
     * @param taskId (string) - идентификатор задачи
     */
    public function setCloseAction()
    {
        # Устанавливаем страницу в JSON формат
        $this->headerAPI();
        
        # Получаем данные из POST
        $taskId = Valid::postVariable('taskId');
        
        # Проводим валидацию данных
        $errors = [];
        
        if (empty($taskId))
        {
            $errors[] = [ 'taskId' => 'Укажите идентификатор задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        # Удаляем из идентификатора задачи 'TASK-'
        $taskId = str_replace('TASK-', '', $taskId);
        
        # Ищем задачу по идентификатору
        if (!$this->models['Tasks']->find($taskId))
        {
            $this->sendResponseGlobalError(400, 'Не найдена запись по указанному идентификатору задачи');
        }
        
        # Закрываем задачу
        $this->models['Tasks']->setClose($taskId);
        
        # Ответ - OK
        $this->sendResponse(200);
        
    }
}