<?php
use \Hcode\Page;
use \Hcode\Pageadmim;
use \Hcode\Model\Products;
use \Hcode\Model\Category;
use \Hcode\Model\Cart;

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

$app->get("/products/:desurl",function($desurl){

  $product = new Products();

  $product->getFromURL($desurl);

  $page = new Page();
  $page->setTpl("product-detail",[
    'product'=>$product->getValues(),
    'categories'=>$product->getCategories()
  ]);

});

$app->get("/cart",function(){

    $cart = Cart::getFromSession();
    $page = new Page();

    $page ->setTpl("cart",[
      'cart'=>$cart->getValues(),
      'products'=>$cart->getProducts()
    ]);


});

$app->get("/cart/:idproduct/add", function($idproduct){

    $product = new Products();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();
    $cart->addProduct($product);

    header("Location: /cart");
    exit;
});

$app->get("/cart/:idproduct/minus", function($idproduct){

    $product = new Products();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();
    $cart->removeProduct($product);

    header("Location: /cart");
    exit;
});

$app->get("/cart/:idproduct/remove", function($idproduct){

    $product = new Products();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();
    $cart->addProduct($product, true);

    header("Location: /cart");
    exit;
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
