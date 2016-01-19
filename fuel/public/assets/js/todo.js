;(function() {
  var focusedTask = null;

  $(function() {
    $('.insert-button').click(function() {
      var target = $('in-form').get();
      target.submit();
    });

    $('.output-task').focus(function() {
      focusedTask = $(this).val();
    }).blur(function() {
      if (focusedTask !== $(this).val()) {

        // DB上のdescriptionを上書きする
        var taskAttrebutes = {};
        taskAttrebutes.id = $(this).parent().attr('id');
        taskAttrebutes.description = $(this).val();
        $.post('update_description', taskAttrebutes, null);
      }

      focusedTask = null;
    });

    $('.status-button').click(function() {
      var status = null;

      if ($(this).children('span').hasClass('glyphicon-ok')) {
        $(this).children('span').removeClass('glyphicon-ok');
        status = 0;
      } else {
        $(this).children('span').addClass('glyphicon-ok');
        status = 1;
      }

      // DB上のstatusを上書きする
      var taskAttrebutes = {};
      taskAttrebutes.id = $(this).parent().attr('id');
      taskAttrebutes.status = status;
      $.post('update_status', taskAttrebutes, null);
    });

    $('.delete-button').click(function() {
      // DB上のタスクを削除する
      var taskAttrebutes = {};
      taskAttrebutes.id = $(this).parent().attr('id');
      $.post('delete_task', taskAttrebutes, null);

      // タスクのhtml要素を削除する
      $('.row#' + $(this).parent().attr('id')).remove();
    });
  });

})();
