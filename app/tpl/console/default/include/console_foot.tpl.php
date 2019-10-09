                </div>
            </div>
        </div>
    </div>

    <footer class="container-fluid text-light p-3 clearfix bg-secondary mt-3">
        <div class="float-left">
            <img class="img-fluid bg-head-logo" src="{:DIR_STATIC}sso/console/<?php echo $config['tpl']['path']; ?>/image/logo_white.svg">
        </div>
        <div class="float-right">
            <span class="d-none d-lg-inline-block">Powered by</span>
            <?php if ($config['tpl']['path'] == 'default') { ?>
                <a href="<?php echo $config['version']['prd_sso_url']; ?>"  class="text-light" target="_blank"><?php echo $config['version']['prd_sso_name']; ?></a>
            <?php } else {
                echo $config['tpl']['path'], ' SSO ';
            }
            echo $config['version']['prd_sso_ver']; ?>
        </div>
    </footer>
