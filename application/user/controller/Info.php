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
    
    // 判断是否有post参数
    $par = $request->post('token');
    if (!$par) {
      return json_encode(array('code'=>'0', 'message'=>'未登录'));
    }

    
    // 通过cookie查询session
    $u = cookie('user');
    if (session($u) != '1') {
      return json_encode(array('code'=>'0', 'message'=>'登陆已过期或未登陆'));
    }


    // 登陆成功返回用户信息
    $user = new User();
    $info = $user->query($u);

    if ($info === 1) return json_encode(array('code'=>'0', 'message'=>'该用户不存在'));
    
    return json_encode($info);
  }
}
