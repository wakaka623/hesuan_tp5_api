<?php
namespace app\import\model;

use think\Model;
use think\Db;


class Form extends Model
{
  /**
   * 查找表字段
   */
  public function getColumns($tableName) 
  {
    $query = 'select COLUMN_NAME,column_comment from INFORMATION_SCHEMA.Columns where table_name="' . $tableName . '" and table_schema="hesuan_admin"';

    // [{COLUMN_NAME: '', column_comment: ''}]
    $columns = Db::query($query);


    $arr = [];
    // [{COLUMN_NAME: column_comment}]
    foreach ($columns as $key => $value) {
      $ke=$value['COLUMN_NAME'];
      $val=$value['column_comment'];
      $arr[$ke]=$val;
    }

    // exit;

    return $arr;
  }

  /**
   * 查找表数据
   */
  public function getTableData($tableName) 
  {
    // 根据id正序导出数据
    $query = 'SELECT * FROM ' . $tableName . ' order by id';

    $data = Db::query($query);

    // $data = DB::name($tableName)->select();

    return $data;
  }

  /**
   * 导入表
   */
	public function import($dbName, $data) {
    // 查询 $dbName 表所有字段和备注
    $columns = Db::query('select COLUMN_NAME,column_comment from INFORMATION_SCHEMA.Columns where table_name="' . $dbName . '" and table_schema="hesuan_admin"');


    // 数据增加数据表对应的字段
    foreach ($data as $key => $value) {
      foreach ($value as $k => $v) {

        foreach ($columns as $y => $e) {
          if ($v['comment'] === $e['column_comment']) {
            $data[$key][$k]['COLUMN_NAME'] = $e['COLUMN_NAME'];
            break;
          }
        }

      }
    }

    $insert = [];
    $insertAll = [];


    // 按照数据表的字段添加到数据库中
    // COLUMN_NAME -> 字段名、 column_comment -> 字段注释
    foreach ($data as $key => $value) {
      foreach ($value as $k => $v) {
        if (array_key_exists('COLUMN_NAME', $v)) {
          $insert[$v['COLUMN_NAME']] = $v['value'];
        }
      }

      array_push($insertAll, $insert);
      $insert = [];
    }


    $num = Db::name($dbName)->insertAll($insertAll);


    return $num;
	}
}
