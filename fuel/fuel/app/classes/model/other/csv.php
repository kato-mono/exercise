<?php

class Model_Other_Csv implements Model_Other_Content
{
  private $file_path;

  public function make_response()
  {
    $response = new Response();
    $response->set_header('Content-Type', 'application/octet-stream')
      ->set_header('Content-Disposition', 'attachment; filename="todo.csv"')
      ->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');

    readfile($this->file_path);

    // 一時ファイルを削除する
    unlink($this->file_path);

    return $response;
  }

  public function make_data($records)
  {
    $this->file_path = '/tmp/' . time() . '.csv';

    $file = new SplFileObject($this->file_path, 'w');

    foreach ($records as $record) {
      $file->fputcsv($record);
    }
  }
}
