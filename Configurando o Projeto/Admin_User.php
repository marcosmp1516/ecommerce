<?php

use \Hcode\Pageadmim;
use \Hcode\Model\User;



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






 ?>
