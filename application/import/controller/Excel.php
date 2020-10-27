<?php
namespace app\import\controller;

use think\Controller;
use think\Loader;
use think\Request;
use \Env;

use app\import\model\Form;
use think\Db;


// require_once $_SERVER['DOCUMENT_ROOT'].'/static/PHPExcel/Classes/PHPExcel/IOFactory.php';
// require_once $_SERVER['DOCUMENT_ROOT'].'/static/PHPExcel/Classes/PHPExcel.php';

// define('EXTEND_PATH','../extend/');

require_once Env::get('root_path') . 'extend\phpexcel\PHPExcel.php';
// require_once Env::get('root_path') . 'extend\phpexcel\PHPExcel\IOFactory.php';

// use PHPExcel\PHPExcel_IOFactory;
// use PHPExcel\PHPExcel;


class Excel extends Controller
{
  /**
   * 导入表
   */
  public function index() {
    $a = Db::query('SELECT * FROM ruida_fund_reconciliation');
    return json_encode($a);

    $file = request()->file('file');

    $path = Env::get('root_path') . 'public\uploads';   // 保存路径

    $info = $file->move($path);    // 保存到本地
    

    // 拼接好保存后的文件路径
    $filename = $path . DIRECTORY_SEPARATOR . $info->getSaveName();
    
    // $objPHPExcel  = new \PHPExcel();

    if(strstr($filename,'.xlsx')) {
      $PHPReader = new \PHPExcel_Reader_Excel2007();
      // echo 'PHPExcel_Reader_Excel2007';
    } else {
      $PHPReader = new \PHPExcel_Reader_Excel5();
      // echo 'PHPExcel_Reader_Excel5';
    }

    //载入excel文件
    if(file_exists($filename)) {  //如果文件存在
      // echo '文件存在';
    } else {
      // echo '文件不存在';
    }


    // 使用PHPExcel获取excel数据
    $PHPExcel = $PHPReader->load($filename);
    $sheet = $PHPExcel->getActiveSheet(0);    // 获得sheet
    $highestRow = $sheet->getHighestRow();    // 取得共有数据数
    $data = $sheet->toArray();
    

    // 拿出标题栏
    $header = $data[0];

    // 对数组数据进行对象化
    foreach ($data as $key => $value) {
      foreach ($value as $k => $v) {
        $obj = array(
          'comment' => $header[$k],
          'value' => $v
        );
        
        $data[$key][$k] = $obj;
      }
    }

    $form = new Form();
    
    
    return json_encode($form->import($data));



    // $a = $form->import($data);
    
    exit;

    // var_dump($data);

  }

  /**
   * 查找表字段
   */
  public function columns() {
    $isPost = request()->isPost();

    if (!$isPost) return;

    $tableName = request()->post('table_name');

    if (!$tableName) {
      return array(
        'code' => '0',
        'message' => '',
      );
    }

    $form = new Form();

    $data = $form->getColumns($tableName);
    
    return json_encode($data);
  }

  /**
   * 查找表数据
   * @param table_name
   */
  public function get_table_data() {
    $isPost = request()->isPost();

    if (!$isPost) return;

    $tableName = request()->post('table_name');

    if (!$tableName) {
      return array(
        'code' => '0',
        'message' => '',
      );
    }

    $form = new Form();

    $data = $form->getTableData($tableName);

    array_shift($data);

    return json_encode($data);
  }
}
