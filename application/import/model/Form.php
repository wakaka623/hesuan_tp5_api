<?php
namespace app\import\model;

use think\Model;
use think\Db;


class Form extends Model
{
  /**
   * 查找表字段
   */
  public function getColumns($tableName) {
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
  public function getTableData($tableName) {
    $query = 'SELECT * FROM ' . $tableName;

    $data = Db::query($query);

    return $data;
  }

  /**
   * 导入表
   */
	public function import($dbName, $data) {
    // 查询 $dbName 表所有字段和备注
    $columns = Db::query('select COLUMN_NAME,column_comment from INFORMATION_SCHEMA.Columns where table_name="' . $dbName . '" and table_schema="hesuan_admin"');

    
    // 给excel数据增加对应的字段名
    foreach ($data as $key => $value) {
      foreach ($value as $k => $v) {
        if ($v['comment'] === $columns[$k + 1]['column_comment']) {
          $data[$key][$k]['COLUMN_NAME'] = $columns[$k + 1]['COLUMN_NAME'];
        }
      }
    }
    

    // 按照数据表的字段添加到数据库中
    // COLUMN_NAME -> 字段名、 column_comment -> 字段注释
    foreach ($data as $key => $value) {
      $query = 'INSERT INTO ' . 'ruida_fund_reconciliation';
      $columnName = '';
      $columnValue = '';

      foreach ($value as $k => $v) {
        // $v['comment'] $v['value'] $v['COLUMN_NAME']
        $columnName = $columnName . $v['COLUMN_NAME'] . ',';
        $columnValue = $columnValue . '"' . $v['value'] . '",';
      }

      $columnName = rtrim($columnName, ',');
      $columnValue = rtrim($columnValue, ',');

      $query = $query . '(' . $columnName . ')' . 'VALUES' . '(' . $columnValue . ')';
      Db::query($query);
    }


    return '1';
	}
}
