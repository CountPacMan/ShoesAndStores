<?php
  class Brand {
    private $brand;
    private $id;

    function __construct($brand, $id = null)   {
      $this->brand = $brand;
      $this->id = $id;
    }

    // getters
    function getBrand()  {
      return $this->brand;
    }

    function getId() {
      return $this->id;
    }

    // setters
    function setBrand($brand)  {
      $this->brand = (string) $brand;
    }

    function setId($id) {
      $this->id = (int) $id;
    }

    // DB save/delete
    function save() {
      $statement = $GLOBALS['DB']->query("INSERT INTO brands (brand) VALUES ('{$this->getBrand()}') RETURNING id;");
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $this->setId($result['id']);
    }

    function delete() {
      $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE brand_id = {$this->getId()};");
      $GLOBALS['DB']->exec("DELETE FROM brands WHERE id = {$this->getId()};");
    }

    function deleteWithStore($store_id) {
      $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE brand_id = {$this->getId()} AND store_id = {$store_id};");
      $GLOBALS['DB']->exec("DELETE FROM stores WHERE id = {$store_id};");
    }

    // DB getters
    function getStores() {
      $returned_results = $GLOBALS['DB']->query("SELECT stores.* FROM stores JOIN stores_brands ON (stores.id = stores_brands.store_id) JOIN brands ON (stores_brands.brand_id = brands.id) WHERE brands.id = {$this->getId()} ORDER BY name;");
      $stores = [];
      foreach($returned_results as $result) {
        $new_store = new Store($result['name'], $result['id']);
        array_push($stores, $new_store);
      }
      return $stores;
    }

    function getOtherStores() {
      $query = $GLOBALS['DB']->query("SELECT DISTINCT stores.* FROM stores JOIN stores_brands ON store_id = stores.id
    JOIN brands ON brands.id = brand_id
    WHERE stores.id NOT IN (SELECT stores.id FROM stores JOIN stores_brands ON store_id = stores.id JOIN brands ON brands.id = brand_id WHERE brands.id = {$this->getId()}) ORDER BY name;");

      $stores = [];
      foreach ($query as $store) {
        $new_store = new Store($store['name'], $store['id']);
        array_push($stores, $new_store);
      }
      return $stores;
    }

    // DB setters
    function updateBrand($brand) {
      $GLOBALS['DB']->exec("UPDATE brands SET brand = '{$brand}' WHERE id = {$this->getId()}");
      $this->setBrand($brand);
    }

    function updateStores($stores) {
      // delete brand's stores in join table
      $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE brand_id = {$this->getId()};");
      // add brands stores in join table
      foreach ($stores as $store) {
        $this->addStore($store);
      }
    }

    function addStore($store) {
      $GLOBALS['DB']->exec("INSERT INTO stores_brands (store_id, brand_id) VALUES ({$store->getId()}, {$this->getId()});");
    }

    // static methods
    static function getAll() {
      $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands ORDER by brand;");
      $brands = array();
      foreach($returned_brands as $brand) {
        $new_brand = new Brand($brand['brand'], $brand['id']);
        array_push($brands, $new_brand);
      }
      return $brands;
    }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM stores_brands;");
      $GLOBALS['DB']->exec("DELETE FROM brands;");
    }

    static function find($search_id) {
      $found_brand = null;
      $brands = Brand::getAll();
      foreach ($brands as $brand) {
        $brand_id = $brand->getId();
        if ($brand_id == $search_id) {
          $found_brand = $brand;
        }
      }
      return $found_brand;
    }

    static function search($brand) {
      $brands = [];
      $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands WHERE UPPER(brand) LIKE UPPER('%{$brand}%') ORDER BY brand;");
      foreach ($returned_brands as $brand) {
        $new_brand = new Brand($brand['brand'], $brand['id']);
        array_push($brands, $new_brand);
      }
      return $brands;
    }
  }
?>
