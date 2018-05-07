<?php include($cfg['pathInclude'] . 'html_head.php'); ?>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-between mb-3">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bg-offcanvas" aria-controls="bg-offcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#bg-profile" aria-controls="bg-profile">
            <span class="oi oi-person"></span>
        </button>

        <div class="collapse navbar-collapse" id="bg-profile">
            <div class="navbar-nav mr-auto d-none d-md-block">
                <a href="<?php echo BG_URL_CONSOLE; ?>" class="nav-link">
                    <span class="oi oi-dashboard"></span>
                    <?php echo BG_SITE_NAME; ?>
                </a>
            </div>
            <div class="navbar-nav mr-auto d-none d-md-block">
                <a href="<?php echo BG_URL_CONSOLE; ?>" class="navbar-brand">
                    <img src="<?php echo BG_URL_STATIC; ?>console/<?php echo BG_DEFAULT_UI; ?>/image/logo.png">
                </a>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item dropdown<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'profile') { ?> active<?php } ?>">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=profile&a=info" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <span class="oi oi-person"></span>
                        <?php if (isset($this->tplData['adminLogged']['admin_nick']) && $this->tplData['adminLogged']['admin_nick']) {
                            echo $this->tplData['adminLogged']['admin_nick'];
                        } else {
                            echo $this->tplData['adminLogged']['admin_name'];
                        } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php foreach ($this->profile as $_key=>$_value) { ?>
                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=profile&a=<?php echo $_key; ?>" class="dropdown-item<?php if (isset($cfg['sub_active']) && $cfg['sub_active'] == $_key) { ?> active<?php } ?>">
                                <?php if (isset($this->lang['common']['profile'][$_key]['icon'])) { ?>
                                    <span class="oi oi-<?php echo $this->lang['common']['profile'][$_key]['icon']; ?>"></span>
                                <?php }

                                if (isset($this->lang['common']['profile'][$_key]['title'])) {
                                    echo $this->lang['common']['profile'][$_key]['title'];
                                } else {
                                    echo $_value['title'];
                                } ?>
                            </a>
                        <?php } ?>

                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=login&a=logout" class="dropdown-item">
                            <span class="oi oi-power-standby"></span>
                            <?php echo $this->lang['common']['href']['logout']; ?>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2">
                <nav id="bg-offcanvas" class="bg-offcanvas collapse mb-3">
                    <div id="bg-accordion" class="bg-accordion">
                        <div class="card border-success">
                            <div class="list-group list-group-flush">
                                <?php foreach ($this->consoleMod as $key_m=>$value_m) { ?>
                                    <a class="list-group-item d-flex justify-content-between list-group-item-action<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m) { ?> bg-success border-success active<?php } ?>" data-toggle="collapse" href="#bg-collapse-<?php echo $key_m; ?>" aria-expanded="true" aria-controls="bg-collapse-<?php echo $key_m; ?>">
                                        <span>
                                            <?php if (isset($this->lang['consoleMod'][$key_m]['main']['icon'])) { ?>
                                                <span class="oi oi-<?php echo $this->lang['consoleMod'][$key_m]['main']['icon']; ?>"></span>
                                            <?php }
                                            if (isset($this->lang['consoleMod'][$key_m]['main']['title'])) {
                                                echo $this->lang['consoleMod'][$key_m]['main']['title'];
                                            } else {
                                                echo $value_m['main']['title'];
                                            } ?>
                                        </span>

                                        <small class="oi oi-chevron-<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m) { ?>right<?php } else { ?>bottom<?php } ?>" id="bg-caret-<?php echo $key_m; ?>"></small>
                                    </a>

                                    <div id="bg-collapse-<?php echo $key_m; ?>" data-key="<?php echo $key_m; ?>" class="collapse<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m) { ?> show<?php } ?>" aria-labelledby="bg-heading-<?php echo $key_m; ?>" data-parent="#bg-accordion">
                                        <?php foreach ($value_m['sub'] as $key_s=>$value_s) { ?>
                                            <a class="list-group-item<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == $key_m && isset($cfg['sub_active']) && $cfg['sub_active'] == $key_s) { ?> list-group-item-success<?php } ?>" href="<?php echo BG_URL_CONSOLE; ?>index.php?m=<?php echo $value_s['mod']; ?>&a=<?php echo $value_s['act']; ?>">
                                                <?php if (isset($this->lang['consoleMod'][$key_m]['sub'][$key_s])) {
                                                    echo $this->lang['consoleMod'][$key_m]['sub'][$key_s];
                                                } else {
                                                    echo $value_s['title'];
                                                } ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <a class="list-group-item list-group-item-action d-flex justify-content-between<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt') { ?> bg-success border-success active<?php } ?>" data-toggle="collapse" href="#bg-collapse-opt" aria-expanded="true" aria-controls="bg-collapse-opt">
                                    <span>
                                        <span class="oi oi-cog"></span>
                                        <?php echo $this->lang['common']['page']['opt']; ?>
                                    </span>

                                    <small class="oi oi-chevron-<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt') { ?>right<?php } else { ?>bottom<?php } ?>" id="bg-caret-opt"></small>
                                </a>

                                <div id="bg-collapse-opt" data-key="opt" class="collapse<?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt') { ?> show<?php } ?>" aria-labelledby="bg-heading-opt" data-parent="#bg-accordion">
                                    <?php foreach ($this->opt as $key_opt=>$value_opt) { ?>
                                        <a class="list-group-item <?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt' && isset($cfg['sub_active']) && $cfg['sub_active'] == $key_opt) { ?> list-group-item-success<?php } ?>" href="<?php echo BG_URL_CONSOLE; ?>index.php?m=opt&a=<?php echo $key_opt; ?>">
                                            <?php if (isset($this->lang['opt'][$key_opt]['title'])) {
                                                echo $this->lang['opt'][$key_opt]['title'];
                                            } else {
                                                echo $key_opt;
                                            } ?>
                                        </a>
                                    <?php } ?>
                                    <a class="list-group-item <?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt' && isset($cfg['sub_active']) && $cfg['sub_active'] == 'dbconfig') { ?> list-group-item-success<?php } ?>" href="<?php echo BG_URL_CONSOLE; ?>index.php?m=opt&a=dbconfig">
                                        <?php echo $this->lang['common']['page']['dbconfig']; ?>
                                    </a>
                                    <a class="list-group-item <?php if (isset($cfg['menu_active']) && $cfg['menu_active'] == 'opt' && isset($cfg['sub_active']) && $cfg['sub_active'] == 'chkver') { ?> list-group-item-success<?php } ?>" href="<?php echo BG_URL_CONSOLE; ?>index.php?m=opt&a=chkver">
                                        <?php echo $this->lang['common']['page']['chkver']; ?>
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer d-none d-md-block">
                                <small>
                                    <?php echo PRD_SSO_POWERED, ' ';
                                    if (BG_DEFAULT_UI == 'default') { ?>
                                        <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
                                    <?php } else {
                                        echo BG_DEFAULT_UI, ' SSO ';
                                    }
                                    echo PRD_SSO_VER; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-md-9 col-lg-10">
                <h4><?php echo $cfg['title']; ?></h4>
                <hr>