<?php

class Model_Status extends \Model {

  public static function select_all()
  {
    $query = DB::select('*')
      ->from('task_status')
      ->execute();
    return $query;
  }
}
