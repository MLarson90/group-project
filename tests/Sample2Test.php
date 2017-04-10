<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

$DB = new PDO('mysql:host=localhost:8889;dbname=test_test', "root", "root");
require_once "src/Sample.php";
require_once "src/Sample2.php";
class Sample2Test extends PHPUnit_Framework_TestCase
{
  protected function tearDown()
  {
    Sample2::deleteAll();
    Sample::deleteAll();
  }
  function test_Save()
  {
    $newClass = new Sample2 ("max", "blue");
    $newClass->save();
    $result = Sample2::getAll();
    $this->assertEquals($result, [$newClass]);
  }
  function test_deleteAll()
  {
    $newClass = new Sample2 ("max","blue");
    $newClass->save();
    Sample2::deleteAll();
    $result = Sample2::getAll();
    $this->assertEquals($result, []);
  }
  function test_getAll()
  {
    $newClass = new Sample2 ('max', 'blue');
    $newClass2 = new Sample2 ('jack', "black");
    $newClass->save();
    $newClass2->save();
    $result = Sample2::getAll();
    $this->assertEquals($result, [$newClass, $newClass2] );
  }
}






?>
