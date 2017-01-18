<?php if ($this->tplData["adminRow"]["admin_id"] < 1) {
    $title_sub    = $this->lang["page"]["add"];
    $str_sub      = "form";
} else {
    $title_sub    = $this->lang["page"]["edit"];
    $str_sub      = "list";
}

$cfg = array(
    "title"          => $this->consoleMod["admin"]["main"]["title"] . " &raquo; " . $title_sub,
    "menu_active"    => "admin",
    "sub_active"     => $str_sub,
    "baigoCheckall"  => "true",
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/",
    "str_url"        => BG_URL_CONSOLE . "index.php?mod=admin",
); ?>

<?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=admin&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang["href"]["back"]; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=admin#form" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang["href"]["help"]; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="admin_form" id="admin_form" autocomplete="off">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
        <input type="hidden" name="act" value="submit">
        <input type="hidden" name="admin_id" value="<?php echo $this->tplData["adminRow"]["admin_id"]; ?>">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php if ($this->tplData["adminRow"]["admin_id"] > 0) { ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo $this->lang["label"]["username"]; ?></label>
                                <input type="text" name="admin_name" id="admin_name" value="<?php echo $this->tplData["adminRow"]["admin_name"]; ?>" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <div id="group_admin_pass">
                                    <label class="control-label"><?php echo $this->lang["label"]["password"]; ?></label>
                                    <input type="text" name="admin_pass" id="admin_pass" class="form-control" placeholder="<?php echo $this->lang["label"]["onlyModi"]; ?>">
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="form-group">
                                <div id="group_admin_name">
                                    <label class="control-label"><?php echo $this->lang["label"]["username"]; ?><span id="msg_admin_name">*</span></label>
                                    <input type="text" name="admin_name" id="admin_name" data-validate class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div id="group_admin_pass">
                                    <label class="control-label"><?php echo $this->lang["label"]["password"]; ?><span id="msg_admin_pass">*</span></label>
                                    <input type="text" name="admin_pass" id="admin_pass" data-validate class="form-control">
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <div id="group_admin_nick">
                                <label class="control-label"><?php echo $this->lang["label"]["nick"]; ?><span id="msg_admin_nick"></span></label>
                                <input type="text" name="admin_nick" id="admin_nick" value="<?php echo $this->tplData["adminRow"]["admin_nick"]; ?>" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang["label"]["allow"]; ?><span id="msg_admin_allow">*</span></label>
                            <dl class="bg-dl">
                                <dd>
                                    <div class="bg-checkbox">
                                        <label for="chk_all">
                                            <input type="checkbox" id="chk_all" data-parent="first">
                                            <?php echo $this->lang["label"]["all"]; ?>
                                        </label>
                                    </div>
                                </dd>
                                <?php foreach ($this->consoleMod as $key_m=>$value_m) { ?>
                                    <?php if (isset($value_m["allow"]) && !fn_isEmpty($value_m["allow"])) { ?>
                                        <dt><?php echo $value_m["main"]["title"]; ?></dt>
                                        <dd>
                                            <label for="allow_<?php echo $key_m; ?>" class="checkbox-inline">
                                                <input type="checkbox" id="allow_<?php echo $key_m; ?>" data-parent="chk_all">
                                                <?php echo $this->lang["label"]["all"]; ?>
                                            </label>
                                            <?php foreach ($value_m["allow"] as $key_s=>$value_s) { ?>
                                                <label for="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" class="checkbox-inline">
                                                    <input type="checkbox" name="admin_allow[<?php echo $key_m; ?>][<?php echo $key_s; ?>]" value="1" id="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" data-parent="allow_<?php echo $key_m; ?>" <?php if (isset($this->tplData["adminRow"]["admin_allow"][$key_m][$key_s])) { ?>checked<?php } ?>>
                                                    <?php echo $value_s; ?>
                                                </label>
                                            <?php } ?>
                                        </dd>
                                    <?php } ?>
                                <?php } ?>

                                <dt><?php echo $this->lang["label"]["opt"]; ?></dt>
                                <dd>
                                    <label for="allow_opt" class="checkbox-inline">
                                        <input type="checkbox" id="allow_opt" data-parent="chk_all">
                                        <?php echo $this->lang["label"]["all"]; ?>
                                    </label>
                                    <?php foreach ($this->opt as $key_s=>$value_s) { ?>
                                        <label for="allow_opt_<?php echo $key_s; ?>" class="checkbox-inline">
                                            <input type="checkbox" name="admin_allow[opt][<?php echo $key_s; ?>]" value="1" id="allow_opt_<?php echo $key_s; ?>" data-parent="allow_opt" <?php if (isset($this->tplData["adminRow"]["admin_allow"]["opt"][$key_s])) { ?>checked<?php } ?>>
                                            <?php echo $value_s["title"]; ?>
                                        </label>
                                    <?php } ?>
                                    <label for="allow_opt_dbconfig" class="checkbox-inline">
                                        <input type="checkbox" name="admin_allow[opt][dbconfig]" value="1" id="allow_opt_dbconfig" data-parent="allow_opt" <?php if (isset($this->tplData["adminRow"]["admin_allow"]["opt"]["dbconfig"])) { ?>checked<?php } ?>>
                                        <?php echo $this->lang["page"]["setupDbConfig"]; ?>
                                    </label>
                                    <label for="allow_opt_chkver" class="checkbox-inline">
                                        <input type="checkbox" name="admin_allow[opt][chkver]" value="1" id="allow_opt_chkver" data-parent="allow_opt" <?php if (isset($this->tplData["adminRow"]["admin_allow"]["opt"]["chkver"])) { ?>checked<?php } ?>>
                                        <?php echo $this->lang["page"]["chkver"]; ?>
                                    </label>
                                </dd>
                            </dl>
                        </div>

                        <div class="form-group">
                            <div id="group_admin_note">
                                <label class="control-label"><?php echo $this->lang["label"]["note"]; ?><span id="msg_admin_note"></span></label>
                                <input type="text" name="admin_note" id="admin_note" value="<?php echo $this->tplData["adminRow"]["admin_note"]; ?>" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="bg-submit-box"></div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-primary bg-submit">
                            <?php echo $this->lang["btn"]["save"]; ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="well">
                    <?php if ($this->tplData["adminRow"]["admin_id"] > 0) { ?>
                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang["label"]["id"]; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData["adminRow"]["admin_id"]; ?></div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div id="group_admin_type">
                            <label class="control-label"><?php echo $this->lang["label"]["type"]; ?><span id="msg_admin_type">*</span></label>
                            <?php foreach ($this->type["admin"] as $key=>$value) { ?>
                                <div class="bg-radio">
                                    <label for="admin_type_<?php echo $key; ?>">
                                        <input type="radio" name="admin_type" id="admin_type_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php if ($this->tplData["adminRow"]["admin_type"] == $key) { ?>checked<?php } ?> data-validate="admin_type">
                                        <?php echo $value; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="group_admin_status">
                            <label class="control-label"><?php echo $this->lang["label"]["status"]; ?><span id="msg_admin_status">*</span></label>
                            <?php foreach ($this->status["admin"] as $key=>$value) { ?>
                                <div class="bg-radio">
                                    <label for="admin_status_<?php echo $key; ?>">
                                        <input type="radio" name="admin_status" id="admin_status_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php if ($this->tplData["adminRow"]["admin_status"] == $key) { ?>checked<?php } ?> data-validate="admin_status">
                                        <?php echo $value; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang["label"]["profileAllow"]; ?></label>
                        <?php foreach ($this->type["profile"] as $_key=>$_value) { ?>
                            <div class="bg-checkbox">
                                <label for="admin_allow_<?php echo $_key; ?>">
                                    <input type="checkbox" name="admin_allow[<?php echo $_key; ?>]" id="admin_allow_<?php echo $_key; ?>" value="1" <?php if (isset($this->tplData["adminRow"]["admin_allow"][$_key])) { ?>checked<?php } ?>>
                                    <?php echo $this->lang["label"]["forbidModi"] . $_value["title"]; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </form>

<?php include($cfg["pathInclude"] . "console_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        admin_name: {
            len: { min: 1, max: 30 },
            validate: { type: "ajax", format: "strDigit", group: "#group_admin_name" },
            msg: { selector: "#msg_admin_name", too_short: "<?php echo $this->rcode["x020201"]; ?>", too_long: "<?php echo $this->rcode["x020202"]; ?>", format_err: "<?php echo $this->rcode["x020203"]; ?>", ajaxIng: "<?php echo $this->rcode["x030401"]; ?>", ajax_err: "<?php echo $this->rcode["x030402"]; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=admin&act=chkname", key: "admin_name", type: "str", attach_selectors: ["#admin_id"], attach_keys: ["admin_id"] }
        },
        admin_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_admin_pass" },
            msg: { selector: "#msg_admin_pass", too_short: "<?php echo $this->rcode["x020205"]; ?>" }
        },
        admin_nick: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_admin_nick" },
            msg: { selector: "#msg_admin_nick", too_long: "<?php echo $this->rcode["x020204"]; ?>" }
        },
        admin_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_admin_note" },
            msg: { selector: "#msg_admin_note", too_long: "<?php echo $this->rcode["x020203"]; ?>" }
        },
        admin_type: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='admin_type']", type: "radio", group: "#group_admin_type" },
            msg: { selector: "#msg_admin_type", too_few: "<?php echo $this->rcode["x020201"]; ?>" }
        },
        admin_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='admin_status']", type: "radio", group: "#group_admin_status" },
            msg: { selector: "#msg_admin_status", too_few: "<?php echo $this->rcode["x020202"]; ?>" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=admin",
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#admin_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#admin_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#admin_form").baigoCheckall();
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>
