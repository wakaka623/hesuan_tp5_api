<?php
namespace app\User\model;

use think\Model;
use think\Db;

class User extends Model
{
	public function query($token){
    $db = DB::name('user_info');
    $user = $db->where('token','=',$token)->find();
    
    

    if (!$user) return 1;  // 用户不存在
    
    return $user;
	}
}
