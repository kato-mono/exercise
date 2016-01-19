<?php

class Model_Todo extends \Model {

  public static function select_all()
  {
    $query = DB::select('*')
      ->from('ensyu.todo')
      ->execute();
    return $query;
  }

  public static function insert_task($description)
  {
    $status = 0; // ステータスの初期状態

    $query = DB::insert('ensyu.todo')
      ->set([
        'status' => $status,
        'description' => $description
      ])
      ->execute();
  }

  public static function update_status($id, $status)
  {
    $query = DB::update('ensyu.todo')
      ->set([
        'status' => $status
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

  public static function delete_task($id)
  {
    $query = DB::delete('ensyu.todo')
      ->where('id', $id)
      ->execute();
  }
}
