                </div>
                <div class="card-footer clearfix">
                    <div class="float-left">
                        <ul class="bg-nav-line">
                            <?php if (isset($cfg['active']) && $cfg['active'] == 'login') { ?>
                                <li>
                                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=forgot"><?php echo $this->lang['mod']['href']['forgot']; ?></a>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php"><?php echo $this->lang['mod']['href']['login']; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="float-right">
                        <a href="<?php echo BG_URL_HELP; ?>" target="_blank">
                            <span class="badge badge-pill badge-primary">
                                <span class="oi oi-question-mark"></span>
                            </span>
                            <?php echo $this->lang['mod']['href']['help']; ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-right mt-3">
                <?php echo PRD_SSO_POWERED, ' ';
                if (BG_DEFAULT_UI == 'default') { ?>
                    <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
                <?php } else {
                    echo BG_DEFAULT_UI, ' SSO ';
                }
                echo PRD_SSO_VER; ?>
            </div>
        </div>
    </div>
