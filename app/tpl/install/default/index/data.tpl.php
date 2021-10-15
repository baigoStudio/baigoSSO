<?php $cfg = array(
  'title'         => $lang->get('Installer'),
  'btn'           => $lang->get('Next'),
  'btn_link'      => true,
  'sub_title'     => $lang->get('Create data'),
  'active'        => 'data',
  'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'index_head' . GK_EXT_TPL);

  include($cfg['pathInclude'] . 'data' . GK_EXT_TPL);
  include($cfg['pathInclude'] . 'install_btn' . GK_EXT_TPL);

include($cfg['pathInclude'] . 'install_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
