<?php
class UserService{

  static public function registCoTurnUser($realm, $username, $password, &$result){
    try{
      $debug = new \strangerfw\utils\Logger('DEBUG');
      $turmadmin = "sudo /usr/local/bin/turnadmin -a -u ${username} -p ${password} -r ${realm}";
      $debug->log("UserService::registCoTurnUser() turmadmin[${turmadmin}] ");
      $result = shell_exec($turmadmin);
      // if(!$result) throw new Exception("Can not regist coTurn user.", 1);
    } catch (\Exception $e) {
      $debug->log("UserService::save() registCoTurnUser:" . $e->getMessage());
   }
  }
}