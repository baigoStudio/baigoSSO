                        </div>
                    </div>
                </div>
            </div>

            <?php if (!isset($ui_ctrl['copyright']) || $ui_ctrl['copyright'] == 'on') { ?>
                <div class="mt-3 text-right">
                    <span class="d-none d-lg-inline-block">Powered by</span>
                    <a href="<?php echo $config['version']['prd_sso_url']; ?>" target="_blank"><?php echo $config['version']['prd_sso_name']; ?></a>
                    <?php echo $config['version']['prd_sso_ver']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
