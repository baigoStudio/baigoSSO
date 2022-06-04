<?php include($tpl_include . 'html_head' . GK_EXT_TPL); ?>

  <div class="container">
    <div class="bg-card-md my-lg-5 my-3">
      <div class="clearfix mb-2">
        <div class="float-left">
          <img class="img-fluid bg-logo-sm" src="<?php echo $ui_ctrl['logo_install']; ?>">
        </div>
        <h3 class="float-right"><?php echo $lang->get('Installer'); ?></h3>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-4">
              <ul class="nav flex-column mb-3">
                <?php foreach ($config['install']['index'] as $key_opt=>$value_opt) { ?>
                  <li class="nav-item">
                    <a class="nav-link<?php if ($cfg['active'] == $key_opt) { ?> disabled<?php } ?>" href="<?php echo $value_opt['href']; ?>">
                      <?php echo $lang->get($value_opt['title']); ?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
            <div class="col-lg-8">
              <h4><?php echo $cfg['sub_title']; ?></h4>
              <hr>
