;(function() {
  var focusedTask = null;

  $(function() {
    $('.insert-btn').click(function() {
      var target = $('in-form').get();
      target.submit();
    });

    $('.output-task').focus(function() {
      focusedTask = $(this).val();
    }).blur(function() {
      if (focusedTask !== $(this).val()) {

        // DB上のdescriptionを上書きする
        var array = {};
        array.id = $(this).parent().attr('id');
        array.description = $(this).val();
        $.post('upddescription', array, null);
      }

      focusedTask = null;
    });

    $('.status-btn').click(function() {
      var status = null;

      if ($(this).children('span').hasClass('glyphicon-ok')) {
        $(this).children('span').removeClass('glyphicon-ok');
        status = 0;
      } else {
        $(this).children('span').addClass('glyphicon-ok');
        status = 1;
      }

      // DB上のstatusを上書きする
      var array = {};
      array.id = $(this).parent().attr('id');
      array.status = status;
      $.post('updstatus', array, null);
    });

    $('.delete-btn').click(function() {
      // DB上のタスクを削除する
      var array = {};
      array.id = $(this).parent().attr('id');
      $.post('deltask', array, null);

      // タスクのhtml要素を削除する
      $('.row#' + $(this).parent().attr('id')).remove();
    });
  });

})();
