                </div>
            </div>

            <div class="mt-3 text-center">
                <?php if (isset($cfg['active']) && $cfg['active'] == 'login') { ?>
                    <a href="<?php echo $url_personal; ?>forgot/" target="_blank"><?php echo $lang->get('Forgot password'); ?></a>
                <?php } else { ?>
                    <a href="<?php echo $route_console; ?>login/"><?php echo $lang->get('Login now'); ?></a>
                <?php } ?>

                <?php if (isset($config['var_extra']['base']['site_name'])) { ?>
                    <hr>
                    <div>
                        <?php echo $config['var_extra']['base']['site_name']; ?>
                    </div>
                <?php } ?>
            </div>

            <?php if (!isset($ui_ctrl['copyright']) || $ui_ctrl['copyright'] == 'on') { ?>
                <div class="mt-5">
                    <hr>
                    <div class="text-center">
                        <span class="d-none d-lg-inline-block">Powered by</span>
                        <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
                        <?php echo PRD_SSO_VER; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
