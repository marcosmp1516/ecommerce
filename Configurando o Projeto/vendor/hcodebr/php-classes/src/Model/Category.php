<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;
/**
 *
 */
class Category extends Model
{

 public static function listAll()
 {
   $sql = new Sql();

   return $sql->select("SELECT * FROM tb_categories  ORDER BY descategory");
 }

public function save()
{
  $sql = new Sql();

   $result = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
       ":idcategory"=>$this->getidcategory(),
       ":descategory"=>$this->getdescategory()
    ));

    $this->setData($result[0]);
    Category::UpdateFile();
}

public function get($idcategory)
{
  $sql = new Sql();

  $result = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory",[
    ':idcategory'=>$idcategory
  ]);

  $this->setData($result[0]);

}


public function delete()
{
  $sql = new Sql();

  $sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory",[
    'idcategory'=>$this->getidcategory()
  ]);

  Category::UpdateFile();
}

public function UpdateFile()
{
  $categores = Category::listAll();
  $html = [];

  foreach ($categores as $row) {
    array_push($html,'<li><a href="/categories/'.$row['idcategory'].'">' . $row['descategory'] .'</a></li>');
  }

  file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR .
 "categores-menu.html",implode('',$html));

}

}


 ?>