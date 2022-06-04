<?php $cfg = array(
  'title'          => $lang->get('Not receiving confirmation emails'),
  'active'         => 'nomail',
  'baigoValidate'  => 'true',
  'baigoSubmit'    => 'true',
  'captchaReload'  => 'true',
);

include($tpl_include . 'personal_head' . GK_EXT_TPL); ?>

  <div class="card">
    <div class="card-body">
      <form name="nomail_form" id="nomail_form" action='<?php echo $hrefRow['nomail-submit']; ?>'>
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

        <div class="form-group">
          <label><?php echo $lang->get('Username'); ?> <span class="text-danger">*</span></label>
          <input type="text" name="user_name" id="user_name" class="form-control">
          <small class="form-text" id="msg_user_name"></small>
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
      user_name: {
        length: '1,30',
        format: 'alpha_dash'
      },
      captcha: {
        length: '4,4',
        format: 'alpha_number',
        ajax: {
          key: 'captcha',
          url: '<?php echo $hrefRow['captcha-check']; ?>'
        }
      }
    },
    attr_names: {
      user_name: '<?php echo $lang->get('Username'); ?>',
      captcha: '<?php echo $lang->get('Captcha'); ?>'
    },
    type_msg: {
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
    },
    format_msg: {
      alpha_dash: '<?php echo $lang->get('{:attr} must be alpha-numeric, dash, underscore'); ?>',
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
    var obj_validate_form  = $('#nomail_form').baigoValidate(opts_validate_form);
    var obj_submit_form    = $('#nomail_form').baigoSubmit(opts_submit);

    $('#nomail_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit(false, function(result){
          if (typeof result.rcode == 'undefined' || result.rcode != 'y010407') {
            captchaReload('<?php echo $hrefRow['captcha']; ?>');
          }
        });
      }
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
