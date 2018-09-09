<?php

use \Hcode\Pageadmim;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Products;

$app->get('/admin', function() {

    User::verifyLongin();

    $page = new Pageadmim();
    $page->setTpl("index");
});

$app->get('/admin/login', function() {

    $page = new Pageadmim([
        "header"=>false,
        "footer"=>false
    ]);
    $page->setTpl("login");
});

$app->post('/admin/login', function() {


    User::login($_POST["login"], $_POST["password"]);

    header("Location: /admin");
    exit;
});

$app->get('/admin/logout', function(){

    User::logout();

    header("Location: /admin/login");
    exit;

});

$app->get("/admin/forgot", function(){

  $page = new Pageadmim([
      "header"=>false,
      "footer"=>false
  ]);
    $page->setTpl("forgot");
});

$app->post("/admin/forgot", function(){

    $user = User::getForgost($_POST["email"]);

    header("Location: /admin/forgot/sent");
    exit;
});

$app->get("/admin/forgot/sent", function(){
  $page = new Pageadmim([
      "header"=>false,
      "footer"=>false
  ]);
    $page->setTpl("forgot-sent");
});

$app->get("/admin/forgot/reset", function(){

  $user = User::validForgotDecrypt($_GET["code"]);

  $page = new Pageadmim([
      "header"=>false,
      "footer"=>false
  ]);
    $page->setTpl("forgot-reset", array(
      "name"=>$user["desperson"],
      "code"=>$_GET["code"]
    ));
});

$app->get("/admin/forgot/reset", function(){
  $ForgotUser = User::validForgotDecrypt($_POST["code"]);

  User::setForgotUsed($ForgotUser["idrecovery"]);
  $user = new User();
  $user->get((int)$ForgotUser["iduser"]);

  $password = password_hash($_POST["password"],PASSWORD_DEFAULT, [
    "cost"=>12
    ] );

  $user->setPassword($password);

  $page = new Pageadmim([
      "header"=>false,
      "footer"=>false
  ]);
    $page->setTpl("forgot-reset-success");


});






 ?>
