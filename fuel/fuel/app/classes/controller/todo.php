<?php
session_start();

class Controller_Todo extends Controller
{
  private $user;

  public function before()
  {
    // 利用者を識別する
    if ( isset($_SESSION['user']) )
    {
      $this->user = $_SESSION['user'];
    }
    else
    {
      var_dump('利用可能なユーザーでアクセスしてください。');
      exit;
    }
  }

  /**
   * アプリのメインページを表示する
   */
  public function action_main()
  {
    $sort_setting = Input::all();

    // Post送信されていない場合は規定値でソートして表示する
    if (Input::method() != 'POST')
    {
      $sort_setting =
        [
          'column' => 'status_code',
          'status_code' => 'desc',
          'deadline' => 'asc'
        ];
    }

    // ソート対象のカラム情報を送信内容から抜き出す
    $sort_by = $sort_setting['column'];

    if ($sort_setting[$sort_by] === 'asc')
    {
      $sort_setting[$sort_by] = 'desc';
    }
    else
    {
      $sort_setting[$sort_by] = 'asc';
    }

    $view = View::forge('todo')
      ->set('order_status_code', $sort_setting['status_code'])
      ->set('order_deadline', $sort_setting['deadline'])
      ->set(
        'task_list',
        $this->construct_task_list(
          (new Model_Todo())
            ->select_query()
            ->order_by($sort_by, $sort_setting[$sort_by])
            ->execute()
        )
      );

    return $view;
  }

  /**
   * Viewから受け取った新規タスクをDBに保存する
   */
  public function action_insert_task()
  {
    $insert_parameter = $this->recieve_correct_post_data();
    $insert_parameter += ['user_id' => $this->user];

    (new Model_Todo())
      ->insert_task($insert_parameter);

    return Response::redirect('todo/main');
  }

  /**
   * タスクの内容を更新する
   */
  public function action_update_task()
  {
    $update_parameter = $this->recieve_correct_post_data();
    $id = $update_parameter['id'];
    unset($update_parameter['id']);

    (new Model_Task($id))
      ->update_query($update_parameter)
      ->execute();

    return Response::redirect('todo/main');
  }

  /**
   * タスクを削除する
   */
  public function action_delete_task()
  {
    $delete_parameter = Input::all();

    (new Model_Task($delete_parameter['id']))
      ->delete_query()
      ->execute();

    return Response::redirect('todo/main');
  }

  public function action_404(){
    return 'お探しのページは見つかりませんでした';
  }

  /**
   * タスクリスト描画部を構築する
   */
  private function construct_task_list($todo_records)
  {
    $task_list_view = [];

    foreach ($todo_records as $todo_record)
    {
      // ステータスボタンのドロップダウン描画部を構築する
      $status_buttons = [];
      $status_records = (new Model_Status())->select();

      foreach ($status_records as $status_record)
      {
        // ドロップダウン描画部を１項目分構築する
        $status_buttons[] = View::forge('status')
          ->set('id', $todo_record['id'])
          ->set('status_code', $status_record['status_code'])
          ->set('description', $status_record['description']);
      }

      // タスク描画部を１箇所分構築する
      $view = View::forge('task')
        ->set('id', $todo_record['id'])
        ->set('status_description', $todo_record['status_description'])
        ->set('status_dropdown', $status_buttons)
        ->set('description', $todo_record['description'])
        ->set('deadline', $todo_record['deadline']);

      $task_list_view[] = $view;
    }
    return $task_list_view;
  }

  /**
   * Postされたデータを修正した状態で受け取る
   */
  private function recieve_correct_post_data()
  {
    $post_data = Input::all();

    if (array_key_exists('deadline', $post_data)
      && !($this->validate_datetime($post_data['deadline'])))
    {
      $post_data['deadline'] = '0';  // 日付の書式が不正な場合の規定値
    }

    return $post_data;
  }

  /**
   * mysqlに可換な日付書式であるか判定する
   */
  private function validate_datetime($datetime_str)
  {
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
