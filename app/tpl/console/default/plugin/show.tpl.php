<?php $cfg = array(
  'title'             => $lang->get('Plugin management', 'console.common') . ' &raquo; ' . $lang->get('Show'),
  'menu_active'       => 'plugin',
  'sub_active'        => 'index',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $hrefRow['index']; ?>" class="nav-link">
      <span class="bg-icon"><?php include($tpl_icon . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <div class="row">
    <div class="col-xl-9">
      <div class="card mb-3">
        <?php include($tpl_ctrl . 'menu' . GK_EXT_TPL); ?>

        <div class="card-body">
          <?php include($tpl_ctrl . 'detail' . GK_EXT_TPL); ?>

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

    <?php include($tpl_ctrl . 'side' . GK_EXT_TPL); ?>
  </div>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL);
include($tpl_include . 'html_foot' . GK_EXT_TPL);
