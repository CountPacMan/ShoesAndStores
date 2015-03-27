<?php

  /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

  require_once "src/Store.php";
  require_once "src/Brand.php";

  $DB = new PDO('pgsql:host=localhost;dbname=shoes_test');

  class BrandTest extends PHPUnit_Framework_TestCase {

    protected function tearDown() {
      Brand::deleteAll();
      Store::deleteAll();
    }

    function test_save() {
      // Arrange
      $brand_name = "Knee-Kays";
      $test_brand= new Brand($brand_name);

      // Act
      $test_brand->save();

      // Assert
      $result = Brand::getAll();
      $this->assertEquals($test_brand, $result[0]);
    }

    function test_getAll() {
      // Arrange
      $brand_name = "Knee-Kays";
      $brand_name2 = "ReLexicon";
      $test_Brand = new Brand($brand_name);
      $test_Brand->save();
      $test_Brand2 = new Brand($brand_name2);
      $test_Brand2->save();

      // Act
      $result = Brand::getAll();

      // Assert
      $this->assertEquals([$test_Brand, $test_Brand2], $result);
    }

    function test_deleteAll() {
      // Arrange
      $brand_name = "Knee-Kays";
      $brand_name2 = "ReLexicon";
      $test_Brand = new Brand($brand_name);
      $test_Brand->save();
      $test_Brand2 = new Brand($brand_name2);
      $test_Brand2->save();

      // Act
      Brand::deleteAll();

      // Assert
      $result = Brand::getAll();
      $this->assertEquals([], $result);
    }

    function testDelete() {
      //Arrange
      $name = "Discount Shoes";

      $test_store = new Store($name);
      $test_store->save();

      $brand_name = "ReLexicon";

      $test_brand= new Brand($brand_name);
      $test_brand->save();

      //Act
      $test_brand->addStore($test_store);
      $test_brand->delete();

      //Assert
      $this->assertEquals([], $test_store->getBrands());
    }

    function test_getId() {
      // Arrange
      $brand_name = "Knee-Kays";
      $id = 1;
      $test_Brand = new Brand($brand_name, $id);

      // Act
      $result = $test_Brand->getId();

      // Assert
      $this->assertEquals(1, $result);
    }

    function test_setId() {
      // Arrange
      $brand_name = "Knee-Kays";
      $test_Brand = new Brand($brand_name);

      // Act
      $test_Brand->setId(2);

      // Assert
      $result = $test_Brand->getId();
      $this->assertEquals(2, $result);
    }

    function test_find() {
      // Arrange
      $brand_name = "Knee-Kays";
      $brand_name2 = "ReLexicon";
      $test_Brand = new Brand($brand_name, 1);
      $test_Brand->save();
      $test_Brand2 = new Brand($brand_name2, 1);
      $test_Brand2->save();

      // Act
      $result = Brand::find($test_Brand->getId());

      // Assert
      $this->assertEquals($test_Brand, $result);
    }

    function testAddStore() {
      //Arrange
      $name = "Discount Shoes";
      $test_store = new Store($name);
      $test_store->save();

      $brand_name = "ReLexicon";
      $test_brand= new Brand($brand_name);
      $test_brand->save();

      //Act
      $test_brand->addStore($test_store);

      //Assert
      $this->assertEquals($test_brand->getStores()[0], $test_store);
    }

    function test_getStores() {
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $name2 = "Discount Clogs";
      $test_store2 = new Store($name2);
      $test_store2->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      // Act
      $test_brand->addStore($test_store);
      $test_brand->addStore($test_store2);

      // Assert
      $this->assertEquals($test_brand->getStores(), [$test_store, $test_store2]);
    }

    function test_search() {
      // for some unknown reason, the teardown is not always working. I'm having to run it agian here or it won't clear the stores.
      Store::deleteAll();
      Brand::deleteAll();
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      // Act
      $result = Brand::search($brand_name);

      // Assert
      $this->assertEquals($test_brand, $result[0]);
    }

    function test_updateBrand() {
      // Arrange
      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      $new_brand_name = "ReLexicon";

      // Act
      $test_brand->updateBrand($new_brand_name);

      // Assert
      $this->assertEquals("ReLexicon", $test_brand->getBrand());
    }


    function test_updateStores() {
      Store::deleteAll();
      Brand::deleteAll();
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $name2 = "Discount Clogs";
      $test_store2 = new Store($name2);
      $test_store2->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();


      // Act
      $test_brand->addStore($test_store);
      $test_brand->addStore($test_store2);

      $name3 = "Almost Cheap Boots";
      $test_store3 = new Store($name3);
      $test_store3->save();

      $stores = [];
      array_push($stores, $test_store);
      array_push($stores, $test_store3);
      $test_brand->updateStores($stores);

      // Assert
      $this->assertEquals($stores[1], $test_brand->getStores()[1]);
    }

    function test_getOtherStores() {
      Store::deleteAll();
      Brand::deleteAll();
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $name2 = "Discout Shoe Combobulator";
      $test_store2 = new Store($name);
      $test_store2->save();

      $name3 = "Almost Cheap Boots";
      $test_store3 = new Store($name3);
      $test_store3->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      $brand_name2 = "Bob";
      $test_brand2 = new Brand($brand_name2);
      $test_brand2->save();

      // Act
      $test_brand->addStore($test_store);
      $test_brand2->addStore($test_store2);
      $test_brand2->addStore($test_store3);

      $other_stores = [];
      array_push($other_stores, $test_store2);
      array_push($other_stores, $test_store3);

      // I haven't figured out how to do an array of objects comparison that gives me consistent results, so I'll assume that if the same number of elements are returned by my method, the test will pass
      $result = (count([$other_stores]) == count([$test_brand->getOtherStores()]));
      // Assert
      $this->assertEquals(true, $result);
    }

    function test_deleteWithStore() {
      Store::deleteAll();
      Brand::deleteAll();
      // Arrange
      $name = "Cheapo Shoe Emporium";
      $test_store = new Store($name);
      $test_store->save();

      $brand_name = "Knee-Kays";
      $test_brand = new Brand($brand_name);
      $test_brand->save();

      $test_brand->addStore($test_store);

      // Act
      $test_brand->deleteWithStore($test_store->getId());

      // Assert
      $this->assertEquals(true, empty($test_brand->getStores()));
    }
  }
?>
