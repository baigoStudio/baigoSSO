<?php include($cfg['pathInclude'] . 'function.php'); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <?php echo $this->lang['consoleMod']['verify']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['show']; ?>
</div>
<div class="modal-body">

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['id']; ?></label>
        <div class="form-control-static"><?php echo $this->tplData['verifyRow']['verify_id']; ?></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?></label>
        <div class="form-control-static">
            <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $str_status; ?></span>
            <?php verify_status_process($this->tplData['verifyRow']['verify_status'], $this->lang['mod']['status']); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['operator']; ?></label>
        <div class="form-control-static">
            <?php if (isset($this->tplData['verifyRow']['userRow']['user_name'])) {
                echo $this->tplData['verifyRow']['userRow']['user_name'];
            } else {
                echo $this->lang['mod']['label']['unknow'];
            } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['timeExpired']; ?></label>
        <div class="form-control-static">
            <?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIMESHORT, $this->tplData['verifyRow']['verify_token_expire']); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['timeInit']; ?></label>
        <div class="form-control-static">
            <?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIMESHORT, $this->tplData['verifyRow']['verify_time_refresh']); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang['mod']['label']['timeDisable']; ?></label>
        <div class="form-control-static">
            <?php if ($this->tplData['verifyRow']['verify_time_disable'] > 0) {
                echo date(BG_SITE_DATE . ' ' . BG_SITE_TIMESHORT, $this->tplData['verifyRow']['verify_time_disable']);
            } ?>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang['common']['btn']['close']; ?></button>
</div>
