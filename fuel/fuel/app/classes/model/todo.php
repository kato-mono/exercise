<?php

class Model_Todo extends \Model {

  public static function select_all()
  {
    $query = DB::select('*');
    $query->from('ensyu.todo');
    return $query->execute();
  }

  public static function insert_task($description)
  {
    $status = 0; // ステータスの初期状態

    $query = DB::insert('ensyu.todo');
    $query->set(array(
      'status' => $status,
      'description' => $description
    ));
    $query->execute();
  }

  public static function update_status($id, $status)
  {
    $query = DB::update('ensyu.todo');
    $query->set(array(
      'status' => $status
    ));
    $query->where('id', $id);
    $query->execute();
  }

  public static function update_disctiption($id, $description)
  {
    $query = DB::update('ensyu.todo');
    $query->set(array(
      'description' => $description
    ));
    $query->where('id', $id);
    $query->execute();
  }

  public static function delete_task($id)
  {
    $query = DB::delete('ensyu.todo');
    $query->where('id', $id);
    $query->execute();
  }
}
