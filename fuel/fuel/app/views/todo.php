<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>TODO</title>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <?php echo Asset::js("todo.js"); ?>
</head>
<body>

  <form action="insert_task" method="POST">
    <div class="list-group">
      <div class="row list-group-item">
        <button type="button" class="col-xs-1 col-ms-1 col-md-1 col-lg-1">
          <span class="glyphicon"></span>
        </button>
        <input name="description" type="text" class="col-xs-8 col-ms-8 col-md-8 col-lg-8" placeholder="タスクを入力" maxlength="100">
        <input name="deadline" type="text" class="col-xs-2 col-ms-2 col-md-2 col-lg-2" placeholder="yyyy-mm-dd hh:mm:ss" maxlength="100">
        <button type="submit" class="col-xs-1 col-ms-1 col-md-1 col-lg-1">
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </div>
    </div>
  </form>

  <div class="list-group">
    <div class="row list-group-item">
      <span class="label label-default">order by:</span>
      <form action="main" method="post">
        <input id='sort_by' type="hidden" name="column" value="">
        <input type="hidden" name="status_code" value="<?php echo $order_status_code; ?>">
        <input type="hidden" name="deadline" value="<?php echo $order_deadline; ?>">
        <button class="sort-submit btn btn-default" type="button" value="status_code">ステータス</button>
        <button class="sort-submit btn btn-default" type="button" value="deadline">期限</button>
      </form>
    </div>
  </div>

  <div class="list-group">
    <?php
      // 入れ子のViewを展開して表示する
      foreach ($task_list as $task) {
        echo $task;
      }
    ?>
  </div>

</body>
</html>
