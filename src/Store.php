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
