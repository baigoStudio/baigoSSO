<?php
if (!function_exists('modal_head')) {
  function modal_head($lang, $cfg) { ?>
    <div class="modal-header">
      <div class="modal-title"><?php echo $cfg['title']; ?></div>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
  <?php }
}

if (!function_exists('modal_foot')) {
  function modal_foot($lang, $cfg) { ?>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
        <?php echo $lang->get('Close', 'console.common'); ?>
      </button>
    </div>
  <?php }
}

$cfg = array(
  'title'         => $lang->get('Error', 'console.common'),
  'no_token'      => 'true',
  'no_loading'    => 'true',
);

switch ($rstatus) {
  case 'y':
    $str_color  = 'success';
    $str_icon   = 'check-circle';
  break;

  default:
    $str_color  = 'danger';
    $str_icon   = 'times-circle';
  break;
}

if (isset($param['view'])) {
  switch ($param['view']) {
    case 'modal':
      modal_head($lang, $cfg);
    break;

    case 'iframe':
      include($tpl_include . 'html_head' . GK_EXT_TPL); ?>
      <div class="m-3">
    <?php break;
  }
} else {
  if ($is_ajax) {
    modal_head($lang, $cfg);
  } else {
    include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

    <nav class="nav mb-3">
      <a href="javascript:history.go(-1);" class="nav-link">
        <span class="bg-icon"><?php include($tpl_icon . 'chevron-left' . BG_EXT_SVG); ?></span>
        <?php echo $lang->get('Back', 'console.common'); ?>
      </a>
    </nav>
  <?php }
} ?>

  <h3 class="text-<?php echo $str_color; ?>">
    <span class="bg-icon"><?php include($tpl_icon . $str_icon . BG_EXT_SVG); ?></span>
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </h3>

  <div class="text-<?php echo $str_color; ?> lead">
    <?php if (isset($rcode)) {
      echo $rcode;
    } ?>
  </div>
  <?php if (isset($detail) && !empty($detail)) { ?>
    <hr>
    <div>
      <?php echo $detail; ?>
    </div>
  <?php }

if (isset($param['view'])) {
  switch ($param['view']) {
    case 'modal':
      modal_foot($lang, $cfg);
    break;

    case 'iframe': ?>
      </div>

      <?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
    break;
  }
} else {
  if ($is_ajax) {
    modal_foot($lang, $cfg);
  } else {
    include($tpl_include . 'console_foot' . GK_EXT_TPL);
    include($tpl_include . 'html_foot' . GK_EXT_TPL);
  }
}
