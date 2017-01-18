<?php $cfg = array(
    "title"          => $this->lang["page"]["console"] . " &raquo; " . $this->lang["page"]["forgot"],
    "active"         => "forgot",
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/"
); ?>

<?php include($cfg["pathInclude"] . "login_head.php"); ?>

    <ul class="nav nav-tabs">
        <?php foreach ($this->type["forgot"] as $_key=>$_value) { ?>
            <li<?php if ($_key == "bymail") { ?> class="active"<?php } ?>>
                <a href="#<?php echo $_key; ?>" data-toggle="tab">
                    <?php echo $_value; ?>
                </a>
            </li>
        <?php } ?>
    </ul>

    <div>&nbsp;</div>

    <div class="tab-content">
        <div class="tab-pane active" id="bymail">
            <form name="forgot_bymail" id="forgot_bymail">
                <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
                <input type="hidden" name="act" value="bymail">
                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang["label"]["username"]; ?></label>
                    <input type="text" name="admin_name" value="<?php echo $this->tplData["userRow"]["user_name"]; ?>" readonly class="form-control">
                </div>

                <?php if (fn_isEmpty($this->tplData["userRow"]["user_mail"])) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="glyphicon glyphicon-remove-sign"></span>
                        <?php echo $this->rcode["x010241"]; ?>
                    </div>
                <?php } else { ?>

                    <div class="alert alert-warning">
                        <span class="glyphicon glyphicon-warning-sign"></span>
                        <?php echo $this->lang["text"]["forgotByMail"]; ?>
                    </div>

                    <div class="bg-submit-box bg-submit-box-bymail"></div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang["btn"]["submit"]; ?></button>
                    </div>
                <?php } ?>
            </form>
        </div>
        <div class="tab-pane" id="byqa">
            <form name="forgot_byqa" id="forgot_byqa">
                <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
                <input type="hidden" name="act" value="byqa">

                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang["label"]["username"]; ?></label>
                    <input type="text" name="admin_name" value="<?php echo $this->tplData["userRow"]["user_name"]; ?>" readonly class="form-control">
                </div>

                <?php if (fn_isEmpty($this->tplData["userRow"]["user_sec_ques_1"])) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span class="glyphicon glyphicon-remove-sign"></span>
                        <?php echo $this->rcode["x010242"]; ?>
                    </div>
                <?php } else { ?>
                    <?php for ($_iii = 1; $_iii <= 3; $_iii++) { ?>
                        <div class="form-group">
                            <div id="group_admin_sec_answ_<?php echo $_iii; ?>">
                                <label class="control-label"><?php echo $this->tplData["userRow"]["user_sec_ques_" . $_iii]; ?><span id="msg_admin_sec_answ_<?php echo $_iii; ?>">*</span></label>
                                <input type="text" name="admin_sec_answ_<?php echo $_iii; ?>" id="admin_sec_answ_<?php echo $_iii; ?>" data-validate placeholder="<?php echo $this->lang["label"]["answer"]; ?>" class="form-control">
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div id="group_admin_pass_new">
                            <label class="control-label"><?php echo $this->lang["label"]["passNew"]; ?><span id="msg_admin_pass_new">*</span></label>
                            <input type="password" name="admin_pass_new" id="admin_pass_new" data-validate placeholder="<?php echo $this->rcode["x010222"]; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="group_admin_pass_confirm">
                            <label class="control-label"><?php echo $this->lang["label"]["passConfirm"]; ?><span id="msg_admin_pass_confirm">*</span></label>
                            <input type="password" name="admin_pass_confirm" id="admin_pass_confirm" data-validate placeholder="<?php echo $this->rcode["x010224"]; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="bg-submit-box bg-submit-box-byqa"></div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary bg-submit-byqa"><?php echo $this->lang["btn"]["submit"]; ?></button>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>

<?php include($cfg["pathInclude"] . "login_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        <?php for ($_iii = 1; $_iii <= 3; $_iii++) { ?>
            admin_sec_answ_<?php echo $_iii; ?>: {
                len: { min: 1, max: 0 },
                validate: { type: "str", format: "text", group: "#group_admin_sec_answ_<?php echo $_iii; ?>" },
                msg: { selector: "#msg_admin_sec_answ_<?php echo $_iii; ?>", too_short: "<?php echo $this->rcode["x010237"]; ?> <?php echo $_iii; ?>" }
            },
        <?php } ?>
        admin_pass_new: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_admin_pass_new" },
            msg: { selector: "#msg_admin_pass_new", too_short: "<?php echo $this->rcode["x010222"]; ?>" }
        },
        admin_pass_confirm: {
            len: { min: 1, max: 0 },
            validate: { type: "confirm", target: "#admin_pass_new", group: "#group_admin_pass_confirm" },
            msg: { selector: "#msg_admin_pass_confirm", too_short: "<?php echo $this->rcode["x010224"]; ?>", not_match: "<?php echo $this->rcode["x010225"]; ?>" }
        }
    };

    var opts_submit_byqa = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=forgot",
        box: {
            selector: ".bg-submit-box-byqa"
        },
        selector: {
            submit_btn: ".bg-submit-byqa"
        },
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        }
    };

    var opts_submit_bymail = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=forgot",
        box: {
            selector: ".bg-submit-box-bymail"
        },
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form  = $("#forgot_byqa").baigoValidator(opts_validator_form);
        var obj_submit_byqa     = $("#forgot_byqa").baigoSubmit(opts_submit_byqa);
        $(".bg-submit-byqa").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_byqa.formSubmit();
            }
        });

        var obj_submit_bymail = $("#forgot_bymail").baigoSubmit(opts_submit_bymail);
        $(".bg-submit").click(function(){
            obj_submit_bymail.formSubmit();
        });
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>