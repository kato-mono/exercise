<li role="presentation">
  <form action="update_task" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="status_code" value="<?php echo $status_code; ?>">
    <button type="submit" class="btn btn-default"><?php echo $description; ?></button>
  </form>
</li>
