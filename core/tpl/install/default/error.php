<?php $cfg = array(
    'title'     => $this->lang['mod']['page']['setup'] . ' &raquo; ' . $this->lang['common']['page']['rcode'],
    'sub_title'     => $this->lang['common']['page']['rcode'],
    'mod_help'      => "install",
    'act_help'      => "phplib",
    'pathInclude'   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

$_str_status = substr($this->tplData['rcode'], 0, 1);
?>

<?php include($cfg['pathInclude'] . 'setup_head.php'); ?>

    <h3 class="<?php if ($_str_status == 'y') { ?>text-success<?php } else { ?>text-danger<?php } ?>">
        <span class="glyphicon glyphicon-<?php if ($_str_status == 'y') { ?>ok-sign<?php } else { ?>remove-sign<?php } ?>"></span>
        <?php if (isset($this->tplData['rcode']) && !fn_isEmpty($this->tplData['rcode']) && isset($this->lang['rcode'][$this->tplData['rcode']])) {
            echo $this->lang['rcode'][$this->tplData['rcode']];
        } ?>
    </h3>
    <hr>
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

    <?php if (isset($this->tplData['rcode']) && !fn_isEmpty($this->tplData['rcode']) && isset($this->install[$this->tplData['rcode']])) {
        echo $this->lang['mod']['text'][$this->tplData['rcode']];
    } ?>

<?php include($cfg['pathInclude'] . 'install_foot.php');
include($cfg['pathInclude'] . 'html_foot.php'); ?>