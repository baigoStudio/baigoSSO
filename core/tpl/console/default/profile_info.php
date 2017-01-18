<?php $cfg = array(
    "title"          => $this->lang["page"]["profile"],
    "menu_active"    => "profile",
    "sub_active"     => "info",
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

    <form name="profile_form" id="profile_form">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
        <input type="hidden" name="act" value="info">

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
                                <label class="control-label"><?php echo $this->lang["label"]["password"]; ?><span id="msg_admin_pass">*</span></label>
                                <input type="password" name="admin_pass" id="admin_pass" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_admin_nick">
                                <label class="control-label"><?php echo $this->lang["label"]["nick"]; ?></label>
                                <input type="text" name="admin_nick" id="admin_nick" value="<?php echo $this->tplData["adminLogged"]["admin_nick"]; ?>" class="form-control">
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

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang["label"]["allow"]; ?></label>
                            <dl class="bg-dl">
                                <?php foreach ($this->consoleMod as $key_m=>$value_m) { ?>
                                    <dt><?php echo $value_m["main"]["title"]; ?></dt>
                                    <dd>
                                        <ul class="list-inline">
                                            <?php foreach ($value_m["allow"] as $key_s=>$value_s) { ?>
                                                <li>
                                                    <span class="glyphicon glyphicon-<?php if (isset($this->tplData["adminLogged"]["admin_allow"][$key_m][$key_s])) { ?>ok-sign text-success<?php } else { ?>remove-sign text-danger<?php } ?>"></span>
                                                    <?php echo $value_s; ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </dd>
                                <?php } ?>
                            </dl>
                        </div>
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
        admin_nick: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_admin_nick" },
            msg: { selector: "#msg_admin_nick", too_long: "<?php echo $this->rcode["x020204"]; ?>" }
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
