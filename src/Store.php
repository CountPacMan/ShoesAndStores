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

  // static methods
  static function deleteAll() {
    $GLOBALS['DB']->exec("DELETE FROM stores *;");
    $GLOBALS['DB']->exec("DELETE FROM stores_brands *;");
  }

}
