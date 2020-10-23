<?php
namespace app\Login\controller;

use think\Controller;
use app\login\model\Admin;

class Logout extends Controller 
{
  public function index() {

    try {

      // 刪除session
      session(cookie('user'), null);
      // 刪除cookie
      setcookie("user", '', time()-3600);
  
      $data = array(
        'statusCode' => '1',
        'message' => '登出成功'
      );
      return json_encode($data);

    } catch(Exception $e) {

      $err = array(
        'statusCode' => '0',
        'message' => $e
      );
      
      return json_encode($err);

    }
  }
}
