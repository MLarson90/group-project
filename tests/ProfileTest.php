<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost;dbname=appdata_test', "root", "root");
require_once "src/Profile.php";

class ProfileTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    Group::deleteAll();
    Task::deleteAll();
    User::deleteAll();
    Profile::deleteAll();
  }

  function test_save(){
    $first_name = "Xing";
    $last_name = "Li";
    $bio = "Hello";
    $picture = "picture";
    $profile = new Profile($first_name, $last_name, $bio, $picture);
    $result = $profile->save();
    $this->assertTrue($result, "Fail");
  }


}






?>
