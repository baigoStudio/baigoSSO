  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a href="<?php echo $route_console; ?>plugin/show/dir/<?php echo $pluginRow['plugin_dir']; ?>/" class="nav-link<?php if ($route['act'] == 'show') { ?> active<?php } ?>">
          <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'eye' . BG_EXT_SVG); ?></span>
          <?php echo $lang->get('Show'); ?>
        </a>
      </li>
      <?php if ($pluginRow['plugin_status'] == 'enable') { ?>
        <li class="nav-item">
          <a href="<?php echo $route_console; ?>plugin/form/dir/<?php echo $pluginRow['plugin_dir']; ?>/" class="nav-link<?php if ($route['act'] == 'form') { ?> active<?php } ?>">
            <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'edit' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Edit'); ?>
          </a>
        </li>
        <?php if ($pluginOpts) { ?>
          <li class="nav-item">
            <a href="<?php echo $route_console; ?>plugin/opts/dir/<?php echo $pluginRow['plugin_dir']; ?>/" class="nav-link<?php if ($route['act'] == 'opts') { ?> active<?php } ?>">
              <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'wrench' . BG_EXT_SVG); ?></span>
              <?php echo $lang->get('Option'); ?>
            </a>
          </li>
        <?php }
      } else { ?>
        <li class="nav-item">
          <a href="<?php echo $route_console; ?>plugin/form/dir/<?php echo $pluginRow['plugin_dir']; ?>/" class="nav-link<?php if ($route['act'] == 'form') { ?> active<?php } ?>">
            <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'edit' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Install'); ?>
          </a>
        </li>
      <?php } ?>
    </ul>
  </div>
