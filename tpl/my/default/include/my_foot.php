            </div>

            <div class="panel-footer clearfix">
                <div class="pull-left">
                    <?php echo PRD_SSO_POWERED; ?>
                    <?php if (BG_DEFAULT_UI == "default") { ?>
                        <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
                    <?php } else { ?>
                        <?php echo BG_DEFAULT_UI; ?> SSO
                    <?php }
                    echo PRD_SSO_VER; ?>
                </div>
                <div class="pull-right foot_logo">
                    <?php if (BG_DEFAULT_UI == "default") { ?>
                        <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_POWERED; ?> <?php echo PRD_SSO_NAME; ?> <?php echo PRD_SSO_VER; ?></a>
                    <?php } else { ?>
                        <a href="javascript:void(0);"><?php echo BG_DEFAULT_UI; ?> SSO</a>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo BG_URL_STATIC; ?>lib/baigoSubmit/baigoSubmit.min.js" type="text/javascript"></script>
    <script src="<?php echo BG_URL_STATIC; ?>lib/baigoValidator/baigoValidator.min.js" type="text/javascript"></script>
    <script src="<?php echo BG_URL_STATIC; ?>lib/reloadImg.js" type="text/javascript"></script>
    <script src="<?php echo BG_URL_STATIC; ?>lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- <?php echo PRD_SSO_POWERED; ?> <?php if (BG_DEFAULT_UI == "default") { ?><?php echo PRD_SSO_NAME; ?><?php } else { ?><?php echo BG_DEFAULT_UI; ?> SSO<?php } ?> <?php echo PRD_SSO_VER; ?> -->
