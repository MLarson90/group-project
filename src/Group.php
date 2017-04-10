<?php
  class Group
  {
      private $group_name;
      private $public;
      private $id;

      function __construct($group_name, $public, $id=null)
      {
          $this->id = $id;
          $this->group_name = $group_name;
          $this->public = $public;
      }
      function getId()
      {
          return $this->id;
      }
      function setGroupName($new_group_name)
      {
          $this->group_name = $new_group_name;
      }
      function getGroupName()
      {
          return $this->group_name;
      }
      function setPublic($new_public)
      {
          $this->public = (int) $new_public;
      }
      function getPublic()
      {
          return $this->public;
      }
      function save()
      {
          $executed = $GLOBALS['DB']->exec("INSERT INTO task_forces (group_name, public) VALUES ('{$this->getGroupName()}', {$this->getPublic()});");
          if ($executed)
          {
              $this->id = $GLOBALS['DB']->lastInsertId();
              return true;
          } else {
              return false;
          }
      }
      static function getAll()
      {
          $groups = array();
          $returned_groups = $GLOBALS['DB']->query("SELECT * FROM task_forces;");
          foreach ($returned_groups as $group)
          {
              $id = $group['id'];
              $group_name = $group['group_name'];
              $public = $group['public'];
              $newgroup = new Group($group_name, $public, $id);
              array_push($groups, $newgroup);
          }
          return $groups;
      }
      static function deleteAll()
      {
          $executed = $GLOBALS['DB']->exec("DELETE FROM task_forces;");
          if (!$executed)
          {
              return false;
          } else {
              return true;
          }
      }

      static function find($id)
      {
          $executed = $GLOBALS['DB']->prepare("SELECT * FROM task_forces WHERE id = :id;");
          $executed->bindParam(':id', $id, PDO::PARAM_INT);
          $executed->execute();
          $result = $executed->fetch(PDO::FETCH_ASSOC);
          $group = new Group($result['group_name'], $result['public'], $result['id']);
          return $group;
      }

      static function findByName($name)
      {
          $executed = $GLOBALS['DB']->prepare("SELECT * FROM task_forces WHERE group_name = :name;");
          $executed->bindParam(':name', $name, PDO::PARAM_STR);
          $executed->execute();
          $result = $executed->fetch(PDO::FETCH_ASSOC);
          $group = new Group($result['group_name'], $result['public'], $result['id']);
          return $group;
      }
      function updateGroupName($new_group_name)
      {
          $executed = $GLOBALS['DB']->exec("UPDATE task_forces SET group_name = '{$new_group_name}' WHERE id = {$this->getId()};");
          if ($executed)
          {
            $this->setGroupName($new_group_name);
            return true;
          } else {
            return false;
          }
      }
      function delete()
      {
          $executed = $GLOBALS['DB']->exec("DELETE FROM task_forces WHERE id = {$this->getId()};");
          if (!$executed)
          {
            return false;
          } else {
            return true;
          }
      }

  }




?>