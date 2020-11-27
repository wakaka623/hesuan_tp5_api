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

@ini_set("memory_limit",'-1');

class Excel extends Controller
{
  /**
   * 给Excel表格添加样式
   * @method
   */
  private function excel_add_style($objPHPExcel, $endRow, $endColumn) {
    $tableEndIndex = $endRow . $endColumn;  // 表格终端的索引位

    // 标题栏加粗
    $objPHPExcel
      ->getActiveSheet()
      ->getStyle('A1:' . $endRow . '1')
      ->getFont()
      ->setBold(true); //字体加粗



    // 设置所有单元格宽度
    // 1个中文 宽度 -> 3
    // 一个数字、字母 宽度 -> 1

    // 所有单元格添加边框
    $styleThinBlackBorderOutline = array(
      'borders' => array(
        'allborders' => array(   //设置全部边框
          'style' => \PHPExcel_Style_Border::BORDER_THIN  //粗的是thick
        ),
      ),
    );
    $objPHPExcel->getActiveSheet()->getStyle('A1:' . $tableEndIndex)->applyFromArray($styleThinBlackBorderOutline);

    // 所有单元格居中
    $objPHPExcel
      ->getActiveSheet()
      ->getStyle('A1:' . $tableEndIndex)
      ->getAlignment()
      ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
      ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    // 设置所有字体
    $objPHPExcel
      ->getActiveSheet()
      ->getStyle('A1:' . $tableEndIndex)
      ->getFont()
      ->setSize(9)
      ->setName('宋体');
  }

  /**
   * 数据转excel
   * @method
   */
  private function data_into_excel($data)
  {
    $selRowLeng = count($data[0]);
    $excelRow = array(     // 表格标题栏坐标
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

    

    //设置当前的表格
    $objPHPExcel->setActiveSheetIndex(0);
    // 把数据转化对应表内容
    foreach ($data as $key => $value) {
      $letterKey = 0;
      
      foreach ($value as $k => $v) {
        $cell = $excelRow[$letterKey] . ($key + 1);
        
        $objPHPExcel->getActiveSheet()
          // ->setCellValue($cell, ' ' . $v);
          // 按指定格式写入数据
          ->setCellValueExplicit($cell, $v, \PHPExcel_Cell_DataType::TYPE_STRING);

        $letterKey++;
      }

    }

    // 给excel表格添加样式
    $excelEndColumn = count($data);    // 表格列最终索引
    $excelEndRow = $excelRow[$selRowLeng - 1];  // 表格行最终索引
    $this->excel_add_style($objPHPExcel, $excelEndRow, $excelEndColumn);

      


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

    $path = Env::get('root_path') . 'public\uploads\\';   // 保存路径
    $url = request()->domain() . '/tp5/public/uploads/';   // 访问路径

    // 查找文件夹，如果文件夹不存在创建改目录
    if (!is_dir($path)) {
      mkdir($path);
    } else {
      $this->delfile($path);   // 删除文件
      mkdir($path);          // 创建文件
    }
    clearstatcache();   // 清除is_dir()方法缓存
    

    $objWriter->save($path . $time . '.xls');

    return [
      'url' => $url,
      'time' => $time
    ];
  }

  /**
   * 删除指定文件
   * @method
   */
  public function delfile($dir) {
    // $dir = Env::get('root_path') . 'public\uploads\\';

    //先删除目录下的文件：
    $dh = opendir($dir);

    while ($file = readdir($dh)) {
      if($file != "." && $file != "..") {
        $fullpath = $dir."/".$file;
        if(!is_dir($fullpath)) {
          unlink($fullpath);
        } else {
          $this->delfile($fullpath);
        }
      }
    }
  
    closedir($dh);

    //删除当前文件夹：
    if(rmdir($dir)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * 获取时间戳毫秒单位
   * @method
   */
  public function msectime(){
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
    $dbName = request()->post('library');

    if (!$file || !$dbName) {
      return json_encode(array(
        'code' => '0',
        'message' => '0'
      ));
    }


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

    $form = new Form();

    // 拿出标题栏
    $header = array_shift($data);
    $endHead = count($header);

    // 判断标题中是否有主键
    if ($header[0] !== '唯一标识码') {
      return json_encode([
        'code' => '0',
        'message' => '缺少主键key'
      ]);
    }


    // 删除标题最尾部null值
    while ($header[$endHead-1] === null) {
      array_pop($header);
      $endHead = count($header);
    }
    // 删除数据最底部空白行
    $repeatDelEnd = 1;
    while ($repeatDelEnd === 1) {
      $empty = 1;
      foreach ($data[count($data)-1] as $key => $value) {
        if ($value !== NULL) {
          $empty = 0;
          break;
        }
      }

      if ($empty) {
        array_pop($data);
      } else {
        $repeatDelEnd = 0;
      }
    }
    // 删除数据每行最后一个或多个null值
    while($data[0][count($data[0])-1] === NULL) {
      foreach ($data as $key => $value) {
        $endData = count($data[$key])-1;
  
        if ($data[$key][$endData] === NULL) {
          array_pop($data[$key]);
        }
      }
    }
    


    
    // 对数组数据进行对象化
    // [comment: "客户名称"value: "应吉跃"]
    $delEmptyRows = [];   // 记录空白行key集合
    $delTotalRows = [];   // 记录合计行key集合
    foreach ($data as $key => $value) {
      $isValEmpty = 1;     // 判断空白行
      $isTotalRow = 0;     // 判断合计行

      foreach ($value as $k => $v) {
        $obj = array(
          'comment' => $header[$k],
          'value' => $v
        );

        // 判断是否有主键
        if ($k === 0 && $obj['comment'] === '唯一标识码' && !$obj['value']) {
          return json_encode([
            'code' => '0',
            'message' => '不存在主键 => ' . ($key + 2),
            'data' => $data
          ]);
        }

        // 判断空白行
        if ($isValEmpty && $k !== 0 && $v !== NULL && $v !== '#N/A') {
          $isValEmpty = 0;
        }


        // 判断是否有重复标题行
        if ($v === $header[$k] || ($k === 0 && $v !== NULL && strlen($v) > 48 && preg_match('/[\x{4e00}-\x{9fa5}]/u', $v) > 0)) {
          return json_encode([
            'code' => '0',
            'message' => '存在重复标题栏 => ' . ($key + 2),
            'data' => $data
          ]);
        }


        // 判断'合计'行
        if (!$isTotalRow && $k !== 0 && ($v === '小计' || $v === '总计' || $v === '合计')) {
          $isTotalRow = 1;
        }
        
        $data[$key][$k] = $obj;
      }

      // 保存'合计'行key
      if ($isTotalRow) {
        array_unshift($delTotalRows, $key);
      }

      // 保存空白行key
      if ($isValEmpty) {
        array_unshift($delEmptyRows, $key);
      }

    }


    // 删除空白行
    if (count($delEmptyRows) > 0) {
      foreach ($delEmptyRows as $key => $value) {
        array_splice($data, $value, 1);
      }
    }

    // 删除'合计'行
    if (count($isTotalRow) > 0) {
      foreach ($delTotalRows as $key => $value) {
        array_splice($data, $value, 1);
      }
    }


    // return json_encode($data);


    

    
    unset($info);   // 关闭指针
    $this->delfile($path);   // 删除文件
    // rmdir($filename);

    
    $importInfo = $form->import($dbName, $data);

    if ($importInfo['code'] === '0') {
      return json_encode([
        'code' => '0',
        'message' => $importInfo['message']
      ]);
    }

    return json_encode([
      'code' => '1',
      'message' => '导入成功',
      'count' => $importInfo['num']
    ]);

  }

  /**
   * 导出数据
   * 下载数据（前端选定数据，转化excel）
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


    $req = $this->data_into_excel($selectData);
    $url = $req['url'];
    $time = $req['time'];

    

    $data = [
      'code' => '1',
      'message' => '导出成功',
      'data' => $url . $time . '.xls'
    ];

    return json_encode($data);
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
   * 获取表数据
   * @param table_name 数据库表名
   * @param page_num   加载的页码(10条数据为1页)
   * @todo 按页查找表数据
   */
  public function get_table_data() 
  {
    $tableName = request()->post('table_name');
    $page = request()->post('page');


    if (!$tableName || !$page) {
      return array(
        'code' => '0',
        'message' => '获取失败',
      );
    }

    $form = new Form();

    $tableData = $form->getTableData($tableName);

    // 0-9     1
    // 10-19   2
    // 20-29   3
    // 30-39   4
    // [(x - 1) * 10]   [x * 10 - 1]
    // 根据页码选择对应范围数据
    $staIndex = ($page - 1) * 10;
    $endIndex = $page * 10 - 1;
    $pagesCount = ceil(count($tableData) / 10);   // 数据按照10条分一页后的总页码数

    $data = [
      'code' => '1',
      'message' => '获取成功',
      'pages_count' => $pagesCount,
      'data' => array_slice($tableData, $staIndex, 10)
    ];

    return json_encode($data);
  }


  /**
   * 搜索关键字
   */
  public function search_key_value() {
    $val = request()->post('val');
    $tableName = request()->post('table_name');

    if (!$tableName || !$val) {
      return json_encode([
        'code' => '0',
        'message' => '获取失败'
      ]);
    }
    $keys = array_keys($val);
    $value=array_values($val);
    $a=$keys[0];
    $b=$value[0];
    $where=[
          [$keys[0],$value[0]]
  ];
      $data = Db::name($tableName)->whereLike($a,$b."%")->field($a)->Distinct(true)->select();
     
      return json_encode($data);
  }


  /**
   * 条件导出数据
   */
  public function get_choice_data() 
  {
    $tableName = request()->post('table_name');
    $condition = request()->post('condition');

    if (!$tableName || !is_array($condition) || count($condition) === 0) {
      $data = [
        'code' => '0',
        'message' => '导出失败'
      ];
      return json_encode($data);
    }


    $data = [];

    foreach ($condition as $key => $value) {
      $data = Db::name($tableName)->where($key,'=',$value)->select();
    }

    $into = $this->data_into_excel($data);
    $url = $into['url'];
    $time = $into['time'];

    return json_encode([
      'code' => '1',
      'message' => '导出成功',
      'url' => $url . $time . '.xls'
    ]);
  }
  
}
