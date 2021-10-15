<?php $cfg = array(
  'title'             => $lang->get('System settings', 'console.common') . ' &raquo; ' . $lang->get('Email template settings', 'console.common'),
  'menu_active'       => 'opt',
  'sub_active'        => 'mailtpl',
  'baigoValidate'    => 'true',
  'baigoSubmit'       => 'true',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <form name="opt_form" id="opt_form" action="<?php echo $route_console; ?>opt/mailtpl-submit/">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="row">
      <div class="col-xl-9">
        <div class="card mb-3">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
              <?php foreach ($mailtplRows as $key=>$value) { ?>
                <li class="nav-item">
                  <a class="nav-link<?php if ($key == 'reg') { ?> active<?php } ?>" href="#<?php echo $key; ?>" data-toggle="tab">
                    <?php echo $lang->get($value['title']); ?>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <?php
              $_arr_rule      = array();
              $_arr_attr      = array();

              foreach ($mailtplRows as $key=>$value) { ?>
                <div class="tab-pane<?php if ($key == 'reg') { ?> active<?php } ?>" id="<?php echo $key; ?>">
                  <?php foreach ($value['lists'] as $key_list=>$value_list) {
                    $_arr_rule[$key . '_' . $key_list]['require'] = $value_list['require'];

                    if (isset($value_list['format'])) {
                      $_arr_rule[$key . '_' . $key_list]['format'] = $value_list['format'];
                    }

                    $_arr_attr[$key . '_' . $key_list]  = $lang->get($value_list['title']);

                    //form
                    $_this_value = $config['var_extra']['mailtpl'][$key . '_' . $key_list]; ?>
                    <div class="form-group">
                      <label>
                        <?php echo $lang->get($value_list['title']);

                        if ($value_list['require'] > 0) { ?> <span class="text-danger">*</span><?php } ?>
                      </label>

                      <?php switch ($value_list['type']) {
                        case 'textarea': ?>
                          <textarea name="<?php echo $key; ?>_<?php echo $key_list; ?>" id="<?php echo $key; ?>_<?php echo $key_list; ?>" class="form-control bg-textarea-md"><?php echo $_this_value; ?></textarea>
                        <?php break;

                        default: ?>
                          <input type="text" value="<?php echo $_this_value; ?>" name="<?php echo $key; ?>_<?php echo $key_list; ?>" id="<?php echo $key; ?>_<?php echo $key_list; ?>" class="form-control">
                        <?php break;
                      } ?>

                      <small class="form-text" id="msg_<?php echo $key_list; ?>"></small>

                      <?php if (isset($value_list['note'])) {
                        $_arr_langReplace = array(
                          'site_url'   => 'http://' . $_SERVER['SERVER_NAME'],
                        ); ?>
                        <small class="form-text"><?php echo $lang->get($value_list['note'], '', $_arr_langReplace); ?></small>
                      <?php } ?>
                    </div>
                  <?php } ?>
                </div>
              <?php } ?>
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
            <h5><?php echo $lang->get('Note'); ?></h5>
            <div><?php echo $lang->get('Content support <code>HTML</code>'); ?></div>
            <div><?php echo $lang->get('<code>{:site_name}</code> will be replaced with "Site name"'); ?></div>
            <div><?php echo $lang->get('<code>{:user_name}</code> will be replaced with "Username"'); ?></div>
            <div><?php echo $lang->get('<code>{:user_mail}</code> will be replaced with "Email"'); ?></div>
            <div><?php echo $lang->get('<code>{:user_mail_new}</code> will be replaced with "New mailbox"'); ?></div>
            <div><?php echo $lang->get('<code>{:verify_url}</code> will be replaced with "Verify link"'); ?></div>
          </div>
        </div>
      </div>
    </div>
  </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: <?php echo json_encode($_arr_rule); ?>,
    attr_names: <?php echo json_encode($_arr_attr); ?>,
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  $(document).ready(function(){
    var obj_validate_form   = $('#opt_form').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#opt_form').baigoSubmit(opts_submit);

    $('#opt_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
