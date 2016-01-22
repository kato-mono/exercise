<?php

class Controller_Todo extends Controller
{
  /**
   * アプリのメインページを表示する
   */
  public function action_main()
  {
    $column = Input::param('column');
    $order = Input::param('order');

    // Post送信されていない場合は規定値でソートして表示する
    if(is_null($column) or is_null($order)) {
      $column = 'status_code';
      $order = 'asc';
    }

    $view = View::forge('todo')
      ->set('task_list',
        $this->construct_task_list(
          (new Model_Todo())
            ->select()
            ->order_by($column, $order)
            ->execute()
        )
      );

    return $view;
  }

  /**
   * Viewから受け取った新規タスクをDBに保存する
   */
  public function action_insert_task(){
    $input_all = Input::all();

    if(!($this->validate_datetime($input_all['deadline'])))
    {
      $input_all['deadline'] = '0';  // 日付の書式が不正な場合の規定値
    }
    (new Model_Todo())
      ->insert_task($input_all);

    return Response::redirect('todo/main');
  }

  /**
   * タスクの内容を更新する
   */
  public function action_update()
  {
    $input_all = Input::all();

    (new Model_Task($id))
      ->update_query($input_all)
      ->execute();

    return Response::redirect('todo/main');
  }

  /**
   * タスクを削除する
   */
  public function action_delete_task()
  {
    $id = Input::param('id');

    (new Model_Task($id))
      ->delete_task($id);

    return Response::redirect('todo/main');
  }

  public function action_404(){
    return 'お探しのページは見つかりませんでした';
  }

  /**
   * タスクリスト描画部を構築する
   */
  public function construct_task_list($todo_records)
  {
    $task_list_view = [];

    foreach ($todo_records as $todo_record) {
      // ステータスボタンのドロップダウン描画部を構築する
      $status_buttons = [];
      $status_records = (new Model_Status())->select();

      foreach ($status_records as $status_record) {
        // ドロップダウン描画部を１項目分構築する
        $status_buttons[] = View::forge('status')
          ->set('status_code', $status_record['status_code'])
          ->set('description', $status_record['description']);
      }

      // タスク描画部を１箇所分構築する
      $view = View::forge('task')
        ->set('id', $todo_record['id'])
        ->set('status_description', $todo_record['status_description'])
        ->set('status_dropdown', $todo_record['status_buttons'])
        ->set('description', $todo_record['description'])
        ->set('deadline', $todo_record['deadline']);

      $task_list_view[] = $view;
    }
    return $task_list_view;
  }

  /**
   * mysqlに可換な日付書式であるか判定する
   */
  public function validate_datetime($datetime_str){
    return
      $datetime_str === date(
        "Y-m-d",
        strtotime($datetime_str)
      )
      or
      $datetime_str === date(
        "Y-m-d H:i:s",
        strtotime($datetime_str)
      );
  }
}
