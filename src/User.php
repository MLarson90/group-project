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
      // function getUserGroup()
      // {
      //   $returned_user_ids = $GLOBALS['DB']->query("SELECT users.id FROM users_groups JOIN users on (users.id = users_groups.users) WHERE users.id = {$this->id};");
      //   $users = array();
      //   foreach($returned_user_ids as $id) {
      //       $search_id = $id['user_id'];
      //       array_push($users, User::findUserbyId($search_id));
      // }

      function getTask()
      {
        $returned_task = $GLOBALS['DB']->query("SELECT  FROM users_groups JOIN users on (users.id = users_groups.users) WHERE users.id = {$this->id};");
        $users = array();
        foreach($returned_user_ids as $id) {
            $search_id = $id['user_id'];
            array_push($users, User::findUserbyId($search_id));
      }



    }





 ?>
