<?php $cfg = array(
  'title'          => $lang->get('Forgot password'),
  'active'         => 'forgot',
  'baigoQuery'     => 'true',
);

include($tpl_include . 'personal_head' . GK_EXT_TPL); ?>

  <div class="card">
    <div class="card-body">
      <form name="forgot_form" id="forgot_form" action='<?php echo $hrefRow['confirm']; ?>'>
        <?php if (isset($msg) && !empty($msg)) { ?>
          <div class="alert alert-danger">
            <span class="bg-icon"><?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get($msg); ?>
          </div>
        <?php } ?>

        <div class="form-group">
          <label><?php echo $lang->get('Username'); ?> <span class="text-danger">*</span></label>
          <input type="text" name="user" id="user" class="form-control">
          <small class="form-text" id="msg_user"></small>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-success btn-block">
            <?php echo $lang->get('Next'); ?>
          </button>
        </div>
      </form>
    </div>
  </div>

<?php include($tpl_include . 'personal_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  $(document).ready(function(){
    var obj_query = $('#forgot_form').baigoQuery();

    $('#forgot_form').submit(function(){
      obj_query.formSubmit();
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
