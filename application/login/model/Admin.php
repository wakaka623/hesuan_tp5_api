<?php
namespace app\Login\model;
use think\Model;
use think\Db;
class Admin extends Model
{
	public function login($name, $pwd){
    $db = DB::name('admin');
    $user = $db->where('username','=',$name)->find();

    // 用户不存在
    if (!$user) {
      $user['code'] = '0';
    } else {
      if($user['password'] == $pwd) {
        $user['code'] = '1';
      } else {
        $user['code'] = '2';
      }
    }

    return $user;
	}
}
