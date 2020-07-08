<?php use ginkgo\Plugin;

    include($cfg['pathInclude'] . 'html_head' . GK_EXT_TPL);

    Plugin::listen('action_console_navbar_before'); ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bg-offcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#bg-profile">
            <span class="fas fa-user"></span>
        </button>

        <div class="collapse navbar-collapse" id="bg-profile">
            <div class="navbar-nav mr-auto d-none d-lg-block">
                <a href="<?php echo $route_console; ?>" class="nav-link">
                    <span class="fas fa-tachometer-alt"></span>
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
                        <span class="fas fa-user"></span>
                        <?php if (isset($adminLogged['admin_nick']) && $adminLogged['admin_nick']) {
                            echo $adminLogged['admin_nick'];
                        } else {
                            echo $adminLogged['admin_name'];
                        } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php foreach ($config['console']['profile_mod'] as $_key=>$_value) { ?>
                            <a href="<?php echo $route_console; ?>profile/<?php echo $_key; ?>/" class="dropdown-item<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'profile' && isset($cfg['sub_active']) && $cfg['sub_active'] == $_key) { ?> active<?php } ?>">
                                <?php if (isset($_value['icon'])) { ?>
                                    <span class="fas fa-<?php echo $_value['icon']; ?> fa-fw"></span>
                                <?php }

                                echo $lang->get($_value['title'], 'console.common'); ?>
                            </a>
                        <?php } ?>

                        <a href="<?php echo $route_console; ?>login/logout/" class="dropdown-item">
                            <span class="fas fa-power-off fa-fw"></span>
                            <?php echo $lang->get('Logout', 'console.common'); ?>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <?php Plugin::listen('action_console_navbar_after'); //后台界面导航条之后 ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 p-0 px-lg-3">
                <?php Plugin::listen('action_console_menu_before'); //后台界面菜单之前 ?>

                <nav id="bg-offcanvas" class="collapse d-lg-block mb-5">
                    <div id="bg-accordion" class="bg-accordion">
                        <div class="card border-success">
                            <div class="list-group list-group-flush">
                                <div class="dropright">
                                    <a href="javascript:void(0);" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" data-toggle="dropdown">
                                        <span>
                                            <span class="fas fa-external-link-alt fa-fw"></span>
                                            <?php echo $lang->get('Shortcut', 'console.common'); ?>
                                        </span>

                                        <small class="fas fa-chevron-right"></small>
                                    </a>

                                    <div class="dropdown-menu">
                                        <?php foreach ($adminLogged['admin_shortcut'] as $key_m=>$value_m) { ?>
                                            <a class="dropdown-item" href="<?php echo $route_console, $value_m['ctrl']; ?>/<?php echo $value_m['act']; ?>/">
                                                <?php echo $value_m['title']; ?>
                                            </a>
                                        <?php } ?>

                                        <a class="dropdown-item" href="<?php echo $route_console; ?>">
                                            <?php echo $lang->get('Dashboard', 'console.common'); ?>
                                        </a>
                                    </div>
                                </div>

                                <?php foreach ($config['console']['console_mod'] as $key_m=>$value_m) { ?>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m) { ?> bg-success border-success active<?php } ?>" data-toggle="collapse" href="#bg-collapse-<?php echo $key_m; ?>">
                                        <span>
                                            <?php if (isset($value_m['main']['icon'])) { ?>
                                                <span class="fas fa-<?php echo $value_m['main']['icon']; ?> fa-fw"></span>
                                            <?php }

                                            echo $lang->get($value_m['main']['title'], 'console.common'); ?>
                                        </span>

                                        <small class="fas fa-chevron-<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m) { ?>up<?php } else { ?>down<?php } ?>" id="bg-caret-<?php echo $key_m; ?>"></small>
                                    </a>

                                    <div id="bg-collapse-<?php echo $key_m; ?>" data-key="<?php echo $key_m; ?>" class="collapse<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m) { ?> show<?php } ?>" data-parent="#bg-accordion">

                                        <?php if ($key_m == 'plugin') {
                                            Plugin::listen('action_console_menu_plugin');
                                        }

                                        foreach ($value_m['lists'] as $key_s=>$value_s) { ?>
                                            <a class="list-group-item<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m && isset($cfg['sub_active']) && $cfg['sub_active'] == $key_s) { ?> list-group-item-success<?php } ?>" href="<?php echo $route_console, $value_s['ctrl']; ?>/<?php echo $value_s['act']; ?>/">
                                                <?php echo $lang->get($value_s['title'], 'console.common'); ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>


                                <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt') { ?> bg-success border-success active<?php } ?>" data-toggle="collapse" href="#bg-collapse-opt">
                                    <span>
                                        <span class="fas fa-cog fa-fw"></span>
                                        <?php echo $lang->get('System settings', 'console.common'); ?>
                                    </span>

                                    <small class="fas fa-chevron-<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt') { ?>up<?php } else { ?>down<?php } ?>" id="bg-caret-opt"></small>
                                </a>

                                <div id="bg-collapse-opt" data-key="opt" class="collapse<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt') { ?> show<?php } ?>" data-parent="#bg-accordion">
                                    <?php foreach ($config['console']['opt'] as $key_s=>$value_s) { ?>
                                        <a class="list-group-item<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt' && isset($cfg['sub_active']) && $cfg['sub_active'] == $key_s) { ?> list-group-item-success<?php } ?>" href="<?php echo $route_console; ?>opt/<?php echo $key_s; ?>/">
                                            <?php echo $lang->get($value_s['title'], 'console.common'); ?>
                                        </a>
                                    <?php } ?>
                                </div>

                                <?php Plugin::listen('action_console_menu_end'); //后台界面菜单末尾 ?>
                            </div>
                            <div class="card-footer d-none d-lg-block">
                                <small>
                                    <a href="<?php echo PRD_SSO_HELP; ?>" target="_blank">
                                        <span class="fas fa-question-circle"></span>
                                        <?php echo $lang->get('Help', 'console.common'); ?>
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>
                </nav>

                <?php Plugin::listen('action_console_menu_after'); //后台界面菜单之后 ?>

            </div>
            <div class="col-lg-9 col-xl-10">
                <h4><?php echo $cfg['title']; ?></h4>
                <hr>