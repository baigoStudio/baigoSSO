<?php $cfg = array(
  'title'             => $lang->get('Administrator', 'console.common') . ' &raquo; ' . $lang->get('Show'),
  'menu_active'       => 'admin',
  'sub_active'        => 'index',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $route_console; ?>admin/" class="nav-link">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <div class="row">
    <div class="col-xl-9">
      <div class="card mb-3">
        <div class="card-body">
          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Username'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $adminRow['admin_name']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Nickname'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $adminRow['admin_nick']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Permission'); ?></label>

            <?php $_is_edit = false;
            include($cfg['pathInclude'] . 'allow_list' . GK_EXT_TPL); ?>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Note'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $adminRow['admin_note']; ?></div>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="<?php echo $route_console; ?>admin/form/id/<?php echo $adminRow['admin_id']; ?>/">
            <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'edit' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Edit'); ?>
          </a>
        </div>
      </div>
    </div>

    <div class="col-xl-3">
      <div class="card bg-light">
        <div class="card-body">
          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('ID'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $adminRow['admin_id']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Status'); ?></label>
            <div class="form-text font-weight-bolder"><?php $str_status = $adminRow['admin_status'];
            include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Type'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $lang->get($adminRow['admin_type']); ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Personal permission'); ?></label>
            <div>
              <?php foreach ($config['console']['profile_mod'] as $key=>$value) {
                if (isset($adminRow['admin_allow_profile'][$key])) { ?>
                  <div>
                    <span class="badge badge-danger">
                      <?php echo $lang->get('Not allowed to edit'), '&nbsp;', $lang->get($value['title'], 'console.common'); ?>
                    </span>
                  </div>
                <?php }
              } ?>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="<?php echo $route_console; ?>admin/form/id/<?php echo $adminRow['admin_id']; ?>/">
            <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'edit' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Edit'); ?>
          </a>
        </div>
      </div>
    </div>
  </div>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
