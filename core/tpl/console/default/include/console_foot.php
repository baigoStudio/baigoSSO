                </div>
            </div>
        </div>
    </div>

    <footer class="container-fluid text-light p-3 clearfix bg-success mt-3">
        <div class="float-left">
            <img class="img-fluid" src="<?php echo BG_URL_STATIC; ?>console/<?php echo BG_DEFAULT_UI; ?>/image/logo.png">
        </div>
        <div class="float-right">
            <?php echo PRD_SSO_POWERED, ' ';
            if (BG_DEFAULT_UI == 'default') { ?>
                <a href="<?php echo PRD_SSO_URL; ?>" target="_blank" class="text-light"><?php echo PRD_SSO_NAME; ?></a>
            <?php } else {
                echo BG_DEFAULT_UI, ' SSO ';
            }
            echo PRD_SSO_VER; ?>
        </div>
    </footer>
