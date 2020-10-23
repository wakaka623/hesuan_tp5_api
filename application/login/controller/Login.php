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
      return json_encode($err);
    }
    
    // 登陆成功返回token
    setcookie("user", $result['username'], time()+3600, '/');
    session($result['username'], '1');


    $data = array(
      'statusCode' => '1',
      'username' => $result['username']
    );
    return json_encode($data);
	}
}
