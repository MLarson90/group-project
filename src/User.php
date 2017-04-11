<?php
  class User
{
    private $username;
    private $password;
    private $id;


    function __construct($username, $password, $id=null)
      {
        $this->username =$username;
        $this->password =$password;
        $this->id = $id;
      }
      function getUserName()
      {
        return $this->username;
      }
      function setUserName($new_username)
      {
         $this->username = $new_username;
      }
      function getPassword()
      {
        return $this->password;
      }
      function setPassword($new_password)
      {
         $this->password = $new_password;
      }
      function getId()
      {
        return $this->id;
      }
      function save()
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO users (username, password) VALUES ('{$this->getUserName()}', '{$this->getPassword()}'); ");
          if($executed){
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          }else{
            return false;
          }
      }
      static function getAll()
      {
        $users = array();
        $returned_users = $GLOBALS['DB']->query('SELECT * FROM users;');
        foreach($returned_users as $user){
          $newUser = new User($user['username'], $user["password"],  $user["id"]);
          array_push($users, $newUser);
        }
          return $users;
      }
      static function deleteAll()
      {
        $deleteAll = $GLOBALS['DB']->exec("DELETE FROM users;");
        if ($deleteAll)
        {
          return true;
        }else {
          return false;
        }
      }
    static function findUserbyId($id)
      {
        $returned_user= $GLOBALS['DB']->prepare("SELECT * FROM users WHERE id=:id;");
        $returned_user->bindParam(':id', $id, PDO::PARAM_STR);
        $returned_user->execute();
        foreach($returned_user as $user){
        $newUser = new User($user['username'], $user['password'], $user['id']);
        return $newUser;
      }
      }
      static function findByUserName($search_name)
      {
        $returned_user = $GLOBALS['DB']->prepare("SELECT * FROM users WHERE username = :name");
        $returned_user->bindParam(':name', $search_name, PDO::PARAM_STR);
        $returned_user->execute();
        foreach($returned_user as $user){
          $name = $user['username'];
          if($name == $search_name){
            $newUser = new User($user['username'], $user['password'], $user['id']);
            return $newUser;
          }
        }
      }
      function updateUserName($new_name)
      {
        $executed = $GLOBALS['DB']->exec("UPDATE users SET username = '{$new_name}' WHERE id = {$this->getId()};");
        if($executed){
          $this->setUserName($new_name);
          return true;
        }else{
          return false;
        }
      }
      function updateUserPassword($new_pass)
      {
        $executed = $GLOBALS['DB']->exec("UPDATE users SET password = '{$new_pass}' WHERE id = {$this->getId()};");
        if($executed){
          $this->setPassword($new_pass);
          return true;
        }else{
          return false;
        }
      }
      function delete()
      {
        $executed = $GLOBALS['DB']->exec("DELETE FROM users WHERE id = {$this->getId()};");
        if(!$executed){
          return false;
        }
        $executed = $GLOBALS['DB']->exec("DELETE FROM users_groups WHERE user_id = {$this->getId()};");
        if(!$executed){
          return false;
        }
        $executed = $GLOBALS["DB"]->exec("DELETE FROM users_profiles WHERE user_id = {$this->getId()};");
        if(!$executed){
          return false;
        }
        $executed = $GLOBALS['DB']->exec("DELETE FROM users_tasks WHERE user_id = {$this->getId()};");
        if (!$executed){
          return false;
        }else{
          return true;
        }
      }

      function addTask($task)
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO users_tasks (user_id, task_id) VALUES ({$this->getId()}, {$task->getId()});");
        if($executed){
          return true;
        }else{
          return false;
        }
      }
      function getTask()
      {
        $returned_task = $GLOBALS['DB']->query("SELECT tasks.* FROM users JOIN users_tasks ON (users_tasks.user_id = users.id) JOIN tasks ON (tasks.id = users_tasks.task_id) WHERE users.id = {$this->getId()};");
        $all_task = array();
        foreach($returned_task as $task) {
            $each_task = new Task($task['task_name'], $task['task_description'], $task['assign_time'], $task['due_time'],$task['id']);
            array_push($all_task, $each_task);
          }
        return $all_task;
      }

    function addGroup($group_id)
    {
      $executed = $GLOBALS['DB']->exec("INSERT INTO users_groups (user_id, group_id) VALUES ({$this->getId()}, $group_id);");
      if($executed){
        return true;
      }else{
        return false;
      }
    }
    // function getGroup(){
    //   $executed = $GLOBALS['DB']->query("SELECT task_force.* FROM users JOIN users_task ON (users_task.user_id = users.id) JOIN task_force ON ()")
    // }

      function joinUserProfile($profile_id)
      {
          $executed = $GLOBALS['DB']->exec("INSERT INTO users_profiles (user_id, profile_id) VALUES ({$this->getId()}, $profile_id);");
          if ($executed)
          {
            return true;
          } else {
            return false;
          }
      }
      static function usernameArray()
      {
        $usernameArray = array();
        $executed = $GLOBALS['DB']->query("SELECT * FROM users;");
        $results = $executed->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $result){
          array_push($usernameArray, $result['username']);
        }
        return $usernameArray;
      }
      static function userpasswordArray()
      {
        $userpasswordArray = array();
        $executed = $GLOBALS['DB']->query("SELECT * FROM users;");
        $results = $executed->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $result){
          array_push($userpasswordArray, $result['password']);
        }
        return $userpasswordArray;
      }

      static function login($username, $password)
      {
        $check = $GLOBALS['DB']->prepare("SELECT * FROM users WHERE username = :username AND password = :password;");
        $check->bindParam(':username', $username, PDO::PARAM_STR);
        $check->bindParam(':password', $password, PDO::PARAM_STR);
        $check->execute();
        $result = $check->fetch(PDO::FETCH_ASSOC);
        $user = new User($result['username'], $result['password'], $result['id']);
        return $result['id'];
      }

  }





 ?>
