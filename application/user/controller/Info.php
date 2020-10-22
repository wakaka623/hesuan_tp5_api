<?php
namespace app\User\controller;

use think\Controller;
use think\Request;
use app\user\model\User;

class Info extends Controller
{
  public function index(Request $request) {
    $isPost = $request->isPost();

    if (!$isPost) {
      echo 'It is not post method';
      exit;
    }
    
    $token = $request->post('token');

    // $date=array(
    //     'token'=>$token
    // );
    // return json_encode($date,true);
    
    if ($token) {
      $token = request()->param()['token'];
    } else {
      return json_encode(array('code'=>'0'));
    }


    $user = new User();
    $info = $user->query($token);
    

    if ($info === 1) return json_encode(array('code'=>'0'));
    
    return json_encode($info);
  }
}
