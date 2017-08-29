<?php $cfg = array(
    "sub_title"     => $this->lang['mod']['page']["dbTable"],
    "mod_help"      => "setup",
    "act_help"      => "dbtable",
    "pathInclude"   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'setup_head.php');
include($cfg['pathInclude'] . "dbtable.php"); ?>

    <hr class="bg-panel-hr">

    <div class="form-group clearfix">
        <div class="pull-left">
            <div class="btn-group">
                <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=dbconfig" class="btn btn-default"><?php echo $this->lang['mod']['btn']['stepPrev']; ?></a>
                <?php include($cfg['pathInclude'] . "setup_drop.php"); ?>
                <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=base" class="btn btn-default"><?php echo $this->lang['mod']['btn']['skip']; ?></a>
            </div>
        </div>

        <div class="pull-right">
            <a href="<?php echo BG_URL_INSTALL; ?>index.php?mod=setup&act=base" class="btn btn-primary"><?php echo $this->lang['mod']['btn']['stepNext']; ?></a>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'install_foot.php');
include($cfg['pathInclude'] . 'html_foot.php'); ?>