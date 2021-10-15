<?php $cfg = array(
  'title'             => $lang->get('Profile', 'console.common') . ' &raquo; ' . $lang->get('Profile info', 'console.common'),
  'menu_active'       => 'profile',
  'sub_active'        => 'info',
  'baigoValidate'    => 'true',
  'baigoSubmit'       => 'true',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <form name="profile_form" id="profile_form" action="<?php echo $route_console; ?>profile/info-submit/">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="row">
      <div class="col-xl-9">
        <div class="card mb-3">
          <div class="card-body">
            <div class="form-group">
              <label><?php echo $lang->get('Username'); ?></label>
              <input type="text" value="<?php echo $adminLogged['admin_name']; ?>" readonly class="form-control">
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Password'); ?> <span class="text-danger">*</span></label>
              <input type="password" name="admin_pass" id="admin_pass" class="form-control">
              <small class="form-text" id="msg_admin_pass"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Nickname'); ?></label>
              <input type="text" name="admin_nick" id="admin_nick" value="<?php echo $adminLogged['admin_nick']; ?>" class="form-control">
              <small class="form-text" id="msg_admin_nick"></small>
            </div>

            <div class="bg-validate-box"></div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <?php echo $lang->get('Save'); ?>
            </button>
          </div>
        </div>

        <div class="card mt-3">
          <div class="card-body">
            <label><?php echo $lang->get('Permission'); ?></label>

            <?php $_is_edit = false;
            $adminRow = $adminLogged;
            include($cfg['pathInclude'] . 'allow_list' . GK_EXT_TPL); ?>
          </div>
        </div>
      </div>

      <?php include($cfg['pathInclude'] . 'profile_side' . GK_EXT_TPL); ?>
    </div>
  </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      admin_pass: {
        require: true
      },
      admin_nick: {
        max: 30
      }
    },
    attr_names: {
      admin_pass: '<?php echo $lang->get('Password'); ?>',
      admin_nick: '<?php echo $lang->get('Nickname'); ?>'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>',
      max: '<?php echo $lang->get('Max size of {:attr} must be {:rule}'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  $(document).ready(function(){
    var obj_validate_form   = $('#profile_form').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#profile_form').baigoSubmit(opts_submit);

    $('#profile_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
