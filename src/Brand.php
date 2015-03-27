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
      $GLOBALS['DB']->exec("DELETE FROM brands WHERE id = {$this->getId()};");
      $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE brand_id = {$this->getId()};");
    }

    // DB getters
    function getStores() {
      $returned_results = $GLOBALS['DB']->query("SELECT stores.* FROM stores JOIN stores_brands ON (stores.id = stores_brands.store_id) JOIN brands ON (stores_brands.brand_id = brands.id) WHERE brands.id = {$this->getId()};");
      $stores = [];
      foreach($returned_results as $result) {
        $new_store = new Store($result['name'], $result['id']);
        array_push($stores, $new_store);
      }
      return $stores;
    }

    // DB setters

    // static methods
    static function getAll() {
      $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
      $brands = array();
      foreach($returned_brands as $brand) {
        $new_brand = new Brand($brand['brand'], $brand['id']);
        array_push($brands, $new_brand);
      }
      return $brands;
    }

    static function deleteAll() {
      $GLOBALS['DB']->exec("DELETE FROM brands;");
      $GLOBALS['DB']->exec("DELETE FROM stores_brands;");
    }
  }
?>
