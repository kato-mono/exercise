<div class="row list-group-item">

  <div class="dropdown col-xs-1 col-ms-1 col-md-1 col-lg-1">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu<?php echo $id; ?>" data-toggle="dropdown">
      <?php echo $status_description; ?>
    </button>

    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu<?php echo $id; ?>">
      <?php
        // 入れ子のViewを展開して表示する
        foreach ($status_dropdown as $item) {
          echo $item;
        }
      ?>
    </ul>
  </div>

  <form action="update_task" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input name="description" type="text" class="blur-submit col-xs-8 col-ms-8 col-md-8 col-lg-8" value="<?php echo $description; ?>" maxlength="100">
  </form>

  <form action="update_task" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input name="deadline" type="text" class="blur-submit col-xs-2 col-ms-2 col-md-2 col-lg-2" value="<?php echo $deadline; ?>" maxlength="20">
  </form>

  <form action="delete_task" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <button type="submit" class="col-xs-1 col-ms-1 col-md-1 col-lg-1">
      <span class="glyphicon glyphicon-trash"></span>
    </button>
  </form>

</div>
