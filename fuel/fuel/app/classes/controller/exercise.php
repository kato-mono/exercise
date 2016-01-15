<?php

class Controller_Exercise extends Controller
{
  public function action_hello()
  {
    return $this->viewSentence('hello world');
  }

  public function action_date()
  {
    $date = new Datetime();
    return $this->viewSentence($date->format("Y-m-d H:i:s"));
  }

  public function action_404(){
    return $this->viewSentence('お探しのページは見つかりませんでした');
  }

  function viewSentence($str){
    $view = View::forge('sentence');
    $view->set('content', $str);
    return Response::forge($view);
  }
}
