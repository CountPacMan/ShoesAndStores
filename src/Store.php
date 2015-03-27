<?php
class Store {
  private $name;
  private $id;

  function __construct($name, $id = null) {
    $this->name = $name;
    $this->id = $id;
  }

  // setters
  function setName ($name) {
    $this->name = (string) $name;
  }

  function setId($id) {
    $this->id = (int) $id;
  }

  // getters
  function getName() {
    return $this->name;
  }

  function getId() {
    return $this->id;
  }

  // DB save/delete
  function save() {
    $statement = $GLOBALS['DB']->query("INSERT INTO stores (name) VALUES ('{$this->getName()}') RETURNING id;");
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $this->setId($result['id']);
  }

  function delete() {
    $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE store_id = {$this->getId()};");
    $GLOBALS['DB']->exec("DELETE FROM stores WHERE id = {$this->getId()};");
  }

  function deleteWithBrand($brand_id) {
    $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE store_id = {$this->getId()} AND brand_id = {$brand_id};");
    $GLOBALS['DB']->exec("DELETE FROM brands WHERE id = {$brand_id};");
  }

  // DB getters
  function getBrands() {
    $returned_results = $GLOBALS['DB']->query("SELECT brands.* FROM brands JOIN stores_brands ON (brands.id = stores_brands.brand_id) JOIN stores ON (stores_brands.store_id = stores.id) WHERE stores.id = {$this->getId()};");
    $brands = [];
    foreach($returned_results as $result) {
      $new_brand = new Brand($result['brand'], $result['id']);
      array_push($brands, $new_brand);
    }
    return $brands;
  }

    // returns brands that this store does not carry
  function getOtherBrands() {
    $query = $GLOBALS['DB']->query("SELECT DISTINCT brands.* FROM brands JOIN stores_brands ON brand_id = brands.id
  JOIN stores ON stores.id = store_id
  WHERE brands.id NOT IN (SELECT brands.id FROM brands JOIN stores_brands ON brand_id = brands.id JOIN stores ON stores.id = store_id WHERE stores.id = {$this->getId()});");

    $brands = [];
    foreach ($query as $brand) {
      $new_brand = new Brand($brand['brand'], $brand['id']);
      array_push($brands, $new_brand);
    }
    return $brands;
  }

  // DB setters
  function addBrand($brand) {
    $insert = true;
    $query = $GLOBALS['DB']->query("SELECT * FROM stores_brands;");
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
      if ($result['brand_id'] == $brand->getId() && $result['store_id'] == $this->getId()) {
        // found a duplicate. Don't insert.
        $insert = false;
      }
    }
    if ($insert) {
      $GLOBALS['DB']->exec("INSERT INTO stores_brands (store_id, brand_id) VALUES ({$this->getId()}, {$brand->getId()});");
    }
  }

  function updateName($name) {
    $GLOBALS['DB']->exec("UPDATE stores SET name = '{$name}' WHERE id = {$this->getId()}");
    $this->setName($name);
  }

  function updateBrands($brands) {
    // delete store's brands in join table
    $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE store_id = {$this->getId()};");
    // add store's brands in join table
    foreach ($brands as $brand) {
      $this->addBrand($brand);
    }
  }

  // static methods
  static function deleteAll() {
    $GLOBALS['DB']->exec("DELETE FROM stores_brands;");
    $GLOBALS['DB']->exec("DELETE FROM stores;");
  }

  static function getAll() {
    $returned_stores = $GLOBALS['DB']->query("SELECT * FROM stores;");
    $stores = [];
    foreach ($returned_stores as $store) {
      $new_store = new Store($store['name'], $store['id']);
      array_push($stores, $new_store);
    }
    return $stores;
  }

  static function find($search_id) {
    $found_store = null;
    $stores = Store::getAll();
    foreach($stores as $store) {
      $store_id = $store->getId();
      if($store_id == $search_id) {
        $found_store = $store;
      }
    }
    return $found_store;
  }

  static function search($name) {
    $stores = [];
    $returned_stores = $GLOBALS['DB']->query("SELECT * FROM stores WHERE UPPER(name) LIKE UPPER('%{$name}%');");
    foreach ($returned_stores as $store) {
      $new_store = new Store($store['name'], $store['id']);
      array_push($stores, $new_store);
    }
    return $stores;
  }

}
