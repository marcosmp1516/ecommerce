<?php
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
/*use \Hcode\Page;
use \Hcode\Pageadmim;
use \Hcode\Model\User;
use \Hcode\Model\Category;*/
$app = new Slim();

$app->config('debug', true);

require_once("site.php");
require_once("admin.php");
require_once("Admin_User.php");
require_once("Admin_Categores.php");
require_once("Admin_Product.php");


$app->run();
//www.hcodecommerce.com.br
 ?>
