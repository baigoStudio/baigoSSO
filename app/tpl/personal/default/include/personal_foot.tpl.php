
            <div class="mt-3 text-center">
                <?php if (isset($config['var_extra']['base']['site_name'])) { ?>
                    <div>
                        <?php echo $config['var_extra']['base']['site_name']; ?>
                    </div>
                <?php } ?>
            </div>


            <?php if (!isset($ui_ctrl['copyright']) || $ui_ctrl['copyright'] === 'on') { ?>
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
