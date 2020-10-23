<?php
namespace app\import\controller;

use think\Controller;
use think\Loader;
use think\Request;

// import('phpexcel.PHPExcel', EXTEND_PATH);

class Excel extends Controller
{
  public function index() {
    // vendor("PHPExcel.PHPExcel");
    // $objPHPExcel = new \PHPExcel();

    $file = request()->file('excel');

    var_dump($file);

    return;
  }
}
