<?php $cfg = array(
    'title'     => $this->lang['mod']['page']['setup'] . ' &raquo; ' . $this->lang['common']['page']['rcode'],
    'sub_title'     => $this->lang['common']['page']['rcode'],
    'mod_help'      => 'setup',
    'act_help'      => 'outline',
    'pathInclude'   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

$_str_status = substr($this->tplData['rcode'], 0, 1);

include($cfg['pathInclude'] . 'setup_head.php'); ?>

    <div class="card-body">
        <h3 class="<?php if ($_str_status == 'y') { ?>text-success<?php } else { ?>text-danger<?php } ?>">
            <span class="oi oi-<?php if ($_str_status == 'y') { ?>circle-check<?php } else { ?>circle-x<?php } ?>"></span>
            <?php if (isset($this->tplData['rcode']) && !fn_isEmpty($this->tplData['rcode']) && isset($this->lang['rcode'][$this->tplData['rcode']])) {
                echo $this->lang['rcode'][$this->tplData['rcode']];
            } ?>
        </h3>
        <hr>
        <div>
            <?php if (isset($this->tplData['rcode']) && !fn_isEmpty($this->tplData['rcode']) && isset($this->lang['common']['text'][$this->tplData['rcode']])) {
                echo $this->lang['common']['text'][$this->tplData['rcode']];
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
    </div>

<?php include($cfg['pathInclude'] . 'install_foot.php');
include($cfg['pathInclude'] . 'html_foot.php');