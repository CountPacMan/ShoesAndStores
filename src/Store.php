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

  // DB
  function save() {
    $statement = $GLOBALS['DB']->query("INSERT INTO stores (name) VALUES ('{$this->getName()}') RETURNING id;");
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $this->setId($result['id']);
  }

  function delete() {
    $GLOBALS['DB']->exec("DELETE FROM stores WHERE id = {$this->getId()};");
    $GLOBALS['DB']->exec("DELETE FROM stores_brands WHERE store_id = {$this->getId()};");
  }

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

  // static methods
  static function deleteAll() {
    $GLOBALS['DB']->exec("DELETE FROM stores *;");
    $GLOBALS['DB']->exec("DELETE FROM stores_brands *;");
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

}
