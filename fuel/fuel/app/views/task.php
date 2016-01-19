<div class="row list-group-item" id="<?php echo $id; ?>">
  <button type="button" class="status-button col-xs-1 col-ms-1 col-md-1 col-lg-1">
    <span class="glyphicon <?php echo $status; ?>"></span>
  </button>
  <input type="text" class="output-task col-xs-10 col-ms-10 col-md-10 col-lg-10" value="<?php echo $description; ?>" maxlength="100">
  <button type="button" class="delete-button col-xs-1 col-ms-1 col-md-1 col-lg-1">
    <span class="glyphicon glyphicon-trash"></span>
  </button>
</div>
