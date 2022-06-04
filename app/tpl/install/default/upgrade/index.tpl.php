<?php $cfg = array(
  'title'         => $lang->get('Upgrader'),
  'btn'           => $lang->get('Next'),
  'btn_link'      => true,
  'sub_title'     => $lang->get('PHP Extensions'),
  'active'        => 'index',
  'no_loading'    => 'true',
);

include($tpl_ctrl . 'head' . GK_EXT_TPL);

  include($tpl_include . 'phplib' . GK_EXT_TPL);

  if (isset($err_count) && $err_count > 0) { ?>
    <hr>

    <div class="alert alert-danger">
      <span class="bg-icon"><?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Missing PHP extensions'); ?>
    </div>
  <?php }

  include($tpl_include . 'install_btn' . GK_EXT_TPL); ?>

<?php include($tpl_include . 'install_foot' . GK_EXT_TPL);
include($tpl_include . 'html_foot' . GK_EXT_TPL);
