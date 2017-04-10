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
}





 ?>
