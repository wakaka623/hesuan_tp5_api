<?php
namespace app\Login\model;
use think\Model;
use think\Db;
class Admin extends Model
{
	public function login($name, $pwd){
    $state = array();
    $db = DB::name('admin');
    $user = $db->where('username','=',$name)->find();

    // 用户不存在
    if (!$user) {
      $state['code'] = '0';
    } else {
      if($user['password'] == $pwd) {
        // session('username',$user['username']);
        // session('uid',$user['id']);
        $state['code'] = '1';
        array_push($state, $user);
      } else {
        $state['code'] = '2';
      }
    }

    return $state;
	}
}
