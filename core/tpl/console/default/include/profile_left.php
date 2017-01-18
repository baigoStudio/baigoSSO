    <div class="col-md-3">
        <div class="well">
            <div class="form-group">
                <label class="control-label"><?php echo $this->lang["label"]["id"]; ?></label>
                <div class="form-control-static"><?php echo $this->tplData["adminLogged"]["admin_id"]; ?></div>
            </div>

            <?php if ($this->tplData["adminLogged"]["admin_status"] == "enable") {
                $css_status = "success";
            } else {
                $css_status = "default";
            } ?>

            <div class="form-group">
                <label class="control-label"><?php echo $this->lang["label"]["status"]; ?></label>
                <div class="form-control-static">
                    <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->status["admin"][$this->tplData["adminLogged"]["admin_status"]]; ?></span>
                </div>
            </div>

            <?php if (isset($this->tplData["adminLogged"]["admin_allow"]["info"])) { ?>
                <div class="form-group">
                    <span class="label label-danger bg-label"><?php echo $this->lang["label"]["profileInfo"]; ?></span>
                </div>
            <?php } ?>

            <?php if (isset($this->tplData["adminLogged"]["admin_allow"]["pass"])) { ?>
                <div class="form-group">
                    <span class="label label-danger bg-label"><?php echo $this->lang["label"]["profilePass"]; ?></span>
                </div>
            <?php } ?>

            <?php if (isset($this->tplData["adminLogged"]["admin_allow"]["qa"])) { ?>
                <div class="form-group">
                    <span class="label label-danger bg-label"><?php echo $this->lang["label"]["profileQa"]; ?></span>
                </div>
            <?php } ?>
        </div>
    </div>