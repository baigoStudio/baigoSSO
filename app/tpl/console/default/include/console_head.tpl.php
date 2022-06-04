<?php use ginkgo\Plugin;

  include($tpl_include . 'html_head' . GK_EXT_TPL);

  Plugin::listen('action_console_navbar_before'); ?>

  <header class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bg-offcanvas">
      <span class="navbar-toggler-icon"></span>
    </button>
    <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#bg-profile">
      <span class="bg-icon"><?php include($tpl_icon . 'user' . BG_EXT_SVG); ?></span>
    </button>

    <div class="collapse navbar-collapse" id="bg-profile">
      <div class="navbar-nav mr-auto d-none d-lg-block">
        <a href="<?php echo $route_console; ?>" class="nav-link">
          <span class="bg-icon"><?php include($tpl_icon . 'tachometer-alt' . BG_EXT_SVG); ?></span>
          <?php if (isset($config['var_extra']['base']['site_name'])) {
            echo $config['var_extra']['base']['site_name'];
          } ?>
        </a>
      </div>
      <div class="navbar-nav mr-auto d-none d-lg-block">
        <a href="<?php echo $route_console; ?>" class="navbar-brand">
          <img src="<?php echo $ui_ctrl['logo_console_head']; ?>" class="bg-head-logo">
        </a>
      </div>
      <ul class="navbar-nav">
        <li class="nav-item dropdown<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'profile') { ?> active<?php } ?>">
          <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-toggle="dropdown">
            <span class="bg-icon"><?php include($tpl_icon . 'user' . BG_EXT_SVG); ?></span>
            <?php if (isset($adminLogged['admin_nick']) && $adminLogged['admin_nick']) {
              echo $adminLogged['admin_nick'];
            } else {
              echo $adminLogged['admin_name'];
            } ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <?php foreach ($config['console']['profile_mod'] as $_key=>$_value) { ?>
              <a href="<?php echo $_value['href']; ?>" class="dropdown-item<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'profile' && isset($cfg['sub_active']) && $cfg['sub_active'] == $_key) { ?> active<?php } ?>">
                <?php if (isset($_value['icon'])) { ?>
                  <span class="bg-icon bg-fw"><?php include($tpl_icon . $_value['icon'] . BG_EXT_SVG); ?></span>
                <?php }

                echo $lang->get($_value['title'], 'console.common'); ?>
              </a>
            <?php } ?>

            <a href="<?php echo $hrefRow['logout']; ?>" class="dropdown-item">
              <span class="bg-icon bg-fw"><?php include($tpl_icon . 'power-off' . BG_EXT_SVG); ?></span>
              <?php echo $lang->get('Logout', 'console.common'); ?>
            </a>
          </div>
        </li>
      </ul>
    </div>
  </header>

  <?php Plugin::listen('action_console_navbar_after'); //后台界面导航条之后 ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-xl-2 p-0 px-lg-3">
        <?php Plugin::listen('action_console_menu_before'); //后台界面菜单之前 ?>

        <nav id="bg-offcanvas" class="collapse d-lg-block mb-5">
          <div class="bg-sidebar">
            <div class="accordion-item mb-3">
              <div class="dropright">
                <button class="accordion-button collapsed" type="button" data-toggle="dropdown">
                  <span>
                    <span class="bg-icon bg-fw"><?php include($tpl_icon . 'external-link-alt' . BG_EXT_SVG); ?></span>
                    <?php echo $lang->get('Shortcut', 'console.common'); ?>
                  </span>
                </button>

                <div class="dropdown-menu">
                  <?php if (isset($adminLogged['admin_shortcut'])) {
                    foreach ($adminLogged['admin_shortcut'] as $key_m=>$value_m) { ?>
                      <a class="dropdown-item" href="<?php echo $value_m['href']; ?>">
                        <?php echo $value_m['title']; ?>
                      </a>
                    <?php }
                  } ?>

                  <div class="dropdown-divider"></div>

                  <a class="dropdown-item" href="<?php echo $route_console; ?>">
                    <?php echo $lang->get('Dashboard', 'console.common'); ?>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div id="bg-sidebar" class="accordion bg-sidebar">
            <?php foreach ($config['console']['console_mod'] as $key_m=>$value_m) { ?>
              <div class="accordion-item">
                <?php if (isset($value_m['lists']) && !empty($value_m['lists'])) { ?>
                  <div class="accordion-header" id="heading-<?php echo $key_m; ?>">
                    <button class="accordion-button<?php if (!isset($cfg['menu_active']) || $cfg['menu_active'] != $key_m) { ?> collapsed<?php } ?>" type="button" data-toggle="collapse" data-target="#bg-collapse-<?php echo $key_m; ?>">
                      <span>
                        <?php if (isset($value_m['main']['icon'])) { ?>
                          <span class="bg-icon bg-fw"><?php include($tpl_icon . $value_m['main']['icon'] . BG_EXT_SVG); ?></span>
                        <?php }

                        echo $lang->get($value_m['main']['title'], 'console.common'); ?>
                      </span>
                    </button>
                  </div>

                  <div id="bg-collapse-<?php echo $key_m; ?>" class="accordion-collapse collapse<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m) { ?> show<?php } ?>" data-parent="#bg-sidebar">
                    <div class="accordion-body">
                      <div class="nav flex-column">
                        <?php if ($key_m == 'link' && isset($links) && !empty($links)) {
                          foreach ($links as $key_link=>$value_link) { ?>
                            <a class="nav-link" href="<?php echo $value_link['link_url']; ?>" <?php if ($value_link['link_blank'] > 0) { ?>target="_blank"<?php } ?>>
                              <span class="d-flex justify-content-between align-items-center">
                                <span>
                                  <?php echo $value_link['link_name']; ?>
                                </span>
                                <span class="bg-icon"><?php include($tpl_icon . 'external-link-square-alt' . BG_EXT_SVG); ?></span>
                              </span>
                            </a>
                          <?php }
                        }

                        if ($key_m == 'plugin') {
                          Plugin::listen('action_console_menu_plugin');
                        }

                        foreach ($value_m['lists'] as $key_s=>$value_s) { ?>
                          <a class="nav-link<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m && isset($cfg['sub_active']) && $cfg['sub_active'] == $key_s) { ?> active<?php } ?>" href="<?php echo $value_s['href']; ?>">
                            <?php echo $lang->get($value_s['title'], 'console.common'); ?>
                          </a>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                <?php } else { ?>
                  <div class="accordion-header" id="heading-<?php echo $key_m; ?>">
                    <a class="accordion-link<?php if (!isset($cfg['menu_active']) || $cfg['menu_active'] != $key_m) { ?> collapsed<?php } ?>" href="<?php echo $value_m['main']['href']; ?>">
                      <span>
                        <?php if (isset($value_m['main']['icon'])) { ?>
                          <span class="bg-icon bg-fw"><?php include($tpl_icon . $value_m['main']['icon'] . BG_EXT_SVG); ?></span>
                        <?php }

                        echo $lang->get($value_m['main']['title'], 'console.common'); ?>
                      </span>
                    </a>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>

            <div class="accordion-item">
              <div class="accordion-header" id="heading-opt">
                <button class="accordion-button<?php if (!isset($cfg['menu_active']) || $cfg['menu_active'] != 'opt') { ?> collapsed<?php } ?>" type="button" data-toggle="collapse" data-target="#bg-collapse-opt">
                  <span>
                    <span class="bg-icon bg-fw"><?php include($tpl_icon . 'cogs' . BG_EXT_SVG); ?></span>
                    <?php echo $lang->get('System settings', 'console.common'); ?>
                  </span>
                </button>
              </div>

              <div id="bg-collapse-opt" class="accordion-collapse collapse<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt') { ?> show<?php } ?>" data-parent="#bg-sidebar">
                <div class="accordion-body">
                  <div class="nav flex-column">
                    <?php foreach ($config['console']['opt_extra'] as $key_s=>$value_s) { ?>
                      <a class="nav-link<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt' && isset($cfg['sub_active']) && $cfg['sub_active'] == $key_s) { ?> active<?php } ?>" href="<?php echo $value_s['href']; ?>">
                        <?php echo $lang->get($value_s['title'], 'console.common'); ?>
                      </a>
                    <?php }

                    foreach ($config['console']['opt'] as $key_s=>$value_s) { ?>
                      <a class="nav-link<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt' && isset($cfg['sub_active']) && $cfg['sub_active'] == $key_s) { ?> active<?php } ?>" href="<?php echo $value_s['href']; ?>">
                        <?php echo $lang->get($value_s['title'], 'console.common'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <?php Plugin::listen('action_console_menu_end'); //后台界面菜单末尾 ?>
          </div>
        </nav>

        <?php Plugin::listen('action_console_menu_after'); //后台界面菜单之后 ?>
      </div>
      <div class="col-lg-9 col-xl-10">
        <h4><?php echo $cfg['title']; ?></h4>
        <hr>
