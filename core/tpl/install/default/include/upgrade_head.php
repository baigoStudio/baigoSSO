<?php $cfg['title'] = $this->lang['mod']['page']['upgrade'];
include($cfg['pathInclude'] . 'html_head.php'); ?>

    <div class="container">
        <div class="bg-panel">
            <h3><?php echo $this->lang['mod']['page']['upgrade']; ?></h3>

            <div class="panel panel-success">
                <div class="panel-heading bg-panel-heading">
                    <img class="img-responsive center-block" src="<?php echo BG_URL_STATIC; ?>console/<?php echo BG_DEFAULT_UI; ?>/image/logo.png">
                </div>

                <div class="panel-body">

                    <?php if (PRD_SSO_PUB > BG_INSTALL_PUB) { ?>
                        <div class="alert alert-warning">
                            <span class="glyphicon glyphicon-warning-sign"></span>
                            <?php echo $this->lang['mod']['label']['upgrade']; ?>
                            <span class="label label-warning bg-label"><?php echo BG_INSTALL_VER; ?></span>
                            <?php echo $this->lang['mod']['label']['to']; ?>
                            <span class="label label-warning bg-label"><?php echo PRD_SSO_VER; ?></span>
                        </div>
                    <?php } ?>

                    <h4><?php echo $cfg['sub_title']; ?></h4>

                    <hr>
