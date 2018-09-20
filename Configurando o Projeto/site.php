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

  $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

  $category = new Category();
  $category->get((int)$idcategory);

  $pagination = $category->getProductPage($page);

  $pages = [];

  for ($i=1; $i <= $pagination['pages'] ; $i++) {
      array_push($pages, [
        'link'=>'/categories/' . $category->getidcategory() . '?page=' . $i,
        'page'=>$i
      ]);
  }

  $page = new Page();
  $page->setTpl("category",[
    'category'=>$category->getValues(),
    'products'=>$pagination["data"],
    'pages'=>$pages
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
