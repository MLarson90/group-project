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

      function setAssignTime($new_assign_time)
      {
         $this->assign_time = $new_assign_time;
      }

      function getDueTime()
      {
        return $this->due_time;
      }

      function setDueTime($new_due_time)
      {
         $this->due_time = $new_due_time;
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

      function updateAll($new_name, $new_description, $new_assign_time, $new_due_time)
      {
        $executed = $GLOBALS['DB']->prepare("UPDATE tasks SET task_name = :task_name, task_description = :task_description, assign_time = :assign_time, due_time = :due_time WHERE id={$this->getId()}");
        $executed->bindParam(':task_name', $new_name, PDO::PARAM_STR);
        $executed->bindParam(':task_description', $new_description, PDO::PARAM_STR);
        $executed->bindParam(':assign_time', $new_assign_time, PDO::PARAM_STR);
        $executed->bindParam(':due_time', $new_due_time, PDO::PARAM_STR);
        $executed->execute();
        if ($executed){
          $this->setName($new_name);
          $this->setDescription($new_description);
          $this->setAssignTime($new_assign_time);
          $this->setDueTime($new_due_time);
          return true;
        } else {
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

      function addUser($user)
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO users_tasks (user_id, task_id) VALUES ({$user->getId()}, {$this->getId()});");
        if ($executed) {
          return true;
        } else {
          return false;
        }
      }

      function getUsers()
      {
        $returned_users = $GLOBALS['DB']->query("SELECT users.* FROM tasks JOIN users_tasks ON (tasks.id = users_tasks.task_id) JOIN users ON (users_tasks.user_id = users.id) WHERE tasks.id = {$this->getId()};");

        $users = array();
        foreach ($returned_users as $user) {
          $id = $user['id'];
          $user_name = $user['username'];
          $password = $user['password'];
          $new_user = new User($user_name, $password, $id);
          array_push($users, $new_user);
        }
        return $users;
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

      function delete()
      {
        $executed = $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()};");
        if (!$executed) {
          return false;
        }
        $executed = $GLOBALS['DB']->exec("DELETE FROM users_tasks WHERE task_id = {$this->getId()};");
      }
      function addGroupToTask($group)
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO tasks_groups (task_id, group_id) VALUES ({$this->getId()}, {$group->getId()});");
        if ($executed){
          return true;
        }else {
          return false;
        }
      }
      function getGroupFromTask()
      {
        $returned_groups = $GLOBALS['DB']->query("SELECT task_forces.* FROM tasks JOIN tasks_groups ON (tasks.id = tasks_groups.task_id) JOIN task_forces ON (tasks_groups.group_id = task_forces.id) WHERE tasks.id = {$this->getId()};");
        foreach($returned_groups as $group){
          $newGroup = new Group($group['group_name'], $group['public'], $group['id']);
          return $newGroup;
        }
      }
    }

 ?>
