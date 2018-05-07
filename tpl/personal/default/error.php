<?php $cfg = array(
    'title'         => $this->lang['common']['page']['rcode'],
    'pathInclude'   => BG_PATH_TPL . 'personal' . DS . 'default' . DS . 'include' . DS,
);

$_str_status = substr($this->tplData['rcode'], 0, 1);

include($cfg['pathInclude'] . 'personal_head.php'); ?>

    <div class="form-group">
        <a href="javascript:history.go(-1);">
            <span class="oi oi-chevron-left"></span>
            <?php echo $this->lang['common']['href']['back']; ?>
        </a>
    </div>

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

<?php include($cfg['pathInclude'] . 'personal_foot.php'); ?>

</body>
</html>
