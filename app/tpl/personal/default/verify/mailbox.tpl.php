<?php $cfg = array(
  'title'          => $lang->get('Change mailbox'),
  'baigoValidate'  => 'true',
  'baigoSubmit'    => 'true',
  'captchaReload'  => 'true',
);

include($tpl_include . 'personal_head' . GK_EXT_TPL); ?>

  <div class="card">
    <div class="card-body">
      <form name="personal_form" id="personal_form" action="<?php echo $hrefRow['mailbox-submit']; ?>">
        <input type="hidden" name="verify_id" value="<?php echo $verifyRow['verify_id']; ?>">
        <input type="hidden" name="verify_token" value="<?php echo $verifyRow['verify_token']; ?>">
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

        <div class="form-group">
          <label><?php echo $lang->get('Username'); ?></label>
          <input type="text" value="<?php echo $userRow['user_name']; ?>" class="form-control" readonly>
        </div>

        <div class="form-group">
          <label><?php echo $lang->get('Old mailbox'); ?></label>
          <input type="text" value="<?php echo $userRow['user_mail']; ?>" class="form-control" readonly>
        </div>

        <div class="form-group">
          <label><?php echo $lang->get('New mailbox'); ?></label>
          <input type="text" value="<?php echo $verifyRow['verify_mail']; ?>" class="form-control" readonly>
        </div>

        <div class="form-group">
          <label><?php echo $lang->get('Captcha'); ?> <span class="text-danger">*</span></label>
          <div class="input-group">
            <input type="text" name="captcha" id="captcha" class="form-control">
            <div class="input-group-append">
              <img src="<?php echo $hrefRow['captcha']; ?>" data-src="<?php echo $hrefRow['captcha']; ?>" class="bg-captcha-img" alt="<?php echo $lang->get('Captcha'); ?>">
            </div>
          </div>
          <small class="form-text" id="msg_captcha"></small>
        </div>

        <div class="bg-validate-box"></div>

        <div class="form-group">
          <button type="submit" class="btn btn-success btn-block">
            <?php echo $lang->get('Apply'); ?>
          </button>
        </div>
      </form>
    </div>
  </div>

<?php include($tpl_include . 'personal_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      captcha: {
        length: '4,4',
        format: 'alpha_number',
        ajax: {
          url: '<?php echo $hrefRow['captcha-check']; ?>'
        }
      }
    },
    attr_names: {
      captcha: '<?php echo $lang->get('Captcha'); ?>'
    },
    type_msg: {
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
    },
    format_msg: {
      alpha_number: '<?php echo $lang->get('{:attr} must be alpha-numeric'); ?>'
    },
    msg: {
      loading: '<?php echo $lang->get('Loading'); ?>',
      ajax_err: '<?php echo $lang->get('Server side error'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  $(document).ready(function(){
    var obj_validate_form  = $('#personal_form').baigoValidate(opts_validate_form);
    var obj_submit_form    = $('#personal_form').baigoSubmit(opts_submit);

    $('#personal_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit(false, function(result){
          if (typeof result.rcode == 'undefined' || result.rcode != 'y010103') {
            captchaReload('<?php echo $hrefRow['captcha']; ?>');
          }
        });
      }
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
