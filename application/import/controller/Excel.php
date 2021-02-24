<?php
namespace app\import\controller;

use think\Controller;
use think\Loader;
use think\Request;
use \Env;

use app\import\model\Form;
use think\Db;

header("Content-Type:text/html;charset=utf-8");


require_once '../extend/phpexcel/PHPExcel.php';
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
      'AY', 'AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN'
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
//    $path = Env::get('root_path') . 'public/uploads';   // 保存路径
    $path = Env::get('root_path') . 'public/uploads';   // 保存路径
    $url = request()->domain() . '/uploads/';   // 访问路径

  
    // 查找文件夹，如果文件夹不存在创建改目录
    if (is_dir($path)) {
      $this->delfile($path);   // 删除文件夹
      mkdir($path);          // 创建文件夹
    } else {
      mkdir($path);
    }
    clearstatcache();   // 清除is_dir()方法缓存
    $objWriter->save($path."/". $time . '.xls');
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
    $sheet = $PHPExcel->getActiveSheet(0);// 获得sheet
    $highestRow = $sheet->getHighestRow();    // 取得共有数据数
    $data = $sheet->toArray();

    $form = new Form();

//    return json_encode($data);
//    exit;

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

//     return json_encode($data);
    // 对数组数据进行对象化
    // [comment: "客户名称"value: "应吉跃"]
    $delEmptyRows = [];   // 记录空白行key集合
    $delTotalRows = [];   // 记录合计行key集合
    foreach ($data as $key => $value) {
      $isValEmpty = 1;     // 判断空白行
      $isTotalRow = 0;     // 判断合计
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
//        return json_encode($data);
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

//     return json_encode($data);

    
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
     * 创建(导出)Excel数据表格
     * @param  array   $list        要导出的数组格式的数据
     * @param  string  $filename    导出的Excel表格数据表的文件名
     * @param  array   $indexKey    $list数组中与Excel表格表头$header中每个项目对应的字段的名字(key值)
     * @param  array   $startRow    第一条数据在Excel表格中起始行
     * @param  [bool]  $excel2007   是否生成Excel2007(.xlsx)以上兼容的数据表
     * 比如: $indexKey与$list数组对应关系如下:
     *     $indexKey = array('id','username','sex','age');
     *     $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24));
     */
//    function exportExcel($list,$filename,$indexKey,$startRow=1,$excel2007=false){
    function exportExcel($startRow=1,$excel2007=false){
        //文件引入
        require_once '../extend/phpexcel/PHPExcel.php';
        require_once '../extend/phpexcel/PHPExcel/Writer/Excel2007.php';
        $isadmin=1;
        $group='';
        // 获取当前时间戳

//        $filename=$time;
        $tableName = request()->post('table_names');
        $startDate=trim(request()->post('startDate'));
        $endtDate=trim(request()->post('endDate'));
        if($startDate&&$endtDate){
            $startDate1=strtotime($startDate);
            $endtDate1=strtotime($endtDate);
            $startDate2=date('Ymd', $startDate1);
            $endtDate2=date('Ymd', $endtDate1);
        }
        $form=new Form();
        $Key=$form->getColumns($tableName,$group,$isadmin);
        $indexKey=array_keys($Key);
//        return var_dump($Key);
        if(empty($filename)) $filename = time();
        if( !is_array($indexKey)) return false;

        $header_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA', 'AB', 'AC', 'AD',
            'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN',
            'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX',
            'AY', 'AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN');
        //初始化PHPExcel()
        $objPHPExcel = new \PHPExcel();

        //设置保存版本格式
        if($excel2007){
            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $filename = $filename.'.xlsx';
        }else{
            $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
            $filename = $filename.'.xls';
        }

        //接下来就是写数据到表格里面去
        $objActSheet = $objPHPExcel->getActiveSheet();
        //$startRow = 1;
        $list1=$form->getTableDate($tableName,$startDate2,$endtDate2);
        $list=array_merge(array($Key),$list1);
        foreach ($list as $row) {
            foreach ($indexKey as $key => $value){
                //这里是设置单元格的内容
                $objActSheet->setCellValue($header_arr[$key].$startRow,$row[$value]);
            }
            $startRow++;
        }
        $time = $this->msectime();
        $path = Env::get('root_path') . 'public/uploads';   // 保存路径
        $url = request()->domain() . '/uploads/';   // 访问路径
        // 下载这个表格，在浏览器输出
//        header("Pragma: public");
//        header("Expires: 0");
//        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
//        header("Content-Type:application/force-download");
//        header("Content-Type:application/vnd.ms-execl");
//        header("Content-Type:application/octet-stream");
//        header("Content-Type:application/download");;
//        header('Content-Disposition:attachment;filename='.$filename.'');
//        header("Content-Transfer-Encoding:binary");
        $objWriter->save($path."/". $time . '.xls');
        $downUrl = $url . $time . '.xls';
        return $downUrl;
    }

    /**
     * @param $tableName
     * 删除表数据
     */
    public function deleteTableDate() {
        $tableName = request()->post('table_names');
        $startDate=trim(request()->post('startDate'));
        $endtDate=trim(request()->post('endDate'));
        if($startDate&&$endtDate){
            $startDate1=strtotime($startDate);
            $endtDate1=strtotime($endtDate);
            $startDate2=date('Ymd', $startDate1);
            $endtDate2=date('Ymd', $endtDate1);
        }
        if ($tableName=='jinkong_chengjiaobiao'||$tableName=='hengyin_client_funds'||$tableName=='hengyin_transaction'||$tableName=='sanli_client_funds'||$tableName=='sanli_transaction'||$tableName=='ruida_client_funds'||$tableName=='ruida_transaction'){
            $where="cast(substring(unique_code, 6, 8) as SIGNED)>=$startDate2  AND cast(substring(unique_code, 6, 8) as SIGNED)<=$endtDate2";
        }elseif ($tableName=='ruida_deposit_and_withdrawal'||$tableName=='huaxin_deposit_and_withdrawal'||$tableName=='jinkong_deposit_and_withdrawal'){
            $where="cast(substring(unique_code, 7, 8) as SIGNED)>=$startDate2  AND cast(substring(unique_code, 7, 8) as SIGNED)<=$endtDate2";
        }else{
            $where="cast(substring(unique_code, 8, 8) as SIGNED)>=$startDate2  AND cast(substring(unique_code, 8, 8) as SIGNED)<=$endtDate2";
        }
        $result=Db::name($tableName)
            ->where($where)
            ->delete();
        if($result==0){
            $error=array(
                'code'=>'0',
                'message'=>'删除失败了，在试试呗!'
            );
            return json_encode($error);
        }else{
            $success=array(
                'code'=>'1',
                'message'=>'操作成功',
                'number'=>$result
            );
            return json_encode($success);
        }
    }

  /**
   * 查找表字段
   */
  public function columns() {
    $isPost = request()->isPost();

    if (!$isPost) return;

    $tableName = request()->post('table_name');
    $group = request()->post('group');
    $isadmin = request()->post('isadmin');

    if (!$tableName) {
      return array(
        'code' => '0',
        'message' => '',
      );
    }

    $form = new Form();

    $data = $form->getColumns($tableName,$group,$isadmin);
    
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
    $group=request()->post('group');
    $isadmin=request()->post('isadmin');
    $startDate=trim(request()->post('startDate'));
    $endtDate=trim(request()->post('endDate'));
    $account=trim(request()->post('account'));
    $customerame=trim(request()->post('customerName'));
    $searchGroup=trim(request()->post('searchGroup'));
    $sortField=trim(request()->post('sortField'));
    $sortType=request()->post('sortType');

    if($startDate&&$endtDate){
        $startDate1=strtotime($startDate);
        $endtDate1=strtotime($endtDate);
        $startDate2=date('Ymd', $startDate1);
        $endtDate2=date('Ymd', $endtDate1);
    }

//    var_dump($endtDate);

    if (!$tableName || is_null($page)) {
      return json_encode(array(
        'code' => '0',
        'message' => '获取失败',
      ));
    }


    $form = new Form();
    $start = $page * 30;

    $tableData = $form->getTableData($tableName, $start,$group,$isadmin,$startDate2,$endtDate2,$account,$customerame,$searchGroup,$sortField,$sortType);


    $data=$tableData['data'];
    $page_collect=array();
    $count_collect=array();
    if ($tableName=='hengyin_client_funds'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['equity_at_the_beginning_of_the_period']=round($value['equity_at_the_beginning_of_the_period']+$page_collect['equity_at_the_beginning_of_the_period'],2);//初期权益
            $page_collect['total_profit_and_loss']=round($value['total_profit_and_loss']+$page_collect['total_profit_and_loss'],2);//盈亏总额
            $page_collect['handling_fee']=round($value['handling_fee']+$page_collect['handling_fee'],2);//手续费
            $page_collect['deposit']=round($value['deposit']+ $page_collect['deposit'],2);//入金
            $page_collect['withdrawal']=round($value['withdrawal']+$page_collect['withdrawal'],2);//出金
            $page_collect['deposit_and_withdrawal']=round($value['deposit_and_withdrawal']+$page_collect['deposit_and_withdrawal'],2);//出入金
            $page_collect['available_at_the_end_of_the_term']=round($value['available_at_the_end_of_the_term']+$page_collect['available_at_the_end_of_the_term'],2);//期末可用
            $page_collect['bond']=round( $page_collect['bond']+$value['bond'],2);//保证金
            $page_collect['ending_equity']=round($page_collect['ending_equity']+$value['ending_equity'],2);//资金权益
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['equity_at_the_beginning_of_the_period']=round($collect['sum(equity_at_the_beginning_of_the_period)']+$count_collect['equity_at_the_beginning_of_the_period'],2);//初期权益
        $count_collect['total_profit_and_loss']=round($collect['sum(total_profit_and_loss)']+$count_collect['total_profit_and_loss'],2);//盈亏总额
        $count_collect['handling_fee']=round($collect['sum(handling_fee)']+ $count_collect['handling_fee'],2);//手续费
        $count_collect['deposit']=round($collect['sum(deposit)']+$count_collect['deposit'],2);//入金
        $count_collect['withdrawal']=round($collect['sum(withdrawal)']+$count_collect['withdrawal'],2);//出金
        $count_collect['deposit_and_withdrawal']=round($collect['sum(deposit_and_withdrawal)']+$count_collect['deposit_and_withdrawal'],2);//出入金
        $count_collect['available_at_the_end_of_the_term']=round($collect['sum(available_at_the_end_of_the_term)']+$count_collect['available_at_the_end_of_the_term'],2);//期末可用
        $count_collect['bond']=round($collect['sum(bond)']+$count_collect['bond'],2);//保证金
        $count_collect['ending_equity']=round($collect['sum(ending_equity)']+$count_collect['ending_equity'],2);//资金权益
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='hengyin_transaction'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['customer_service_charge']=round($value['customer_service_charge']+$page_collect['customer_service_charge'],2);//客户手续费
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);//平仓盈亏
            $page_collect['transaction_amount']=round($value['transaction_amount']+$page_collect['transaction_amount'],2);//成交金额
            $page_collect['number_of_transactions']=round($value['number_of_transactions']+$page_collect['number_of_transactions'],2);//成交手数
            $page_collect['today_hand_count']=round($value['today_hand_count']+$page_collect['today_hand_count'],2);//平今手数
            $page_collect['current_service_charge']=round($value['current_service_charge']+$page_collect['current_service_charge'],2);//平今手续费
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['customer_service_charge']=round($collect['sum(customer_service_charge)']+$count_collect['customer_service_charge'],2);//客户手续费
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);//平仓盈亏
        $count_collect['transaction_amount']=round($collect['sum(transaction_amount)']+$count_collect['transaction_amount'],2);//成交金额
        $count_collect['number_of_transactions']=round($collect['sum(number_of_transactions)']+$count_collect['number_of_transactions'],2);//成交手数
        $count_collect['today_hand_count']=round($collect['sum(today_hand_count)']+ $count_collect['today_hand_count'],2);//平今手数
        $count_collect['current_service_charge']=round($collect['sum(current_service_charge)']+$count_collect['current_service_charge'],2);//平今手续费
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='sanli_client_funds'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['equity_at_the_beginning_of_the_period']=round($value['equity_at_the_beginning_of_the_period']+$page_collect['equity_at_the_beginning_of_the_period'],2);
            $page_collect['deposit']=round($value['deposit']+$page_collect['deposit'],2);
            $page_collect['withdrawal']=round($value['withdrawal']+$page_collect['withdrawal'],2);
            $page_collect['deposit_and_withdrawal']=round($value['deposit_and_withdrawal']+$page_collect['deposit_and_withdrawal'],2);
            $page_collect['total_profit_and_loss']=round($value['total_profit_and_loss']+$page_collect['total_profit_and_loss'],2);
            $page_collect['net_profit_and_loss']=round($value['net_profit_and_loss']+$page_collect['net_profit_and_loss'],2);
            $page_collect['handling_fee']=round($value['handling_fee']+$page_collect['handling_fee'],2);
            $page_collect['hand_in_fee']=round($value['hand_in_fee']+$page_collect['hand_in_fee'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['ending_equity']=round($value['ending_equity']+$page_collect['ending_equity'],2);
            $page_collect['bond']=round($value['bond']+$page_collect['bond'],2);
            $page_collect['available_at_the_end_of_the_term']=round($value['available_at_the_end_of_the_term']+$page_collect['available_at_the_end_of_the_term'],2);
            $page_collect['average_daily_equity']=round($value['average_daily_equity']+$page_collect['average_daily_equity'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['equity_at_the_beginning_of_the_period']=round($collect['sum(equity_at_the_beginning_of_the_period)']+$count_collect['equity_at_the_beginning_of_the_period'],2);
        $count_collect['deposit']=round($collect['sum(deposit)']+$count_collect['deposit'],2);
        $count_collect['withdrawal']=round($collect['sum(withdrawal)']+$count_collect['withdrawal'],2);
        $count_collect['deposit_and_withdrawal']=round($collect['sum(deposit_and_withdrawal)']+$count_collect['deposit_and_withdrawal'],2);
        $count_collect['total_profit_and_loss']=round($collect['sum(total_profit_and_loss)']+ $count_collect['total_profit_and_loss'],2);
        $count_collect['net_profit_and_loss']=round($collect['sum(net_profit_and_loss)']+$count_collect['net_profit_and_loss'],2);
        $count_collect['handling_fee']=round($collect['sum(handling_fee)']+$count_collect['handling_fee'],2);
        $count_collect['hand_in_fee']=round($collect['sum(hand_in_fee)']+$count_collect['hand_in_fee'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['ending_equity']=round($collect['sum(ending_equity)']+$count_collect['ending_equity'],2);
        $count_collect['bond']=round($collect['sum(bond)']+$count_collect['bond'],2);
        $count_collect['available_at_the_end_of_the_term']=round($collect['sum(available_at_the_end_of_the_term)']+$count_collect['available_at_the_end_of_the_term'],2);
        $count_collect['average_daily_equity']=round($collect['sum(average_daily_equity)']+$count_collect['average_daily_equity'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='sanli_transaction'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['service_charge']=round($value['service_charge']+$page_collect['service_charge'],2);
            $page_collect['handing_in_service_charge']=round($value['handing_in_service_charge']+$page_collect['handing_in_service_charge'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['transaction_price']=round($value['transaction_price']+$page_collect['transaction_price'],2);
            $page_collect['transaction_amount']=round($value['transaction_amount']+$page_collect['transaction_amount'],2);
            $page_collect['option_exercise_opening_amount']=round($value['option_exercise_opening_amount']+$page_collect['option_exercise_opening_amount'],2);
            $page_collect['hand_count']=round($value['hand_count']+$page_collect['hand_count'],2);
            $page_collect['number_of_options_executed']=round($value['number_of_options_executed']+$page_collect['number_of_options_executed'],2);
            $page_collect['number_of_abandoned_options']=round($value['number_of_abandoned_options']+$page_collect['number_of_abandoned_options'],2);
            $page_collect['number_of_open_positions_under_option_execution']=round($value['number_of_open_positions_under_option_execution']+$page_collect['number_of_open_positions_under_option_execution'],2);
            $page_collect['today_hand_count']=round($value['today_hand_count']+$page_collect['today_hand_count'],2);
            $page_collect['current_service_charge']=round($value['current_service_charge']+$page_collect['current_service_charge'],2);
            $page_collect['service_charge_handed_in_at_present']=round($value['service_charge_handed_in_at_present']+$page_collect['service_charge_handed_in_at_present'],2);
            $page_collect['floating_profit_and_loss_of_option_closing']=round($value['floating_profit_and_loss_of_option_closing']+$page_collect['floating_profit_and_loss_of_option_closing'],2);
            $page_collect['income_royalty']=round($value['income_royalty']+$page_collect['income_royalty'],2);
            $page_collect['payment_royalty']=round($value['payment_royalty']+$page_collect['payment_royalty'],2);
            $page_collect['royalty']=round($value['royalty']+$page_collect['royalty'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['service_charge']=round($collect['sum(service_charge)']+$count_collect['service_charge'],2);
        $count_collect['handing_in_service_charge']=round($collect['sum(handing_in_service_charge)']+$count_collect['handing_in_service_charge'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['transaction_price']=round($collect['sum(transaction_price)']+$count_collect['transaction_price'],2);
        $count_collect['transaction_amount']=round($collect['sum(transaction_amount)']+ $count_collect['transaction_amount'],2);
        $count_collect['option_exercise_opening_amount']=round($collect['sum(option_exercise_opening_amount)']+$count_collect['option_exercise_opening_amount'],2);
        $count_collect['hand_count']=round($collect['sum(hand_count)']+$count_collect['hand_count'],2);
        $count_collect['number_of_options_executed']=round($collect['sum(number_of_options_executed)']+$count_collect['number_of_options_executed'],2);
        $count_collect['number_of_abandoned_options']=round($collect['sum(number_of_abandoned_options)']+$count_collect['number_of_abandoned_options'],2);
        $count_collect['number_of_open_positions_under_option_execution']=round($collect['sum(number_of_open_positions_under_option_execution)']+$count_collect['number_of_open_positions_under_option_execution'],2);
        $count_collect['today_hand_count']=round($collect['sum(today_hand_count)']+$count_collect['today_hand_count'],2);
        $count_collect['current_service_charge']=round($collect['sum(current_service_charge)']+$count_collect['current_service_charge'],2);
        $count_collect['service_charge_handed_in_at_present']=round($collect['sum(service_charge_handed_in_at_present)']+$count_collect['service_charge_handed_in_at_present'],2);
        $count_collect['floating_profit_and_loss_of_option_closing']=round($collect['sum(floating_profit_and_loss_of_option_closing)']+$count_collect['floating_profit_and_loss_of_option_closing'],2);
        $count_collect['income_royalty']=round($collect['sum(income_royalty)']+$count_collect['income_royalty'],2);
        $count_collect['payment_royalty']=round($collect['sum(payment_royalty)']+$count_collect['payment_royalty'],2);
        $count_collect['royalty']=round($collect['sum(royalty)']+$count_collect['royalty'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='ruida_client_funds'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['deposit']=round($value['deposit']+$page_collect['deposit'],2);
            $page_collect['withdrawal']=round($value['withdrawal']+$page_collect['withdrawal'],2);
            $page_collect['deposit_and_withdrawal']=round($value['deposit_and_withdrawal']+$page_collect['deposit_and_withdrawal'],2);
            $page_collect['handling_fee']=round($value['handling_fee']+$page_collect['handling_fee'],2);
            $page_collect['total_profit_and_loss']=round($value['total_profit_and_loss']+$page_collect['total_profit_and_loss'],2);
            $page_collect['ending_equity']=round($value['ending_equity']+$page_collect['ending_equity'],2);
            $page_collect['available_funds']=round($value['available_funds']+$page_collect['available_funds'],2);
            $page_collect['customer_margin']=round($value['customer_margin']+$page_collect['customer_margin'],2);
            $page_collect['balance_today']=round($value['balance_today']+$page_collect['balance_today'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['closing_profit_and_loss']=round($value['closing_profit_and_loss']+$page_collect['closing_profit_and_loss'],2);
            $page_collect['floating_profit_and_loss']=round($value['floating_profit_and_loss']+$page_collect['floating_profit_and_loss'],2);
            $page_collect['balance_of_last_day']=round($value['balance_of_last_day']+$page_collect['balance_of_last_day'],2);
            $page_collect['hand_in_fee']=round($value['hand_in_fee']+$page_collect['hand_in_fee'],2);
            $page_collect['net_handling_charge']=round($value['net_handling_charge']+$page_collect['net_handling_charge'],2);
            $page_collect['returning_a_servant']=round($value['returning_a_servant']+$page_collect['returning_a_servant'],2);
            $page_collect['exchange_margin']=round($value['exchange_margin']+$page_collect['exchange_margin'],2);
            $page_collect['delivery_margin']=round($value['delivery_margin']+$page_collect['delivery_margin'],2);
            $page_collect['exchange_settlement_margin']=round($value['exchange_settlement_margin']+$page_collect['exchange_settlement_margin'],2);
            $page_collect['margin_call']=round($value['margin_call']+$page_collect['margin_call'],2);
            $page_collect['money_pledge_amount']=round($value['money_pledge_amount']+$page_collect['money_pledge_amount'],2);
            $page_collect['foreign_exchange_in']=round($value['foreign_exchange_in']+$page_collect['foreign_exchange_in'],2);
            $page_collect['foreign_exchange']=round($value['foreign_exchange']+$page_collect['foreign_exchange'],2);
            $page_collect['pledge_amount']=round($value['pledge_amount']+$page_collect['pledge_amount'],2);
            $page_collect['execution_fee']=round($value['execution_fee']+$page_collect['execution_fee'],2);
            $page_collect['hand_in_the_execution_fee']=round($value['hand_in_the_execution_fee']+$page_collect['hand_in_the_execution_fee'],2);
            $page_collect['royalty_income_and_expenditure']=round($value['royalty_income_and_expenditure']+$page_collect['royalty_income_and_expenditure'],2);
            $page_collect['operating_profit_and_loss']=round($value['operating_profit_and_loss']+$page_collect['operating_profit_and_loss'],2);
            $page_collect['market_value_of_options']=round($value['market_value_of_options']+$page_collect['market_value_of_options'],2);
            $page_collect['customer_market_value_equity']=round($value['customer_market_value_equity']+$page_collect['customer_market_value_equity'],2);
            $page_collect['delivery_commission']=round($value['delivery_commission']+$page_collect['delivery_commission'],2);
            $page_collect['delivery_service_charge']=round($value['delivery_service_charge']+$page_collect['delivery_service_charge'],2);
            $page_collect['total_freeze']=round($value['total_freeze']+$page_collect['total_freeze'],2);
            $page_collect['value_added_tax']=round($value['value_added_tax']+$page_collect['value_added_tax'],2);
            $page_collect['funds_available_to_exchange_level_clients']=round($value['funds_available_to_exchange_level_clients']+$page_collect['funds_available_to_exchange_level_clients'],2);
            $page_collect['frozen_amount_of_currency_pledge']=round($value['frozen_amount_of_currency_pledge']+$page_collect['frozen_amount_of_currency_pledge'],2);
            $page_collect['risk_freezing_funds_the_next_day']=round($value['risk_freezing_funds_the_next_day']+$page_collect['risk_freezing_funds_the_next_day'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['deposit']=round($collect['sum(deposit)']+$count_collect['deposit'],2);
        $count_collect['withdrawal']=round($collect['sum(withdrawal)']+$count_collect['withdrawal'],2);
        $count_collect['deposit_and_withdrawal']=round($collect['sum(deposit_and_withdrawal)']+$count_collect['deposit_and_withdrawal'],2);
        $count_collect['handling_fee']=round($collect['sum(handling_fee)']+$count_collect['handling_fee'],2);
        $count_collect['total_profit_and_loss']=round($collect['sum(total_profit_and_loss)']+ $count_collect['total_profit_and_loss'],2);
        $count_collect['ending_equity']=round($collect['sum(ending_equity)']+$count_collect['ending_equity'],2);
        $count_collect['available_funds']=round($collect['sum(available_funds)']+$count_collect['available_funds'],2);
        $count_collect['customer_margin']=round($collect['sum(customer_margin)']+$count_collect['customer_margin'],2);
        $count_collect['balance_today']=round($collect['sum(balance_today)']+$count_collect['balance_today'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['closing_profit_and_loss']=round($collect['sum(closing_profit_and_loss)']+$count_collect['closing_profit_and_loss'],2);
        $count_collect['floating_profit_and_loss']=round($collect['sum(floating_profit_and_loss)']+$count_collect['floating_profit_and_loss'],2);
        $count_collect['balance_of_last_day']=round($collect['sum(balance_of_last_day)']+$count_collect['balance_of_last_day'],2);
        $count_collect['hand_in_fee']=round($collect['sum(hand_in_fee)']+$count_collect['hand_in_fee'],2);
        $count_collect['net_handling_charge']=round($collect['sum(net_handling_charge)']+$count_collect['net_handling_charge'],2);
        $count_collect['returning_a_servant']=round($collect['sum(returning_a_servant)']+$count_collect['returning_a_servant'],2);
        $count_collect['exchange_margin']=round($collect['sum(exchange_margin)']+$count_collect['exchange_margin'],2);
        $count_collect['delivery_margin']=round($collect['sum(delivery_margin)']+$count_collect['delivery_margin'],2);
        $count_collect['exchange_settlement_margin']=round($collect['sum(exchange_settlement_margin)']+$count_collect['exchange_settlement_margin'],2);
        $count_collect['margin_call']=round($collect['sum(margin_call)']+$count_collect['margin_call'],2);
        $count_collect['money_pledge_amount']=round($collect['sum(money_pledge_amount)']+$count_collect['money_pledge_amount'],2);
        $count_collect['foreign_exchange_in']=round($collect['sum(foreign_exchange_in)']+$count_collect['foreign_exchange_in'],2);
        $count_collect['foreign_exchange']=round($collect['sum(foreign_exchange)']+$count_collect['foreign_exchange'],2);
        $count_collect['pledge_amount']=round($collect['sum(pledge_amount)']+$count_collect['pledge_amount'],2);
        $count_collect['execution_fee']=round($collect['sum(execution_fee)']+$count_collect['execution_fee'],2);
        $count_collect['hand_in_the_execution_fee']=round($collect['sum(hand_in_the_execution_fee)']+$count_collect['hand_in_the_execution_fee'],2);
        $count_collect['royalty_income_and_expenditure']=round($collect['sum(royalty_income_and_expenditure)']+$count_collect['royalty_income_and_expenditure'],2);
        $count_collect['operating_profit_and_loss']=round($collect['sum(operating_profit_and_loss)']+$count_collect['operating_profit_and_loss'],2);
        $count_collect['market_value_of_options']=round($collect['sum(market_value_of_options)']+$count_collect['market_value_of_options'],2);
        $count_collect['customer_market_value_equity']=round($collect['sum(customer_market_value_equity)']+$count_collect['customer_market_value_equity'],2);
        $count_collect['delivery_commission']=round($collect['sum(delivery_commission)']+$count_collect['delivery_commission'],2);
        $count_collect['delivery_service_charge']=round($collect['sum(delivery_service_charge)']+$count_collect['delivery_service_charge'],2);
        $count_collect['total_freeze']=round($collect['sum(total_freeze)']+$count_collect['total_freeze'],2);
        $count_collect['value_added_tax']=round($collect['sum(value_added_tax)']+$count_collect['value_added_tax'],2);
        $count_collect['funds_available_to_exchange_level_clients']=round($collect['sum(funds_available_to_exchange_level_clients)']+$count_collect['funds_available_to_exchange_level_clients'],2);
        $count_collect['frozen_amount_of_currency_pledge']=round($collect['sum(frozen_amount_of_currency_pledge)']+$count_collect['frozen_amount_of_currency_pledge'],2);
        $count_collect['risk_freezing_funds_the_next_day']=round($collect['sum(risk_freezing_funds_the_next_day)']+$count_collect['risk_freezing_funds_the_next_day'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='ruida_transaction'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['transaction_price']=round($value['transaction_price']+$page_collect['transaction_price'],2);
            $page_collect['turnover']=round($value['turnover']+$page_collect['turnover'],2);
            $page_collect['service_charge']=round($value['service_charge']+$page_collect['service_charge'],2);
            $page_collect['handing_in_the_service_charge']=round($value['handing_in_the_service_charge']+$page_collect['handing_in_the_service_charge'],2);
            $page_collect['specified_price']=round($value['specified_price']+$page_collect['specified_price'],2);
            $page_collect['quantity_per_hand']=round($value['quantity_per_hand']+$page_collect['quantity_per_hand'],2);
            $page_collect['mark_of_current_cost_calculation']=round($value['mark_of_current_cost_calculation']+$page_collect['mark_of_current_cost_calculation'],2);
            $page_collect['surcharge_1']=round($value['surcharge_1']+$page_collect['surcharge_1'],2);
            $page_collect['surcharge_2']=round($value['surcharge_2']+$page_collect['surcharge_2'],2);
            $page_collect['other_charges']=round($value['other_charges']+$page_collect['other_charges'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['transaction_price']=round($collect['sum(transaction_price)']+$count_collect['transaction_price'],2);
        $count_collect['turnover']=round($collect['sum(turnover)']+$count_collect['turnover'],2);
        $count_collect['service_charge']=round($collect['sum(service_charge)']+$count_collect['service_charge'],2);
        $count_collect['handing_in_the_service_charge']=round($collect['sum(handing_in_the_service_charge)']+$count_collect['handing_in_the_service_charge'],2);
        $count_collect['specified_price']=round($collect['sum(specified_price)']+ $count_collect['specified_price'],2);
        $count_collect['quantity_per_hand']=round($collect['sum(quantity_per_hand)']+$count_collect['quantity_per_hand'],2);
        $count_collect['mark_of_current_cost_calculation']=round($collect['sum(mark_of_current_cost_calculation)']+$count_collect['mark_of_current_cost_calculation'],2);
        $count_collect['surcharge_1']=round($collect['sum(surcharge_1)']+$count_collect['surcharge_1'],2);
        $count_collect['surcharge_2']=round($collect['sum(surcharge_2)']+$count_collect['surcharge_2'],2);
        $count_collect['other_charges']=round($collect['sum(other_charges)']+$count_collect['other_charges'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='ruida_deposit_and_withdrawal'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['cash_in']=round($value['cash_in']+$page_collect['cash_in'],2);
            $page_collect['cash_out']=round($value['cash_out']+$page_collect['cash_out'],2);
            $page_collect['cash_in_and_out']=round($value['cash_in_and_out']+$page_collect['cash_in_and_out'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['cash_in']=round($collect['sum(cash_in)']+$count_collect['cash_in'],2);
        $count_collect['cash_out']=round($collect['sum(cash_out)']+$count_collect['cash_out'],2);
        $count_collect['cash_in_and_out']=round($collect['sum(cash_in_and_out)']+$count_collect['cash_in_and_out'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;

    }elseif ($tableName=='huaxin_client_funds'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['beginning_equity']=round($value['beginning_equity']+$page_collect['beginning_equity'],2);
            $page_collect['deposit']=round($value['deposit']+$page_collect['deposit'],2);
            $page_collect['withdrawal']=round($value['withdrawal']+$page_collect['withdrawal'],2);
            $page_collect['deposit_and_withdrawal']=round($value['deposit_and_withdrawal']+$page_collect['deposit_and_withdrawal'],2);
            $page_collect['money_pledge_amount']=round($value['money_pledge_amount']+$page_collect['money_pledge_amount'],2);
            $page_collect['money_pledge_amount_out']=round($value['money_pledge_amount_out']+$page_collect['money_pledge_amount_out'],2);
            $page_collect['pledge_amount']=round($value['pledge_amount']+$page_collect['pledge_amount'],2);
            $page_collect['change_amount_of_pledge']=round($value['change_amount_of_pledge']+$page_collect['change_amount_of_pledge'],2);
            $page_collect['handling_fee']=round($value['handling_fee']+$page_collect['handling_fee'],2);
            $page_collect['hand_in_fee']=round($value['hand_in_fee']+$page_collect['hand_in_fee'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['position_profit_and_loss']=round($value['position_profit_and_loss']+$page_collect['position_profit_and_loss'],2);
            $page_collect['closing_profit_and_loss']=round($value['closing_profit_and_loss']+$page_collect['closing_profit_and_loss'],2);
            $page_collect['total_profit_and_loss']=round($value['total_profit_and_loss']+$page_collect['total_profit_and_loss'],2);
            $page_collect['investor_margin']=round($value['investor_margin']+$page_collect['investor_margin'],2);
            $page_collect['occupation_of_money_pledge_deposit']=round($value['occupation_of_money_pledge_deposit']+$page_collect['occupation_of_money_pledge_deposit'],2);
            $page_collect['exchange_margin']=round($value['exchange_margin']+$page_collect['exchange_margin'],2);
            $page_collect['delivery_margin']=round($value['delivery_margin']+$page_collect['delivery_margin'],2);
            $page_collect['margin_call']=round($value['margin_call']+$page_collect['margin_call'],2);
            $page_collect['available_funds']=round($value['available_funds']+$page_collect['available_funds'],2);
            $page_collect['investors_rights_and_interests']=round($value['investors_rights_and_interests']+$page_collect['investors_rights_and_interests'],2);
            $page_collect['net_profit']=round($value['net_profit']+$page_collect['net_profit'],2);
            $page_collect['ending_equity']=round($value['ending_equity']+$page_collect['ending_equity'],2);
            $page_collect['exercise_fee']=round($value['exercise_fee']+$page_collect['exercise_fee'],2);
            $page_collect['commission_for_exercise_of_stock_exchange']=round($value['commission_for_exercise_of_stock_exchange']+$page_collect['commission_for_exercise_of_stock_exchange'],2);
            $page_collect['market_value_equity']=round($value['market_value_equity']+$page_collect['market_value_equity'],2);
            $page_collect['market_value_of_options_long_positions']=round($value['market_value_of_options_long_positions']+$page_collect['market_value_of_options_long_positions'],2);
            $page_collect['market_value_of_short_positions_in_options']=round($value['market_value_of_short_positions_in_options']+$page_collect['market_value_of_short_positions_in_options'],2);
            $page_collect['income_from_option_premium']=round($value['income_from_option_premium']+$page_collect['income_from_option_premium'],2);
            $page_collect['option_premium_expenditure']=round($value['option_premium_expenditure']+$page_collect['option_premium_expenditure'],2);
            $page_collect['break_even_option']=round($value['break_even_option']+$page_collect['break_even_option'],2);
            $page_collect['change_amount_of_currency_pledge']=round($value['change_amount_of_currency_pledge']+$page_collect['change_amount_of_currency_pledge'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['beginning_equity']=round($collect['sum(beginning_equity)']+$count_collect['beginning_equity'],2);
        $count_collect['deposit']=round($collect['sum(deposit)']+$count_collect['deposit'],2);
        $count_collect['withdrawal']=round($collect['sum(withdrawal)']+$count_collect['withdrawal'],2);
        $count_collect['deposit_and_withdrawal']=round($collect['sum(deposit_and_withdrawal)']+$count_collect['deposit_and_withdrawal'],2);
        $count_collect['money_pledge_amount']=round($collect['sum(money_pledge_amount)']+$count_collect['money_pledge_amount'],2);
        $count_collect['money_pledge_amount_out']=round($collect['sum(money_pledge_amount_out)']+$count_collect['money_pledge_amount_out'],2);
        $count_collect['pledge_amount']=round($collect['sum(pledge_amount)']+$count_collect['pledge_amount'],2);
        $count_collect['change_amount_of_pledge']=round($collect['sum(change_amount_of_pledge)']+$count_collect['change_amount_of_pledge'],2);
        $count_collect['handling_fee']=round($collect['sum(handling_fee)']+$count_collect['handling_fee'],2);
        $count_collect['hand_in_fee']=round($collect['sum(hand_in_fee)']+$count_collect['hand_in_fee'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['position_profit_and_loss']=round($collect['sum(position_profit_and_loss)']+$count_collect['position_profit_and_loss'],2);
        $count_collect['closing_profit_and_loss']=round($collect['sum(closing_profit_and_loss)']+$count_collect['closing_profit_and_loss'],2);
        $count_collect['total_profit_and_loss']=round($collect['sum(total_profit_and_loss)']+$count_collect['total_profit_and_loss'],2);
        $count_collect['investor_margin']=round($collect['sum(investor_margin)']+$count_collect['investor_margin'],2);
        $count_collect['occupation_of_money_pledge_deposit']=round($collect['sum(occupation_of_money_pledge_deposit)']+$count_collect['occupation_of_money_pledge_deposit'],2);
        $count_collect['exchange_margin']=round($collect['sum(exchange_margin)']+$count_collect['exchange_margin'],2);
        $count_collect['delivery_margin']=round($collect['sum(delivery_margin)']+$count_collect['delivery_margin'],2);
        $count_collect['margin_call']=round($collect['sum(margin_call)']+$count_collect['margin_call'],2);
        $count_collect['available_funds']=round($collect['sum(available_funds)']+$count_collect['available_funds'],2);
        $count_collect['investors_rights_and_interests']=round($collect['sum(investors_rights_and_interests)']+$count_collect['investors_rights_and_interests'],2);
        $count_collect['net_profit']=round($collect['sum(net_profit)']+$count_collect['net_profit'],2);
        $count_collect['ending_equity']=round($collect['sum(ending_equity)']+$count_collect['ending_equity'],2);
        $count_collect['exercise_fee']=round($collect['sum(exercise_fee)']+$count_collect['exercise_fee'],2);
        $count_collect['commission_for_exercise_of_stock_exchange']=round($collect['sum(commission_for_exercise_of_stock_exchange)']+$count_collect['commission_for_exercise_of_stock_exchange'],2);
        $count_collect['market_value_equity']=round($collect['sum(market_value_equity)']+$count_collect['market_value_equity'],2);
        $count_collect['market_value_of_options_long_positions']=round($collect['sum(market_value_of_options_long_positions)']+$count_collect['market_value_of_options_long_positions'],2);
        $count_collect['market_value_of_short_positions_in_options']=round($collect['sum(market_value_of_short_positions_in_options)']+$count_collect['market_value_of_short_positions_in_options'],2);
        $count_collect['income_from_option_premium']=round($collect['sum(income_from_option_premium)']+$count_collect['income_from_option_premium'],2);
        $count_collect['option_premium_expenditure']=round($collect['sum(option_premium_expenditure)']+$count_collect['option_premium_expenditure'],2);
        $count_collect['break_even_option']=round($collect['sum(break_even_option)']+$count_collect['break_even_option'],2);
        $count_collect['change_amount_of_currency_pledge']=round($collect['sum(change_amount_of_currency_pledge)']+$count_collect['change_amount_of_currency_pledge'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='huaxin_transaction'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['service_charge']=round($value['service_charge']+$page_collect['service_charge'],2);
            $page_collect['handing_in_the_service_charge']=round($value['handing_in_the_service_charge']+$page_collect['handing_in_the_service_charge'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['profit_and_loss']=round($value['profit_and_loss']+$page_collect['profit_and_loss'],2);
            $page_collect['total_volume']=round($value['total_volume']+$page_collect['total_volume'],2);
            $page_collect['trading_volume_of_the_platform']=round($value['trading_volume_of_the_platform']+$page_collect['trading_volume_of_the_platform'],2);
            $page_collect['average_volume']=round($value['average_volume']+$page_collect['average_volume'],2);
            $page_collect['average_amount_of_fees_charged_on_this_platform']=round($value['average_amount_of_fees_charged_on_this_platform']+$page_collect['average_amount_of_fees_charged_on_this_platform'],2);
            $page_collect['this_platform_is_free_of_charge']=round($value['this_platform_is_free_of_charge']+$page_collect['this_platform_is_free_of_charge'],2);
            $page_collect['turnover']=round($value['turnover']+$page_collect['turnover'],2);
            $page_collect['current_turnover']=round($value['current_turnover']+$page_collect['current_turnover'],2);
            $page_collect['closing_profit_and_loss']=round($value['closing_profit_and_loss']+$page_collect['closing_profit_and_loss'],2);
            $page_collect['position_profit_and_loss']=round($value['position_profit_and_loss']+$page_collect['position_profit_and_loss'],2);
            $page_collect['net_profit']=round($value['net_profit']+$page_collect['net_profit'],2);
            $page_collect['delivery_commission']=round($value['delivery_commission']+$page_collect['delivery_commission'],2);
            $page_collect['delivery_service_charge']=round($value['delivery_service_charge']+$page_collect['delivery_service_charge'],2);
            $page_collect['handling_fee_for_retained_delivery']=round($value['handling_fee_for_retained_delivery']+$page_collect['handling_fee_for_retained_delivery'],2);
            $page_collect['delivery_quantity']=round($value['delivery_quantity']+$page_collect['delivery_quantity'],2);
            $page_collect['buy_holding']=round($value['buy_holding']+$page_collect['buy_holding'],2);
            $page_collect['sales_volume']=round($value['sales_volume']+$page_collect['sales_volume'],2);
            $page_collect['investor_margin']=round($value['investor_margin']+$page_collect['investor_margin'],2);
            $page_collect['exchange_margin']=round($value['exchange_margin']+$page_collect['exchange_margin'],2);
            $page_collect['market_value_of_long_options']=round($value['market_value_of_long_options']+$page_collect['market_value_of_long_options'],2);
            $page_collect['market_value_of_short_options']=round($value['market_value_of_short_options']+$page_collect['market_value_of_short_options'],2);
            $page_collect['royalty_income']=round($value['royalty_income']+$page_collect['royalty_income'],2);
            $page_collect['royalty_payment']=round($value['royalty_payment']+$page_collect['royalty_payment'],2);
            $page_collect['commission_for_exercise_of_stock_exchange']=round($value['commission_for_exercise_of_stock_exchange']+$page_collect['commission_for_exercise_of_stock_exchange'],2);
            $page_collect['exercise_fee']=round($value['exercise_fee']+$page_collect['exercise_fee'],2);
            $page_collect['operating_profit_and_loss']=round($value['operating_profit_and_loss']+$page_collect['operating_profit_and_loss'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['service_charge']=round($collect['sum(service_charge)']+$count_collect['service_charge'],2);
        $count_collect['handing_in_the_service_charge']=round($collect['sum(handing_in_the_service_charge)']+$count_collect['handing_in_the_service_charge'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['profit_and_loss']=round($collect['sum(profit_and_loss)']+$count_collect['profit_and_loss'],2);
        $count_collect['total_volume']=round($collect['sum(total_volume)']+$count_collect['total_volume'],2);
        $count_collect['trading_volume_of_the_platform']=round($collect['sum(trading_volume_of_the_platform)']+$count_collect['trading_volume_of_the_platform'],2);
        $count_collect['average_volume']=round($collect['sum(average_volume)']+$count_collect['average_volume'],2);
        $count_collect['average_amount_of_fees_charged_on_this_platform']=round($collect['sum(average_amount_of_fees_charged_on_this_platform)']+$count_collect['average_amount_of_fees_charged_on_this_platform'],2);
        $count_collect['this_platform_is_free_of_charge']=round($collect['sum(this_platform_is_free_of_charge)']+$count_collect['this_platform_is_free_of_charge'],2);
        $count_collect['turnover']=round($collect['sum(turnover)']+$count_collect['turnover'],2);
        $count_collect['current_turnover']=round($collect['sum(current_turnover)']+$count_collect['current_turnover'],2);
        $count_collect['closing_profit_and_loss']=round($collect['sum(closing_profit_and_loss)']+$count_collect['closing_profit_and_loss'],2);
        $count_collect['position_profit_and_loss']=round($collect['sum(position_profit_and_loss)']+$count_collect['position_profit_and_loss'],2);
        $count_collect['net_profit']=round($collect['sum(net_profit)']+$count_collect['net_profit'],2);
        $count_collect['delivery_commission']=round($collect['sum(delivery_commission)']+$count_collect['delivery_commission'],2);
        $count_collect['delivery_service_charge']=round($collect['sum(delivery_service_charge)']+$count_collect['delivery_service_charge'],2);
        $count_collect['handling_fee_for_retained_delivery']=round($collect['sum(handling_fee_for_retained_delivery)']+$count_collect['handling_fee_for_retained_delivery'],2);
        $count_collect['delivery_quantity']=round($collect['sum(delivery_quantity)']+$count_collect['delivery_quantity'],2);
        $count_collect['buy_holding']=round($collect['sum(buy_holding)']+$count_collect['buy_holding'],2);
        $count_collect['sales_volume']=round($collect['sum(sales_volume)']+$count_collect['sales_volume'],2);
        $count_collect['investor_margin']=round($collect['sum(investor_margin)']+$count_collect['investor_margin'],2);
        $count_collect['exchange_margin']=round($collect['sum(exchange_margin)']+$count_collect['exchange_margin'],2);
        $count_collect['market_value_of_long_options']=round($collect['sum(market_value_of_long_options)']+$count_collect['market_value_of_long_options'],2);
        $count_collect['market_value_of_short_options']=round($collect['sum(market_value_of_short_options)']+$count_collect['market_value_of_short_options'],2);
        $count_collect['royalty_income']=round($collect['sum(royalty_income)']+$count_collect['royalty_income'],2);
        $count_collect['royalty_payment']=round($collect['sum(royalty_payment)']+$count_collect['royalty_payment'],2);
        $count_collect['commission_for_exercise_of_stock_exchange']=round($collect['sum(commission_for_exercise_of_stock_exchange)']+$count_collect['commission_for_exercise_of_stock_exchange'],2);
        $count_collect['exercise_fee']=round($collect['sum(exercise_fee)']+$count_collect['exercise_fee'],2);
        $count_collect['operating_profit_and_loss']=round($collect['sum(operating_profit_and_loss)']+$count_collect['operating_profit_and_loss'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='huaxin_deposit_and_withdrawal'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['cash_in']=round($value['cash_in']+$page_collect['cash_in'],2);
            $page_collect['cash_out']=round($value['cash_out']+$page_collect['cash_out'],2);
            $page_collect['cash_in_and_out']=round($value['cash_in_and_out']+$page_collect['cash_in_and_out'],2);
            $page_collect['number_of_gold_entries']=round($value['number_of_gold_entries']+$page_collect['number_of_gold_entries'],2);
            $page_collect['gold_output']=round($value['gold_output']+$page_collect['gold_output'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['cash_in']=round($collect['sum(cash_in)']+$count_collect['cash_in'],2);
        $count_collect['cash_out']=round($collect['sum(cash_out)']+$count_collect['cash_out'],2);
        $count_collect['cash_in_and_out']=round($collect['sum(cash_in_and_out)']+$count_collect['cash_in_and_out'],2);
        $count_collect['number_of_gold_entries']=round($collect['sum(number_of_gold_entries)']+$count_collect['number_of_gold_entries'],2);
        $count_collect['gold_output']=round($collect['sum(gold_output)']+$count_collect['gold_output'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='huaixn_history'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['number']=round($value['number']+$page_collect['number'],2);
            $page_collect['price']=round($value['price']+$page_collect['price'],2);
            $page_collect['exchange_commission']=round($value['exchange_commission']+$page_collect['exchange_commission'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['number']=round($collect['sum(number)']+$count_collect['number'],2);
        $count_collect['price']=round($collect['sum(price)']+$count_collect['price'],2);
        $count_collect['exchange_commission']=round($collect['sum(exchange_commission)']+$count_collect['exchange_commission'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='jinkong_client_funds'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['equity_at_the_beginning_of_the_period']=round($value['equity_at_the_beginning_of_the_period']+$page_collect['equity_at_the_beginning_of_the_period'],2);
            $page_collect['deposit']=round($value['deposit']+$page_collect['deposit'],2);
            $page_collect['withdrawal']=round($value['withdrawal']+$page_collect['withdrawal'],2);
            $page_collect['deposit_and_withdrawal']=round($value['deposit_and_withdrawal']+$page_collect['deposit_and_withdrawal'],2);
            $page_collect['money_pledge_amount']=round($value['money_pledge_amount']+$page_collect['money_pledge_amount'],2);
            $page_collect['change_of_currency_pledge']=round($value['change_of_currency_pledge']+$page_collect['change_of_currency_pledge'],2);
            $page_collect['declaration_fee']=round($value['declaration_fee']+$page_collect['declaration_fee'],2);
            $page_collect['hand_in_fee']=round($value['hand_in_fee']+$page_collect['hand_in_fee'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['investor_protection_fund']=round($value['investor_protection_fund']+$page_collect['investor_protection_fund'],2);
            $page_collect['handling_charge_turnover_rate']=round($value['handling_charge_turnover_rate']+$page_collect['handling_charge_turnover_rate'],2);
            $page_collect['software_cost']=round($value['software_cost']+$page_collect['software_cost'],2);
            $page_collect['returning_a_servant']=round($value['returning_a_servant']+$page_collect['returning_a_servant'],2);
            $page_collect['net_retention_fee']=round($value['net_retention_fee']+$page_collect['net_retention_fee'],2);
            $page_collect['number_of_transactions']=round($value['number_of_transactions']+$page_collect['number_of_transactions'],2);
            $page_collect['transaction_amount']=round($value['transaction_amount']+$page_collect['transaction_amount'],2);
            $page_collect['today_hand_count']=round($value['today_hand_count']+$page_collect['today_hand_count'],2);
            $page_collect['today_current_turnover']=round($value['today_current_turnover']+$page_collect['today_current_turnover'],2);
            $page_collect['total_profit_and_loss']=round($value['total_profit_and_loss']+$page_collect['total_profit_and_loss'],2);
            $page_collect['position_profit_and_loss']=round($value['position_profit_and_loss']+$page_collect['position_profit_and_loss'],2);
            $page_collect['closing_profit_and_loss']=round($value['closing_profit_and_loss']+$page_collect['closing_profit_and_loss'],2);
            $page_collect['ending_equity']=round($value['ending_equity']+$page_collect['ending_equity'],2);
            $page_collect['investor_margin']=round($value['investor_margin']+$page_collect['investor_margin'],2);
            $page_collect['exchange_margin']=round($value['exchange_margin']+$page_collect['exchange_margin'],2);
            $page_collect['Risk_1']=round($value['Risk_1']+$page_collect['Risk_1'],2);
            $page_collect['Risk_2']=round($value['Risk_2']+$page_collect['Risk_2'],2);
            $page_collect['available_funds']=round($value['available_funds']+$page_collect['available_funds'],2);
            $page_collect['occupation_of_money_pledge_deposit']=round($value['occupation_of_money_pledge_deposit']+$page_collect['occupation_of_money_pledge_deposit'],2);
            $page_collect['pledge_amount']=round($value['pledge_amount']+$page_collect['pledge_amount'],2);
            $page_collect['customer_equity_peak']=round($value['customer_equity_peak']+$page_collect['customer_equity_peak'],2);
            $page_collect['customer_margin_peak']=round($value['customer_margin_peak']+$page_collect['customer_margin_peak'],2);
            $page_collect['peak_amount_of_funds_available_to_customers']=round($value['peak_amount_of_funds_available_to_customers']+$page_collect['peak_amount_of_funds_available_to_customers'],2);
            $page_collect['daily_average_exchange_available']=round($value['daily_average_exchange_available']+$page_collect['daily_average_exchange_available'],2);
            $page_collect['daily_daily_average_company_available']=round($value['daily_daily_average_company_available']+$page_collect['daily_daily_average_company_available'],2);
            $page_collect['daily_average_at_home']=round($value['daily_average_at_home']+$page_collect['daily_average_at_home'],2);
            $page_collect['average_daily_equity']=round($value['average_daily_equity']+$page_collect['average_daily_equity'],2);
            $page_collect['exercise_fee']=round($value['exercise_fee']+$page_collect['exercise_fee'],2);
            $page_collect['commission_for_exercise_of_stock_exchange']=round($value['commission_for_exercise_of_stock_exchange']+$page_collect['commission_for_exercise_of_stock_exchange'],2);
            $page_collect['performance_fee']=round($value['performance_fee']+$page_collect['performance_fee'],2);
            $page_collect['service_charge_for_performance_of_the_exchange']=round($value['service_charge_for_performance_of_the_exchange']+$page_collect['service_charge_for_performance_of_the_exchange'],2);
            $page_collect['market_value_equity']=round($value['market_value_equity']+$page_collect['market_value_equity'],2);
            $page_collect['market_value_of_options_long_positions']=round($value['market_value_of_options_long_positions']+$page_collect['market_value_of_options_long_positions'],2);
            $page_collect['market_value_of_short_positions_in_options']=round($value['market_value_of_short_positions_in_options']+$page_collect['market_value_of_short_positions_in_options'],2);
            $page_collect['income_from_option_premium']=round($value['income_from_option_premium']+$page_collect['income_from_option_premium'],2);
            $page_collect['option_premium_expenditure']=round($value['option_premium_expenditure']+$page_collect['option_premium_expenditure'],2);
            $page_collect['profit_and_loss_of_option_exercise']=round($value['profit_and_loss_of_option_exercise']+$page_collect['profit_and_loss_of_option_exercise'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['equity_at_the_beginning_of_the_period']=round($collect['sum(equity_at_the_beginning_of_the_period)']+$count_collect['equity_at_the_beginning_of_the_period'],2);
        $count_collect['deposit']=round($collect['sum(deposit)']+$count_collect['deposit'],2);
        $count_collect['withdrawal']=round($collect['sum(withdrawal)']+$count_collect['withdrawal'],2);
        $count_collect['deposit_and_withdrawal']=round($collect['sum(deposit_and_withdrawal)']+$count_collect['deposit_and_withdrawal'],2);
        $count_collect['money_pledge_amount']=round($collect['sum(money_pledge_amount)']+$count_collect['money_pledge_amount'],2);
        $count_collect['change_of_currency_pledge']=round($collect['sum(change_of_currency_pledge)']+$count_collect['change_of_currency_pledge'],2);
        $count_collect['declaration_fee']=round($collect['sum(declaration_fee)']+$count_collect['declaration_fee'],2);
        $count_collect['hand_in_fee']=round($collect['sum(hand_in_fee)']+$count_collect['hand_in_fee'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['investor_protection_fund']=round($collect['sum(investor_protection_fund)']+$count_collect['investor_protection_fund'],2);
        $count_collect['handling_charge_turnover_rate']=round($collect['sum(handling_charge_turnover_rate)']+$count_collect['handling_charge_turnover_rate'],2);
        $count_collect['software_cost']=round($collect['sum(software_cost)']+$count_collect['software_cost'],2);
        $count_collect['returning_a_servant']=round($collect['sum(returning_a_servant)']+$count_collect['returning_a_servant'],2);
        $count_collect['net_retention_fee']=round($collect['sum(net_retention_fee)']+$count_collect['net_retention_fee'],2);
        $count_collect['number_of_transactions']=round($collect['sum(number_of_transactions)']+$count_collect['number_of_transactions'],2);
        $count_collect['transaction_amount']=round($collect['sum(transaction_amount)']+$count_collect['transaction_amount'],2);
        $count_collect['today_hand_count']=round($collect['sum(today_hand_count)']+$count_collect['today_hand_count'],2);
        $count_collect['today_current_turnover']=round($collect['sum(today_current_turnover)']+$count_collect['today_current_turnover'],2);
        $count_collect['total_profit_and_loss']=round($collect['sum(total_profit_and_loss)']+$count_collect['total_profit_and_loss'],2);
        $count_collect['position_profit_and_loss']=round($collect['sum(position_profit_and_loss)']+$count_collect['position_profit_and_loss'],2);
        $count_collect['closing_profit_and_loss']=round($collect['sum(closing_profit_and_loss)']+$count_collect['closing_profit_and_loss'],2);
        $count_collect['ending_equity']=round($collect['sum(ending_equity)']+$count_collect['ending_equity'],2);
        $count_collect['investor_margin']=round($collect['sum(investor_margin)']+$count_collect['investor_margin'],2);
        $count_collect['exchange_margin']=round($collect['sum(exchange_margin)']+$count_collect['exchange_margin'],2);
        $count_collect['Risk_1']=round($collect['sum(Risk_1)']+$count_collect['Risk_1'],2);
        $count_collect['Risk_2']=round($collect['sum(Risk_2)']+$count_collect['Risk_2'],2);
        $count_collect['available_funds']=round($collect['sum(available_funds)']+$count_collect['available_funds'],2);
        $count_collect['occupation_of_money_pledge_deposit']=round($collect['sum(occupation_of_money_pledge_deposit)']+$count_collect['occupation_of_money_pledge_deposit'],2);
        $count_collect['pledge_amount']=round($collect['sum(pledge_amount)']+$count_collect['pledge_amount'],2);
        $count_collect['customer_equity_peak']=round($collect['sum(customer_equity_peak)']+$count_collect['customer_equity_peak'],2);
        $count_collect['customer_margin_peak']=round($collect['sum(customer_margin_peak)']+$count_collect['customer_margin_peak'],2);
        $count_collect['peak_amount_of_funds_available_to_customers']=round($collect['sum(peak_amount_of_funds_available_to_customers)']+$count_collect['peak_amount_of_funds_available_to_customers'],2);
        $count_collect['daily_average_exchange_available']=round($collect['sum(daily_average_exchange_available)']+$count_collect['daily_average_exchange_available'],2);
        $count_collect['daily_daily_average_company_available']=round($collect['sum(daily_daily_average_company_available)']+$count_collect['daily_daily_average_company_available'],2);
        $count_collect['daily_average_at_home']=round($collect['sum(daily_average_at_home)']+$count_collect['daily_average_at_home'],2);
        $count_collect['average_daily_equity']=round($collect['sum(average_daily_equity)']+$count_collect['average_daily_equity'],2);
        $count_collect['exercise_fee']=round($collect['sum(exercise_fee)']+$count_collect['exercise_fee'],2);
        $count_collect['commission_for_exercise_of_stock_exchange']=round($collect['sum(commission_for_exercise_of_stock_exchange)']+$count_collect['commission_for_exercise_of_stock_exchange'],2);
        $count_collect['performance_fee']=round($collect['sum(performance_fee)']+$count_collect['performance_fee'],2);
        $count_collect['service_charge_for_performance_of_the_exchange']=round($collect['sum(service_charge_for_performance_of_the_exchange)']+$count_collect['service_charge_for_performance_of_the_exchange'],2);
        $count_collect['market_value_equity']=round($collect['sum(market_value_equity)']+$count_collect['market_value_equity'],2);
        $count_collect['market_value_of_options_long_positions']=round($collect['sum(market_value_of_options_long_positions)']+$count_collect['market_value_of_options_long_positions'],2);
        $count_collect['market_value_of_short_positions_in_options']=round($collect['sum(market_value_of_short_positions_in_options)']+$count_collect['market_value_of_short_positions_in_options'],2);
        $count_collect['income_from_option_premium']=round($collect['sum(income_from_option_premium)']+$count_collect['income_from_option_premium'],2);
        $count_collect['option_premium_expenditure']=round($collect['sum(option_premium_expenditure)']+$count_collect['option_premium_expenditure'],2);
        $count_collect['profit_and_loss_of_option_exercise']=round($collect['sum(profit_and_loss_of_option_exercise)']+$count_collect['profit_and_loss_of_option_exercise'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='jinkong_transaction'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['service_charge']=round($value['service_charge']+$page_collect['service_charge'],2);
            $page_collect['handing_in_the_service_charge']=round($value['handing_in_the_service_charge']+$page_collect['handing_in_the_service_charge'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['profit_and_loss']=round($value['profit_and_loss']+$page_collect['profit_and_loss'],2);
            $page_collect['total_volume']=round($value['total_volume']+$page_collect['total_volume'],2);
            $page_collect['trading_volume_of_the_platform']=round($value['trading_volume_of_the_platform']+$page_collect['trading_volume_of_the_platform'],2);
            $page_collect['average_volume']=round($value['average_volume']+$page_collect['average_volume'],2);
            $page_collect['average_amount_of_fees_charged_on_this_platform']=round($value['average_amount_of_fees_charged_on_this_platform']+$page_collect['average_amount_of_fees_charged_on_this_platform'],2);
            $page_collect['this_platform_is_free_of_charge']=round($value['this_platform_is_free_of_charge']+$page_collect['this_platform_is_free_of_charge'],2);
            $page_collect['turnover']=round($value['turnover']+$page_collect['turnover'],2);
            $page_collect['current_turnover']=round($value['current_turnover']+$page_collect['current_turnover'],2);
            $page_collect['closing_profit_and_loss']=round($value['closing_profit_and_loss']+$page_collect['closing_profit_and_loss'],2);
            $page_collect['position_profit_and_loss']=round($value['position_profit_and_loss']+$page_collect['position_profit_and_loss'],2);
            $page_collect['net_profit']=round($value['net_profit']+$page_collect['net_profit'],2);
            $page_collect['delivery_commission']=round($value['delivery_commission']+$page_collect['delivery_commission'],2);
            $page_collect['delivery_service_charge']=round($value['delivery_service_charge']+$page_collect['delivery_service_charge'],2);
            $page_collect['handling_fee_for_retained_delivery']=round($value['handling_fee_for_retained_delivery']+$page_collect['handling_fee_for_retained_delivery'],2);
            $page_collect['delivery_quantity']=round($value['delivery_quantity']+$page_collect['delivery_quantity'],2);
            $page_collect['buy_holding']=round($value['buy_holding']+$page_collect['buy_holding'],2);
            $page_collect['sales_volume']=round($value['sales_volume']+$page_collect['sales_volume'],2);
            $page_collect['investor_margin']=round($value['investor_margin']+$page_collect['investor_margin'],2);
            $page_collect['exchange_margin']=round($value['exchange_margin']+$page_collect['exchange_margin'],2);
            $page_collect['market_value_of_long_options']=round($value['market_value_of_long_options']+$page_collect['market_value_of_long_options'],2);
            $page_collect['market_value_of_short_options']=round($value['market_value_of_short_options']+$page_collect['market_value_of_short_options'],2);
            $page_collect['royalty_income']=round($value['royalty_income']+$page_collect['royalty_income'],2);
            $page_collect['royalty_payment']=round($value['royalty_payment']+$page_collect['royalty_payment'],2);
            $page_collect['commission_for_exercise_of_stock_exchange']=round($value['commission_for_exercise_of_stock_exchange']+$page_collect['commission_for_exercise_of_stock_exchange'],2);
            $page_collect['exercise_fee']=round($value['exercise_fee']+$page_collect['exercise_fee'],2);
            $page_collect['operating_profit_and_loss']=round($value['operating_profit_and_loss']+$page_collect['operating_profit_and_loss'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['service_charge']=round($collect['sum(service_charge)']+$count_collect['service_charge'],2);
        $count_collect['handing_in_the_service_charge']=round($collect['sum(handing_in_the_service_charge)']+$count_collect['handing_in_the_service_charge'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['profit_and_loss']=round($collect['sum(profit_and_loss)']+$count_collect['profit_and_loss'],2);
        $count_collect['total_volume']=round($collect['sum(total_volume)']+$count_collect['total_volume'],2);
        $count_collect['trading_volume_of_the_platform']=round($collect['sum(trading_volume_of_the_platform)']+$count_collect['trading_volume_of_the_platform'],2);
        $count_collect['average_volume']=round($collect['sum(average_volume)']+$count_collect['average_volume'],2);
        $count_collect['average_amount_of_fees_charged_on_this_platform']=round($collect['sum(average_amount_of_fees_charged_on_this_platform)']+$count_collect['average_amount_of_fees_charged_on_this_platform'],2);
        $count_collect['this_platform_is_free_of_charge']=round($collect['sum(this_platform_is_free_of_charge)']+$count_collect['this_platform_is_free_of_charge'],2);
        $count_collect['turnover']=round($collect['sum(turnover)']+$count_collect['turnover'],2);
        $count_collect['current_turnover']=round($collect['sum(current_turnover)']+$count_collect['current_turnover'],2);
        $count_collect['closing_profit_and_loss']=round($collect['sum(closing_profit_and_loss)']+$count_collect['closing_profit_and_loss'],2);
        $count_collect['position_profit_and_loss']=round($collect['sum(position_profit_and_loss)']+$count_collect['position_profit_and_loss'],2);
        $count_collect['net_profit']=round($collect['sum(net_profit)']+$count_collect['net_profit'],2);
        $count_collect['delivery_commission']=round($collect['sum(delivery_commission)']+$count_collect['delivery_commission'],2);
        $count_collect['delivery_service_charge']=round($collect['sum(delivery_service_charge)']+$count_collect['delivery_service_charge'],2);
        $count_collect['handling_fee_for_retained_delivery']=round($collect['sum(handling_fee_for_retained_delivery)']+$count_collect['handling_fee_for_retained_delivery'],2);
        $count_collect['delivery_quantity']=round($collect['sum(delivery_quantity)']+$count_collect['delivery_quantity'],2);
        $count_collect['buy_holding']=round($collect['sum(buy_holding)']+$count_collect['buy_holding'],2);
        $count_collect['sales_volume']=round($collect['sum(sales_volume)']+$count_collect['sales_volume'],2);
        $count_collect['investor_margin']=round($collect['sum(investor_margin)']+$count_collect['investor_margin'],2);
        $count_collect['exchange_margin']=round($collect['sum(exchange_margin)']+$count_collect['exchange_margin'],2);
        $count_collect['market_value_of_long_options']=round($collect['sum(market_value_of_long_options)']+$count_collect['market_value_of_long_options'],2);
        $count_collect['market_value_of_short_options']=round($collect['sum(market_value_of_short_options)']+$count_collect['market_value_of_short_options'],2);
        $count_collect['royalty_income']=round($collect['sum(royalty_income)']+$count_collect['royalty_income'],2);
        $count_collect['royalty_payment']=round($collect['sum(royalty_payment)']+$count_collect['royalty_payment'],2);
        $count_collect['commission_for_exercise_of_stock_exchange']=round($collect['sum(commission_for_exercise_of_stock_exchange)']+$count_collect['commission_for_exercise_of_stock_exchange'],2);
        $count_collect['exercise_fee']=round($collect['sum(exercise_fee)']+$count_collect['exercise_fee'],2);
        $count_collect['operating_profit_and_loss']=round($collect['sum(operating_profit_and_loss)']+$count_collect['operating_profit_and_loss'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='jinkong_deposit_and_withdrawal'){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['cash_in']=round($value['cash_in']+$page_collect['cash_in'],2);
            $page_collect['cash_out']=round($value['cash_out']+$page_collect['cash_out'],2);
            $page_collect['cash_in_and_out']=round($value['cash_in_and_out']+$page_collect['cash_in_and_out'],2);
            $page_collect['number_of_gold_entries']=round($value['number_of_gold_entries']+$page_collect['number_of_gold_entries'],2);
            $page_collect['gold_output']=round($value['gold_output']+$page_collect['gold_output'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['cash_in']=round($collect['sum(cash_in)']+$count_collect['cash_in'],2);
        $count_collect['cash_out']=round($collect['sum(cash_out)']+$count_collect['cash_out'],2);
        $count_collect['cash_in_and_out']=round($collect['sum(cash_in_and_out)']+$count_collect['cash_in_and_out'],2);
        $count_collect['number_of_gold_entries']=round($collect['sum(number_of_gold_entries)']+$count_collect['number_of_gold_entries'],2);
        $count_collect['gold_output']=round($collect['sum(gold_output)']+$count_collect['gold_output'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }elseif ($tableName=='                                                                                                                                                                                                                                                                                                                                  '){
        foreach ($data as $key=>$value){
            $page_collect['unique_code']="当前页合计";//抬头
            $page_collect['num_of_transactions']=round($value['num_of_transactions']+$page_collect['num_of_transactions'],2);
            $page_collect['transaction_price']=round($value['transaction_price']+$page_collect['transaction_price'],2);
            $page_collect['transaction_amount']=round($value['transaction_amount']+$page_collect['transaction_amount'],2);
            $page_collect['service_charge']=round($value['service_charge']+$page_collect['service_charge'],2);
            $page_collect['handing_in_service_charge']=round($value['handing_in_service_charge']+$page_collect['handing_in_service_charge'],2);
            $page_collect['retention_fee']=round($value['retention_fee']+$page_collect['retention_fee'],2);
            $page_collect['royalty_income']=round($value['royalty_income']+$page_collect['royalty_income'],2);
            $page_collect['royalty_payment']=round($value['royalty_payment']+$page_collect['royalty_payment'],2);
        }
        $collect=$tableData['collect'];
        $count_collect['unique_code']="所有合计";//抬头
        $count_collect['num_of_transactions']=round($collect['sum(num_of_transactions)']+$count_collect['num_of_transactions'],2);
        $count_collect['transaction_price']=round($collect['sum(transaction_price)']+$count_collect['transaction_price'],2);
        $count_collect['transaction_amount']=round($collect['sum(transaction_amount)']+$count_collect['transaction_amount'],2);
        $count_collect['service_charge']=round($collect['sum(service_charge)']+$count_collect['service_charge'],2);
        $count_collect['handing_in_service_charge']=round($collect['sum(handing_in_service_charge)']+$count_collect['handing_in_service_charge'],2);
        $count_collect['retention_fee']=round($collect['sum(retention_fee)']+$count_collect['retention_fee'],2);
        $count_collect['royalty_income']=round($collect['sum(royalty_income)']+$count_collect['royalty_income'],2);
        $count_collect['royalty_payment']=round($collect['sum(royalty_payment)']+$count_collect['royalty_payment'],2);
        $count=count($data);
        $data[$count]=$page_collect;
        $data[$count+1]=$count_collect;
    }
    $data = [
      'code' => '1',
      'message' => '获取成功',
      'pages_count' => ceil($tableData['count'] / 30),
      'data' => $data
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
