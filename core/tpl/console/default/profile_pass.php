<?php $cfg = array(
    "title"          => $this->lang["page"]["profile"],
    "menu_active"    => "profile",
    "sub_active"     => "pass",
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/",
    "str_url"        => BG_URL_CONSOLE . "index.php?mod=profile&act=info",
); ?>

<?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <?php include($cfg["pathInclude"] . "profile_menu.php"); ?>
        </ul>
    </div>

    <form name="profile_form" id="profile_form" autocomplete="off">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
        <input type="hidden" name="act" value="pass">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang["label"]["username"]; ?></label>
                            <input type="text" value="<?php echo $this->tplData["adminLogged"]["admin_name"]; ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <div id="group_admin_pass">
                                <label class="control-label"><?php echo $this->lang["label"]["passOld"]; ?><span id="msg_admin_pass">*</span></label>
                                <input type="password" name="admin_pass" id="admin_pass" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_admin_pass_new">
                                <label class="control-label"><?php echo $this->lang["label"]["passNew"]; ?><span id="msg_admin_pass_new">*</span></label>
                                <input type="password" name="admin_pass_new" id="admin_pass_new" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_admin_pass_confirm">
                                <label class="control-label"><?php echo $this->lang["label"]["passConfirm"]; ?><span id="msg_admin_pass_confirm">*</span></label>
                                <input type="password" name="admin_pass_confirm" id="admin_pass_confirm" data-validate class="form-control">
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

            <?php include($cfg["pathInclude"] . "profile_left.php"); ?>
        </div>
    </form>

<?php include($cfg["pathInclude"] . "console_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        admin_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_admin_pass" },
            msg: { selector: "#msg_admin_pass", too_short: "<?php echo $this->rcode["x010243"]; ?>" }
        },
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
    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=profile",
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#profile_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#profile_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>
