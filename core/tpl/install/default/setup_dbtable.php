<?php $cfg = array(
    'title'         => $this->lang['mod']['page']['setup'] . ' &raquo; ' . $this->lang['mod']['page']['dbtable'],
    "sub_title"     => $this->lang['mod']['page']['dbtable'],
    "mod_help"      => 'setup',
    "act_help"      => 'dbtable',
    "pathInclude"   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'setup_head.php'); ?>

    <div class="card-body">
        <?php include($cfg['pathInclude'] . 'dbtable.php'); ?>
    </div>

    <div class="card-footer">
        <div class="btn-toolbar justify-content-between">
            <div class="btn-group">
                <a href="<?php echo BG_URL_INSTALL; ?>index.php?m=setup&a=dbconfig" class="btn btn-outline-secondary"><?php echo $this->lang['mod']['btn']['prev']; ?></a>
                <?php include($cfg['pathInclude'] . 'setup_drop.php'); ?>
                <a href="<?php echo BG_URL_INSTALL; ?>index.php?m=setup&a=base" class="btn btn-secondary"><?php echo $this->lang['mod']['btn']['skip']; ?></a>
            </div>

            <div class="btn-group">
                <a href="<?php echo BG_URL_INSTALL; ?>index.php?m=setup&a=base" class="btn btn-primary"><?php echo $this->lang['mod']['btn']['next']; ?></a>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'install_foot.php');
include($cfg['pathInclude'] . 'html_foot.php');