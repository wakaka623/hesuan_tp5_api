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
      array_push($state, 'code', '0');
    } else {
      if($user['password'] == $pwd) {
        // session('username',$user['username']);
        // session('uid',$user['id']);
        array_push($state, 'code', '1');
        array_push($state, 'user', $user);
      } else {
        array_push($state, 'code', '2');
      }
    }

    return $state;
	}
}
