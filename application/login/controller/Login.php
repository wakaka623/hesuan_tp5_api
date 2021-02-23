<?php
namespace app\Login\controller;

use think\Db;
use think\Request;
use think\Controller;
use app\login\model\Admin;



class Login extends Controller
{
    /**
     * 登录接口
     */
	public function index(Request  $request) {
//        $errorOne=array(
//            'code'=>'0',
//            'message'=>'登录名或者密码为空，别闹'
//        );
//        $errorTwo=array(
//            'code'=>'0',
//            'message'=>'用户名或密码出错，请重新输入！'
//        );
//        $success=array(
//            'code'=>'1',
//            'message'=>'登录成功！'
//        );
//	    $username =$request->post('username');
//	    $password =$request->post('password');
//	    if(!$username||!$password) return json_encode($errorOne);
//        $admin=new Admin();
//        $user=$admin->login($username);
//        $pwd=$this->encrypt($user['password'],'D');
//        if (!$user==NULL||!$user==''){
//            foreach ($user as $key => &$value){
//                if($key == 'password') {
//                    unset($user['password']);
//                }
//            }
//        }
//        $userMsg=array(
//            'date'=>$user,
//            'meta'=>$success
//        );
//        if ($pwd===$password){
//             return json_encode($userMsg);
//        }else{
//            return json_encode($errorTwo);
//        }



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
      'username' => $result['username'],
        'group'=> $result['group'],
        'is_admin'=>$result['is_admin']
    );
    return json_encode($data);
	}

	/**
     *注册接口
     * */
	public  function  register(Request $request){

        $group = $request->post('group');
        $psw = $this->encrypt($request->post('password'),'E');
        $name = $request->post('username');
        $token=$this->GetRandStr(32);
        $isAdmin=$request->post('is_admin');

        $err = array(
            'code'=>'0',
            'message'=>"用户名或密码或组别为空，难搞哦！"
    );
        $admin = new Admin();
        if (!$name || !$psw || !$group) return json_encode($err);
        $result = $admin->reg($name, $psw,$token,$group,$isAdmin);
        return json_encode($result);
    }
    /**
     *获取用户信息
     */
    public function  getUser(Request $req){
        $isAdmin=$req->post('user');
        $user=Db::name('admin')->select();
        $temps = array_column($user, 'group');
        array_multisort($temps,SORT_ASC,$user);
        $data=array(
            'code'=>'0',
            'message'=>'您没有权限'
        );
        $userMsg=array(
            'data'=>$user,
            'code'=>'1'
        );
        if ($isAdmin=='admin'){
            return json_encode($userMsg);
        }else{
            return  json_encode($data);
        }
    }

    /**
     * @param $id
     * @return string
     *根据用户id返回用户信息
     */
    public function getUserMsg($id){
        $usermsg=Db::name('admin')->where('id',$id)->find();
        return json_encode($usermsg);
    }

    /**
     * @param Request $req
     * 根据id修改用户信息
     */
    public function updataUser(Request $req){
        $id=$req->post('Id');
        $user=$req->post('username');
        $passw=$this->encrypt($req->post('password'),'E');
        $group=$req->post('group');
        $data=array(
            'code'=>'0',
            'message'=>'更新失败了，在试试呗'
        );
        $data1=array(
            'code'=>'1',
            'message'=>'操作成功'
        );
        $code=Db::name('admin')
            ->where('Id',$id)
            ->update(['username'=>$user,'password'=>$passw,'group'=>$group]);
        if ($code=='1'){
            return json_encode($data1);
        }else{
            return json_encode($data);
        }
    }
    /**
     * @param $id
     * @return string
     *根据用户id返回用户信息
     */
    public function delUser($id){
        $success=array(
            'code'=>'1',
            'message'=>'操作成功'
        );
        $error=array(
            'code'=>'0',
            'message'=>'删除失败了，在试试呗'
        );
        $code=Db::name('admin')->where('id',$id)->delete();
        if($code=='1'){
            return json_encode($success);
        }else{
            return json_encode($error);
        }
    }
    /**
     * 生成一串随机数
     * @length 传入多少位，生成多少位随机数
    */
    function GetRandStr($length){
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str)-1;
        $randstr = '';
        for ($i=0;$i<$length;$i++) {
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return $randstr;
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
            if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
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
