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

$app->get("/categories/:idcategory", function($idcategory){

  $category = new Category();
  $category->get((int)$idcategory);

  $page = new Page();
  $page->setTpl("category",[
    'category'=>$category->getValues(),
    'products'=>[]
  ]);

});


 ?>
