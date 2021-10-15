<?php $cfg = array(
  'title'             => $lang->get('Plugin management', 'console.common') . ' &raquo; ' . $lang->get('Show'),
  'menu_active'       => 'plugin',
  'sub_active'        => 'index',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $route_console; ?>plugin/" class="nav-link">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <div class="row">
    <div class="col-xl-9">
      <div class="card mb-3">
        <?php include($cfg['pathInclude'] . 'plugin_menu' . GK_EXT_TPL); ?>

        <div class="card-body">
          <?php include($cfg['pathInclude'] . 'plugin_detail' . GK_EXT_TPL); ?>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Directory'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $pluginRow['plugin_dir']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Note'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $pluginRow['plugin_note']; ?></div>
          </div>
        </div>
      </div>
    </div>

    <?php include($cfg['pathInclude'] . 'plugin_side' . GK_EXT_TPL); ?>
  </div>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
