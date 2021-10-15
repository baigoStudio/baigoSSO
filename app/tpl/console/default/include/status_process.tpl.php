<?php
  if (!isset($str_status)) {
    $str_status = '';
  }

  switch ($str_status) {
    case 'error':
      $_str_color = 'danger';
    break;

    case 'wait':
      $_str_color = 'warning';
    break;

    case 'enable':
    case 'read':
      $_str_color = 'success';
    break;

    case 'on':
      $_str_color = 'info';
    break;

    default:
      $_str_color = 'secondary';
    break;
  }
  ?><span class="badge badge-<?php echo $_str_color; ?>"><?php echo $lang->get($str_status); ?></span>
