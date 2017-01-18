<?php $cfg = array(
    "title"         => $this->lang["page"]["rcode"],
    "pathInclude"   => BG_PATH_TPLSYS . "console/default/include/",
);

$_str_status = substr($this->tplData["rcode"], 0, 1);
?>

<?php if ($GLOBALS["view"] == "iframe") { ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <?php echo $this->lang["page"]["rcode"]; ?>
    </div>
    <div class="modal-body">
<?php } else { ?>

    <?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group">
        <a href="javascript:history.go(-1);">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <?php echo $this->lang["href"]["back"]; ?>
        </a>
    </div>
<?php } ?>

        <div class="alert alert-<?php if ($_str_status == "y") { ?>success<?php } else { ?>danger<?php } ?>">
            <h3>
                <span class="glyphicon glyphicon-<?php if ($_str_status == "y") { ?>ok-sign<?php } else { ?>remove-sign<?php } ?>"></span>
                <?php if (isset($this->tplData["rcode"]) && !fn_isEmpty($this->tplData["rcode"]) && isset($this->rcode[$this->tplData["rcode"]])) {
                    echo $this->rcode[$this->tplData["rcode"]];
                } ?>
            </h3>
            <p>
                <?php if (isset($this->tplData["rcode"]) && !fn_isEmpty($this->tplData["rcode"]) && isset($this->lang["text"][$this->tplData["rcode"]])) {
                    echo $this->lang["text"][$this->tplData["rcode"]];
                } ?>
            </p>
            <p>
                <?php if (isset($this->tplData["rcode"]) && !fn_isEmpty($this->tplData["rcode"])) {
                    echo $this->lang["label"]["rcode"]; ?>
                    :
                    <?php echo $this->tplData["rcode"];
                } ?>
            </p>
        </div>

<?php if ($GLOBALS["view"] == "iframe") { ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $this->lang["btn"]["close"]; ?></button>
    </div>
<?php } else { ?>
    <?php include($cfg["pathInclude"] . "console_foot.php"); ?>
    <?php include($cfg["pathInclude"] . "html_foot.php"); ?>
<?php } ?>

