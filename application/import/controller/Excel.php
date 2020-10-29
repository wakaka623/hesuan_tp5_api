<?php
namespace app\import\controller;

use think\Controller;
use think\Loader;
use think\Request;
use \Env;

use app\import\model\Form;
use think\Db;


require_once Env::get('root_path') . 'extend\phpexcel\PHPExcel.php';
// require_once Env::get('root_path') . 'extend\phpexcel\PHPExcel\IOFactory.php';


class Excel extends Controller
{
  //获取毫秒时间戳
  function msectime(){
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
  }

  /**
   * 导入表
   */
  public function index() {
    $isPost = request()->isPost();

    if(!$isPost) return;

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
   * 下载表
   */
  public function download() {
    
    $isPost = request()->isPost();

    if (!$isPost) return;

    $selectData = request()->post('select_data');

    if (!$selectData || count($selectData) === 0) {
      return json_encode(array(
        'code' => '0',
        'message' => '未传递参数或空数组'
      ));
    }


    $selRowLeng = count($selectData[0]);
    $excelRow = array(
      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 
      'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 
      'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD',
      'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN',
      'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX',
      'AY', 'AZ'
    );

    if ($selRowLeng > count($excelRow)) {
      return json_encode(array(
        'code' => '0',
        'message' => '表格列出超出最大值'
      ));
    }


    $objPHPExcel = new \PHPExcel();

    /*右键属性所显示的信息*/
    // $objPHPExcel->getProperties()->setCreator("钧一")  //作者
    // ->setLastModifiedBy("钧一")  //最后一次保存者
    // ->setTitle('报备数据')  //标题
    // ->setSubject('数据EXCEL导出') //主题
    // ->setDescription('导出数据')  //描述
    // ->setKeywords("excel")   //标记
    // ->setCategory("result file");  //类别

    //设置单元格宽度
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    

    //设置当前的表格
    $objPHPExcel->setActiveSheetIndex(0);
    // 对表格输入内容
    foreach ($selectData as $key => $value) {
      $letterKey = 0;
      
      foreach ($value as $k => $v) {
        $cell = $excelRow[$letterKey] . ($key + 1);
        
        $objPHPExcel->getActiveSheet()
          ->setCellValue($cell, $v);

        $letterKey++;
      }

    }



    // 设置表格第一行显示内容
    // $objPHPExcel->getActiveSheet()
    //     ->setCellValue('A1', 'ID')
    //     ->setCellValue('B1', '名称');


    //设置当前的表格
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    ob_end_clean();

    
    // $filename = '报备数据.xls';
    // header('Pragma:public');

    // header('Content-Type:application/x-msexecl;name="'.$filename.'"');
    // header('Content-Disposition:inline;filename="'.$filename.'"');

    // return 'http://localhost/tp5/public/uploads/20201029/b7e7a16cdab572ea2d3e33863ec46738.xlsx';

    // 获取当前时间戳
    $time = $this->msectime();
    
    $path = Env::get('root_path') . 'public\uploads\\' . $time . '.xls';   // 保存路径

    $objWriter->save($path);

    return 'http://localhost/tp5/public/uploads/' . $time . '.xls';
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
