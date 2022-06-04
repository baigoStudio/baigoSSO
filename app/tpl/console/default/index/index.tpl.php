<?php
if (!function_exists('status_process')) {
  function status_process($str_status, $echo = '') {
    switch ($str_status) {
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
    } ?>
    <span class="badge badge-pill badge-<?php echo $_str_color; ?>">
      <?php echo $echo; ?>
    </span>
  <?php }
}

$cfg = array(
  'title'             => $lang->get('Dashboard', 'console.common'),
  'menu_active'       => 'dashboard',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span><?php echo $lang->get('Shortcut', 'console.common'); ?></span>
      <span>
        <a href="<?php echo $hrefRow['setting']; ?>">
          <span class="bg-icon"><?php include($tpl_icon . 'wrench' . BG_EXT_SVG); ?></span>
          <?php echo $lang->get('Settings'); ?>
        </a>
      </span>
    </div>
    <div class="card-body">
      <?php foreach ($adminLogged['admin_shortcut'] as $key_m=>$value_m) { ?>
        <a class="btn btn-primary m-2" href="<?php echo $value_m['href']; ?>">
          <?php echo $value_m['title']; ?>
        </a>
      <?php } ?>
    </div>
  </div>

  <div class="card-columns">
    <?php foreach ($countLists as $key=>$value) { ?>
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span><?php echo $lang->get($value['title']); ?></span>
          <span><?php echo $lang->get('Count'); ?></span>
        </div>
        <ul class="list-group list-group-flush">
          <?php foreach ($countRows[$key] as $key_sub=>$value_sub) {
            if ($key_sub == 'total') {
              $_str_title = 'Total';
            } else {
              $_str_title = $key_sub;
            } ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                  <?php echo $lang->get($_str_title); ?>
                </span>
                <?php status_process($key_sub, $value_sub); ?>
            </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
  </div>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL);
include($tpl_include . 'html_foot' . GK_EXT_TPL);
