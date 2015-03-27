<?php

  /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

  require_once "src/Store.php";
  require_once "src/Brand.php";

  $DB = new PDO('pgsql:host=localhost;dbname=shoes_test');

  class StoreTest extends PHPUnit_Framework_TestCase {

    protected function tearDown() {
      Store::deleteAll();
      Brand::deleteAll();
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

    function test_getAll() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $name2 = "Discount Surplus Clogs";
      $test_store = new Store($name);
      $test_store->save();
      $test_store2 = new Store($name2);
      $test_store2->save();

      // Act
      $result = Store::getAll();

      // Assert
      $this->assertEquals([$test_store, $test_store2], $result);
    }

    function test_deleteAll() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $name2 = "Discount Surplus Clogs";
      $test_store = new Store($name);
      $test_store->save();
      $test_store2 = new Store($name);
      $test_store2->save();

      // Act
      Brand::deleteAll();
      Store::deleteAll();
      $result = Store::getAll();

      // Assert
      $this->assertEquals([], $result);
    }

    function testDelete() {
      //Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      //Act
      $test_store->addBrand($test_brand);
      $test_store->delete();

      //Assert
      $this->assertEquals([], $test_brand->getStores());
    }

    function test_find() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $name2 = "Discount Surplus Clogs";
      $test_store = new Store($name);
      $test_store->save();
      $test_store2 = new Store($name2);
      $test_store2->save();

      // Act
      $result = Store::find($test_store->getId());

      // Assert
      $this->assertEquals($test_store, $result);
    }

    function testAddBrand() {
        //Arrange
        $name = "Cheapo Shoe Emporium";
        $test_store = new Store($name);
        $test_store->save();

        $brand_name = "Knee-Kays";
        $test_brand = new Brand($brand_name);
        $test_brand->save();

        //Act
        $test_store->addBrand($test_brand);

        //Assert
        $this->assertEquals($test_store->getBrands()[0], $test_brand);
    }

    function test_getBrands() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      $brand_name2 = "Bob";
      $test_brand2 = new Brand($brand_name2);
      $test_brand2->save();

      // Act
      $test_store->addBrand($test_brand);
      $test_store->addBrand($test_brand2);

      // Assert
      $this->assertEquals($test_store->getBrands(), [$test_brand, $test_brand2]);
    }

    function test_search() {
      // for some unknown reason, the teardown is not always working. I'm having to run it agian here or it won't clear the stores.
      Store::deleteAll();
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      // Act
      $result = Store::search($name);

      // Assert
      $this->assertEquals($test_store, $result[0]);
    }

  }
?>
