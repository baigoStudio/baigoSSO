  <hr>
  <div class="btn-toolbar justify-content-between">
    <div class="btn-group">
      <?php if ($route['act'] != $step['prev']) { ?>
        <a href="<?php echo $route_install, $route['ctrl']; ?>/<?php echo $step['prev']; ?>/" class="btn btn-outline-secondary"><?php echo $lang->get('Previous'); ?></a>
      <?php }

      if ($route['act'] != $step['next']) { ?>
        <a href="<?php echo $route_install, $route['ctrl']; ?>/<?php echo $step['next']; ?>/" class="btn btn-outline-secondary"><?php echo $lang->get('Skip'); ?></a>
      <?php } ?>
    </div>
    <?php if (isset($err_count) && $err_count > 0) { ?>
      <div class="btn-group">
        <button type="button" class="btn btn-primary disabled"><?php echo $lang->get($cfg['btn']); ?></button>
      </div>
    <?php } else { ?>
      <div class="btn-group">
        <?php if (isset($cfg['btn_link'])) { ?>
          <a href="<?php echo $route_install, $route['ctrl']; ?>/<?php echo $step['next']; ?>/" class="btn btn-primary"><?php echo $lang->get($cfg['btn']); ?></a>
        <?php } else { ?>
          <button type="submit" class="btn btn-primary"><?php echo $lang->get($cfg['btn']); ?></button>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
