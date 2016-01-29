<?php

class Model_Todo extends \Model {

  protected $from;  // テーブル名を保持する

  public function __construct()
  {
    $this->from = 'ensyu.todo';
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

  public function make_csv($user)
  {
    $todo_records = $this->select_query()
      ->where('user_id', $user)
      ->execute();

    $csvFilePath = '/tmp/'.time().'.csv';
    $csvFile = new SplFileObject($csvFilePath, 'w');

    foreach ($todo_records as $todo_record)
    {
      $csvFile->fputcsv($todo_record);
    }

    return $csvFilePath;
  }
}
