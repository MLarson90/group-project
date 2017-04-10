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
}






?>
