<?php

class Model_Other_Xml implements Model_Other_Content
{
  private $xml;

  public function make_response()
  {
    $response = new Response();
    $response->set_header('Content-Type', 'application/xml')
      ->set_header('Content-Disposition', 'attachment; filename="todo.xml"')
      ->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
      ->body($this->xml);

    return $response;
  }

  public function make_data($records)
  {
    $root = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' ?><records></records>");
    foreach ($records as $record) {
      $row = $root->addChild('record');
      $row->addChild('id', $record['id']);
      $row->addChild('status_description', $record['status_description']);
      $row->addChild('status_code', $record['status_code']);
      $row->addChild('description', $record['description']);
      $row->addChild('deadline', $record['deadline']);
    }

    $this->xml = $root->asXML();
  }
}
