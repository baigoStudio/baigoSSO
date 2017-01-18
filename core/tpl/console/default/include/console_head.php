<?php include($cfg["pathInclude"] . "html_head.php"); ?>

    <header class="container-fluid bg-navbar">
        <div class="row">
            <div class="col-xs-4 col-sm-3">
                <a href="<?php echo BG_URL_CONSOLE; ?>" class="bg-navbar-btn hidden-sm hidden-xs">
                    <span class="glyphicon glyphicon-dashboard"></span>
                    <?php echo BG_SITE_NAME; ?>
                </a>
                <a href="javascript:void(0);" class="bg-navbar-btn hidden-md hidden-lg" data-toggle="collapse" data-target="#bg-offcanvas">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
            </div>
            <div class="col-xs-4 col-sm-6">
                <img class="img-responsive center-block bg-navbar-img" src="<?php echo BG_URL_STATIC; ?>console/<?php echo BG_DEFAULT_UI; ?>/image/logo.png">
            </div>
            <div class="col-xs-4 col-sm-3 text-right">
                <div class="dropdown<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == "profile") { ?> active<?php } ?>">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=profile" class="bg-navbar-btn dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-user"></span>
                        <span class="hidden-sm hidden-xs">
                            <?php if (isset($this->tplData["adminLogged"]["admin_nick"]) && $this->tplData["adminLogged"]["admin_nick"]) {
                                echo $this->tplData["adminLogged"]["admin_nick"];
                            } else {
                                echo $this->tplData["adminLogged"]["admin_name"];
                            } ?>
                            <span class="caret"></span>
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right bg-navbar-dropdown">
                        <?php include($cfg["pathInclude"] . "profile_menu.php"); ?>
                        <li>
                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=login&act=logout">
                                <span class="glyphicon glyphicon-off"></span>
                                <?php echo $this->lang["href"]["logout"]; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2">
                <div id="bg-offcanvas" class="panel panel-default bg-panel-accordion collapse">
                    <ul data-toggle="baigoAccordion" class="bg-accordion">
                        <?php foreach ($this->consoleMod as $key_m=>$value_m) { ?>
                            <li>
                                <div class="clearfix menu<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == $key_m) { ?> active<?php } ?>">
                                    <a href="javascript:void(0);" class="text-success">
                                        <span class="glyphicon glyphicon-<?php echo $value_m["main"]["icon"]; ?>"></span>
                                        <?php echo $value_m["main"]["title"]; ?>
                                        <span class="glyphicon glyphicon-menu-left pull-right bg-chevron"></span>
                                    </a>
                                </div>

                                <ul class="submenu<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == $key_m) { ?> in<?php } ?>">
                                    <?php foreach ($value_m["sub"] as $key_s=>$value_s) { ?>
                                        <li<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == $key_m && isset($cfg["sub_active"]) && $cfg["sub_active"] == $key_s) { ?> class="active"<?php } ?>>
                                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=<?php echo $value_s["mod"]; ?>&act=<?php echo $value_s["act"]; ?>"><?php echo $value_s["title"]; ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                        <li>
                            <div class="clearfix menu<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == "opt") { ?> active<?php } ?>">
                                <a href="javascript:void(0);" class="text-success">
                                    <span class="glyphicon glyphicon-cog"></span>
                                    <?php echo $this->lang["href"]["opt"]; ?>
                                    <span class="glyphicon glyphicon-menu-left pull-right bg-chevron"></span>
                                </a>
                            </div>

                            <ul class="submenu<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == "opt") { ?> in<?php } ?>">
                                <?php foreach ($this->opt as $key_opt=>$value_opt) { ?>
                                    <li<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == "opt" && isset($cfg["sub_active"]) && $cfg["sub_active"] == $key_opt) { ?> class="active"<?php } ?>>
                                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=opt&act=<?php echo $key_opt; ?>"><?php echo $value_opt["title"]; ?></a>
                                    </li>
                                <?php } ?>
                                <li<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == "opt" && isset($cfg["sub_active"]) && $cfg["sub_active"] == "dbconfig") { ?> class="active"<?php } ?>>
                                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=opt&act=dbconfig"><?php echo $this->lang["page"]["setupDbConfig"]; ?></a>
                                </li>
                                <li<?php if (isset($cfg["menu_active"]) && $cfg["menu_active"] == "opt" && isset($cfg["sub_active"]) && $cfg["sub_active"] == "chkver") { ?> class="active"<?php } ?>>
                                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=opt&act=chkver"><?php echo $this->lang["page"]["chkver"]; ?></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-lg-10">
                <h4><?php echo $cfg["title"]; ?></h4>
                <hr>