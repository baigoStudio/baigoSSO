<?php $cfg = array(
    "title"          => $this->consoleMod["log"]["main"]["title"],
    "menu_active"    => "log",
    "sub_active"     => "list",
    "baigoCheckall"  => "true",
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/",
    "str_url"        => BG_URL_CONSOLE . "index.php?mod=log&act=list&" . $this->tplData["query"],
); ?>

<?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group clearfix">
        <div class="pull-left">
            <ul class="nav nav-pills bg-nav-pills">
                <li>
                    <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=log" target="_blank">
                        <span class="glyphicon glyphicon-question-sign"></span>
                        <?php echo $this->lang["href"]["help"]; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="pull-right">
            <form name="log_search" id="log_search" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get" class="form-inline">
                <input type="hidden" name="mod" value="log">
                <input type="hidden" name="act" value="list">
                <div class="form-group hidden-sm hidden-xs">
                    <select name="type" class="form-control input-sm">
                        <option value=""><?php echo $this->lang["option"]["allType"]; ?></option>
                        <?php foreach ($this->type["log"] as $key=>$value) { ?>
                            <option <?php if ($this->tplData["search"]["type"] == $key) { ?>selected<?php } ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group hidden-sm hidden-xs">
                    <select name="status" class="form-control input-sm">
                        <option value=""><?php echo $this->lang["option"]["allStatus"]; ?></option>
                        <?php foreach ($this->status["log"] as $key=>$value) { ?>
                            <option <?php if ($this->tplData["search"]["status"] == $key) { ?>selected<?php } ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="key" value="<?php echo $this->tplData["search"]["key"]; ?>" placeholder="<?php echo $this->lang["label"]["key"]; ?>" class="form-control input-sm">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form name="log_list" id="log_list" class="form-inline">
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
                            <th><?php echo $this->lang["label"]["title"]; ?></th>
                            <th class="text-nowrap bg-td-lg"><?php echo $this->lang["label"]["operator"]; ?></th>
                            <th class="text-nowrap bg-td-sm"><?php echo $this->lang["label"]["status"]; ?> / <?php echo $this->lang["label"]["type"]; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->tplData["logRows"] as $key=>$value) {
                            if ($value["log_status"] == "read") {
                                $css_status = "default";
                            } else {
                                $css_status = "warning";
                            } ?>
                            <tr>
                                <td class="text-nowrap bg-td-xs"><input type="checkbox" name="log_ids[]" value="<?php echo $value["log_id"]; ?>" id="log_id_<?php echo $value["log_id"]; ?>" data-validate="log_id" data-parent="chk_all"></td>
                                <td class="text-nowrap bg-td-xs"><?php echo $value["log_id"]; ?></td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>
                                            <?php echo $value["log_title"]; ?>
                                        </li>
                                        <li>
                                            <a href="#log_modal" data-toggle="modal" data-id="<?php echo $value["log_id"]; ?>"><?php echo $this->lang["href"]["show"]; ?></a>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-nowrap bg-td-lg">
                                    <ul class="list-unstyled">
                                        <li>
                                            <?php switch($value["log_type"]) {
                                                case "admin":
                                                    if (isset($value["adminRow"]["admin_name"])) { ?>
                                                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=log&act=list&operator_id=<?php echo $value["adminRow"]["admin_id"]; ?>"><?php echo $value["adminRow"]["admin_name"]; ?></a>
                                                    <?php } else {
                                                        echo $this->lang["label"]["unknow"];
                                                    }
                                                break;

                                                case "app":
                                                    if (isset($value["appRow"]["app_name"])) { ?>
                                                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=log&act=list&operator_id=<?php echo $value["appRow"]["app_id"]; ?>"><?php echo $value["appRow"]["app_name"]; ?></a>
                                                    <?php } else {
                                                        echo $this->lang["label"]["unknow"];
                                                    }
                                                break;

                                                default:
                                                    echo $this->type["log"][$value["log_type"]];
                                                break;
                                            } ?>
                                        </li>
                                        <li><?php echo date(BG_SITE_DATESHORT . " " . BG_SITE_TIMESHORT, $value["log_time"]); ?></li>
                                    </ul>
                                </td>
                                <td class="text-nowrap bg-td-sm">
                                    <ul class="list-unstyled">
                                        <li>
                                            <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->status["log"][$value["log_status"]]; ?></span>
                                        </li>
                                        <li><?php echo $this->type["log"][$value["log_type"]]; ?></li>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><span id="msg_log_id"></span></td>
                            <td colspan="3">
                                <div class="bg-submit-box"></div>
                                <div class="form-group">
                                    <div id="group_act">
                                        <select name="act" id="act" data-validate class="form-control input-sm">
                                            <option value=""><?php echo $this->lang["option"]["batch"]; ?></option>
                                            <?php foreach ($this->status["log"] as $key=>$value) { ?>
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

    <div class="modal fade" id="log_modal">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>

<?php include($cfg["pathInclude"] . "console_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        log_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='log_id']", type: "checkbox" },
            msg: { selector: "#msg_log_id", too_few: "<?php echo $this->rcode["x030202"]; ?>" }
        },
        act: {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_act" },
            msg: { selector: "#msg_act", too_few: "<?php echo $this->rcode["x030203"]; ?>" }
        }
    };
    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=log",
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
        $("#log_modal").on("show.bs.modal",function(event){
            var _obj_button = $(event.relatedTarget);
            var _log_id     = _obj_button.data("id");
            $("#log_modal .modal-content").load("<?php echo BG_URL_CONSOLE; ?>index.php?mod=log&act=show&log_id=" + _log_id + "&view=iframe");
        });
        var obj_validator_form    = $("#log_list").baigoValidator(opts_validator_list);
        var obj_submit_form       = $("#log_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#log_list").baigoCheckall();
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>
