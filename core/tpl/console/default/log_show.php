<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <?php echo $this->consoleMod["log"]["main"]["title"] . " &raquo; " . $this->lang["page"]["detail"]; ?>
</div>
<div class="modal-body">

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["id"]; ?></label>
        <div class="form-control-static"><?php echo $this->tplData["logRow"]["log_id"]; ?></div>
    </div>

    <?php if ($this->tplData["logRow"]["log_status"] == "read") {
        $css_status = "default";
    } else {
        $css_status = "warning";
    } ?>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["status"]; ?></label>
        <div class="form-control-static">
            <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->status["log"][$this->tplData["logRow"]["log_status"]]; ?></span>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["content"]; ?></label>
        <div class="form-control-static"><?php echo $this->tplData["logRow"]["log_title"]; ?></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["target"]; ?></label>
        <div class="form-control-static">
            <?php foreach ($this->tplData["logRow"]["log_targets"] as $key=>$value) { ?>
                <div>
                    <?php switch($this->tplData["logRow"]["log_target_type"]) {
                        case "admin": ?>
                            <?php if (isset($value["adminRow"]["admin_name"])) { ?>
                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=admin&act=show&admin_id=<?php echo $value["adminRow"]["admin_id"]; ?>"><?php echo $value["adminRow"]["admin_name"]; ?></a>
                            <?php } else {
                                echo $this->lang["label"]["unknow"];
                            } ?>
                            -> ID: <?php echo $value["admin_id"]; ?>
                        <?php break;

                        case "user": ?>
                            <?php if (isset($value["userRow"]["user_name"])) { ?>
                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=user&act=show&user_id=<?php echo $value["userRow"]["user_id"]; ?>"><?php echo $value["userRow"]["user_name"]; ?></a>
                            <?php } else {
                                echo $this->lang["label"]["unknow"];
                            } ?>
                            -> ID: <?php echo $value["user_id"]; ?>
                        <?php break;

                        case "app": ?>
                            <?php if (isset($value["appRow"]["app_name"])) { ?>
                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=show&app_id=<?php echo $value["appRow"]["app_id"]; ?>"><?php echo $value["appRow"]["app_name"]; ?></a>
                            <?php } else {
                                echo $this->lang["label"]["unknow"];
                            } ?>
                            -> ID: <?php echo $value["app_id"]; ?>
                        <?php break;

                        case "verify":
                            if (isset($value["verifyRow"]["verify_user_id"])) { ?>
                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=verify&act=show&verify_id=<?php echo $value["verifyRow"]["verify_id"]; ?>"><?php echo $value["verifyRow"]["verify_user_id"]; ?></a>
                            <?php } else {
                                echo $this->lang["label"]["unknow"];
                            } ?>
                            -> ID: <?php echo $value["verify_id"]; ?>
                        <?php break;

                        case "log":
                            if (isset($value["logRow"]["log_id"])) {
                                echo $value["logRow"]["log_id"];
                            } else {
                                echo $this->lang["label"]["unknow"];
                            }
                        break;

                        default:
                            echo $this->type["logTarget"][$this->tplData["logRow"]["log_target_type"]]; ?>: <?php echo $value; ?>
                    <?php break;
                    } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["type"]; ?></label>
        <div class="form-control-static"><?php echo $this->type["log"][$this->tplData["logRow"]["log_type"]]; ?></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["operator"]; ?></label>
        <div class="form-control-static">
            <?php switch($this->tplData["logRow"]["log_type"]) {
                case "admin": ?>
                    <?php if (isset($this->tplData["logRow"]["adminRow"]["admin_name"])) { ?>
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=admin&act=show&admin_id=<?php echo $this->tplData["logRow"]["adminRow"]["admin_id"]; ?>"><?php echo $this->tplData["logRow"]["adminRow"]["admin_name"]; ?></a>
                    <?php } else {
                        echo $this->lang["label"]["unknow"];
                    }
                break;

                case "app":
                    if (isset($this->tplData["logRow"]["appRow"]["app_name"])) { ?>
                         <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=show&app_id=<?php echo $this->tplData["logRow"]["appRow"]["app_id"]; ?>"><?php echo $this->tplData["logRow"]["appRow"]["app_name"]; ?></a>
                    <?php } else {
                        echo $this->lang["label"]["unknow"];
                    }
                break;

                default:
                    echo $this->type["log"][$value["log_type"]];
                break;
            } ?>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php echo $this->lang["label"]["result"]; ?></label>
        <p>
            <?php echo $this->tplData["logRow"]["log_result"]; ?>
        </p>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo $this->lang["btn"]["close"]; ?></button>
</div>
