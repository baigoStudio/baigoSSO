<?php $cfg = array(
  'title'          => $lang->get('Login'),
  'active'         => 'login',
  'baigoValidate'  => 'true',
  'baigoSubmit'    => 'true',
  'captchaReload'  => 'true',
  'tooltip'        => 'true',
);

include($tpl_ctrl . 'head' . GK_EXT_TPL); ?>

  <form name="login_form" id="login_form" action="<?php echo $hrefRow['submit']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="form-group">
      <label><?php echo $lang->get('Username'); ?></label>
      <input type="text" name="admin_name" id="admin_name" class="form-control">
      <small class="form-text" id="msg_admin_name"></small>
    </div>

    <div class="form-group">
      <label><?php echo $lang->get('Password'); ?></label>
      <input type="password" name="admin_pass" id="admin_pass" class="form-control">
      <small class="form-text" id="msg_admin_pass"></small>
    </div>

    <div class="form-group">
      <label><?php echo $lang->get('Captcha'); ?></label>
      <div class="input-group">
        <input type="text" name="captcha" id="captcha" class="form-control">
        <div class="input-group-append">
          <img src="<?php echo $hrefRow['captcha']; ?>" data-src="<?php echo $hrefRow['captcha']; ?>" class="bg-captcha-img" alt="<?php echo $lang->get('Refresh'); ?>">
        </div>
      </div>

      <small class="form-text" id="msg_captcha"></small>
    </div>

    <div class="form-group">
      <div class="custom-control custom-switch">
        <input type="checkbox" name="admin_remember" id="admin_remember" value="remember" class="custom-control-input">
        <label for="admin_remember" class="custom-control-label" data-toggle="tooltip" data-placement="right" title="<?php echo $lang->get('Do not enable on public computers'); ?>">
          <?php echo $lang->get('Remember me'); ?>
        </label>
      </div>
    </div>

    <div class="bg-validate-box"></div>

    <button type="submit" class="btn btn-success btn-block">
      <?php echo $lang->get('Login'); ?>
    </button>
  </form>

<?php include($tpl_ctrl . 'foot' . GK_EXT_TPL); ?>

  <form name="clear_form" id="clear_form" action="<?php echo $hrefRow['cookie']; ?>" class="my-3 text-center">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <button type="submit" class="btn btn-link">
      <?php echo $lang->get('Clear cookie'); ?>
    </button>
  </form>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      admin_name: {
        require: true,
        format: 'alpha_dash'
      },
      admin_pass: {
        require: true
      },
      captcha: {
        length: '4,4',
        format: 'alpha_number',
        ajax: {
          url: '<?php echo $hrefRow['captcha-check']; ?>'
        }
      }
    },
    attr_names: {
      admin_name: '<?php echo $lang->get('Username'); ?>',
      admin_pass: '<?php echo $lang->get('Password'); ?>',
      captcha: '<?php echo $lang->get('Captcha'); ?>'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>',
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
    },
    format_msg: {
      alpha_number: '<?php echo $lang->get('{:attr} must be alpha-numeric'); ?>',
      alpha_dash: '<?php echo $lang->get('{:attr} must be alpha-numeric, dash, underscore'); ?>'
    },
    msg: {
      loading: '<?php echo $lang->get('Loading'); ?>',
      ajax_err: '<?php echo $lang->get('Server side error'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  opts_submit.jump = {
    url: '<?php echo $forward; ?>',
    text: '<?php echo $lang->get('Redirecting'); ?>'
  };

  $(document).ready(function(){
    var obj_validate_form  = $('#login_form').baigoValidate(opts_validate_form);
    var obj_submit_form    = $('#login_form').baigoSubmit(opts_submit);

    $('#login_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit(false, function(result){
          if (typeof result.rcode == 'undefined' || result.rcode != 'y020401') {
            captchaReload('<?php echo $hrefRow['captcha']; ?>');
          }
        });
      }
    });

    opts_submit.jump = {};

    var obj_submit_clear = $('#clear_form').baigoSubmit(opts_submit);

    $('#clear_form').submit(function(){
      obj_submit_clear.formSubmit();
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
