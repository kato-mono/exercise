(function() {
  var focusedTask = null; // フォーカスされたタスクのdescriptionを一時的に保持する変数

  var todo = {};  // イベントハンドラ用function群を保持する変数

  todo.focusSubmitValue = function() {
    focusedTask = $(this).val();
  };

  todo.blurSubmitValue = function() {
    if (focusedTask !== $(this).val()) {
      $(this).closest('form').submit();
    }

    focusedTask = null;
  };

  todo.clickSortButton = function() {
    // どのソートボタンが押されたかの情報を設定する
    $('#sort_by').val(
      $(this).val()
    );
    $(this).closest('form').submit();
  };

  /**
   * イベントハンドラに動作を設定する
   */
  var setTodoEvent = function() {
    $('.blur-submit')
      .focus(todo.focusSubmitValue)
      .blur(todo.blurSubmitValue);

    $('.sort-submit')
      .click(todo.clickSortButton);
  };

  $(function() {
    setTodoEvent();
  });

})();
