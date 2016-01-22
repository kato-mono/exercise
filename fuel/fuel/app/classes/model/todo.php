<?php

class Model_Todo extends \Model {

  private $from;  // テーブル名を保持する
  private $column_names; // カラム名の一覧を保持する

  public function __construct()
  {
    $this->from = 'ensyu.todo';
    $this->$column_names = $this->select_column_names();
  }

  public function get_column_names()
  {
    return $this->column_names;
  }

  public function select_query()
  {
    $query = DB::select(
        'ensyu.todo.id',
        [
          'ensyu.task_status.description',
          'status_description'
        ],
        'ensyu.todo.status_code',
        'ensyu.todo.description',
        'ensyu.todo.deadline'
      )
      ->from($this->from)
      ->join('ensyu.task_status')
      ->on(
        'ensyu.todo.status_code',
        '=',
        'ensyu.task_status.status_code');
    return $query;
  }

  public function insert_task($insert_value)
  {
    $query = DB::insert($this->from)
      ->set($insert_value)
      ->execute();
  }

  public function select_column_names()
  {
    $query = DB::select(
        'column_name'
      )
      ->from('information_schema.columns')
      ->where('information_schema.columns.table_schema', DB::expr('ensyu'))
      ->and_where('information_schema.columns.table_name', DB::expr('todo'))
      ->execute();
    return $query;
  }
}
