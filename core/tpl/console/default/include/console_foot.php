                </div>
            </div>
        </div>
    </div>

    <footer class="container-fluid clearfix bg-success bg-footer">
        <div class="pull-left">
            <img class="img-responsive" src="<?php echo BG_URL_STATIC; ?>console/<?php echo BG_DEFAULT_UI; ?>/image/logo.png">
        </div>
        <div class="pull-right">
            <?php echo PRD_SSO_POWERED, ' ';
            if (BG_DEFAULT_UI == 'default') { ?>
                <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
            <?php } else {
                echo BG_DEFAULT_UI, ' SSO ';
            }
            echo PRD_SSO_VER; ?>
        </div>
    </footer>
