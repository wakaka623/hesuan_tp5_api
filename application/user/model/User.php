<?php
namespace app\User\model;

use think\Model;
use think\Db;

class User extends Model
{
	public function query($name){
    $db = DB::name('user_info');
    $user = $db->where('name','=',$name)->find();
    
    

    if (!$user) return 1;  // 用户不存在
    
    return $user;
	}
}
