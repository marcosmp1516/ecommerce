<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;
/**
 *
 */
class User extends Model
{
  const SESSION = "User";
  const SECRET = "HcodePhp7_Secret";

  public static function login($login, $password)
  {
      $sql = new Sql();
      $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
        ":LOGIN"=>$login
      ));

      if (count($results) === 0) {
        throw new \Exception("Usuario inexistente ou senha invalida.");
      }

      $data = $results[0];

      if (password_verify($password, $data["despassword"]) === true) {

          $user = new User();
          $user->setData($data);

          $_SESSION[User::SESSION] = $user->getValues();

          return $user;
          //var_dump($user);

      }else{
        throw new \Exception("Usuario inexistente ou senha invalida.");
      }

  }


  public static function verifyLongin($inadmin = true)
  {
    if (!isset($_SESSION[User::SESSION])
    || !$_SESSION[User::SESSION]
    || !(int)$_SESSION[User::SESSION]["iduser"] > 0
    || (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin) {

        header("Location: /admin/login");
        exit;
    }
  }

  public static function logout()
  {
    $_SESSION[User::SESSION] = NULL;
  }

 public static function listAll()
 {
   $sql = new Sql();

   return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
 }

 public function save()
 {
   $sql = new Sql();


  $result = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
      ":desperson"=>$this->getdesperson(),
      ":deslogin"=>$this->getdeslogin(),
      ":despassword"=>$this->getdespassword(),
      ":desemail"=>$this->getdesemail(),
      ":nrphone"=>$this->getnrphone(),
      ":inadmin"=>$this->getinadmin()
   ));

   $this->setData($result[0]);
 }

 public function get($iduser)
 {
   $sql = new Sql();

   $result = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
     ":iduser"=>$iduser
   ));

   $this->setData($result[0]);

 }

 public function update()
 {
   $sql = new Sql();


  $result = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
      ":iduser"=>$this->getiduser(),
      ":desperson"=>$this->getdesperson(),
      ":deslogin"=>$this->getdeslogin(),
      ":despassword"=>$this->getdespassword(),
      ":desemail"=>$this->getdesemail(),
      ":nrphone"=>$this->getnrphone(),
      ":inadmin"=>$this->getinadmin()
   ));

   $this->setData($result[0]);
 }

 public function delete()
 {
   $sql = new Sql();

   $sql->query("CALL sp_users_delete(:iduser)", array(
      ":iduser"=>$this->getiduser()
   ));
 }

 public static function getForgost($email, $inadmin = true)
 {
   $sql = new Sql();

   $results = $sql->select("SELECT * FROM tb_persons a INNER JOIN tb_users b USING(idperson) WHERE a.desemail = :email", array(
     ":email"=>$email
   ));

   if (count($results) === 0) {
     throw new \Exception("Não foi possivel recuperar a senha.");

   }else{
       $data = $results[0];
       $resultsrecories =  $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
         ":iduser"=>$data["iduser"],
         ":desip"=>$_SERVER["REMOTE_ADDR"]
       ));

       if (count($resultsrecories) === 0) {

         throw new \Exception("Não foi possivel recuperar a senha");

       }else{
         $dataRecovery = $resultsrecories[0];

         $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));

         $code = openssl_encrypt( $dataRecovery["idrecovery"], 'aes-256-cbc', User::SECRET, 0, $iv );
         $resultcode = base64_encode($iv.$code);

         if ($inadmin === true) {
           $link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$resultcode";
         }else{
            $link = "http://www.hcodecommerce.com.br/forgot/reset?code=$resultcode";
         }


         $mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir Senha da Hcode Store", "forgot",
         array(
           "name"=>$data["desperson"],
           "link"=>$link
         ));

         $mailer->send();

         return $link;

       }
   }

 }

 public static function validForgotDecrypt($resultcode)
 {

   $resultvalid = base64_decode($resultcode);

   $codevalid = mb_substr($resultvalid, openssl_cipher_iv_length('aes-256-cbc'), null, '8bit');
   $iv = mb_substr($resultvalid, 0, openssl_cipher_iv_length('aes-256-cbc'), '8bit');
   $idrecovery = openssl_decrypt($codevalid, 'aes-256-cbc', User::SECRET, 0, $iv);

   $sql = new Sql();

   $results = $sql->select("SELECT * FROM tb_userspasswordsrecoveries a INNER JOIN
   tb_users b USING(iduser) INNER JOIN tb_persons c USING(idperson) WHERE
   a.idrecovery = :idrecovery AND a.dtrecovery IS NULL AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();", array(
     ":idrecovery"=>$idrecovery
   ));

   if (count($results) === 0) {
     throw new \Exception("Não foi possivel recuperar a senha");
   }else{
     return $results[0];
   }

 }

 public static function setForgotUsed($idrecovery)
 {
   $sql = new Sql();

   $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = idrecovery", array(
     ":idrecovery"=>$idrecovery
   ));
 }

public function setPassword($password)
{
  $sql = new Sql();

  $sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
    ":password"=>$password,
    ":iduser"=>$this->getiduser()
  ));
}


}







 ?>
