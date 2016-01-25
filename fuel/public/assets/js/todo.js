(function() {
  var focusedTask = null; // フォーカスされたタスクのdescriptionを一時的に保持する変数

  var eventAction = {};  // イベントハンドラ用function群を保持する変数

  var order = { status: 'asc', deadline: 'desc' };

  eventAction.focusSubmitValue = function() {
    focusedTask = $(this).val();
  };

  eventAction.blurSubmitValue = function() {
    if (focusedTask !== $(this).val()) {
      // どのソートボタンが押されたかの情報を設定する
      $('#sort_by').val(
        $(this).attr('name').val()
      );
      $(this).closest('form').get().submit;
    }

    focusedTask = null;
  };

  /**
   * イベントハンドラに動作を設定する
   */
  var setEventAction = function() {
    $('.blur-submit')
      .focus(eventAction.focusSubmitValue)
      .blur(eventAction.blurSubmitValue);
  };

  $(function() {
    setEventAction();
  });

})();
