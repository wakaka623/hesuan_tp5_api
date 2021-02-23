<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

namespace think;
//访问源头

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if($origin)
{
    header('Access-Control-Allow-Origin:' . $origin);
}
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Headers:Content-type');
header('Content-type:application/json');
header('Access-Control-Allow-Methods:PUT,POST,GET,DELETE,OPTIONS');

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';
// phpinfo();

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
