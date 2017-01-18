<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <?php echo $this->consoleMod["log"]["main"]["title"] . " &raquo; " . $this->lang["page"]["detail"]; ?>
</div>
<div class="modal-body">

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["id"]; ?></label>
        <div class="form-control-static"><?php echo $this->tplData["verifyRow"]["verify_id"]; ?></div>
    </div>

    <?php switch($this->tplData["verifyRow"]["verify_status"]) {
        case "enable":
            $css_status = "sucess";
            $str_status = $this->status["verify"][$this->tplData["verifyRow"]["verify_status"]];
        break;

        case "expired":
            $css_status = "sucess";
            $str_status = $this->lang["label"]["expired"];
        break;

        default:
            $css_status = "default";
            $str_status = $this->status["verify"][$this->tplData["verifyRow"]["verify_status"]];
        break;
    } ?>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["status"]; ?></label>
        <div class="form-control-static">
            <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $str_status; ?></span>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["operator"]; ?></label>
        <div class="form-control-static">
            <?php if (isset($this->tplData["verifyRow"]["userRow"]["user_name"])) {
                echo $this->tplData["verifyRow"]["userRow"]["user_name"];
            } else {
                echo $this->lang["label"]["unknow"];
            } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["timeExpired"]; ?></label>
        <div class="form-control-static">
            <?php echo date(BG_SITE_DATE . " " . BG_SITE_TIMESHORT, $this->tplData["verifyRow"]["verify_token_expire"]); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["timeInit"]; ?></label>
        <div class="form-control-static">
            <?php echo date(BG_SITE_DATE . " " . BG_SITE_TIMESHORT, $this->tplData["verifyRow"]["verify_time_refresh"]); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["timeDisable"]; ?></label>
        <div class="form-control-static">
            <?php echo date(BG_SITE_DATE . " " . BG_SITE_TIMESHORT, $this->tplData["verifyRow"]["verify_time_disable"]); ?>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $this->lang["btn"]["close"]; ?></button>
</div>
