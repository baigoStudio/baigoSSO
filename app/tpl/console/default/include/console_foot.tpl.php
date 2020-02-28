                </div>
            </div>
        </div>
    </div>

    <footer class="container-fluid text-light p-3 clearfix bg-secondary mt-3">
        <div class="float-left">
            <img class="img-fluid bg-foot-logo" src="<?php echo $ui_ctrl['logo_console_foot']; ?>">
        </div>
        <?php if (!isset($ui_ctrl['copyright']) || $ui_ctrl['copyright'] == 'on') { ?>
            <div class="float-right">
                <span class="d-none d-lg-inline-block">Powered by</span>
                <a href="<?php echo $config['version']['prd_sso_url']; ?>"  class="text-light" target="_blank"><?php echo $config['version']['prd_sso_name']; ?></a>
                <?php echo $config['version']['prd_sso_ver']; ?>
            </div>
        <?php } ?>
    </footer>
