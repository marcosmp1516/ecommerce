<?php
use \Hcode\Pageadmim;
use \Hcode\Model\User;
use \Hcode\Model\Products;

$app->get("/admin/products",function(){
    User::verifyLongin();

    $products = Products::listAll();
    $page = new Pageadmim();
    $page->setTpl("products",[
      "products"=>$products
    ]);

});

$app->get("/admin/products/create",function(){
    User::verifyLongin();

    $page = new Pageadmim();
    $page->setTpl("products-create");

});

$app->post("/admin/products/create",function(){
    User::verifyLongin();
    $product = new Products();
    $product ->setData($_POST);
    $product->save();

    header("Location: /admin/products");
    exit;

});

$app->get("/admin/products/:idproduct",function($idproduct){
    User::verifyLongin();
    $product = new Products();

    $product->get((int)$idproduct);

    $page = new Pageadmim();
    $page->setTpl("products-update", [
      'product'=>$product->getValues()
    ]);

});

$app->post("/admin/products/:idproduct",function($idproduct){
    User::verifyLongin();
    $product = new Products();

    $product->get((int)$idproduct);

    $product->setData($_POST);
    $product->save();

    $product->setPhoto($_FILES["file"]);

    header('Location: /admin/products');
    exit;

});

$app->get("/admin/products/:idproduct/delete",function($idproduct){
    User::verifyLongin();
    $product = new Products();

    $product->get((int)$idproduct);
    $product->delete();

    header('Location: /admin/products');
    exit;


});



 ?>
