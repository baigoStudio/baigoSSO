<?php $cfg = array(
  'title'         => $lang->get('Installer'),
  'btn'           => $lang->get('Save'),
  'sub_title'     => $lang->get('Database settings'),
  'active'        => 'dbconfig',
);

include($tpl_ctrl . 'head' . GK_EXT_TPL); ?>

  <form name="dbconfig_form" id="dbconfig_form" action="<?php echo $hrefRow['dbconfig-submit']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <?php include($tpl_console . 'dbconfig' . GK_EXT_TPL);
    include($tpl_include . 'install_btn' . GK_EXT_TPL); ?>
  </form>

<?php include($tpl_include . 'install_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  opts_submit.jump = {
    url: '<?php echo $step['next']['href']; ?>',
    text: '<?php echo $lang->get('Redirecting'); ?>'
  };

  $(document).ready(function(){
    var obj_validate_form    = $('#dbconfig_form').baigoValidate(opts_validate_form);
    var obj_submit_form      = $('#dbconfig_form').baigoSubmit(opts_submit);
    $('#dbconfig_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
