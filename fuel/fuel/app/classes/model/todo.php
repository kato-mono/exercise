<?php

class Model_Todo extends \Model {

  public static function select_all()
  {
    $query = DB::query('SELECT * FROM ensyu.todo');
    return $query->execute();
  }

  public static function insert_task($description)
  {
    $status = 0; // ステータスの初期状態

    $query = DB::query('INSERT INTO ensyu.todo (status, description) VALUES(:status, :description);');
    $query->param('status', $status);
    $query->param('description', $description);
    $query->execute();
  }

  public static function update_status($id, $status)
  {
    $query = DB::query('UPDATE ensyu.todo SET status=:status WHERE id=:id');
    $query->param('id', $id);
    $query->param('status', $status);
    $query->execute();
  }

  public static function update_disctiption($id, $description)
  {
    $query = DB::query('UPDATE ensyu.todo SET description=:description WHERE id=:id');
    $query->param('id', $id);
    $query->param('description', $description);
    $query->execute();
  }

  public static function delete_task($id)
  {
    $query = DB::query('DELETE FROM ensyu.todo WHERE id=:id');
    $query->param('id', $id);
    $query->execute();
  }
}
