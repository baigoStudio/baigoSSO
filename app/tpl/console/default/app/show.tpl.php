<?php $cfg = array(
  'title'             => $lang->get('App', 'console.common') . ' &raquo; ' . $lang->get('Show'),
  'menu_active'       => 'app',
  'sub_active'        => 'index',
  'baigoSubmit'       => 'true',
  'baigoDialog'       => 'true',
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
        <div class="card-body">
          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('App name'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $appRow['app_name']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Base url of API'); ?></label>
            <div><?php echo $url_api; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('App ID'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $appRow['app_id']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('App Key'); ?></label>
            <input type="text" value="<?php echo $appRow['app_key']; ?>" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('App Secret'); ?></label>
            <input type="text" value="<?php echo $appRow['app_secret']; ?>" class="form-control" readonly>
          </div>

          <form name="app_form" id="app_form" action="<?php echo $hrefRow['reset']; ?>">
            <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
            <input type="hidden" name="app_id" value="<?php echo $appRow['app_id']; ?>">

            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <span class="bg-icon"><?php include($tpl_icon . 'redo-alt' . BG_EXT_SVG); ?></span>
                <?php echo $lang->get('Reset App Key & App Secret'); ?>
              </button>
              <small class="form-text"><?php echo $lang->get('If you suspect that the App Key or App Secret has been compromised, you can reset them.'); ?></small>
            </div>
          </form>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('URL of notifications'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $appRow['app_url_notify']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('URL of sync notifications'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $appRow['app_url_sync']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('License'); ?></label>
            <dl>
              <?php foreach ($allowRows as $key_m=>$value_m) {
                if (isset($appRow['app_allow'][$key_m][$key_s])) {
                  $str_icon  = 'check-circle';
                  $str_color = 'success';
                } else {
                  $str_icon  = 'times-circle';
                  $str_color = 'danger';
                } ?>
                <dt>
                  <?php echo $lang->get($value_m['title']); ?>
                </dt>
                <dd>
                  <?php foreach ($value_m['allow'] as $key_s=>$value_s) { ?>
                    <span>
                      <span class="bg-icon text-<?php echo $str_color; ?>"><?php include($tpl_icon . $str_icon . BG_EXT_SVG); ?></span>

                      <?php echo $lang->get($value_s); ?>
                    </span>
                  <?php } ?>
                </dd>
              <?php } ?>
            </dl>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Allowed IPs'); ?></label>
            <div class="form-text font-weight-bolder"><pre><?php echo $appRow['app_ip_allow']; ?></pre></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Banned IPs'); ?></label>
            <div class="form-text font-weight-bolder"><pre><?php echo $appRow['app_ip_bad']; ?></pre></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Note'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $appRow['app_note']; ?></div>
          </div>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-between">
            <a href="#modal_nm" class="btn btn-info" data-toggle="modal">
              <span class="bg-icon"><?php include($tpl_icon . 'flag-checkered' . BG_EXT_SVG); ?></span>
              <?php echo $lang->get('Notification test'); ?>
            </a>

            <a href="<?php echo $hrefRow['edit'], $appRow['app_id']; ?>">
              <span class="bg-icon"><?php include($tpl_icon . 'edit' . BG_EXT_SVG); ?></span>
              <?php echo $lang->get('Edit'); ?>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3">
      <div class="card bg-light">
        <div class="card-body">
          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('ID'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $appRow['app_id']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Status'); ?></label>
            <div class="form-text font-weight-bolder"><?php $str_status = $appRow['app_status'];
              include($tpl_include . 'status_process' . GK_EXT_TPL); ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Sync'); ?></label>
            <div class="form-text font-weight-bolder"><?php $str_status = $appRow['app_sync'];
              include($tpl_include . 'status_process' . GK_EXT_TPL); ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Parameter'); ?></label>
            <?php foreach ($appRow['app_param'] as $_key=>$_value) { ?>
              <div class="input-group mb-2">
                <span class="input-group-prepend">
                  <span class="input-group-text"><?php if (isset($_value['key'])) { echo $_value['key']; } ?></span>
                </span>
                <input type="text" value="<?php if (isset($_value['value'])) { echo $_value['value']; } ?>" class="form-control" readonly>
              </div>
            <?php } ?>
            <small class="form-text"><?php echo $lang->get('These parameters will be transmitted with the notification, such as: <code>key_1=value_1&key_2=value_2</code>'); ?></small>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="<?php echo $hrefRow['edit'], $appRow['app_id']; ?>">
            <span class="bg-icon"><?php include($tpl_icon . 'edit' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Edit'); ?>
          </a>
        </div>
      </div>
    </div>
  </div>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL);

  include($tpl_include . 'modal_nm' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  $(document).ready(function(){
    var obj_submit_notify  = $('#app_notify').baigoSubmit(opts_submit);

    $('#app_notify').submit(function(){
      obj_submit_notify.formSubmit();
    });

    var obj_dialog       = $.baigoDialog(opts_dialog);
    var obj_submit_reset = $('#app_form').baigoSubmit(opts_submit);

    $('#app_form').submit(function(){
      obj_dialog.confirm('<?php echo $lang->get('Are you sure to reset?'); ?>', function(result){
        if (result) {
          obj_submit_reset.formSubmit();
        }
      });
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
