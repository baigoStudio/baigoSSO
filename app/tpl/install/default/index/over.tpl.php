<?php $cfg = array(
  'title'         => $lang->get('Installer'),
  'btn'           => $lang->get('Complete', 'install.common'),
  'sub_title'     => $lang->get('Complete installation'),
  'active'        => 'over',
  'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'index_head' . GK_EXT_TPL); ?>

  <form name="over_form" id="over_form" action="<?php echo $route_install; ?>index/over-submit/">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <div class="alert alert-success">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'check-circle' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Last step, complete the installation'); ?>
    </div>

    <?php include($cfg['pathInclude'] . 'install_btn' . GK_EXT_TPL) ?>
  </form>

<?php include($cfg['pathInclude'] . 'install_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  opts_submit.jump = {
    url: '<?php echo $route_console; ?>',
    text: '<?php echo $lang->get('Redirecting'); ?>'
  };

  $(document).ready(function(){
    var obj_submit_form       = $('#over_form').baigoSubmit(opts_submit);
    $('#over_form').submit(function(){
      obj_submit_form.formSubmit();
    });
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
