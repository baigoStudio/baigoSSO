<?php $cfg = array(
    "title"  => $this->lang["page"]["rcode"],
);

$_str_status = substr($this->tplData["rcode"], 0, 1);

include(BG_PATH_TPL . "my/default/include/my_head.php"); ?>

    <div class="form-group">
        <a href="javascript:history.go(-1);">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <?php echo $this->lang["href"]["back"]; ?>
        </a>
    </div>

    <div class="alert alert-<?php if ($_str_status == "y") { ?>success<?php } else { ?>danger<?php } ?>">
        <span class="glyphicon glyphicon-<?php if ($_str_status == "y") { ?>ok-sign<?php } else { ?>remove-sign<?php } ?>"></span>
        <?php if (isset($this->tplData["rcode"]) && !fn_isEmpty($this->tplData["rcode"]) && isset($this->rcode[$this->tplData["rcode"]])) {
            echo $this->rcode[$this->tplData["rcode"]];
        } ?>
    </div>

<?php include(BG_PATH_TPL . "my/default/include/my_foot.php"); ?>

</body>
</html>
