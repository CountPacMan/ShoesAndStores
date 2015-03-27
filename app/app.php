<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Brand.php";
  require_once __DIR__."/../src/Store.php";

  $app = new Silex\Application();

  $app['debug'] = true;

  $DB = new PDO('pgsql:host=localhost;dbname=shoes');

  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

  // get

  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig', array('added' => false, 'stores' => Store::getAll(), 'brand_added' => true, 'brands' => Brand::getAll(), 'no_brand_fail' => false));
  });

  $app->get("/stores/{id}", function($id) use ($app) {
    $store = Store::find($id);
    $stores = [];
    array_push($stores, $store);
    $brands = [];
    $brand = $store->getBrands();
    array_push($brands, $brand);
    return $app['twig']->render('stores.html.twig', array('brands' => $brands, 'stores' => $stores));
  });

  $app->get("/stores/{id}/edit", function($id) use ($app) {
    $store = Store::find($id);
    $brands = $store->getBrands();
    $other_brands = $store->getOtherBrands();
    return $app['twig']->render('stores_edit.html.twig', array('store' => $store, 'brands' => $brands, 'other_brands' => $other_brands));
  });

  $app->get("/stores", function() use ($app) {
    $stores = Store::getAll();
    $brands = [];
    foreach ($stores as $store) {
      $brand = $store->getBrands();
      array_push($brands, $brand);
    }
    return $app['twig']->render('stores.html.twig', array('brands' => $brands, 'stores' => $stores));
  });

  $app->get("/brands", function() use ($app) {
    $brands = Brand::getAll();
    $stores = [];
    foreach ($brands as $brand) {
      $store= $brand->getStores();
      array_push($stores, $store);
    }
    return $app['twig']->render('brands.html.twig', array('stores' => $stores, 'brands' => $brands));
  });

  $app->get("/brands/{id}", function($id) use ($app) {
    $brand = Brand::find($id);
    $brands = [];
    array_push($brands, $brand);
    $store = $brand->getStores();
    $stores = [];
    array_push($stores, $store);
    return $app['twig']->render('brands.html.twig', array('stores' => $stores, 'brands' => $brands));
  });

  $app->get("/brands/{id}/edit", function($id) use ($app) {
    $brand = Brand::find($id);
    $stores = $brand->getStores();
    $other_stores = $brand->getOtherStores();
    return $app['twig']->render('brands_edit.html.twig', array('brand' => $brand, 'stores' => $stores, 'other_stores' => $other_stores));
  });

  // post

  $app->post("/stores", function() use ($app) {
    $added = false;
    $no_brand_fail = false;
    if (isset($_POST['brand_id'])) {
      $store = new Store($_POST['name']);
      $store->save();
      for ($i = 0; $i < count($_POST['brand_id']); $i++){
        $brand= Brand::find($_POST['brand_id'][$i]);
        $brand->addStore($store);
        $added = true;
      }
    } elseif (!empty($_POST['brand'])) {
      $store = new Store($_POST['name']);
      $store->save();
      $brand = new Brand($_POST['brand']);
      $brand->save();
      $store->addBrand($brand);
      $added = true;
    } else {
      $no_brand_fail = true;
    }

    return $app['twig']->render('index.html.twig', array('added' => $added, 'stores' => Store::getAll(), 'brand_added' => true, 'brands' => Brand::getAll(), 'no_brand_fail' => $no_brand_fail));
  });

  $app->post("/brands", function() use ($app) {
    $added = false;
    $brand_added = true;
    if (isset($_POST['store_id'])) {
      $brand = new Brand($_POST['brand']);
      $brand->save();
      for ($i = 0; $i < count($_POST['store_id']); $i++) {
        $store = Store::find($_POST['store_id'][$i]);
        $store->addBrand($brand);
      }
      $added = true;
    } else {
      $brand_added = false;
    }
    return $app['twig']->render('index.html.twig', array('added' => $added, 'stores' => Store::getAll(), 'brand_added' => $brand_added, 'brands' => Brand::getAll(), 'no_brand_fail' => false));
  });

  $app->post("/search/stores", function() use ($app) {
    $stores = Store::search($_POST['name']);
    $brands = [];
    foreach ($stores as $store) {
      $brand = $store->getBrands();
      array_push($brands, $brand);
    }
    return $app['twig']->render('stores.html.twig', array('brands' => $brands, 'stores' => $stores));
  });

  $app->post("/search/brands", function() use ($app) {
    $brands = Brand::search($_POST['name']);
    $stores = [];
    foreach ($brands as $brand) {
      $store = $brand->getStores();
      array_push($stores, $store);
    }
    return $app['twig']->render('brands.html.twig', array('brands' => $brands, 'stores' => $stores));
  });

  // delete

  $app->delete("/destroy", function() use ($app) {
    Store::deleteAll();
    Brand::deleteAll();
    return $app['twig']->render('index.html.twig', array('added' => false, 'stores' => Store::getAll(), 'brand_added' => true, 'brands' => Brand::getAll(), 'no_brand_fail' => false));
  });


  return $app;
?>
