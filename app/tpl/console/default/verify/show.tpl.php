  <div class="modal-header">
    <?php echo $lang->get('Validation token', 'console.common'), ' &raquo; ', $lang->get('Show'); ?>
    <button type="button" class="close" data-dismiss="modal">
      &times;
    </button>
  </div>
  <div class="modal-body">
    <div class="form-group">
      <label class="text-muted font-weight-light"><?php echo $lang->get('ID'); ?></label>
      <div class="form-text font-weight-bolder"><?php echo $verifyRow['verify_id']; ?></div>
    </div>

    <div class="form-group">
      <label class="text-muted font-weight-light"><?php echo $lang->get('Status'); ?></label>
      <div class="form-text font-weight-bolder">
        <?php $str_status = $verifyRow['verify_status'];
        include($path_tpl . 'include' . DS . 'status_process' . GK_EXT_TPL); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="text-muted font-weight-light"><?php echo $lang->get('Type'); ?></label>
      <div class="form-text font-weight-bolder"><?php echo $lang->get($verifyRow['verify_type']); ?></div>
    </div>

    <div class="form-group">
      <label class="text-muted font-weight-light"><?php echo $lang->get('Operator'); ?></label>
      <div class="form-text font-weight-bolder"><?php if (isset($verifyRow['userRow']['user_name'])) {
        echo $verifyRow['userRow']['user_name'];
      } else {
        echo $lang->get('Unknown');
      } ?></div>
    </div>

    <div class="form-group">
      <label class="text-muted font-weight-light"><?php echo $lang->get('Expiry'); ?></label>
      <div class="form-text font-weight-bolder"><?php echo $verifyRow['verify_time_expire_format']['date_time']; ?></div>
    </div>

    <div class="form-group">
      <label class="text-muted font-weight-light"><?php echo $lang->get('Initiate time'); ?></label>
      <div class="form-text font-weight-bolder"><?php echo $verifyRow['verify_time_refresh_format']['date_time']; ?></div>
    </div>

    <div class="form-group">
      <label class="text-muted font-weight-light"><?php echo $lang->get('Use time'); ?></label>
      <div class="form-text font-weight-bolder"><?php echo $verifyRow['verify_time_disabled_format']['date_time']; ?></div>
    </div>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
      <?php echo $lang->get('Close', 'console.common'); ?>
    </button>
  </div>
