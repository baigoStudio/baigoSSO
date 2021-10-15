<?php $cfg = array(
  'title'         => $lang->get('Installer'),
  'btn'           => $lang->get('Next'),
  'btn_link'      => true,
  'sub_title'     => $lang->get('PHP Extensions'),
  'active'        => 'index',
  'no_loading'    => 'true',
  'pathInclude'   => $path_tpl . 'include' . DS,
);


include($cfg['pathInclude'] . 'index_head' . GK_EXT_TPL);

  include($cfg['pathInclude'] . 'phplib' . GK_EXT_TPL);

  if (isset($err_count) && $err_count > 0) { ?>
    <hr>

    <div class="alert alert-danger">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'times-circle' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Missing PHP extensions'); ?>
    </div>
  <?php }

  include($cfg['pathInclude'] . 'install_btn' . GK_EXT_TPL);

include($cfg['pathInclude'] . 'install_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
