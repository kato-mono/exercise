<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>TODO</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="/js/todo.js"></script>
</head>
<body>

  <div class="list-group">
    <div class="row list-group-item">
      <button type="button" class="col-xs-1 col-ms-1 col-md-1 col-lg-1">
        <span class="glyphicon"></span>
      </button>
      <input type="text" class="input-task col-xs-10 col-ms-10 col-md-10 col-lg-10" maxlength="100">
      <button type="button" class="col-xs-1 col-ms-1 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-plus"></span>
      </button>
    </div>
  </div>


  <div class="list-group">
    <div class="row list-group-item" id="1">
      <button type="button" class="status-btn col-xs-1 col-ms-1 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-ok"></span>
      </button>
      <input type="text" class="output-task col-xs-10 col-ms-10 col-md-10 col-lg-10" maxlength="100">
      <button type="button" class="delete-btn col-xs-1 col-ms-1 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-trash"></span>
      </button>
    </div>

    <div class="row list-group-item" id="2">
      <button type="button" class="status-btn ccol-xs-1 col-ms-1 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-ok"></span>
      </button>
      <input type="text" class="output-task col-xs-10 col-ms-10 col-md-10 col-lg-10" maxlength="100">
      <button type="button" class="delete-btn col-xs-1 col-ms-1 col-md-1 col-lg-1">
        <span class="glyphicon glyphicon-trash"></span>
      </button>
    </div>
  </div>
</body>
</html>
