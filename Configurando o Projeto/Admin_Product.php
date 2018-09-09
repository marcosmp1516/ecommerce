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


 ?>
