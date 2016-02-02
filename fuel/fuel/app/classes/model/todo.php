<?php

class Model_Todo extends \Model {

  protected $from;  // テーブル名を保持する
  protected $user_id;

  public function __construct($user_id)
  {
    $this->from = 'ensyu.todo';
    $this->user_id = $user_id;
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
        'ensyu.task_status.status_code')
      ->where('user_id', $this->user_id);
    return $query;
  }

  public function search_task($search_value)
  {
    $query = $this->select_query();

    if (empty(trim($search_value)))
    {
      // 検索文字列が空白やスペースのみの場合
    }
    else
    {
      $query->where('ensyu.todo.description', 'like', '%'.$search_value.'%');
    }

    return $query->execute();
  }

  public function insert_task($insert_value)
  {
    $query = DB::insert($this->from)
      ->set($insert_value)
      ->execute();
  }

  public function make_content($content)
  {
    $todo_records = $this->select_query()
      ->where('user_id', $this->user_id)
      ->execute();

    $content->make_data($todo_records);
  }
}
