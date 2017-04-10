<?php
  class Profile
  {
      private $first_name;
      private $last_name;
      private $picture;
      private $bio;
      private $id;
      private $date;

      function __construct($first_name, $last_name, $picture, $bio, $id=null, $date=null)
      {
          $this->first_name = $first_name;
          $this->last_name = $last_name;
          $this->picture = $picture;
          $this->bio = $bio;
          $this->id = $id;
          $this->date = $date;
      }
      function getDate()
      {
        return $this->date;
      }
      function getId()
      {
          return $this->id;
      }
      function setFirstName($new_first_name)
      {
          $this->first_name = $new_first_name;
      }
      function getFirstName()
      {
          return $this->first_name;
      }
      function setLastName($new_last_name)
      {
          $this->last_name = $new_last_name;
      }
      function getLastName()
      {
          return $this->last_name;
      }
      function setPicture($new_picture)
      {
          $this->picture = $new_picture;
      }
      function getPicture()
      {
          return $this->picture;
      }
      function setBio($new_bio)
      {
          $this->bio = $new_bio;
      }
      function getBio()
      {
          return $this->bio;
      }
      function save()
      {
          $executed = $GLOBALS['DB']->exec("INSERT INTO profiles (first_name, last_name, picture, join_date, bio) VALUES ('{$this->getFirstName()}', '{$this->getLastName()}', '{$this->getPicture()}', NOW(), '{$this->getBio()}');");
          if ($executed)
          {
              $this->id = $GLOBALS['DB']->lastInsertId();
              return true;
          } else {
              return false;
          }
      }

      static function deleteAll()
      {
          $executed = $GLOBALS['DB']->exec("DELETE FROM profiles;");
          if (!$executed)
          {
              return false;
          } else {
              return true;
          }
      }

      static function findProfile($id)
      {
          $executed = $GLOBALS['DB']->prepare("SELECT * FROM profiles WHERE id = :id;");
          $executed->bindParam(':id', $id, PDO::PARAM_INT);
          $executed->execute();
          $result = $executed->fetch(PDO::FETCH_ASSOC);
          $profile = new Profile ($result['first_name'], $result['last_name'], $result['picture'], $result['bio'], $result['id'], $result['join_date']);
          return $profile;
      }

      static function findByName($name)
      {
          $executed = $GLOBALS['DB']->prepare("SELECT * FROM profiles WHERE first_name = :name;");
          $executed->bindParam(':name', $name, PDO::PARAM_STR);
          $executed->execute();
          $result = $executed->fetch(PDO::FETCH_ASSOC);
          $profile = new Profile ($result['first_name'], $result['last_name'], $result['picture'], $result['bio'], $result['id'], $result['join_date']);
          return $profile;
      }
      function updateFirstName($new_first_name, $new_last_name, $new_pic, $new_bio)
      {
          $executed = $GLOBALS['DB']->prepare("UPDATE profiles SET first_name = :first_name, last_name = :last_name, picture = :picture, bio = :bio WHERE id = {$this->getId()};");
          $executed->bindParam(':first_name', $new_first_name, PDO::PARAM_STR);
          $executed->bindParam(':last_name', $new_last_name, PDO::PARAM_STR);
          $executed->bindParam(':picture', $new_pic, PDO::PARAM_STR);
          $executed->bindParam(':bio', $new_bio, PDO::PARAM_STR);
          $executed->execute();
          if ($executed)
          {
            $this->setFirstName($new_first_name);
            return true;
          } else {
            return false;
          }
      }
      function delete()
      {
          $executed = $GLOBALS['DB']->exec("DELETE FROM profiles WHERE id = {$this->getId()};");
          if (!$executed)
          {
          $executed = $GLOBALS['DB']->exec("DELETE FROM users_profiles WHERE profile_id = {$this->getId()};");
          if (!$executed){
              return false;
          } else {
              return true;
          }
          }
      }

    }




?>
