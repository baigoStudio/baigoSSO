<?php $cfg = array(
  'title'         => $lang->get('Upgrader'),
  'btn'           => $lang->get('Complete', 'install.common'),
  'sub_title'     => $lang->get('Complete upgrade'),
  'active'        => 'over',
);

include($tpl_ctrl . 'head' . GK_EXT_TPL); ?>

  <form name="over_form" id="over_form" action="<?php echo $hrefRow['over-submit']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <div class="alert alert-success">
      <span class="bg-icon"><?php include($tpl_icon . 'check-circle' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Last step, complete the upgrade'); ?>
    </div>

    <?php include($tpl_include . 'install_btn' . GK_EXT_TPL); ?>
  </form>

<?php include($tpl_include . 'install_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  opts_submit.jump = {
    url: '<?php echo $route_console; ?>',
    text: '<?php echo $lang->get('Redirecting'); ?>'
  };

  $(document).ready(function(){
    var obj_submit_form = $('#over_form').baigoSubmit(opts_submit);
    $('#over_form').submit(function(){
      obj_submit_form.formSubmit();
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
