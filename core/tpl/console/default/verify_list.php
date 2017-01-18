<?php $cfg = array(
    "title"          => $this->consoleMod["log"]["main"]["title"] . " &raquo; " . $this->consoleMod["log"]["sub"]["verify"]["title"],
    "menu_active"    => "log",
    "sub_active"     => "verify",
    "baigoCheckall"  => "true",
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/",
    "str_url"        => BG_URL_CONSOLE . "index.php?mod=verify&act=list",
); ?>

<?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=log" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang["href"]["help"]; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="verify_list" id="verify_list" class="form-inline">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">

        <div class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-nowrap bg-td-xs">
                                <label for="chk_all" class="checkbox-inline">
                                    <input type="checkbox" name="chk_all" id="chk_all" data-parent="first">
                                    <?php echo $this->lang["label"]["all"]; ?>
                                </label>
                            </th>
                            <th class="text-nowrap bg-td-xs"><?php echo $this->lang["label"]["id"]; ?></th>
                            <th><?php echo $this->lang["label"]["operator"]; ?></th>
                            <th class="text-nowrap bg-td-lg"><?php echo $this->lang["label"]["status"]; ?> / <?php echo $this->lang["label"]["timeInit"]; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->tplData["verifyRows"] as $key=>$value) {
                            switch($value["verify_status"]) {
                                case "enable":
                                    $css_status = "success";
                                    $str_status = $this->status["verify"][$value["verify_status"]];
                                break;

                                case "expired":
                                    $css_status = "default";
                                    $str_status = $this->lang["label"]["expired"];
                                break;

                                default:
                                    $css_status = "default";
                                    $str_status = $this->status["verify"][$value["verify_status"]];
                                break;
                            } ?>
                            <tr>
                                <td class="text-nowrap bg-td-xs"><input type="checkbox" name="verify_ids[]" value="<?php echo $value["verify_id"]; ?>" id="verify_id_<?php echo $value["verify_id"]; ?>" data-validate="verify_id" data-parent="chk_all"></td>
                                <td class="text-nowrap bg-td-xs"><?php echo $value["verify_id"]; ?></td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>
                                            <?php if (isset($value["userRow"]["user_name"])) {
                                                echo $value["userRow"]["user_name"];
                                            } else {
                                                echo $this->lang["label"]["unknow"];
                                            } ?>
                                        </li>
                                        <li>
                                            <a href="#verify_modal" data-toggle="modal" data-id="<?php echo $value["verify_id"]; ?>"><?php echo $this->lang["href"]["show"]; ?></a>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-nowrap bg-td-lg">
                                    <ul class="list-unstyled">
                                        <li>
                                            <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $str_status; ?></span>
                                        </li>
                                        <li><?php echo date(BG_SITE_DATE . " " . BG_SITE_TIMESHORT, $value["verify_time_refresh"]); ?></li>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><span id="msg_verify_id"></span></td>
                            <td colspan="3">
                                <div class="bg-submit-box"></div>
                                <div class="form-group">
                                    <div id="group_act">
                                        <select name="act" id="act" data-validate class="form-control input-sm">
                                            <option value=""><?php echo $this->lang["option"]["batch"]; ?></option>
                                            <?php foreach ($this->status["verify"] as $key=>$value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php } ?>
                                            <option value="del"><?php echo $this->lang["option"]["del"]; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-primary bg-submit"><?php echo $this->lang["btn"]["submit"]; ?></button>
                                </div>
                                <div class="form-group">
                                    <span id="msg_act"></span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </form>

    <div class="text-right">
        <?php include($cfg["pathInclude"] . "page.php"); ?>
    </div>

    <div class="modal fade" id="verify_modal">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>

<?php include($cfg["pathInclude"] . "console_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        verify_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='verify_id']", type: "checkbox" },
            msg: { selector: "#msg_verify_id", too_few: "<?php echo $this->rcode["x030202"]; ?>" }
        },
        act: {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_act" },
            msg: { selector: "#msg_act", too_few: "<?php echo $this->rcode["x030203"]; ?>" }
        }
    };
    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=verify",
        confirm: {
            selector: "#act",
            val: "del",
            msg: "<?php echo $this->lang["confirm"]["del"]; ?>",
        },
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        }
    };

    $(document).ready(function(){
        $("#verify_modal").on("show.bs.modal",function(event){
            var _obj_button = $(event.relatedTarget);
            var _verify_id  = _obj_button.data("id");
            $("#verify_modal .modal-content").load("<?php echo BG_URL_CONSOLE; ?>index.php?mod=verify&act=show&verify_id=" + _verify_id + "&view=iframe");
        });
        var obj_validator_form    = $("#verify_list").baigoValidator(opts_validator_list);
        var obj_submit_form       = $("#verify_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#verify_list").baigoCheckall();
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>
