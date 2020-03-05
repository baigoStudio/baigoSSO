                        </div>
                    </div>
                </div>
            </div>

            <?php if (!isset($ui_ctrl['copyright']) || $ui_ctrl['copyright'] == 'on') { ?>
                <div class="mt-3 text-right">
                    <span class="d-none d-lg-inline-block">Powered by</span>
                    <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
                    <?php echo PRD_SSO_VER; ?>
                </div>
            <?php } ?>
        </div>
    </div>
