<?php $cfg = array(
    'title'         => $this->lang['mod']['page']['upgrade'] . ' &raquo; ' . $this->lang['mod']['page']['phplib'],
    'sub_title'     => $this->lang['mod']['page']['phplib'],
    'mod_help'      => 'upgrade',
    'act_help'      => 'phplib',
    'pathInclude'   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'upgrade_head.php'); ?>
    <div class="card-body">
        <?php include($cfg['pathInclude'] . 'phplib.php');

        if (isset($this->tplData['errCount']) && $this->tplData['errCount'] > 0) { ?>
            <div class="alert alert-danger">
                <span class="oi oi-circle-x"></span>
                <?php echo $this->lang['mod']['text']['phplibErr']; ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-success">
                <span class="oi oi-circle-check"></span>
                <?php echo $this->lang['mod']['text']['phplibOk']; ?>
            </div>
        <?php } ?>
    </div>

    <div class="card-footer">
        <?php if (isset($this->tplData['errCount']) && $this->tplData['errCount'] > 0) { ?>
            <div class="text-right">
                <button class="btn btn-primary" disabled><?php echo $this->lang['mod']['btn']['next']; ?></button>
            </div>
        <?php } else { ?>
            <div class="btn-toolbar justify-content-between">
                <div class="btn-group">
                    <?php include($cfg['pathInclude'] . 'upgrade_drop.php'); ?>
                    <a href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=dbconfig" class="btn btn-secondary"><?php echo $this->lang['mod']['btn']['skip']; ?></a>
                </div>

                <div class="btn-group">
                    <a class="btn btn-primary" href="<?php echo BG_URL_INSTALL; ?>index.php?m=upgrade&a=dbconfig"><?php echo $this->lang['mod']['btn']['next']; ?></a>
                </div>
            </div>
        <?php } ?>
    </div>

<?php include($cfg['pathInclude'] . 'install_foot.php');
include($cfg['pathInclude'] . 'html_foot.php');