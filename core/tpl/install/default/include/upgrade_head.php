<?php $cfg['title'] = $this->lang['mod']['page']['upgrade'];
include($cfg['pathInclude'] . 'html_head.php'); ?>

    <div class="container">
        <div class="bg-card-md">
            <h3><?php echo $this->lang['mod']['page']['upgrade']; ?></h3>

            <div class="card">
                <div class="card-header bg-card-header">
                    <img class="img-fluid mx-auto d-block" src="<?php echo BG_URL_STATIC; ?>console/<?php echo BG_DEFAULT_UI; ?>/image/logo.png">
                </div>

                <div class="card-body pb-0">
                    <?php if (PRD_SSO_PUB > BG_INSTALL_PUB) { ?>
                        <div class="alert alert-warning">
                            <span class="oi oi-warning"></span>
                            <?php echo $this->lang['mod']['label']['upgrade']; ?>
                            <span class="badge badge-warning"><?php echo BG_INSTALL_VER; ?></span>
                            <?php echo $this->lang['mod']['label']['to']; ?>
                            <span class="badge badge-warning"><?php echo PRD_SSO_VER; ?></span>
                        </div>
                    <?php } ?>

                    <h4><?php echo $cfg['sub_title']; ?></h4>

                    <hr class="mb-0">
                </div>
