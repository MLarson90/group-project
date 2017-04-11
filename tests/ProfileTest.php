<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

<<<<<<< HEAD
$DB = new PDO('mysql:host=localhost:8889;dbname=appdata_test', "root", "root");
=======
$DB = new PDO('mysql:host=localhost;dbname=appdata_test', "root", "root");
>>>>>>> e82a28d11c744a88a1eb48f1228a0d7b250632c0
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
<<<<<<< HEAD
    $profile = new Profile($first_name, $last_name, $picture, $bio );
    $result = $profile->save($first_name, $last_name, $picture, $bio);
=======
    $profile = new Profile($first_name, $last_name, $bio, $picture);
    $result = $profile->save();
>>>>>>> e82a28d11c744a88a1eb48f1228a0d7b250632c0
    $this->assertTrue($result, "Fail");
  }


}






?>
