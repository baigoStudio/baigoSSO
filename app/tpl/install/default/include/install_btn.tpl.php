  <hr>
  <div class="btn-toolbar justify-content-between">
    <div class="btn-group">
      <?php if ($route['act'] != $step['prev']['act']) { ?>
        <a href="<?php echo $step['prev']['href']; ?>" class="btn btn-outline-secondary"><?php echo $lang->get('Previous'); ?></a>
      <?php }

      if ($route['act'] != $step['next']['act']) { ?>
        <a href="<?php echo $step['next']['href']; ?>" class="btn btn-outline-secondary"><?php echo $lang->get('Skip'); ?></a>
      <?php } ?>
    </div>
    <?php if (isset($err_count) && $err_count > 0) { ?>
      <div class="btn-group">
        <button type="button" class="btn btn-primary disabled"><?php echo $lang->get($cfg['btn']); ?></button>
      </div>
    <?php } else { ?>
      <div class="btn-group">
        <?php if (isset($cfg['btn_link'])) { ?>
          <a href="<?php echo $step['next']['href']; ?>" class="btn btn-primary"><?php echo $lang->get($cfg['btn']); ?></a>
        <?php } else { ?>
          <button type="submit" class="btn btn-primary"><?php echo $lang->get($cfg['btn']); ?></button>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
