<?php
  class Task
{
    private $task_name;
    private $task_description;
    private $assign_time;
    private $due_time;
    private $id;

    function __construct($task_name, $task_description, $assign_time, $due_time, $id=null)
      {
        $this->task_name = $task_name;
        $this->task_description = $task_description;
        $this->assign_time = $assign_time;
        $this->due_time = $due_time;
        $this->id = $id;
      }
      function getName()
      {
        return $this->task_name;
      }

      function setName($new_name)
      {
         $this->task_name = $new_name;
      }

      function getDescription()
      {
        return $this->task_description;
      }

      function setDescription($new_description)
      {
         $this->task_description = $new_description;
      }

      function getAssignTime()
      {
        return $this->assign_time;
      }

      function setAssignTime()
      {
         $this->assign_time = $assign_time;
      }

      function getDueTime()
      {
        return $this->due_time;
      }

      function setDueTime()
      {
         $this->due_time = $due_time;
      }

      function getId()
      {
        return $this->id;
      }

      function save()
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO tasks (task_name, task_description, assign_time, due_time) VALUES ('{$this->getName()}', '{$this->getDescription()}', '{$this->getAssignTime()}', '{$this->getDueTime()}'); ");
          if($executed){
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          }else{
          return false;
      }
    }
    static function getAll()
      {
        $tasks = array();
        $returned_tasks = $GLOBALS['DB']->query('SELECT * FROM tasks;');
        foreach($returned_tasks as $task)
        {
          $newTask = new Task($task['task_name'], $task['task_description'],  $task['assign_time'], $task['due_time'], $task['id']);
          array_push($tasks, $newTask);
        }

        return $tasks;
      }
      static function deleteAll()
      {
        $deleteAll = $GLOBALS['DB']->exec("DELETE FROM tasks;");
        if ($deleteAll)
        {
          return true;
        }else {
          return false;
        }
      }
    }

 ?>
