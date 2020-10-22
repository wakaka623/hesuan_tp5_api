<?php
namespace app\Login\controller;

use think\Controller;
use app\login\model\Admin;


class Login extends Controller
{
	public function index() {
    $isPost = request()->isPost();
    $err = array('statusCode'=>'0');

		if(!$isPost) return;

    $admin = new Admin();
    $name = request()->post('username');
    $psw = request()->post('password');

    if (!$name || !$psw) return json_encode($err);

    $result = $admin->login($name, $psw);
    
    // 密码错误或用户不存在
    if ($result['code'] === '0' || $result['code'] === '2') {
      array_push($err, 'data', null);
      return json_encode($err);
    } else {
      // $this->success('信息正确，正在为您跳转','index/index');
      $arr = array('token'=>$num['token']);
      return json_encode($arr);
    }
	}
	// return $this->fetch('login');
}
