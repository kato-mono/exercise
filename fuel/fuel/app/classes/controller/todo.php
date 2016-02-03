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
    $parameter = Input::all();
    $sort_setting = null;

    // Post送信されていない場合は規定値を使用する
    if (Input::method() != 'POST')
    {
      if (isset($_SESSION['search_keyword']) and isset($_SESSION['sort_setting']))
      {
        $search_keyword = $_SESSION['search_keyword'];
        $sort_setting = $_SESSION['sort_setting'];
      }
      else
      {
        $sort_setting =
          [
            'column' => 'status_code',
            'status_code' => 'desc',
            'deadline' => 'asc'
          ];
        $search_keyword = '';
      }
    }
    else
    {
      $sort_setting =
        [
          'column' => $parameter['column'],
          'status_code' => $parameter['status_code'],
          'deadline' => $parameter['deadline']
        ];
      $search_keyword = $parameter['search_keyword'];

      // ソート対象のカラム情報を送信内容から抜き出す
      $sort_by = $sort_setting['column'];

      if ($sort_by === '')
      {
        $sort_setting['column'] = 'status_code';
        $sort_by = $sort_setting['column'];
      }
      else if ($sort_setting[$sort_by] === 'asc')
      {
        $sort_setting[$sort_by] = 'desc';
      }
      else
      {
        $sort_setting[$sort_by] = 'asc';
      }
    }

    $todo_records = (new Model_Todo($this->user))
      ->search_task($search_keyword, $sort_setting);

    $task_list_view = $this->construct_task_list($todo_records);

    $view = View::forge('todo')
      ->set('order_status_code', $sort_setting['status_code'])
      ->set('order_deadline', $sort_setting['deadline'])
      ->set('search_keyword', $search_keyword)
      ->set('task_list', $task_list_view);

    // 検索設定を保持する
    $_SESSION['search_keyword'] = $search_keyword;
    $_SESSION['sort_setting'] = $sort_setting;

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
    $records = null;
    if (isset($_SESSION['search_keyword']) and isset($_SESSION['sort_setting']))
    {
      $records = (new Model_Todo($this->user))
        ->search_task($_SESSION['search_keyword'], $_SESSION['sort_setting']);
    }
    else
    {
      var_dump('画面表示設定を読み込めませんでした。');
      exit;
    }

    return $data_format->make_response($records);
  }

  /**
   * Viewから受け取った新規タスクをDBに保存する
   */
  public function action_insert_task()
  {
    $insert_parameter = $this->recieve_correct_post_data();
    $insert_parameter += ['user_id' => $this->user];

    (new Model_Todo($this->user))
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
