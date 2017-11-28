<?php include($cfg['pathInclude'] . 'function.php'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <?php echo $this->lang['consoleMod']['pm']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['show']; ?>
</div>
<div class="modal-body">

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['pmFrom']; ?></label>
        <div class="form-control-static">
            <?php if ($this->tplData['pmRow']['pm_from'] == -1) {
                echo $this->lang['mod']['label']['pmSys'];
            } else if (isset($this->tplData['pmRow']['fromUser']['user_name'])) {
                echo $this->tplData['pmRow']['fromUser']['user_name'];
            } else {
                echo $this->lang['mod']['label']['unknow'];
            } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['pmTo']; ?></label>
        <div class="form-control-static">
            <?php if (isset($this->tplData['pmRow']['toUser']['user_name'])) {
                echo $this->tplData['pmRow']['toUser']['user_name'];
            } else {
                echo $this->lang['mod']['label']['unknow'];
            } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['title']; ?></label>
        <div class="form-control-static"><?php echo fn_htmlcode($this->tplData['pmRow']['pm_title'], 'decode', 'json'); ?></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['content']; ?></label>
        <div><?php echo fn_htmlcode($this->tplData['pmRow']['pm_content'], 'decode', 'json'); ?></div>
    </div>

    <div class="form-group">
        <div class="form-control-static">
            <?php pm_status_process($this->tplData['pmRow']['pm_status'], $this->lang['mod']['status']); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="form-control-static">
            <span class="glyphicon glyphicon-import"></span>
            <?php echo $this->lang['mod']['type'][$this->tplData['pmRow']['pm_type']]; ?>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang['common']['btn']['close']; ?></button>
</div>
