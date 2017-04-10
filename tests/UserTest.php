<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost:8889;dbname=appdata_test', "root", "root");
require_once "src/User.php";
class UserTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    User::deleteAll();
  }
  function test_Save()
  {
    $newUser = new User ("sample@gmail.com", "password");
    $newUser->save();
    $result = User::getAll();
    $this->assertEquals($result, [$newUser]);
  }
  function test_deleteAll()
  {
    $newUser = new User ("sample@gmail.com","password");
    $newUser->save();
    User::deleteAll();
    $result = User::getAll();
    $this->assertEquals($result, []);
  }
  function test_getAll()
  {
    $newUser = new User ('sample@gmail.com', 'password');
    $newUser2 = new User ('guy@gmail.com', "admin");
    $newUser->save();
    $newUser2->save();
    $result = User::getAll();
    $this->assertEquals($result, [$newUser, $newUser2] );
  }
   function test_findUserbyId()
  {
    $newUser = new User ('sample@gmail.com', 'password');
    $newUser2 = new User ('samdfdle@gmail.com', 'pasdfdsword');
    $newUser->save();
    $newUser2->save();
    $test = $newUser->getId();
    $result = User::findUserbyId($test);
    $this->assertEquals($newUser, $result);
  }
  function test_findByUserName()
  {
    $newUser = new User ('sample@gmail.com', 'password');
    $newUser2 = new User ('samdfdle@gmail.com', 'pasdfdsword');
    $newUser->save();
    $newUser2->save();
    $test = $newUser->getUserName();
    $result = User::findByUserName($test);
    $this->assertEquals($newUser, $result);
  }
  function test_updateUserName()
  {
    $newUser = new User ('sample@gmail.com', 'password');
    $newUser->save();
    $newUser->updateUserName("john@gmail.com");
    $result = $newUser->getUserName();
    $this->assertEquals("john@gmail.com", $result);
  }
  function test_updateUserPassword()
  {
    $newUser = new User ('sample@gmail.com', 'password');
    $newUser->save();
    $newUser->updateUserPassword("admin");
    $result = $newUser->getPassword();
    $this->assertEquals("admin", $result);
  }
  function test_delete()
  {
    $newUser = new User ('sample@gmail.com', 'password');
    $newUser2 = new User ('samdfdle@gmail.com', 'pasdfdsword');
    $newUser->save();
    $newUser2->save();
    $newUser->delete();
    $result = User::getAll();
    $this->assertEquals($result, [$newUser2]);
  }

  function test_addTask()
  {
    $newTask = new User ("sample@gmail.com", "password");
    $newTask->save();
    $result = User::getAll();
    $this->assertEquals($result, [$newTask]);
  }
  function test_getTask()
  {
    $getTaskTest = new User("clean gutters", "password");
    $getTaskTest->save();
    $getTaskTest2 = new User("take out trash", "password");
    $getTaskTest2->save();
    $result=$getTaskTest->getTask();
    $this->assertEquals([$getTaskTest, $getTaskTest2], $result);
  }


}






?>
