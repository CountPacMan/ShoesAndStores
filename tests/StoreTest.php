<?php

  /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

  require_once "src/Store.php";

  $DB = new PDO('pgsql:host=localhost;dbname=shoes_test');

  class StoreTest extends PHPUnit_Framework_TestCase {

    protected function tearDown() {
      Store::deleteAll();
    }

    function test_getName() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);

      // Act
      $result = $test_store->getName();

      // Assert
      $this->assertEquals($name, $result);
    }

    function test_getId() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $id = 1;
      $test_store = new Store($name, $id);

      // Act
      $result = $test_store->getId();

      // Assert
      $this->assertEquals(1, $result);
    }

    function test_setId() {
      // Assert
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);

      // Act
      $test_store->setId(2);
      $result = $test_store->getId();

      // Assert
      $this->assertEquals(2, $result);
    }

    function test_save() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      // Act
      $result = Store::getAll();

      // Assert
      $this->assertEquals($test_store, $result[0]);
    }
  }
?>
