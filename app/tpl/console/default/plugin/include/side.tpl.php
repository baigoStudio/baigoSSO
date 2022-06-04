  <div class="col-xl-3">
    <div class="card">
      <div class="card-body bg-light">
        <div class="form-group">
          <label class="text-muted font-weight-light"><?php echo $lang->get('Status'); ?></label>
          <div class="form-text font-weight-bolder"><?php $str_status = $pluginRow['plugin_status'];
          include($tpl_include . 'status_process' . GK_EXT_TPL); ?></div>
        </div>

        <?php include($tpl_ctrl . 'detail' . GK_EXT_TPL); ?>
      </div>
    </div>
  </div>
