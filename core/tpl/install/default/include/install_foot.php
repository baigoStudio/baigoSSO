                </div>

                <div class="panel-footer clearfix">
                    <div class="pull-left">
                        <?php if (BG_DEFAULT_UI == 'default') { ?>
                            <a href="<?php echo PRD_SSO_URL; ?>" target="_blank">
                                <?php echo PRD_SSO_POWERED, ' ', PRD_SSO_NAME; ?>
                            </a>
                        <?php } else {
                            echo BG_DEFAULT_UI, ' SSO ';
                        } ?>
                    </div>
                    <div class="pull-right">
                        <?php if (isset($cfg['mod_help']) && isset($cfg['act_help'])) { ?>
                            <a href="<?php echo BG_URL_HELP; ?>index.php?mod=<?php echo $cfg['mod_help']; ?>&act=<?php echo $cfg['act_help']; ?>" target="_blank">
                                <span class="glyphicon glyphicon-question-sign"></span>
                                <?php echo $this->lang['mod']['href']['help']; ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
