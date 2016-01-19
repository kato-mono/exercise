<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>TODO</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <?php echo Asset::js("todo.js"); ?>
</head>
<body>

  <form id='in-form' action="insert" method="POST">
    <div class="list-group">
      <div class="row list-group-item">
        <button type="button" class="col-xs-1 col-ms-1 col-md-1 col-lg-1">
          <span class="glyphicon"></span>
        </button>
        <input name="description" type="text" class="input-task col-xs-10 col-ms-10 col-md-10 col-lg-10" maxlength="100">
        <button type="submit" class="col-xs-1 col-ms-1 col-md-1 col-lg-1">
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </div>
    </div>
  </form>

  <div class="list-group">
    <?php
      // 入れ子のViewを展開して表示する
      foreach ($taskList as $task) {
        echo $task;
      }
    ?>
  </div>

</body>
</html>
