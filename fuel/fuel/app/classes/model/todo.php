<?php

class Model_Todo extends \Model {

  public static function select_all()
  {
    $query = Model_Todo::select_all_query();
    return $query->execute();
  }

  public static function select_all_orderby($column, $order)
  {
    $query = Model_Todo::select_all_query();
    return $query->order_by($column, $order)->execute();
  }

  public static function select_all_query()
  {
    $query = DB::select(
        'ensyu.todo.id',
        ['ensyu.task_status.description','status_description'],
        'ensyu.todo.status_code',
        'ensyu.todo.description',
        'ensyu.todo.deadline'
      )
      ->from('ensyu.todo')
      ->join('ensyu.task_status')
      ->on('ensyu.todo.status_code', '=', 'ensyu.task_status.status_code');
    return $query;
  }

  public static function insert_task($description, $deadline)
  {
    $query = DB::insert('ensyu.todo')
      ->set([
        'description' => $description,
        'deadline' => $deadline
      ])
      ->execute();
  }

  public static function update_status($id, $status_code)
  {
    $query = DB::update('ensyu.todo')
      ->set([
        'status_code' => $status_code
      ])
      ->where('id', $id)
      ->execute();
  }

  public static function update_disctiption($id, $description)
  {
    $query = DB::update('ensyu.todo')
      ->set([
        'description' => $description
      ])
      ->where('id', $id)
      ->execute();
  }

  public static function update_deadline($id, $deadline)
  {
    $query = DB::update('ensyu.todo')
      ->set([
        'deadline' => $deadline
      ])
      ->where('id', $id)
      ->execute();
  }

  public static function delete_task($id)
  {
    $query = DB::delete('ensyu.todo')
      ->where('id', $id)
      ->execute();
  }
}
