<?php
session_start();

class Controller_Todo extends Controller
{
  private $user;
  private $model_todo;

  public function before()
  {
    // 利用者を識別する
    if (isset($_SESSION['user']))
    {
      $this->user = $_SESSION['user'];
    }
    else
    {
      var_dump('利用可能なユーザーでアクセスしてください。');
      exit;
    }

    $this->model_todo = new Model_Todo($this->user);

    if (!isset($_SESSION['sort_setting']))
    {
      $_SESSION['sort_setting'] = $this->model_todo
        ->make_initial_setting('sort_setting');
    }

    if (!isset($_SESSION['search_keyword']))
    {
      $_SESSION['search_keyword'] = $this->model_todo
        ->make_initial_setting('search_keyword');
    }
  }

  /**
   * アプリのメインページを表示する
   */
  public function action_main()
  {
    $sort_setting = $_SESSION['sort_setting'];
    $search_keyword = $_SESSION['search_keyword'];

    $todo_records = $this->model_todo
      ->search_task($search_keyword, $sort_setting);

    $task_list_view = $this->construct_task_list($todo_records);

    $view = View::forge('todo')
      ->set('search_keyword', $search_keyword)
      ->set('task_list', $task_list_view);

    return $view;
  }

  /**
   * ダウンロードコンテンツを返す
   */
  public function action_download_content()
  {
    $content_type = Input::post('content_type');
    $data_format = null;

    if ($content_type === 'csv')
    {
      $data_format = new Model_Data_Csv();
    }
    else if ($content_type === 'xml')
    {
      $data_format = new Model_Data_Xml();
    }
    else if ($content_type === 'json')
    {
      $data_format = new Model_Data_Json();
    }
    else
    {
      var_dump('content_typeが 不正です。');
      exit;
    }

    // 書き出すデータを取得する
    $records = $this->model_todo
      ->search_task($_SESSION['search_keyword'], $_SESSION['sort_setting']);

    return $data_format->make_response($records);
  }

  /**
   * 指定された条件に合致するタスクを表示する
   */
  public function action_search_task()
  {
    $parameter = Input::all();

    $_SESSION['sort_setting'] = $this->model_todo
      ->change_sort_order($parameter['sort_by'], $_SESSION['sort_setting']);

    $_SESSION['search_keyword'] = trim($parameter['search_keyword']);

    return Response::redirect('todo/main');
  }

  /**
   * Viewから受け取った新規タスクをDBに保存する
   */
  public function action_insert_task()
  {
    $insert_parameter = $this->recieve_correct_post_data();
    $insert_parameter += ['user_id' => $this->user];

    $this->model_todo
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

    (new Model_Task($id, $this->user))
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

    (new Model_Task($delete_parameter['id'], $this->user))
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
