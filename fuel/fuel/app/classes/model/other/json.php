<?php

class Model_Other_Json implements Model_Other_Content
{
  private $json;

  public function make_response()
  {
    $response = new Response();
    $response->set_header('Content-Type', 'application/json')
      ->set_header('Content-Disposition', 'attachment; filename="todo.json"')
      ->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
      ->body($this->json);

    return $response;
  }

  public function make_data($records)
  {
    $rows = null;
    foreach ($records as $record) {
      $rows[] = [
          'id' => $record['id'],
          'status_description' => $record['status_description'],
          'status_code' => $record['status_code'],
          'description' => $record['description'],
          'deadline' => $record['deadline']
        ];
    }
    $this->json = json_encode($rows);
  }
}
