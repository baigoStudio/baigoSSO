<?php $cfg = array(
  'title'         => $lang->get('Upgrader'),
  'btn'           => $lang->get('Next'),
  'btn_link'      => true,
  'sub_title'     => $lang->get('Update data'),
  'active'        => 'data',
  'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'upgrade_head' . GK_EXT_TPL);

  include($cfg['pathInclude'] . 'data' . GK_EXT_TPL);
  include($cfg['pathInclude'] . 'install_btn' . GK_EXT_TPL);

include($cfg['pathInclude'] . 'install_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
