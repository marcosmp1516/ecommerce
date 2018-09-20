<?php
use \Hcode\Page;
use \Hcode\Pageadmim;
use \Hcode\Model\Products;
use \Hcode\Model\Category;

$app->get('/', function() {

    $products = Products::listAll();

    $page = new Page();

    $page->setTpl("index", [
      'products'=>Products::checkList($products)
    ]);
});

$app->get("/categories/:idcategory", function($idcategory){

  $category = new Category();
  $category->get((int)$idcategory);

  $page = new Page();
  $page->setTpl("category",[
    'category'=>$category->getValues(),
    'products'=>Products::checkList($category->getProducts())
  ]);

});
/*$app->get("/categories/:idcategory", function($idcategory){
  User::verifyLongin();
  $category = new Category();
  $category->get((int)$idcategory);

  $page = new Page();
  $page->setTpl("category",[
    'category'=>$category->getValues(),
    'products'=>[]
  ]);

});*/


 ?>
