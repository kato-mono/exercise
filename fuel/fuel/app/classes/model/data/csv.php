<?php

class Model_Data_Csv extends Model_Data
{
  public function __construct()
  {
    $headers = [
      'Content-Type' => 'application/octet-stream',
      'Content-Disposition' => 'attachment; filename="todo.csv"',
      'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate'
    ];

    parent::__construct($headers);
  }

  public function make_data($records)
  {
    $formated_data = "";

    foreach ($records as $record) {
      $formated_data .= implode(',', $record).PHP_EOL;
    }

    return $formated_data;
  }
}
