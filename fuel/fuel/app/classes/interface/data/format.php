<?php

interface Interface_Data_Format
{
  public function make_response($records);

  public function make_data($records);
}
