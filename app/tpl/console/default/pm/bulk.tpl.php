<?php $cfg = array(
  'title'             => $lang->get('Private message', 'console.common') . ' &raquo; ' . $lang->get('Bulk'),
  'menu_active'       => 'pm',
  'sub_active'        => 'bulk',
  'baigoValidate'    => 'true',
  'baigoSubmit'       => 'true',
  'datetimepicker'    => 'true',
  'tooltip'           => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $hrefRow['index']; ?>" class="nav-link">
      <span class="bg-icon"><?php include($tpl_icon . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <form name="pm_bulk" id="pm_bulk" action="<?php echo $hrefRow['bulk-submit']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="card">
      <div class="card-body">
        <div class="form-group">
          <label><?php echo $lang->get('Bulk send type'); ?> <span class="text-danger">*</span></label>
          <select name="pm_bulk_type" id="pm_bulk_type" class="form-control">
            <option value="bulk_users"><?php echo $lang->get('Enter username'); ?></option>
            <option value="bulk_key_name"><?php echo $lang->get('Username keyword'); ?></option>
            <option value="bulk_key_mail"><?php echo $lang->get('Mailbox keyword'); ?></option>
            <option value="bulk_range_id"><?php echo $lang->get('User ID range'); ?></option>
            <option value="bulk_range_reg"><?php echo $lang->get('Registration time range'); ?></option>
            <option value="bulk_range_login"><?php echo $lang->get('Login time range'); ?></option>
          </select>
          <small class="form-text" id="msg_pm_bulk_type"></small>
        </div>

        <div class="form-group">
          <div id="bulk_users" class="bulk_types">
            <label><?php echo $lang->get('Recipient'); ?> <span class="text-danger">*</span></label>
            <input type="text" name="pm_to_users" id="pm_to_users" class="form-control" title="<?php echo $lang->get('For multiple recipients, please use <kbd>,</kbd> to separate'); ?>" data-toggle="tooltip" data-placement="bottom">
            <small class="form-text" id="msg_pm_to_users"><?php echo $lang->get('For multiple recipients, please use <kbd>,</kbd> to separate'); ?></small>
          </div>

          <div id="bulk_key_name" class="bulk_types">
            <label><?php echo $lang->get('Keyword'); ?> <span class="text-danger">*</span></label>
            <input type="text" name="pm_to_key_name" id="pm_to_key_name" class="form-control" title="<?php echo $lang->get('Send to users where username contains the keyword'); ?>" data-toggle="tooltip" data-placement="bottom">
            <small class="form-text" id="msg_pm_to_key_name"><?php echo $lang->get('Send to users where username contains the keyword'); ?></small>
          </div>

          <div id="bulk_key_mail" class="bulk_types">
            <label><?php echo $lang->get('Keyword'); ?> <span class="text-danger">*</span></label>
            <input type="text" name="pm_to_key_mail" id="pm_to_key_mail" class="form-control" title="<?php echo $lang->get('Send to users where username contains the keyword'); ?>" data-toggle="tooltip" data-placement="bottom">
            <small class="form-text" id="msg_pm_to_key_mail"><?php echo $lang->get('Send to users where email contains the keyword'); ?></small>
          </div>

          <div id="bulk_range_id" class="bulk_types">
            <label><?php echo $lang->get('ID'); ?> <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="text" name="pm_to_min_id" id="pm_to_min_id" class="form-control">
              <div class="input-group-append">
                <span class="input-group-text border-right-0"><?php echo $lang->get('To'); ?></span>
              </div>
              <input type="text" name="pm_to_max_id" id="pm_to_max_id" class="form-control">
            </div>
            <small class="form-text" id="msg_pm_to_min_id"><?php echo $lang->get('Sent to users in the ID range'); ?></small>
            <small class="form-text" id="msg_pm_to_max_id"></small>
          </div>

          <div id="bulk_range_reg" class="bulk_types">
            <label><?php echo $lang->get('Registration time'); ?> <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="text" name="pm_to_begin_reg" id="pm_to_begin_reg" class="form-control input_datetime" value="<?php echo $begin_datetime; ?>">
              <div class="input-group-append">
                <span class="input-group-text border-right-0"><?php echo $lang->get('To'); ?></span>
              </div>
              <input type="text" name="pm_to_end_reg" id="pm_to_end_reg" class="form-control input_datetime" value="<?php echo $end_datetime; ?>">
            </div>
            <small class="form-text" id="msg_pm_to_begin_reg"><?php echo $lang->get('Sent to users in the registration time range'); ?></small>
            <small class="form-text" id="msg_pm_to_end_reg"></small>
          </div>

          <div id="bulk_range_login" class="bulk_types">
            <label><?php echo $lang->get('Login time'); ?> <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="text" name="pm_to_begin_login" id="pm_to_begin_login" class="form-control input_datetime" value="<?php echo $begin_datetime; ?>">
              <div class="input-group-append">
                <span class="input-group-text border-right-0"><?php echo $lang->get('To'); ?></span>
              </div>
              <input type="text" name="pm_to_end_login" id="pm_to_end_login" class="form-control input_datetime" value="<?php echo $end_datetime; ?>">
            </div>
            <small class="form-text" id="msg_pm_to_begin_login"><?php echo $lang->get('Sent to users in the login time range'); ?></small>
            <small class="form-text" id="msg_pm_to_end_login"></small>
          </div>
        </div>

        <div class="form-group">
          <label><?php echo $lang->get('Title'); ?></label>
          <input type="text" name="pm_title" id="pm_title" class="form-control">
          <small class="form-text" id="msg_pm_title"></small>
        </div>

        <div class="form-group">
          <label><?php echo $lang->get('Content'); ?> <span class="text-danger">*</span></label>
          <textarea name="pm_content" id="pm_content" class="form-control bg-textarea-md"></textarea>
          <small class="form-text" id="msg_pm_content"></small>
        </div>

        <div class="bg-validate-box"></div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">
          <?php echo $lang->get('Send'); ?>
        </button>
      </div>
    </div>
  </form>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      pm_title: {
        max: 90
      },
      pm_content: {
        length: '1,900'
      },
      pm_bulk_type: {
        require: true
      }
    },
    attr_names: {
      pm_title: '<?php echo $lang->get('Title'); ?>',
      pm_content: '<?php echo $lang->get('Content'); ?>',
      pm_bulk_type: '<?php echo $lang->get('Bulk send type'); ?>',
      pm_to_users: '<?php echo $lang->get('Recipient'); ?>',
      pm_to_key_name: '<?php echo $lang->get('Keyword'); ?>',
      pm_to_key_mail: '<?php echo $lang->get('Keyword'); ?>',
      pm_to_min_id: '<?php echo $lang->get('Start ID'); ?>',
      pm_to_max_id: '<?php echo $lang->get('End ID'); ?>',
      pm_to_begin_reg: '<?php echo $lang->get('Start time'); ?>',
      pm_to_end_reg: '<?php echo $lang->get('End time'); ?>',
      pm_to_begin_login: '<?php echo $lang->get('Start time'); ?>',
      pm_to_end_login: '<?php echo $lang->get('End time'); ?>'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>',
      max: '<?php echo $lang->get('Max size of {:attr} must be {:rule}'); ?>',
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
    },
    format_msg: {
      'int': '<?php echo $lang->get('{:attr} must be integer'); ?>',
      date_time: '<?php echo $lang->get('{:attr} not a valid datetime'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  function bulkType(type_id) {
    $('.bulk_types').hide();
    $('#' + type_id).show();

    opts_validate_form.rules = {
      pm_title: {
        max: 90
      },
      pm_content: {
        length: '1,900'
      },
      pm_bulk_type: {
        require: true
      }
    };

    switch (type_id) {
      case 'bulk_users':
        opts_validate_form.rules.pm_to_users = {
          require: true
        };
      break;

      case 'bulk_key_name':
        opts_validate_form.rules.pm_to_key_name = {
          require: true
        };
      break;

      case 'bulk_key_mail':
        opts_validate_form.rules.pm_to_key_mail = {
          require: true
        };
      break;

      case 'bulk_range_id':
        opts_validate_form.rules.pm_to_min_id = {
          require: true,
          format: 'int'
        };
        opts_validate_form.rules.pm_to_max_id = {
          require: true,
          format: 'int'
        };
      break;

      case 'bulk_range_reg':
        opts_validate_form.rules.pm_to_begin_reg = {
          require: true,
          format: 'date_time'
        };
        opts_validate_form.rules.pm_to_end_reg = {
          require: true,
          format: 'date_time'
        };
      break;

      case 'bulk_range_login':
        opts_validate_form.rules.pm_to_begin_login = {
          require: true,
          format: 'date_time'
        };
        opts_validate_form.rules.pm_to_end_login = {
          require: true,
          format: 'date_time'
        };
      break;
    }
  }

  $(document).ready(function(){
    bulkType('bulk_users');

    var obj_validate_form   = $('#pm_bulk').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#pm_bulk').baigoSubmit(opts_submit);

   $('#pm_bulk').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });

    $('#pm_bulk_type').change(function(){
      var _type_id = $(this).val();
      bulkType(_type_id);
      obj_validate_form.setRules(opts_validate_form.rules);
    });
    $('.input_datetime').datetimepicker(opts_datetimepicker);
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
