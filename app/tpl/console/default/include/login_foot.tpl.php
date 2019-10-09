                </div>
            </div>

            <div class="mt-3 text-center">
                <?php if (isset($cfg['active']) && $cfg['active'] == 'login') { ?>
                    <a href="<?php echo $url_personal; ?>forgot/" target="_blank"><?php echo $lang->get('Forgot password'); ?></a>
                <?php } else { ?>
                    <a href="<?php echo $route_console; ?>login/"><?php echo $lang->get('Login now'); ?></a>
                <?php } ?>

                <?php if (isset($config['site_name'])) { ?>
                    <hr>
                    <div>
                        <?php echo $config['site_name']; ?>
                    </div>
                <?php } ?>
            </div>

            <div class="mt-5">
                <hr>
                <div class="text-center">
                    <span class="d-none d-lg-inline-block">Powered by</span>
                    <?php if ($config['tpl']['path'] == 'default') { ?>
                        <a href="<?php echo $config['version']['prd_sso_url']; ?>" target="_blank"><?php echo $config['version']['prd_sso_name']; ?></a>
                    <?php } else {
                        echo $config['tpl']['path'], ' SSO ';
                    }
                    echo $config['version']['prd_sso_ver']; ?>
                </div>
            </div>
        </div>
    </div>
