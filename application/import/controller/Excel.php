<?php
namespace app\import\controller;

use think\Controller;
use think\Loader;
use think\Request;
use \Env;

use app\import\model\Form;
use think\Db;

header("Content-Type:text/html;charset=utf-8");


require_once Env::get('root_path') . 'extend\phpexcel\PHPExcel.php';
// require_once Env::get('root_path') . 'extend\phpexcel\PHPExcel\IOFactory.php';

@ini_set("memory_limit",'-1');

class Excel extends Controller
{
  /**
   * 给Excel表格添加样式
   * @param $objPHPExcel PHPExcel实例
   * @param $endRow 横轴最后一个位置字母
   * @param $endColumn 纵轴最后一个位置数字
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
   * @param {Array} $data 
   * [
   *  ['key'=>'value', 'key'=>'value'...], 
   *  ['key'=>'value', 'key'=>'value'...], 
   *  ...
   * ]
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

    

    $titleAxis = [];    // 记录key值row坐标
    $titleRows = $data[0];   // 获取标题栏

    $rownum = 0;
    // 按数组顺序输入表格标题
    // 并把横坐标记录$titleAxis
    $objPHPExcel->setActiveSheetIndex(0);
    foreach ($titleRows as $key => $value) {
      $cell = $excelRow[$rownum] . 1;   // 当前对应表格的坐标(横坐标 . 纵坐标)
      
      $titleAxis[$key] = $excelRow[$rownum];   // 记录key值row坐标
      $rownum++;
        
      $objPHPExcel->getActiveSheet()
        // ->setCellValue($cell, ' ' . $v);
        // 按指定格式写入数据
        ->setCellValueExplicit($cell, $value, \PHPExcel_Cell_DataType::TYPE_STRING);
    }


    // 输入表格内容↓
    // 数据key和标题key不批对不会输入内容
    for ($i=1; $i < count($data); $i++) { 
      foreach ($data[$i] as $k => $v) {
        // 数据的key和标题的key配对
        if (array_key_exists($k, $titleRows)) {
          $cell = $titleAxis[$k] . ($i + 1);     // 根据$titleAxis记录的坐标
          
          $objPHPExcel->getActiveSheet()
            // 按指定格式写入数据
            ->setCellValueExplicit($cell, $v, \PHPExcel_Cell_DataType::TYPE_STRING);
        }
      }
    }


    // 给excel表格添加样式
    $excelEndColumn = count($data);    // 表格列最终索引
    $excelEndRow = $excelRow[$selRowLeng - 1];  // 表格行最终索引
    $this->excel_add_style($objPHPExcel, $excelEndRow, $excelEndColumn);



    //设置当前的表格
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  // 转换excel
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
    if (is_dir($path)) {
      $this->delfile($path);   // 删除文件
      mkdir($path);          // 创建文件
    } else {
      mkdir($path);
    }
    clearstatcache();   // 清除is_dir()方法缓存

    $objWriter->save($path . $time . '.xls');

    $downUrl = $url . $time . '.xls';

    return $downUrl;
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
  public function msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
  }

  /**
   * 导入表
   * 根据和数据库的注释对照，自动填充数据库写好的字段
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

    // return json_encode($data);

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


    $isCaseIn = 0;   // 判断是否有"入金"字段
    // 对字段名去除首尾空格
    // 判断是否有"出入金"字段
    $isCashInOutEmpty = 1;
    foreach ($header as $key => $value) {
      $value = trim($value);
      $header[$key] = $value;

      if ($value === '入金') {
        $isCaseIn = 1;
      }

      if ($value === '出入金' || $value === '净入金') {
        $isCashInOutEmpty = 0;
      }
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
            'message' => '不存在主键 row => ' . ($key + 2),
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

    
    // 新增"出入金"字段
    if ($isCaseIn === 1 && $isCashInOutEmpty === 1) {
      foreach ($data as $key => $value) {
        $obj = [
          'comment' => '出入金',
          'value' => ''
        ];

        $cashIn = 0;
        $cashOut = 0;
        $cashInOut = 0;

        foreach ($value as $k => $v) {
          if ($v['comment'] === '入金') {
            $cashIn = str_replace(',', '', $v['value']);
            $cashIn = floatval($cashIn);
          } else if ($v['comment'] === '出金') {
            $cashOut = str_replace(',', '', $v['value']);
            $cashOut = floatval($cashOut);
          }
        }

        $cashInOut = strval($cashIn - $cashOut);
        // 查找运算结果是否包含小数
        if (!strpos($cashInOut, '.')) {
          $cashInOut = $cashInOut . '.00';
        }

        $obj['value'] = $cashInOut;
        
        array_push($data[$key], $obj);
      }
    }


    

    
    unset($info);   // 关闭指针
    try {
      $this->delfile($path);   // 删除文件
      // rmdir($filename);
    } catch(\Exception $e) {
      return json_encode([
        'code' => '0',
        'message' => '文件操作失败，请重试'
      ]);
    }

    
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

    

    $data = [
      'code' => '1',
      'message' => '导出成功',
      'data' => $req
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
   * @param page 加载的页码(10条数据为1页) 0为起始页
   * @todo 按页查找表数据（给前端展示）
   */
  public function get_table_data() 
  {
    $tableName = request()->post('table_name');
    $page = request()->post('page');


    if (!$tableName || is_null($page)) {
      return json_encode(array(
        'code' => '0',
        'message' => '获取失败',
      ));
    }


    $form = new Form();
    $start = $page * 10;

    $tableData = $form->getTableData($tableName, $start);

    $data = [
      'code' => '1',
      'message' => '获取成功',
      'pages_count' => ceil($tableData['count'] / 10),
      'data' => $tableData['data']
    ];

    return json_encode($data);
  }


  /**
   * 支持多个表，
   * 两个值之间范围查询，
   * 多个值指定查询
   * @param {Array} tableNames 导出的表
   * @param {Array} selectColumns 导出的字段和注释
   * @param {Array} selectData 导出的字段和条件
   * @todo 按照表名，根据条件合并导出excel
   */
  public function merge_export() {
    // $tableNames = ['sanli_client_funds', 'jinkong_client_funds'];
    // $selectColumns = [
    //   'unique_code' => '唯一标识码',
    //   'category' => '类别',
    //   'customer_number' => '客户号',
    //   'customer_name' => '客户名称',
    //   'deposit' => '入金',
    // ];
    // $selectData = [
    //   'unique_code' => [20201126, 20201127],   // 表示取区间范围
    //   'deposit' => [1, 500000000000],
    //   'customer_name' => ['孟万水', '李雷权', '王帅', '张洪伟'],
    // ];

    $tableNames = request()->post('table_names');
    $selectData = request()->post('select_data');
    $selectColumns = request()->post('select_columns');


    $data = [];
    $data[0] = $selectColumns;    // 加入标题栏


    try {
      $columns = '';   // sql语法筛选column
      foreach ($selectColumns as $k => $v) {
        $columns = $columns . $k . ',';
      }
      $columns = substr($columns, 0, strlen($columns)-1);
    } catch(\Exception $e) {
      // return $e;
      return json_encode([
        'code' => '0',
        'message' => '标题字段获取错误'
      ]);
    }


    // 选择的数据都是空字符不做查询筛选
    foreach ($selectData as $k => $v) {
      $isEmpty = 1;
      foreach ($v as $key => $value) {
        if (trim($value) !== '') $isEmpty = 0;
      }
      if ($isEmpty === 1) unset($selectData[$k]);
    }
    if (!isset($selectData['unique_code'])) {
      return json_encode([
        'code' => '0',
        'message' => '选择的主键内容为空'
      ]);
    }

    // 获取条件语句
    try {
      $i = 0;
      $whereExprn = 'where ';    // sql条件语句

      foreach ($selectData as $k => $v) {
        if ($k === 'unique_code') {
          // unique_code字段做特殊取值处理
          if (count($v) > 1) {
            if (intval($v[0]) > 0) {
              $v[0] = (int)$v[0];
            } else {
              $v[0] = (int)substr($v[0], count($v[0])-9, 8);
            }
            if (intval($v[1]) > 0) {
              $v[1] = (int)$v[1];
            } else {
              $v[1] = (int)substr($v[1], count($v[1])-9, 8);
            }
            $whereExprn = $whereExprn . "cast(substring($k, 6, 8) as SIGNED)>=$v[0] AND cast(substring($k, 6, 8) as SIGNED)<=$v[1]";
          } else {
            if (intval($v[0]) > 0) {
              $v[0] = (int)$v[0];
              $whereExprn = $whereExprn . "cast(substring($k, 6, 8) as SIGNED)=$v[0]";
            } else {
              $whereExprn = $whereExprn . "$k='$v[0]'";
            }
          }
        } else if ($k === 'deposit' || $k === 'withdrawal' || $k === 'deposit_and_withdrawal') {
          // 入金、出金、出入金
          // 数字区间值
          if (count($v) === 2) {
            $v[0] = (int)$v[0];
            $v[1] = (int)$v[1];
            $whereExprn = $whereExprn . "(cast($k as SIGNED) >= $v[0] AND cast($k as SIGNED) <= $v[1])";
          } else {
            $v[0] = (int)$v[0];
            $whereExprn = $whereExprn . "cast($k as SIGNED) = $v[0]";
          }
        } else {
          // 普通值，多选
          if (count($v) > 1) {
            $whereExprn = $whereExprn . '(';
            for ($j=0; $j < count($v); $j++) { 
              $whereExprn = $whereExprn . "$k = '$v[$j]'";

              if ($j != count($v) - 1) $whereExprn = $whereExprn . ' OR ';
            }
            $whereExprn = $whereExprn . ')';
          } else {
            $whereExprn = $whereExprn . "$k = '$v[0]'";
          }
        }

        if ($i !== count($selectData)-1) {
          $whereExprn = $whereExprn . ' AND ';
        }

        $i++;
      }
    } catch(\Exception $e) {
      // return $e;
      return json_encode([
        'code' => '0',
        'message' => '条件取值异常'
      ]);
    }

    // return $whereExprn;

    if (count($selectData) === 0) $whereExprn = '';

    try {
      foreach ($tableNames as $key => $value) {
        $query = "SELECT $columns FROM $value $whereExprn";
        $result = Db::query($query);
  
        $data = array_merge($data, $result);
      }
    } catch (\Exception $e) {
      // return $e;
      return json_encode([
        'code' => '0',
        'message' => '数据库查询出现异常'
      ]);
    }

    // return json_encode($data);
    if (count($data) === 1) {
      return json_encode([
        'code' => '0',
        'message' => '搜索结果为空'
      ]);
    }

    // return json_encode($data);

    // 判断是否有需要统计的字段
    $isPass = 0;
    $testToast = [
      'deposit',                 // 入金
      'withdrawal',              // 出金
      'deposit_and_withdrawal',  // 出入金
      'handling_fee',            // 手续费
      'hand_in_fee',             // 上交手续费
      'retention_fee',           // 留存手续费
      'total_profit_and_loss',   // 总盈亏
    ];
    for ($i=0; $i < count($testToast); $i++) { 
      if (isset($data[0][$testToast[$i]])) {
        $isPass = 1;
        break;
      } 
    }
    // 数据最尾部添加合计行
    if ($isPass) {
      $amount = 0;
      $totalRow = [];     // 合计行
      $userToast = [];    // 用户选中的统计字段
      $keys = array_keys($data[0]);

      // 初始化值
      foreach ($keys as $k => $v) {
        $totalRow[$v] = '#N/A';

        if ($v === 'unique_code') $totalRow[$v] = '合计';
      }
      // 核对用户有选中统计的字段
      foreach ($keys as $key => $value) {
        for ($i=0; $i < count($testToast); $i++) { 
          if ($value === $testToast[$i]) {
            array_push($userToast, $value);
            break;
          }
        }
      }


      // 合计导出数据要统计的值
      foreach ($userToast as $key) {
        $amount = 0;
        for ($i=1; $i < count($data); $i++) { 
          $amount += (int)$data[$i][$key];
        }
        $totalRow[$key] = strval($amount);
      }

      array_push($data, $totalRow);
    }

    // return json_encode($data);
    


    try {
      // 数据转化excel
      $url = $this->data_into_excel($data);
    } catch(\Exception $e) {
      return json_encode([
        'code' => '0',
        'message' => '文件操作失败请重试'
      ]);
    }

    return json_encode([
      'code' => '1',
      'message' => '导出成功',
      'url' => $url
    ]);
  }
}
