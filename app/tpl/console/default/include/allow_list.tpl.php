  <?php
  if (!isset($_is_edit)) {
    $_is_edit = true;
  }

  if (isset($adminRow)) {
    $_arr_adminRow = $adminRow;
  } else {
    $_arr_adminRow = array(
      'admin_type' => '',
    );
  } ?>
  <dl>
    <?php if ($_is_edit) { ?>
      <dd>
        <div class="form-check">
          <input type="checkbox" id="chk_all" data-parent="first" class="form-check-input">
          <label for="chk_all"  class="form-check-label">
            <?php echo $lang->get('All'); ?>
          </label>
        </div>
      </dd>
    <?php }

    foreach ($config['console']['console_mod'] as $_key_m=>$_value_m) { ?>
      <dt>
        <?php echo $lang->get($_value_m['main']['title'], 'console.common'); ?>
      </dt>
      <dd>
        <?php if ($_is_edit) { ?>
          <div class="form-check form-check-inline">
            <input type="checkbox" id="allow_<?php echo $_key_m; ?>" data-parent="chk_all" class="form-check-input">
            <label for="allow_<?php echo $_key_m; ?>" class="form-check-label">
              <?php echo $lang->get('All'); ?>
            </label>
          </div>
        <?php }

        foreach ($_value_m['allow'] as $_key_s=>$_value_s) {
          if ($_is_edit) { ?>
            <div class="form-check form-check-inline">
              <input type="checkbox" name="admin_allow[<?php echo $_key_m; ?>][<?php echo $_key_s; ?>]" value="1" id="allow_<?php echo $_key_m; ?>_<?php echo $_key_s; ?>" <?php if (isset($_arr_adminRow['admin_allow'][$_key_m][$_key_s]) || $_arr_adminRow['admin_type'] == 'super') { ?>checked<?php } ?> data-parent="allow_<?php echo $_key_m; ?>" data-validate="admin_allow" class="form-check-input">
              <label for="allow_<?php echo $_key_m; ?>_<?php echo $_key_s; ?>" class="form-check-label">
                <?php echo $lang->get($_value_s, 'console.common'); ?>
              </label>
            </div>
          <?php } else {
            if (isset($_arr_adminRow['admin_allow'][$_key_m][$_key_s]) || $_arr_adminRow['admin_type'] == 'super') {
              $str_icon  = 'check-circle';
              $str_color = 'success';
            } else {
              $str_icon  = 'times-circle';
              $str_color = 'danger';
            } ?>
            <span>
              <span class="bg-icon text-<?php echo $str_color; ?>"><?php include($cfg_global['pathIcon'] . $str_icon . BG_EXT_SVG); ?></span>
              <?php echo $lang->get($_value_s, 'console.common'); ?>
            </span>
          <?php }
        } ?>
      </dd>
    <?php } ?>

    <dt><?php echo $lang->get('System settings', 'console.common'); ?></dt>
    <dd>
      <?php if ($_is_edit) { ?>
        <div class="form-check form-check-inline">
          <input type="checkbox" id="allow_opt" data-parent="chk_all" class="form-check-input">
          <label for="allow_opt" class="form-check-label">
            <?php echo $lang->get('All'); ?>
          </label>
        </div>
      <?php }

      foreach ($config['console']['opt'] as $_key_s=>$_value_s) {
        if ($_is_edit) { ?>
          <div class="form-check form-check-inline">
            <input type="checkbox" name="admin_allow[opt][<?php echo $_key_s; ?>]" value="1" id="allow_opt_<?php echo $_key_s; ?>" data-parent="allow_opt" <?php if (isset($_arr_adminRow['admin_allow']['opt'][$_key_s]) || $_arr_adminRow['admin_type'] == 'super') { ?>checked<?php } ?> data-validate="admin_allow" class="form-check-input">
            <label for="allow_opt_<?php echo $_key_s; ?>" class="form-check-label">
              <?php echo $lang->get($_value_s['title'], 'console.common'); ?>
            </label>
          </div>
        <?php } else {
          if (isset($_arr_adminRow['admin_allow']['opt'][$_key_s]) || $_arr_adminRow['admin_type'] == 'super') {
            $str_icon  = 'check-circle';
            $str_color = 'success';
          } else {
            $str_icon  = 'times-circle';
            $str_color = 'danger';
          } ?>
          <span>
            <span class="bg-icon text-<?php echo $str_color; ?>"><?php include($cfg_global['pathIcon'] . $str_icon . BG_EXT_SVG); ?></span>
            <?php echo $lang->get($_value_s['title'], 'console.common'); ?>
          </span>
        <?php }
      } ?>
    </dd>
  </dl>
