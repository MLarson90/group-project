<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost:8889;dbname=appdata_test', "root", "root");
require_once "src/Task.php";
class TaskTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    Task::deleteAll();
  }

    function test_save()
    {
      $newTask = new Task ("shopping", "get groceries", "2017-04-10", "2017-06-10");
      $newTask->save();
      $result = Task::getAll();
      $this->assertEquals($result, [$newTask]);
    }

    function test_updateAll()
    {
      $test_task = new Task("shopping", "get groceries", "2017-04-10", "2017-06-10");
      $test_task->save();

      $test_task->updateAll("plan vacation", "list travel details", "2017-05-10", "2017-05-15");

      $this->assertEquals("plan vacation", $test_task->getName());
    }

    function test_deleteAll()
    {
      $newTask = new Task ("shopping", "get groceries", "2017-04-10", "2017-06-10");
      $newTask->save();
      Task::deleteAll();
      $result = Task::getAll();
      $this->assertEquals($result, []);
    }

    function test_getAll()
    {
      $newTask = new Task ("shopping", "get groceries", "2017-04-10", "2017-06-10");
      $newTask2 = new Task ("plan vacation", "list travel details", "2017-05-10", "2017-05-15");
      $newTask->save();
      $newTask2->save();
      $result = Task::getAll();
      $this->assertEquals($result, [$newTask, $newTask2] );
    }
  }






?>
