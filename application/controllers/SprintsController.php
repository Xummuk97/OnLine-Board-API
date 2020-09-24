<?php

namespace application\controllers;

use application\core\Controller;
use application\libs\Valid;
use application\libs\Utils;

class SprintsController extends Controller 
{
    /**
     * Создание контроллера
     * 
     * Загружает модели
     */
    public function onCreate()
    {
        $this->loadModels([ 'Tasks', 'Sprints' ]);
    }
    
    /**
     * Добавление спринта
     * 
     * @param Week (int) - неделя
     * @param Year (int) - год
     * 
     * @response Id (string) - идентификатор добавленного спринта
     */
    public function addAction()
    {
        # Устанавливаем страницу в JSON формат
        $this->headerAPI();
        
        # Получаем данные из POST
        $week = Valid::postVariable('Week');
        $year = Valid::postVariable('Year');
        
        # Проводим валидацию данных
        $errors = [];
        
        if (empty($week))
        {
            $errors[] = [ 'Week' => 'Укажите неделю спринта' ];
        }
        
        if (empty($year))
        {
            $errors[] = [ 'Year' => 'Укажите год спринта' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        # Удаляем первые 2 цифры у года
        $id = substr($year, 2) . '-' . $week;
        
        # Ищем спринт с введённым идентификатором
        if ($this->models['Sprints']->find($id))
        {
            $this->sendResponseGlobalError(400, 'Спринт с указанным идентификатором уже есть');
        }
        
        # Добавляем спринт
        $this->models['Sprints']->add($id);
        
        # Отправляем ответ
        $this->sendResponse(200, [
            'Id' => $id,
        ]);
    }
    
    /**
     * Запуск спринта
     * 
     * @param sprintId (string) - Идентификатор спринта
     */
    public function startAction()
    {
        # Устанавливаем страницу в JSON формат
        $this->headerAPI();
        
        # Получаем данные из POST
        $sprintId = Valid::postVariable('sprintId');
        
        # Проводим валидацию данных
        $errors = [];
        
        if (empty($sprintId))
        {
            $errors[] = [ 'sprintId' => 'Укажите идентификатор спринта' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        # Ищем спринт с введённым идентификатором
        if (!$this->models['Sprints']->find($sprintId))
        {
            $this->sendResponseGlobalError(400, 'Спринт с указанным идентификатором не найден');
        }
        
        # Обработка задач спринта
        $tasks = $this->models['Tasks']->getFromSprint($sprintId); # Получаем все задачи спринта
        $task_time = 0; # Общая сумма оценок задач в спринте
        
        # Цикл по всем задачам
        foreach ($tasks as $task)
        {
            # Если оценка задачи не указана - ошибка
            if ($task['estimation'] == '-')
            {
                $this->sendResponseGlobalError(400, 'В спринте есть неоцененные задачи');
            }
            
            # Получаем сумму оценок задач в спринте
            $task_time += Utils::fmtTimeToInt($task['estimation']);
        }
        
        # Все оценки для задач в спринте указаны - значит он в работе
        $this->models['Sprints']->setWork($sprintId);
        
        # Есть ли уже запущенные спринты
        if ($this->models['Sprints']->checkNoStart())
        {
            $this->sendResponseGlobalError(400, 'Есть уже запущенный спринт');
        }
        
        # Проверяем, произведена ли попытка запустить спринт за 7 дней или больше до его начала
        $current_time = time(); # Получаем текущую дату
        $date = explode('-', $sprintId); # Парсим идентификатор спринта
        $year = $date[0];                # Получаем неделю и год и помещаем их в переменные
        $week = $date[1];
        
        # Если значение недели менее 10-ти, добавлять в начало символ '0'
        if ($week < 10)
        {
            $week = '0' . $week;
        }
        
        # Конкатенация года и недели с разделением 'W' - для преобразования полученной даты в unixtime
        $date = '20' . $year . 'W' . $week;
        
        # Преобразование полученной даты в unixtime
        $sprint_time = strtotime($date);
        
        # Вычитаем из полученной даты неделю (60*60*24*7) и сравниваем, больше ли она текущей даты
        if ($sprint_time - 604800 > $current_time)
        {
            $this->sendResponseGlobalError(400, 'Попытка запустить спринт за 7 дней или больше до даты его начала');
        }
        
        # Проверяем суммарную оценку задач с 40 часов (60*60*40)
        if ($task_time > 144000)
        {
            $this->sendResponseGlobalError(400, 'Суммарная оценка в задаче > 40 часов');
        }
        
        # Запускаем спринт
        $this->models['Sprints']->setStart($sprintId);
        
        # Отправляем ответ OK
        $this->sendResponse(200);
    }
    
    /**
     * Добавление задачи в спринт
     * 
     * @param sprintId (string) - идентификатор спинта
     * @param taskId (string) - идентификатор задачи
     */
    public function addTaskAction()
    {
        # Устанавливаем страницу в JSON формат
        $this->headerAPI();
        
        # Получаем данные из POST
        $sprintId = Valid::postVariable('sprintId');
        $taskId = Valid::postVariable('taskId');
        
        # Проводим валидацию данных
        $errors = [];
        
        if (empty($sprintId))
        {
            $errors[] = [ 'sprintId' => 'Укажите идентификатор спринта' ];
        }
        
        if (empty($taskId))
        {
            $errors[] = [ 'taskId' => 'Укажите идентификатор задачи' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        # Ищем спринт с указанным идентификатором
        if (!$this->models['Sprints']->find($sprintId))
        {
            $this->sendResponseGlobalError(400, 'Спринт с указанным идентификатором не найден');
        }
        
        # Удаляем из идентификатора задачи 'TASK-'
        $taskId = str_replace('TASK-', '', $taskId);
        
        # Ищем задачу с указанным идентификатором
        if (!$this->models['Tasks']->find($taskId))
        {
            $this->sendResponseGlobalError(400, 'Задача с указанным идентификатором не найдена');
        }
        
        # Установка задачи в спринт
        $this->models['Tasks']->setSprint($sprintId, $taskId);
        
        # Отправляем ответ OK
        $this->sendResponse(200);
    }
    
    /**
     * Закрытие спринта
     * 
     * @param sprintId (string) - Идентификатор спринта
     */
    public function setCloseAction()
    {
        # Устанавливаем страницу в JSON формат
        $this->headerAPI();
        
        # Получаем данные из POST
        $sprintId = Valid::postVariable('sprintId');
        
        # Проводим валидацию данных
        $errors = [];
        
        if (empty($sprintId))
        {
            $errors[] = [ 'sprintId' => 'Укажите идентификатор спринта' ];
        }
        
        $this->sendResponseFieldErrors(400, $errors);
        
        # Ищем спринт по идентификатору
        if (!$this->models['Sprints']->find($sprintId))
        {
            $this->sendResponseGlobalError(400, 'Спринт с указанным идентификатором не найден');
        }
        
        # Проверяем есть ли не закрытые задачи в спринте
        if ($this->models['Tasks']->checkAllCloseFromSprint($sprintId))
        {
            $this->sendResponseGlobalError(400, 'В спринте есть незакрытые задачи');
        }
        
        # Закрываем спринт
        $this->models['Sprints']->setClose($sprintId);
        
        # Отправляем ответ OK
        $this->sendResponse(200);
    }
}