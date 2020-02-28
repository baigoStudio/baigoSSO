<?php include($cfg['pathInclude'] . 'html_head' . GK_EXT_TPL); ?>

    <div class="container">
        <div class="bg-card-xs my-lg-5 my-3">
            <div class="my-3 text-center">
                <div class="mb-3">
                    <img class="d-block mx-auto bg-logo-sm" src="<?php echo $ui_ctrl['logo_console_login']; ?>">
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
