<div class="row list-group-item" id="<?php echo $id; ?>">

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

  <input type="text" class="output-description col-xs-8 col-ms-8 col-md-8 col-lg-8" value="<?php echo $description; ?>" maxlength="100">

  <input type="text" class="output-deadline col-xs-2 col-ms-2 col-md-2 col-lg-2" value="<?php echo $deadline; ?>" maxlength="20">

  <button type="button" class="delete-button col-xs-1 col-ms-1 col-md-1 col-lg-1">
    <span class="glyphicon glyphicon-trash"></span>
  </button>

</div>
