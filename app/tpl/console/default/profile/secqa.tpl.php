<?php $cfg = array(
  'title'             => $lang->get('Profile', 'console.common') . ' &raquo; ' . $lang->get('Security question', 'console.common'),
  'menu_active'       => 'profile',
  'sub_active'        => 'secqa',
  'baigoValidate'     => 'true',
  'baigoSubmit'       => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <form name="profile_form" id="profile_form" action="<?php echo $hrefRow['secqa-submit']; ?>">
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

            <?php for ($_iii = 1; $_iii <= $config['var_default']['count_secqa']; $_iii++) { ?>
              <div class="form-group">
                <label>
                  <?php echo $lang->get('Security question'), ' ', $_iii; ?>
                  <span class="text-danger">*</span>
                </label>
                <div class="form-row">
                  <div class="col-sm-7 col-6">
                    <input type="text" name="admin_sec_ques[<?php echo $_iii; ?>]" id="admin_sec_ques_<?php echo $_iii; ?>" value="<?php if (isset($userRow['user_sec_ques'][$_iii])) { echo $userRow['user_sec_ques'][$_iii]; } ?>" class="form-control">
                  </div>
                  <div class="col-sm-5 col-6">
                    <select class="form-control" id="admin_ques_often_<?php echo $_iii; ?>">
                      <option value=""><?php echo $lang->get('Frequently security questions'); ?></option>
                      <?php foreach ($secqaRows as $_key=>$_value) { ?>
                        <option value="<?php echo $lang->get($_value); ?>"><?php echo $lang->get($_value); ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <small class="form-text" id="msg_admin_sec_ques_<?php echo $_iii; ?>"></small>
              </div>

              <div class="form-group">
                <label>
                  <?php echo $lang->get('Security answer'), ' ', $_iii; ?>
                  <span class="text-danger">*</span>
                </label>
                <div class="form-row">
                  <div class="col-sm-7 col-6">
                    <input type="text" name="admin_sec_answ[<?php echo $_iii; ?>]" id="admin_sec_answ_<?php echo $_iii; ?>" class="form-control">
                  </div>
                </div>
                <small class="form-text" id="msg_admin_sec_answ_<?php echo $_iii; ?>"></small>
              </div>
            <?php } ?>

            <div class="bg-validate-box"></div>
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <?php echo $lang->get('Save'); ?>
            </button>
          </div>
        </div>
      </div>

      <?php include($tpl_ctrl . 'side' . GK_EXT_TPL); ?>
    </div>
  </form>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      <?php for ($_iii = 1; $_iii <= $config['var_default']['count_secqa']; $_iii++) { ?>
        admin_sec_ques_<?php echo $_iii; ?>: {
          length: '1,90'
        },
        admin_sec_answ_<?php echo $_iii; ?>: {
          require: true
        },
      <?php } ?>
      admin_pass: {
        require: true
      }
    },
    attr_names: {
      <?php for ($_iii = 1; $_iii <= $config['var_default']['count_secqa']; $_iii++) { ?>
          admin_sec_ques_<?php echo $_iii; ?>: '<?php echo $lang->get('Security question'), ' ', $_iii; ?>',
          admin_sec_answ_<?php echo $_iii; ?>: '<?php echo $lang->get('Security answer'), ' ', $_iii; ?>',
      <?php } ?>
      admin_pass: '<?php echo $lang->get('Password'); ?>'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>',
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
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

    <?php for ($_iii = 1; $_iii <= $config['var_default']['count_secqa']; $_iii++) { ?>
      $('#admin_ques_often_<?php echo $_iii; ?>').change(function(){
        var _qo_<?php echo $_iii; ?> = $(this).val();
        $('#admin_sec_ques_<?php echo $_iii; ?>').val(_qo_<?php echo $_iii; ?>);
      });
    <?php } ?>
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
