<?php
  class Sample2
{
    private $name;
    private $color;
    private $id;


    function __construct($name, $color, $id=null)
      {
        $this->name =$name;
        $this->color =$color;
        $this->id = $id;
      }
      function getName()
      {
        return $this->name;
      }
      function setName($new_name)
      {
         $this->name = $new_name;
      }
      function getColor()
      {
        return $this->color;
      }
      function setColor($new_color)
      {
         $this->color = $new_color;
      }
      function getId()
      {
        return $this->id;
      }
      function save()
      {
        $executed = $GLOBALS['DB']->exec("INSERT INTO class (name, color) VALUES ('{$this->getName()}', '{$this->getColor()}'); ");
          if($executed){
            $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          }else{
          return false;
          }
      }
    static function getAll()
      {
        $classes = array();
        $returned_classes = $GLOBALS['DB']->query('SELECT * FROM class;');
        foreach($returned_classes as $class){
          $newClass = new Sample2($class['name'], $class["color"],  $class["id"]);
          array_push($classes, $newClass);
        }
          return $classes;
      }
      static function deleteAll()
      {
        $deleteAll = $GLOBALS['DB']->exec("DELETE FROM class;");
        if ($deleteAll)
        {
          return true;
        }else {
          return false;
        }
      }
}





 ?>
