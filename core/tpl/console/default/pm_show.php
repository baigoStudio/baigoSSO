<?php if ($this->tplData["pmRow"]["pm_status"] == "read") {
    $css_status = "success";
} else {
    $css_status = "warning";
} ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <?php echo $this->consoleMod["pm"]["main"]["title"] . " &raquo; " . $this->lang["page"]["show"]; ?>
</div>
<div class="modal-body">

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["pmFrom"]; ?></label>
        <div class="form-control-static">
            <?php if ($this->tplData["pmRow"]["pm_from"] == -1) {
                echo $this->lang["label"]["pmSys"];
            } else if (isset($this->tplData["pmRow"]["fromUser"]["user_name"])) {
                echo $this->tplData["pmRow"]["fromUser"]["user_name"];
            } else {
                echo $this->lang["label"]["unknow"];
            } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["pmTo"]; ?></label>
        <div class="form-control-static">
            <?php if (isset($this->tplData["pmRow"]["toUser"]["user_name"])) {
                echo $this->tplData["pmRow"]["toUser"]["user_name"];
            } else {
                echo $this->lang["label"]["unknow"];
            } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["title"]; ?></label>
        <div class="form-control-static"><?php echo fn_htmlcode($this->tplData["pmRow"]["pm_title"], "decode", "json"); ?></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["content"]; ?></label>
        <p><?php echo fn_htmlcode($this->tplData["pmRow"]["pm_content"], "decode", "json"); ?></p>
    </div>

    <div class="form-group">
        <div class="form-control-static">
            <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->status["pm"][$this->tplData["pmRow"]["pm_status"]]; ?></span>
        </div>
    </div>

    <div class="form-group">
        <div class="form-control-static">
            <span class="glyphicon glyphicon-import"></span>
            <?php echo $this->type["pm"][$this->tplData["pmRow"]["pm_type"]]; ?>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $this->lang["btn"]["close"]; ?></button>
</div>
