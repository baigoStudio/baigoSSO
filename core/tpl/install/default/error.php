<?php $cfg = array(
    'sub_title'     => $this->lang['common']['page']['rcode'],
    'pathInclude'   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

$_str_status = substr($this->tplData['rcode'], 0, 1);

include($cfg['pathInclude'] . 'setup_head.php'); ?>

    <div class="form-group">
        <a href="javascript:history.go(-1);">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <?php echo $this->lang['mod']['href']['back']; ?>
        </a>
    </div>

    <div class="alert alert-<?php if ($_str_status == "y") { ?>success<?php } else { ?>danger<?php } ?>">
        <h3>
            <span class="glyphicon glyphicon-<?php if ($_str_status == "y") { ?>ok-sign<?php } else { ?>remove-sign<?php } ?>"></span>
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

<?php include($cfg['pathInclude'] . 'install_foot.php');
include($cfg['pathInclude'] . 'html_foot.php'); ?>