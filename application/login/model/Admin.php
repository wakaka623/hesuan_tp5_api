<?php
namespace app\Login\model;
use think\Model;
use think\Db;
class Admin extends Model
{
	public function login($name,$pwd){
//	    $user=Db::name('admin')->where('username',$name)->find();
//	    return $user;

    $db = DB::name('admin');
    $user = $db->where('username','=',$name)->find();

    // 用户不存在
    if (!$user) {
      $user['code'] = '0';
    } else {
      if($this->encrypt($user['password'],'D') == $pwd) {
          $user['code'] = '1';
      } else {
          $user['code'] = '2';
      }
    }
    return $user;
	}
	public  function  reg($name,$pwd,$token,$group,$isAdmin){
	    $img='https://img.mingjia998.com/397da3ef75648c258ca3e57e598b2bc.png';
	    $find=Db::name('admin')->where('username','=',$name)->find();
        if ($find){
            $user['code'] = '0';
            $user['message'] = "账号已存在";
            return $user;
        }
        $ins=Db::name('admin')->insert(['username'=>$name,'password'=>$pwd,'token'=>$token,'group'=>$group,'is_admin'=>$isAdmin]);
        Db::name('user_info')->insert(['name'=>$name,'avatar'=>$img,'introduction'=>'普通用户','roles'=>$token]);
        if ($ins){
            $user['code'] = '1';
            $user['message'] = "注册成功";
        }else{
            $user['code'] = '0';
            $user['message'] = "注册失败";
        }
        return $user;
    }

    /**
     *$string :需要加密解密的字符串
     *$operation:判断是加密还是解密:E:加密 D:解密
     *$key :加密的钥匙(密匙);
     */
    function encrypt($string,$operation,$key=''){
        $src = array("/","+","=");
        $dist = array("_a","_b","_c");
        if($operation=='D'){
            $string = str_replace($dist,$src,$string);
        }
        $key=md5($key);
        $key_length=strlen($key);
        $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
        $string_length=strlen($string);
        $rndkey=$box=array();
        $result='';
        for($i=0;$i<=255;$i++){
            $rndkey[$i]=ord($key[$i%$key_length]);
            $box[$i]=$i;
        }
        for($j=$i=0;$i<256;$i++){
            $j=($j+$box[$i]+$rndkey[$i])%256;
            $tmp=$box[$i];
            $box[$i]=$box[$j];
            $box[$j]=$tmp;
        }
        for($a=$j=$i=0;$i<$string_length;$i++){
            $a=($a+1)%256;
            $j=($j+$box[$a])%256;
            $tmp=$box[$a];
            $box[$a]=$box[$j];
            $box[$j]=$tmp;
            $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
        }
        if($operation=='D'){
            if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){ // www.jbxue.com
                return substr($result,8);
            }else{
                return'';
            }
        }else{
            $rdate = str_replace('=','',base64_encode($result));
            $rdate = str_replace($src,$dist,$rdate);
            return $rdate;
        }
    }
}
