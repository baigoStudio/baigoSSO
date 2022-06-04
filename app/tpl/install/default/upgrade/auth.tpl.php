<?php $cfg = array(
  'title'         => $lang->get('Upgrader'),
  'btn'           => $lang->get('Add'),
  'sub_title'     => $lang->get('Add administrator'),
  'active'        => 'admin',
);

include($tpl_ctrl . 'head' . GK_EXT_TPL);

  include($tpl_ctrl . 'admin_menu' . GK_EXT_TPL); ?>

  <form name="auth_form" id="auth_form" action="<?php echo $hrefRow['auth-submit']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="alert alert-warning">
      <span class="bg-icon"><?php include($tpl_icon . 'exclamation-triangle' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('This step will authorizes an existing user as a super administrator with all permissions.'); ?>
    </div>

    <div class="form-group">
      <label><?php echo $lang->get('Username'); ?> <span class="text-danger">*</span></label>
      <input type="text" name="admin_name" id="admin_name" class="form-control">
      <small class="form-text" id="msg_admin_name"></small>
    </div>

    <div class="bg-validate-box"></div>

    <?php $cfg['btn'] = $lang->get('Authorization');
    include($tpl_include . 'install_btn' . GK_EXT_TPL); ?>
  </form>

<?php include($tpl_include . 'install_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      admin_name: {
        length: '1,30',
        format: 'alpha_dash',
        ajax: {
          url: '<?php echo $hrefRow['auth-check']; ?>'
        }
      }
    },
    attr_names: {
      admin_name: '<?php echo $lang->get('Username'); ?>'
    },
    type_msg: {
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
    },
    format_msg: {
      alpha_dash: '<?php echo $lang->get('{:attr} must be alpha-numeric, dash, underscore'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  opts_submit.jump = {
    url: '<?php echo $step['next']['href']; ?>',
    text: '<?php echo $lang->get('Redirecting'); ?>'
  };

  $(document).ready(function(){
    var obj_validate_form     = $('#auth_form').baigoValidate(opts_validate_form);
    var obj_submit_form       = $('#auth_form').baigoSubmit(opts_submit);
    $('#auth_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
