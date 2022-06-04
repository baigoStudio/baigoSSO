<?php $cfg = array(
  'title'         => $lang->get('Installer'),
  'btn'           => $lang->get('Next'),
  'btn_link'      => true,
  'sub_title'     => $lang->get('Create data'),
  'active'        => 'data',
);

include($tpl_ctrl . 'head' . GK_EXT_TPL);

  include($tpl_include . 'data_form' . GK_EXT_TPL);
  include($tpl_include . 'install_btn' . GK_EXT_TPL);

include($tpl_include . 'install_foot' . GK_EXT_TPL);

  include($tpl_include . 'data_script' . GK_EXT_TPL);

include($tpl_include . 'html_foot' . GK_EXT_TPL);
