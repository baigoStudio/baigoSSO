<?php $cfg = array(
    'title'         => $lang->get('Installer', 'install.common'),
    'sub_title'     => $lang->get('Error', 'install.common'),
    'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'html_head' . GK_EXT_TPL); ?>

    <div class="container">
        <div class="bg-card-sm my-3">
            <h3><?php echo $lang->get('Installer', 'install.common'); ?></h3>
            <div class="card">
                <div class="card-header">
                    <img class="img-fluid mx-auto bg-head-logo" src="{:DIR_STATIC}sso/console/<?php echo $config['tpl']['path']; ?>/image/logo_green.svg">
                </div>

                <div class="card-body">
                    <h3 class="text-danger">
                        <span class="fas fa-times-circle"></span>
                        <?php if (isset($msg)) {
                            echo $lang->get($msg, 'install.common');
                        } ?>
                    </h3>
                    <div class="text-danger lead">
                        <?php if (isset($rcode)) {
                            echo $rcode;
                        } ?>
                    </div>
                    <hr>
                    <div>
                        <?php $_arr_langReplace = array(
                            'path_installed'    => $path_installed,
                            'route_install'     => $route_install,
                        );

                        if (isset($rcode)) {
                            echo $lang->get($rcode, 'install.common', $_arr_langReplace);
                        } ?>
                    </div>
                </div>
            </div>

            <div class="my-3 text-right">
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

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);