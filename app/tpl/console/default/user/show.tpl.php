<?php $cfg = array(
  'title'             => $lang->get('User management', 'console.common') . ' &raquo; ' . $lang->get('Show'),
  'menu_active'       => 'user',
  'sub_active'        => 'index',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $hrefRow['index']; ?>" class="nav-link">
      <span class="bg-icon"><?php include($tpl_icon . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <div class="row">
    <div class="col-xl-9">
      <div class="card mb-3">
        <div class="card-body">
          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Username'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $userRow['user_name']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Nickname'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $userRow['user_nick']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Email'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $userRow['user_mail']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Note'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $userRow['user_note']; ?></div>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="<?php echo $hrefRow['edit'], $userRow['user_id']; ?>">
            <span class="bg-icon"><?php include($tpl_icon . 'edit' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Edit'); ?>
          </a>
        </div>
      </div>

      <h5>
        <?php echo $lang->get('Authorized App'); ?>
      </h5>
      <div class="table-responsive">
        <table class="table table-striped table-hover border">
          <thead>
            <tr>
              <th class="text-nowrap bg-td-xs"><?php echo $lang->get('ID'); ?></th>
              <th><?php echo $lang->get('App'); ?></th>
              <th class="text-nowrap bg-td-md"><?php echo $lang->get('Status'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appRows as $key=>$value) { ?>
              <tr>
                <td class="text-nowrap bg-td-xs"><?php echo $value['app_id']; ?></td>
                <td>
                  <a href="<?php echo $hrefRow['app-show'], $value['app_id']; ?>"><?php echo $value['app_name']; ?></a>
                </td>
                <td class="text-nowrap bg-td-md">
                  <?php $str_status = $value['app_status'];
                  include($tpl_include . 'status_process' . GK_EXT_TPL); ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-xl-3">
      <div class="card bg-light">
        <div class="card-body">
          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('ID'); ?></label>
            <div class="form-text font-weight-bolder"><?php echo $userRow['user_id']; ?></div>
          </div>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Status'); ?></label>
            <div class="form-text font-weight-bolder"><?php $str_status = $userRow['user_status'];
            include($tpl_include . 'status_process' . GK_EXT_TPL); ?></div>
          </div>

          <?php if (is_array($userRow['user_contact'])) {
            foreach ($userRow['user_contact'] as $key=>$value) { ?>
              <div class="form-group">
                <label class="text-muted font-weight-light">
                  <?php if (isset($value['key'])) { echo $value['key']; } ?>
                </label>
                <div class="form-text font-weight-bolder"><?php if (isset($value['value'])) { echo $value['value']; } ?></div>
              </div>
            <?php }
          }

          if (is_array($userRow['user_extend'])) {
            foreach ($userRow['user_extend'] as $key=>$value) { ?>
              <div class="form-group">
                <label class="text-muted font-weight-light">
                  <?php if (isset($value['key'])) { echo $value['key']; } ?>
                </label>
                <div class="form-text font-weight-bolder"><?php if (isset($value['value'])) { echo $value['value']; } ?></div>
              </div>
            <?php }
          } ?>

          <div class="form-group">
            <label class="text-muted font-weight-light"><?php echo $lang->get('Registered from App'); ?></label>
            <div>
              <?php if (isset($appRow['app_name'])) { ?>
                <a href="<?php echo $hrefRow['app-show'], $userRow['user_app_id']; ?>">
                  <?php echo $appRow['app_name']; ?>
                </a>
              <?php } else {
                echo $lang->get('Unknown');
              } ?>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <a href="<?php echo $hrefRow['edit'], $userRow['user_id']; ?>">
            <span class="bg-icon"><?php include($tpl_icon . 'edit' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Edit'); ?>
          </a>
        </div>
      </div>
    </div>
  </div>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL);
include($tpl_include . 'html_foot' . GK_EXT_TPL);
