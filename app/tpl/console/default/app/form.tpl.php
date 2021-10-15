<?php if ($appRow['app_id'] > 0) {
  $title_sub    = $lang->get('Edit');
  $str_sub      = 'index';
} else {
  $title_sub    = $lang->get('Add');
  $str_sub      = 'form';
}

$cfg = array(
  'title'             => $lang->get('App', 'console.common') . ' &raquo; ' . $title_sub,
  'menu_active'       => 'app',
  'sub_active'        => $str_sub,
  'baigoValidate'    => 'true',
  'baigoSubmit'       => 'true',
  'baigoCheckall'     => 'true',
  'tooltip'           => 'true',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $route_console; ?>app/" class="nav-link">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <form name="app_form" id="app_form" action="<?php echo $route_console; ?>app/submit/">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <input type="hidden" name="app_id" id="app_id" value="<?php echo $appRow['app_id']; ?>">

    <div class="row">
      <div class="col-xl-9">
        <div class="card mb-3">
          <div class="card-body">
            <div class="form-group">
              <label><?php echo $lang->get('App name'); ?></label>
              <input type="text" name="app_name" id="app_name" value="<?php echo $appRow['app_name']; ?>" class="form-control">
              <small class="form-text" id="msg_app_name"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('URL of notifications'); ?></label>
              <input type="text" name="app_url_notify" id="app_url_notify" value="<?php echo $appRow['app_url_notify']; ?>" class="form-control" title="<?php echo $lang->get('Start with <code>http://</code> or <code>https://</code>'); ?>" data-toggle="tooltip" data-placement="bottom">
              <small class="form-text" id="msg_app_url_notify"><?php echo $lang->get('Start with <code>http://</code> or <code>https://</code>'); ?></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('URL of sync notifications'); ?></label>
              <input type="text" name="app_url_sync" id="app_url_sync" value="<?php echo $appRow['app_url_sync']; ?>" class="form-control" title="<?php echo $lang->get('Start with <code>http://</code> or <code>https://</code>'); ?>" data-toggle="tooltip" data-placement="bottom">
              <small class="form-text" id="msg_app_url_sync"><?php echo $lang->get('Start with <code>http://</code> or <code>https://</code>'); ?></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('License'); ?> <span class="text-danger">*</span></label>
              <dl>
                <dd>
                  <div class="form-check">
                    <input type="checkbox" id="chk_all" data-parent="first" class="form-check-input">
                    <label for="chk_all" class="form-check-label">
                      <?php echo $lang->get('All'); ?>
                    </label>
                  </div>
                </dd>
                <?php foreach ($allowRows as $key_m=>$value_m) { ?>
                  <dt>
                    <?php echo $lang->get($value_m['title']); ?>
                  </dt>
                  <dd>
                    <div class="form-check form-check-inline">
                      <input type="checkbox" id="allow_<?php echo $key_m; ?>" data-parent="chk_all" class="form-check-input">
                      <label for="allow_<?php echo $key_m; ?>" class="form-check-label">
                        <?php echo $lang->get('All'); ?>
                      </label>
                    </div>
                    <?php foreach ($value_m['allow'] as $key_s=>$value_s) { ?>
                      <div class="form-check form-check-inline">
                        <input type="checkbox" name="app_allow[<?php echo $key_m; ?>][<?php echo $key_s; ?>]" value="1" id="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" data-parent="allow_<?php echo $key_m; ?>" <?php if (isset($appRow['app_allow'][$key_m][$key_s])) { ?>checked<?php } ?> class="form-check-input">
                        <label for="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" class="form-check-label">
                          <?php echo $lang->get($value_s); ?>
                        </label>
                      </div>
                    <?php } ?>
                  </dd>
                <?php } ?>
              </dl>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Allowed IPs'); ?></label>
              <textarea name="app_ip_allow" id="app_ip_allow" class="form-control bg-textarea-md" title="<?php echo $lang->get('For multiple IP, please use <kbd>|</kbd> to separate, allowing the use of the wildcard <kbd>*</kbd> (eg 192.168.1.*)'); ?>" data-toggle="tooltip" data-placement="bottom"><?php echo $appRow['app_ip_allow']; ?></textarea>
              <small class="form-text" id="msg_app_ip_allow">
                <?php echo $lang->get('For multiple IP, please use <kbd>|</kbd> to separate, allowing the use of the wildcard <kbd>*</kbd> (eg 192.168.1.*)'); ?>
              </small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Banned IPs'); ?></label>
              <textarea name="app_ip_bad" id="app_ip_bad" class="form-control bg-textarea-md" title="<?php echo $lang->get('For multiple IP, please use <kbd>|</kbd> to separate, allowing the use of the wildcard <kbd>*</kbd> (eg 192.168.1.*)'); ?>" data-toggle="tooltip" data-placement="bottom"><?php echo $appRow['app_ip_bad']; ?></textarea>
              <small class="form-text" id="msg_app_ip_bad">
                <?php echo $lang->get('For multiple IP, please use <kbd>|</kbd> to separate, allowing the use of the wildcard <kbd>*</kbd> (eg 192.168.1.*)'); ?>
              </small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Note'); ?></label>
              <input type="text" name="app_note" id="app_note" value="<?php echo $appRow['app_note']; ?>" class="form-control">
              <small class="form-text" id="msg_app_note"></small>
            </div>

            <div class="bg-validate-box"></div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <?php echo $lang->get('Save'); ?>
            </button>
          </div>
        </div>
      </div>

      <div class="col-xl-3">
        <div class="card bg-light">
          <div class="card-body">
            <?php if ($appRow['app_id'] > 0) { ?>
              <div class="form-group">
                <label class="text-muted font-weight-light"><?php echo $lang->get('ID'); ?></label>
                <div class="form-text font-weight-bolder"><?php echo $appRow['app_id']; ?></div>
              </div>
            <?php } ?>

            <div class="form-group">
              <label><?php echo $lang->get('Status'); ?> <span class="text-danger">*</span></label>
              <?php foreach ($status as $key=>$value) { ?>
                <div class="form-check">
                  <input type="radio" name="app_status" id="app_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($appRow['app_status'] == $value) { ?>checked<?php } ?> class="form-check-input">
                  <label for="app_status_<?php echo $value; ?>" class="form-check-label">
                    <?php echo $lang->get($value); ?>
                  </label>
                </div>
              <?php } ?>
              <small class="form-text" id="msg_app_status"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Sync'); ?> <span class="text-danger">*</span></label>
              <?php foreach ($sync as $key=>$value) { ?>
                <div class="form-check">
                  <input type="radio" name="app_sync" id="app_sync_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($appRow['app_sync'] == $value) { ?>checked<?php } ?> class="form-check-input">
                  <label for="app_sync_<?php echo $value; ?>" class="form-check-label">
                    <?php echo $lang->get($value); ?>
                  </label>
                </div>
              <?php } ?>
              <small class="form-text" id="msg_app_sync"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Parameter'); ?></label>
              <div id="param_list">
                <?php $_key = 0;
                foreach ($appRow['app_param'] as $_key=>$_value) { ?>
                  <div id="param_group_<?php echo $_key; ?>">
                    <div class="input-group mb-2">
                      <span class="input-group-prepend">
                        <span class="input-group-text"><?php echo $lang->get('Name'); ?></span>
                      </span>
                      <input type="text" name="app_param[<?php echo $_key; ?>][key]" id="app_param_<?php echo $_key; ?>_key" value="<?php if (isset($_value['key'])) { echo $_value['key']; } ?>" class="form-control">
                      <span class="input-group-append">
                        <button type="button" class="btn btn-info param_del" data-count="<?php echo $_key; ?>">
                          <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'trash-alt' . BG_EXT_SVG); ?></span>
                        </button>
                      </span>
                    </div>

                    <div class="input-group">
                      <span class="input-group-prepend">
                        <span class="input-group-text"><?php echo $lang->get('Value'); ?></span>
                      </span>
                      <input type="text" name="app_param[<?php echo $_key; ?>][value]" id="app_param_<?php echo $_key; ?>_value" value="<?php if (isset($_value['value'])) { echo $_value['value']; } ?>" class="form-control">
                    </div>

                    <hr>
                  </div>
                <?php } ?>
              </div>

              <button type="button" class="btn btn-info param_add">
                <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'plus' . BG_EXT_SVG); ?></span>
              </button>
              <small class="form-text"><?php echo $lang->get('These parameters will be transmitted with the notification, such as: <code>key_1=value_1&key_2=value_2</code>'); ?></small>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <?php echo $lang->get('Save'); ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      app_name: {
        length: '1,30'
      },
      app_url_notify: {
        length: '1,3000',
        format: 'url'
      },
      app_url_sync: {
        length: '1,3000',
        format: 'url'
      },
      app_ip_allow: {
        max: 3000
      },
      app_ip_bad: {
        max: 3000
      },
      app_note: {
        max: 30
      },
      app_status: {
        require: true
      },
      app_sync: {
        require: true
      }
    },
    attr_names: {
      app_name: '<?php echo $lang->get('App name'); ?>',
      app_url_notify: '<?php echo $lang->get('URL of notifications'); ?>',
      app_url_sync: '<?php echo $lang->get('URL of sync notifications'); ?>',
      app_ip_allow: '<?php echo $lang->get('Allowed IPs'); ?>',
      app_ip_bad: '<?php echo $lang->get('Banned IPs'); ?>',
      app_note: '<?php echo $lang->get('Note'); ?>',
      app_status: '<?php echo $lang->get('Status'); ?>',
      app_sync: '<?php echo $lang->get('Sync'); ?>'
    },
    selector_types: {
      app_status: 'name',
      app_sync: 'name'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>',
      max: '<?php echo $lang->get('Max size of {:attr} must be {:rule}'); ?>',
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
    },
    format_msg: {
      url: '<?php echo $lang->get('{:attr} not a valid url'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  function paramDel(count) {
    $('#param_group_' + count).remove();
  }

  function paramAdd(count) {
    $('#param_list').append('<div id="param_group_' + count + '">' +
      '<div class="input-group mb-2">' +
        '<span class="input-group-prepend"><span class="input-group-text"><?php echo $lang->get('Name'); ?></span></span>' +
        '<input type="text" name="app_param[' + count + '][key]" id="app_param_' + count + '" class="form-control">' +
        '<span class="input-group-append">' +
          '<button type="button" class="btn btn-info param_del" data-count="' + count + '">' +
            '<span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'trash-alt' . BG_EXT_SVG); ?></span>' +
          '</button>' +
        '</span>' +
      '</div>' +
      '<div class="input-group">' +
        '<span class="input-group-prepend"><span class="input-group-text"><?php echo $lang->get('Value'); ?></span></span>' +
        '<input type="text" name="app_param[' + count + '][value]" id="app_param_' + count + '" class="form-control">' +
      '</div>' +
      '<hr>' +
    '</div>');
  }

  $(document).ready(function(){
    var obj_validate_form   = $('#app_form').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#app_form').baigoSubmit(opts_submit);

    $('#app_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });

    $('#app_form').baigoCheckall();

    var _count = <?php echo ++$_key; ?>;
    $('.param_add').click(function(){
      _count++;
      paramAdd(_count);
    });

    $('.param_del').click(function(){
      var _count  = $(this).data('count');
      paramDel(_count);
    });
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
