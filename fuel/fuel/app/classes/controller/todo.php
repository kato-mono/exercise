<?php

class Controller_Todo extends Controller
{
  /**
   * アプリのメインページを表示する
   */
  public function action_main()
  {
    $view = View::forge('todo')
      ->set('task_list', $this->construct_task_list($this->fetch_task_list_data()));
    return $view;
  }

  /**
   * タスクリストをソートする
   */
  public function action_sort_task_list()
  {
    $column = Input::param('column');
    $order = Input::param('order');

    $view = View::forge('todo')
      ->set('task_list',
        $this->construct_task_list(
          $this->fetch_task_list_data_orderd($column, $order)
        )
      );

    return $view->render();
  }

  /**
   * Viewから受け取った新規タスクをDBに保存する
   */
  public function action_insert_task(){
    $description = Input::param('description');
    $deadline = Input::param('deadline');
    if(!($this->validate_datetime($deadline)))
    {
      $deadline = '0';  // 日付の書式が不正な場合の規定値
    }
    Model_Todo::insert_task($description, $deadline);

    return Response::redirect('todo/main');
  }

  /**
   * タスクのステータスを更新する
   */
  public function action_update_status()
  {
    $id = Input::param('id');
    $status_code = Input::param('status');
    Model_Todo::update_status($id, $status_code);
  }

  /**
   * タスクの記述を更新する
   */
  public function action_update_description()
  {
    $id = Input::param('id');
    $description = Input::param('description');
    Model_Todo::update_disctiption($id, $description);
  }

  /**
   * タスクの期限を更新する
   */
  public function action_update_deadline()
  {
    $id = Input::param('id');
    $deadline = Input::param('deadline');
    if($this->validate_datetime($deadline))
    {
      Model_Todo::update_deadline($id, $deadline);
      return Response::forge('yes');
    }
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
   * タスクリスト描画部構築用データを取得する
   */
  public function fetch_task_list_data()
  {
    return Model_Todo::select_all();
  }

  /**
   * タスクリスト描画部構築用データ(ソート済)を取得する
   */
  public function fetch_task_list_data_orderd($column, $order)
  {
    return Model_Todo::select_all_orderby($column, $order);
  }

  /**
   * タスクリスト描画部を構築する
   */
  public function construct_task_list($task_list_data)
  {
    $task_list = [];
    foreach ($task_list_data as $item) {
      $task_list[] = $this->construct_task($item['id'], $item['status_description'], $item['description'], $item['deadline']);
    }
    return $task_list;
  }

  /**
   * タスク描画部を１箇所分構築する
   */
  public function construct_task($id, $status_description, $description, $deadline)
  {
    $view = View::forge('task')
      ->set('id', $id)
      ->set('status_description', $status_description)
      ->set('status_dropdown', $this->construct_status_dropdown())
      ->set('description', $description)
      ->set('deadline', $deadline);
    return $view;
  }

  /**
   * ステータスボタンのドロップダウン描画部を構築する
   */
  public function construct_status_dropdown()
  {
    $status_buttons = [];
    $result = Model_Status::select_all();
    foreach ($result as $item) {
      $status_buttons[] = $this->construct_status_dropdown_item($item['status_code'],$item['description']);
    }
    return $status_buttons;
  }

  /**
   * ドロップダウン描画部を１項目分構築する
   */
  public function construct_status_dropdown_item($status_code, $status_description)
  {
    $view = View::forge('status')
      ->set('status_code', $status_code)
      ->set('status_description', $status_description);
    return $view;
  }

  /**
   * mysqlに可換な日付書式であるか判定する
   */
  public function validate_datetime($datetime_str){
    return
      $datetime_str === date("Y-m-d", strtotime($datetime_str))
      or
      $datetime_str === date("Y-m-d H:i:s", strtotime($datetime_str));
  }
}
