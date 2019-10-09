<?php include($cfg['pathInclude'] . 'html_head' . GK_EXT_TPL); ?>

    <div class="container">
        <div class="bg-card-xs my-lg-5 my-3">
            <div class="my-3 text-center">
                <div class="mb-3">
                    <img class="d-block mx-auto bg-login-logo" src="{:DIR_STATIC}sso/console/<?php echo $config['tpl']['path']; ?>/image/logo_green.svg">
                </div>

                <div class="lead">
                    <?php if (isset($cfg['title'])) {
                        echo $cfg['title'], ' - ';
                    }

                    echo $lang->get('Console', 'console.common'); ?>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
