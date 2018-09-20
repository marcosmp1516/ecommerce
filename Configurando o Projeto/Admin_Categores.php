<?php

use \Hcode\Pageadmim;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Products;

$app->get("/admin/categories",function(){
    User::verifyLongin();
    $categories = Category::listAll();
    $page = new Pageadmim();
    $page->setTpl("categories",[
    'categories'=>$categories
  ]);

});

$app->get("/admin/categories/create",function(){

  User::verifyLongin();
  $page = new Pageadmim();
  $page->setTpl("categories-create");

});

$app->post("/admin/categories/create",function(){

  User::verifyLongin();
  $categores = new Category();

  $categores->setData($_POST);

  $categores->save();

  header("Location: /admin/categories");
  exit;


});

$app->get("/admin/categories/:idcategory/delete",function($idcategory){
  User::verifyLongin();
  $category = new Category();
  $category->get((int)$idcategory);
  $category->delete();

  header("Location: /admin/categories");
  exit;

});


$app->get("/admin/categories/:idcategory",function($idcategory){

  User::verifyLongin();
  $category = new Category();
  $category->get((int)$idcategory);

  $page = new Pageadmim();
  $page->setTpl("categories-update",[
    'category'=>$category->getValues()
  ]);

});

$app->post("/admin/categories/:idcategory",function($idcategory){

  User::verifyLongin();

  $category = new Category();
  $category->get((int)$idcategory);

  $category->setData($_POST);
  $category->save();

  header("Location: /admin/categories");
  exit;

});


$app->get("/admin/categories/:idcategory/products", function($idcategory){

  User::verifyLongin();
  $category = new Category();
  $category->get((int)$idcategory);


  $page = new Pageadmim();
  $page->setTpl("categories-products",[
    'category'=>$category->getValues(),
    'productsRelated'=>$category->getProducts(),
    'productsNotRelated'=>$category->getProducts(false)
  ]);
});

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

  User::verifyLongin();
  $category = new Category();
  $category->get((int)$idcategory);

  $product = new Products();

  $product->get((int)$idproduct);
  $category->addProduct($product);

  header("Location: /admin/categories/" . $idcategory . "/products");
  exit;


});

$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

  User::verifyLongin();
  $category = new Category();
  $category->get((int)$idcategory);

  $product = new Products();

  $product->get((int)$idproduct);
  $category->removeProduct($product);

  header("Location: /admin/categories/" . $idcategory . "/products");
  exit;


});


 ?>
