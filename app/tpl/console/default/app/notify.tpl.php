  <?php
  $str_icon   = 'check-circle';
  $str_color  = 'success';

  if (isset($rstatus) && $rstatus == 'x') {
    $str_icon  = 'times-circle';
    $str_color = 'danger';
  } ?>
  <div class="modal-header">
    <div class="modal-title"><?php echo $lang->get('Notification test'); ?></div>
    <button type="button" class="close" data-dismiss="modal">
      &times;
    </button>
  </div>

  <div class="modal-body">
    <h3 class="text-<?php echo $str_color; ?>">
      <span class="bg-icon"><?php include($tpl_icon . $str_icon . BG_EXT_SVG); ?></span>
      <?php if (isset($msg)) {
        echo $lang->get($msg);
      } ?>
    </h3>

    <div class="text-<?php echo $str_color; ?> lead">
      <?php if (isset($rcode)) {
        echo $rcode;
      } ?>
    </div>

    <?php if (!empty($msg_more)) { ?>
      <hr>
      <div class="text-wrap text-break">
        <?php echo $msg_more; ?>
      </div>
    <?php } ?>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
      <?php echo $lang->get('Close', 'console.common'); ?>
    </button>
  </div>
