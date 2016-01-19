<?php

class Controller_Todo extends Controller
{
  /**
   * アプリのメインページを表示する
   */
  public function action_main()
  {
    // タスクリスト描画部を構築する
    $task_list = [];
    $result = Model_Todo::select_all();
    foreach ($result as $item) {
      $task_list[] = $this->construct_task($item['id'], $item['status'], $item['description']);
    }

    $view = View::forge('todo')
      ->set('task_list', $task_list);
    return $view;
  }

  /**
   * Viewから受け取った新規タスクをDBに保存する
   */
  public function action_insert_task(){
    $description = Input::param('description');
    Model_Todo::insert_task($description);
    return Request::forge('todo/main')->execute();
  }

  /**
   * タスクのステータスを更新する
   */
  public function action_update_status()
  {
    $id = Input::param('id');
    $status = Input::param('status');
    Model_Todo::update_status($id, $status);
  }

  /**
   * タスクの内容を更新する
   */
  public function action_update_description()
  {
    $id = Input::param('id');
    $description = Input::param('description');
    Model_Todo::update_disctiption($id, $description);
  }

  /**
   * タスクを削除する
   */
  public function action_delete_task()
  {
    $id = Input::param('id');
    Model_Todo::delete_task($id);
  }

  public function action_404(){
    return 'お探しのページは見つかりませんでした';
  }

  /**
   * タスク描画部を１箇所分構築する
   */
  public function construct_task($id, $status, $description)
  {
    $icon = 'glyphicon-ok';  // 既存のタスクでステータスが完了状態のものにアイコンを表示するための設定値

    $view = View::forge('task')
      ->set('id', $id)
      ->set('description', $description);
    if($status === 1) {
      $view->set('status', $icon);
    } else {
      $view->set('status', '');
    }
    return $view;
  }
}
