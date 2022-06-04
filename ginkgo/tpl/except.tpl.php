<?php include($tpl_path . DS . 'include' . DS . 'head' . GK_EXT_TPL); ?>

  <div class="alert">
    <h1 class="text-center">
      <?php include($tpl_path . DS . 'include' . DS . 'except.svg'); ?>
    </h1>
    <h3 class="text-center">
      <?php if (isset($http_status) && !empty($http_status)) {
        echo $lang->get($http_status);
      } else {
        echo $lang->get('Error');
      } ?>
    </h3>
    <?php if (isset($status_code) && !empty($status_code)) { ?>
      <h4 class="text-center">
        <?php echo $status_code; ?>
      </h4>
    <?php } ?>
    <hr>
    <dl>
      <?php if (isset($err_message) && !empty($err_message)) { ?>
        <dt><?php echo $lang->get('Error message'); ?></dt>
        <dd>
          <div class="lead text-break"><?php echo $lang->get($err_message); ?></div>

          <?php if (isset($err_detail) && !empty($err_detail)) { ?>
            <div class="text-break"><?php echo $lang->get($err_detail); ?></div>
          <?php } ?>
        </dd>
      <?php }

      if (isset($err_file) && !empty($err_file)) { ?>
        <dt><?php echo $lang->get('Error file'); ?></dt>
        <dd class="lead text-break">
          <?php echo $err_file; ?>
        </dd>
      <?php }

      if (isset($err_line) && !empty($err_line)) { ?>
        <dt><?php echo $lang->get('Line number'); ?></dt>
        <dd class="lead text-break">
          <?php echo $err_line; ?>
        </dd>
      <?php }

      if (isset($err_type) && !empty($err_type)) { ?>
        <dt><?php echo $lang->get('Error type'); ?></dt>
        <dd class="lead text-break">
          <?php echo $err_type; ?>
        </dd>
      <?php }

      if (isset($http_status) && !empty($http_status)) { ?>
        <dt><?php echo $lang->get('Http status'); ?></dt>
        <dd class="lead text-break">
          <?php echo $http_status; ?>
        </dd>
      <?php } ?>
    </dl>
  </div>

<?php include($tpl_path . DS . 'include' . DS . 'foot' . GK_EXT_TPL);
