(function() {
  var focusedTask = null; // フォーカスされたタスクのdescriptionを一時的に保持する変数

  var eventAction = {};  // イベントハンドラ用function群を保持する変数

  var order = { status: 'asc', deadline: 'desc' };

  eventAction.focusOutputDescription = function() {
    focusedTask = $(this).val();
  };

  eventAction.blurOutputDescription = function() {
    if (focusedTask !== $(this).val()) {

      // DB上のdescriptionを上書きする
      var taskAttrebutes = {};
      taskAttrebutes.id = $(this).closest('.row').attr('id');
      taskAttrebutes.description = $(this).val();
      $.post('update_description', taskAttrebutes, null);
    }

    focusedTask = null;
  };

  eventAction.focusOutputDeadline = function() {
    focusedTask = $(this).val();
  };

  eventAction.blurOutputDeadline = function() {
    if (focusedTask !== $(this).val()) {
      // DB上のdeadlineを上書きする
      var taskAttrebutes = {};
      taskAttrebutes.id = $(this).closest('.row').attr('id');
      taskAttrebutes.deadline = $(this).val();
      $.post(
        'update_deadline',
        taskAttrebutes,
        function(wasUpdated, type) {
          // 入力された日付の書式、または内容に問題がある場合
          if (wasUpdated !== 'yes') {
            location.reload();
          }
        }
      );
    }

    focusedTask = null;
  };

  eventAction.clickOrderButton = function() {
    var targetName = $(this).attr('name');
    var target = null;

    // DBのカラムstatus_codeのみ配列のキーとしてつかえないため(スネークなので)独自のキー名へ変換する
    if ($(this).attr('name') === 'status_code') {
      target = 'status';
    } else {
      target = targetName;
    }

    var taskAttrebutes = {};
    taskAttrebutes.column = $(this).attr('name');
    taskAttrebutes.order = order[target];
    if (order[target] === 'asc') {
      order[target] = 'desc';
    } else {
      order[target] = 'asc';
    }

    $.post(
      'sort_task_list',
      taskAttrebutes,
      function(view, type) {
        document.body.innerHTML = view;
        setEventAction();
      }
    );
  };

  eventAction.clickStatusButton = function() {
    // DB上のstatusを上書きする
    var taskAttrebutes = {};
    taskAttrebutes.id = $(this).closest('.row').attr('id');
    taskAttrebutes.status = $(this).closest('li').val();
    $.post('update_status', taskAttrebutes, null);

    // ボタンの文言を選択されたステータスの記述に変更する
    var statusDescription = $(this).text();
    $(this).closest('.dropdown').children('button').text(statusDescription);
  };

  eventAction.clickDeleteButton = function() {
    // DB上のタスクを削除する
    var taskAttrebutes = {};
    taskAttrebutes.id = $(this).closest('.row').attr('id');
    $.post('delete_task', taskAttrebutes, null);

    // タスクのhtml要素を削除する
    $('.row#' + $(this).closest('.row').attr('id')).remove();
  };

  /**
   * イベントハンドラに動作を設定する
   */
  var setEventAction = function() {
    $('.output-description')
      .focus(eventAction.focusOutputDescription)
      .blur(eventAction.blurOutputDescription);

    $('.output-deadline')
      .focus(eventAction.focusOutputDeadline)
      .blur(eventAction.blurOutputDeadline);

    $('.order-button').click(eventAction.clickOrderButton);

    $('.status-button').click(eventAction.clickStatusButton);

    $('.delete-button').click(eventAction.clickDeleteButton);
  };

  $(function() {
    setEventAction();
  });

})();
