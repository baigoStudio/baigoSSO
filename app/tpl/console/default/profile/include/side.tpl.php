  <div class="col-xl-3">
    <div class="card bg-light">
      <div class="card-body">
        <div class="form-group">
          <label class="text-muted font-weight-light"><?php echo $lang->get('ID'); ?></label>
          <div><?php echo $adminLogged['admin_id']; ?></div>
        </div>

        <div class="form-group">
          <label class="text-muted font-weight-light"><?php echo $lang->get('Status'); ?></label>
          <div class="form-text font-weight-bolder"><?php $str_status = $adminLogged['admin_status'];
          include($tpl_include . 'status_process' . GK_EXT_TPL); ?></div>
        </div>

        <div class="form-group">
          <label class="text-muted font-weight-light"><?php echo $lang->get('Type'); ?></label>
          <div class="form-text font-weight-bolder">
            <?php echo $lang->get($adminLogged['admin_type']); ?>
          </div>
        </div>

        <div class="form-group">
          <?php foreach ($config['console']['profile_mod'] as $key=>$value) {
            if (isset($adminLogged['admin_allow_profile'][$key])) { ?>
              <div class="form-text font-weight-bolder">
                <span class="badge badge-danger">
                  <?php echo $lang->get('Not allowed to edit'), '&nbsp;', $lang->get($value['title'], 'console.common'); ?>
                </span>
              </div>
            <?php }
          } ?>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">
          <?php echo $lang->get('Save'); ?>
        </button>
      </div>
    </div>
  </div>
