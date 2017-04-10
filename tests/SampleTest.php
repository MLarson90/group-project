<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost:8889;dbname=test_test', "root", "root");
require_once "src/Sample.php";
require_once "src/Sample2.php";
class SampleTest extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    Sample::deleteAll();
    Sample2::deleteAll();

  }
    function test_Save()
    {
      $newClass = new Sample ("max", "blue");
      $newClass->save();
      $result = Sample::getAll();
      $this->assertEquals($result, [$newClass]);
    }

    function test_deleteAll()
    {
      $newClass = new Sample ("max","blue");
      $newClass->save();
      Sample::deleteAll();
      $result = Sample::getAll();
      $this->assertEquals($result, []);
    }
    function test_getAll()
    {
      $newClass = new Sample ('max', 'blue');
      $newClass2 = new Sample ('jack', "black");
      $newClass->save();
      $newClass2->save();
      $result = Sample::getAll();
      $this->assertEquals($result, [$newClass, $newClass2] );
    }
  }






?>
