<?php
// 命名空间 - > app\<模块>\controller
namespace app\test\controller;

// 继承它可以用很多方法
use think\Controller;

// Test类名要跟文件名一样(首字母最好大写)
class Test extends Controller
{
    // 操作
    public function test()
    {
      // 默认渲染 -> 当前模块/view/当前控制器名（小写）/当前操作（小写）.html
      //               test/view/test/test.html
      return $this->fetch();      
      // return view('./index');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
