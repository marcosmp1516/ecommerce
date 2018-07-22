<?php
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\Pageadmim;
use \Hcode\Model\User;
use \Hcode\Model\Category;
$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {

    $page = new Page();
    $page->setTpl("index");
});

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

$app->get("/admin/users", function(){

    User::verifyLongin();
    $users = User::listAll();
    $page = new Pageadmim();
    $page->setTpl("users", array(
      "users"=>$users
    ));

});

$app->get("/admin/users/create", function(){

    User::verifyLongin();

    $page = new Pageadmim();
    $page->setTpl("users-create");

});

$app->get("/admin/users/:iduser/delete", function($iduser){
    User::verifyLongin();
    $user = new User();
    $user->get((int)$iduser);
    $user->delete();
    header("Location: /admin/users");
    exit;

});

$app->get("/admin/users/:iduser", function($iduser){

    User::verifyLongin();
    $user = new User();
    $user->get((int)$iduser);
    $page = new Pageadmim();
    $page->setTpl("users-update", array(
      "user"=>$user->getValues()
    ));

});

$app->post("/admin/users/create", function(){
    User::verifyLongin();

//  var_dump($_POST);

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    $user->setData($_POST);
    $user->save();

    header("Location: /admin/users");
    exit;
  //  var_dump($user);
});

$app->post("/admin/users/:iduser", function($iduser){
    User::verifyLongin();

    $user = new User();
    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
    $user->get((int)$iduser);
    $user->setData($_POST);
    $user->update();

    header("Location: /admin/users");
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

$app->get("/admin/categories",function(){

    $categories = Category::listAll();
    $page = new Pageadmim();
    $page->setTpl("categories",[
    'categories'=>$categories
  ]);

});

$app->get("/admin/categories/create",function(){


  $page = new Pageadmim();
  $page->setTpl("categories-create");

});

$app->post("/admin/categories/create",function(){

  var_dump($_POST);
  $categores = new Category();

  $categores->setData($_POST);

  $categores->seve();

  header("Location: /admin/categories");
  exit;


});



$app->run();
//www.hcodecommerce.com.br
 ?>
