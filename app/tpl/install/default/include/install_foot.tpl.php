                            <div class="bg-box"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-right">
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
