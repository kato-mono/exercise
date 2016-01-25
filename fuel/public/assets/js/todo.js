(function() {
  var focusedTask = null; // フォーカスされたタスクのdescriptionを一時的に保持する変数

  var eventAction = {};  // イベントハンドラ用function群を保持する変数

  eventAction.focusSubmitValue = function() {
    focusedTask = $(this).val();
  };

  eventAction.blurSubmitValue = function() {
    if (focusedTask !== $(this).val()) {
      $(this).closest('form').submit();
    }

    focusedTask = null;
  };

  eventAction.clickSortButton = function() {
    // どのソートボタンが押されたかの情報を設定する
    $('#sort_by').val(
      $(this).val()
    );
    $(this).closest('form').submit();
  };

  /**
   * イベントハンドラに動作を設定する
   */
  var setEventAction = function() {
    $('.blur-submit')
      .focus(eventAction.focusSubmitValue)
      .blur(eventAction.blurSubmitValue);

    $('.sort-submit')
      .click(eventAction.clickSortButton);
  };

  $(function() {
    setEventAction();
  });

})();
