<?php

class Model_Task extends Model_Todo {
  private $id;

  public function __construct($id) {
    $this->id = $id;
  }

  // $set_valueに入力される連想配列のキーはDB上のカラム名とする
  private function update_query($set_value)
  {
    $query = DB::update($this->from)
      ->set($set_value)
      ->where('id', $this->id);
    return $query;
  }

  public function delete_query()
  {
    $query = DB::delete($this->from)
      ->where('id', $this->id);
    return $query;
  }
}
