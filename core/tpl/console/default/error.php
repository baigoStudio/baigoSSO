<?php $cfg = array(
    'title'         => $this->lang['common']['page']['rcode'],
    'pathInclude'   => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
);

$_str_status = substr($this->tplData['rcode'], 0, 1);

if ($GLOBALS['view'] == 'iframe') { ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <?php echo $this->lang['common']['page']['rcode']; ?>
    </div>
    <div class="modal-body">
<?php } else {
    include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <a href="javascript:history.go(-1);">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <?php echo $this->lang['mod']['href']['back']; ?>
        </a>
    </div>
<?php } ?>

        <div class="alert alert-<?php if ($_str_status == 'y') { ?>success<?php } else { ?>danger<?php } ?>">
            <h3>
                <span class="glyphicon glyphicon-<?php if ($_str_status == 'y') { ?>ok-sign<?php } else { ?>remove-sign<?php } ?>"></span>
                <?php if (isset($this->tplData['rcode']) && !fn_isEmpty($this->tplData['rcode']) && isset($this->lang['rcode'][$this->tplData['rcode']])) {
                    echo $this->lang['rcode'][$this->tplData['rcode']];
                } ?>
            </h3>
            <div>
                <?php if (isset($this->tplData['rcode']) && !fn_isEmpty($this->tplData['rcode']) && isset($this->lang['mod']['text'][$this->tplData['rcode']])) {
                    echo $this->lang['mod']['text'][$this->tplData['rcode']];
                } ?>
            </div>
            <div>
                <?php if (isset($this->tplData['rcode']) && !fn_isEmpty($this->tplData['rcode'])) {
                    echo $this->lang['common']['page']['rcode'], ' ', $this->tplData['rcode'];
                } ?>
            </div>
        </div>

<?php if ($GLOBALS['view'] == 'iframe') { ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang['common']['btn']['close']; ?></button>
    </div>
<?php } else {
    include($cfg['pathInclude'] . 'console_foot.php');
    include($cfg['pathInclude'] . 'html_foot.php');
} ?>

