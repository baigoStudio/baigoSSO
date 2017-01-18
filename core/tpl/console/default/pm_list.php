<?php $cfg = array(
    "title"          => $this->consoleMod["pm"]["main"]["title"],
    "menu_active"    => "pm",
    "sub_active"     => "list",
    "baigoCheckall"  => "true",
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "tooltip"        => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/",
    "str_url"        => BG_URL_CONSOLE . "index.php?mod=pm&act=list&" . $this->tplData["query"],
); ?>

<?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group clearfix">
        <div class="pull-left">
            <ul class="nav nav-pills bg-nav-pills">
                <li>
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=pm&act=send">
                        <span class="glyphicon glyphicon-send"></span>
                        <?php echo $this->lang["href"]["pmSend"]; ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=pm&act=bulk">
                        <span class="glyphicon glyphicon-bullhorn"></span>
                        <?php echo $this->lang["href"]["pmBulk"]; ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=pm" target="_blank">
                        <span class="glyphicon glyphicon-question-sign"></span>
                        <?php echo $this->lang["href"]["help"]; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="pull-right">
            <form name="pm_search" id="pm_search" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get" class="form-inline">
                <input type="hidden" name="mod" value="pm">
                <input type="hidden" name="act" value="list">
                <div class="form-group hidden-sm hidden-xs">
                    <select name="status" class="form-control input-sm">
                        <option value=""><?php echo $this->lang["option"]["allStatus"]; ?></option>
                        <?php foreach ($this->status["pm"] as $key=>$value) { ?>
                            <option <?php if ($this->tplData["search"]["status"] == $key) { ?>selected<?php } ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group hidden-sm hidden-xs">
                    <select name="type" class="form-control input-sm">
                        <option value=""><?php echo $this->lang["option"]["allType"]; ?></option>
                        <?php foreach ($this->type["pm"] as $key=>$value) { ?>
                            <option <?php if ($this->tplData["search"]["type"] == $key) { ?>selected<?php } ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
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

    <form name="pm_list" id="pm_list" class="form-inline">
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
                            <th><?php echo $this->lang["label"]["pm"]; ?></th>
                            <th><?php echo $this->lang["label"]["pmFrom"]; ?> / <?php echo $this->lang["label"]["pmTo"]; ?></th>
                            <th class="text-nowrap bg-td-md"><?php echo $this->lang["label"]["time"]; ?></th>
                            <th class="text-nowrap bg-td-sm"><?php echo $this->lang["label"]["status"]; ?> / <?php echo $this->lang["label"]["type"]; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->tplData["pmRows"] as $key=>$value) {
                            if ($value["pm_status"] == "read") {
                                $css_status = "success";
                            } else {
                                $css_status = "warning";
                            }

                            if ($value["pm_type"] == "in") {
                                $icon_type = "inbox";
                            } else {
                                $icon_type = "send";
                            } ?>
                            <tr>
                                <td class="text-nowrap bg-td-xs"><input type="checkbox" name="pm_ids[]" value="<?php echo $value["pm_id"]; ?>" id="pm_id_<?php echo $value["pm_id"]; ?>" data-validate="pm_id" data-parent="chk_all"></td>
                                <td class="text-nowrap bg-td-xs"><?php echo $value["pm_id"]; ?></td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li><?php echo $value["pm_title"]; ?></li>
                                        <li>
                                            <ul class="bg-nav-line">
                                                <li>
                                                    <a href="#pm_modal" data-toggle="modal" data-id="<?php echo $value["pm_id"]; ?>"><?php echo $this->lang["href"]["show"]; ?></a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>
                                            <?php if ($value["pm_from"] == -1) {
                                                echo $this->lang["label"]["pmSys"];
                                            } else if (isset($value["fromUser"]["user_name"])) { ?>
                                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=pm&act=list&pm_from=<?php echo $value["pm_from"]; ?>"><?php echo $value["fromUser"]["user_name"]; ?></a>
                                            <?php } else {
                                                echo $this->lang["label"]["unknow"];
                                            } ?>
                                        </li>
                                        <li>
                                            <?php if (isset($value["toUser"]["user_name"])) { ?>
                                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=pm&act=list&pm_to=<?php echo $value["pm_to"]; ?>"><?php echo $value["toUser"]["user_name"]; ?></a>
                                            <?php } else {
                                                echo $this->lang["label"]["unknow"];
                                            } ?>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-nowrap bg-td-md">
                                    <abbr title="<?php echo date(BG_SITE_DATE . " " . BG_SITE_TIMESHORT, $value["pm_time"]); ?>" data-toggle="tooltip" data-placement="bottom">
                                        <?php echo date(BG_SITE_DATESHORT . " " . BG_SITE_TIMESHORT, $value["pm_time"]); ?>
                                    </abbr>
                                </td>
                                <td class="text-nowrap bg-td-sm">
                                    <ul class="list-unstyled">
                                        <li>
                                            <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->status["pm"][$value["pm_status"]]; ?></span>
                                        </li>
                                        <li>
                                            <span class="glyphicon glyphicon-<?php echo $icon_type; ?>"></span>
                                            <?php echo $this->type["pm"][$value["pm_type"]]; ?>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><span id="msg_pm_id"></span></td>
                            <td colspan="4">
                                <div class="bg-submit-box"></div>
                                <div class="form-group">
                                    <div id="group_act">
                                        <select name="act" id="act" data-validate class="form-control input-sm">
                                            <option value=""><?php echo $this->lang["option"]["batch"]; ?></option>
                                            <?php foreach ($this->status["pm"] as $key=>$value) { ?>
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

    <div class="modal fade" id="pm_modal">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>

<?php include($cfg["pathInclude"] . "console_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        pm_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='pm_id']", type: "checkbox" },
            msg: { selector: "#msg_pm_id", too_few: "<?php echo $this->rcode["x030202"]; ?>" }
        },
        act: {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_act" },
            msg: { selector: "#msg_act", too_few: "<?php echo $this->rcode["x030203"]; ?>" }
        }
    };

    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=pm",
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
        $("#pm_modal").on("show.bs.modal",function(event){
            var _obj_button = $(event.relatedTarget);
            var _pm_id  = _obj_button.data("id");
            $("#pm_modal .modal-content").load("<?php echo BG_URL_CONSOLE; ?>index.php?mod=pm&act=show&pm_id=" + _pm_id + "&view=iframe");
        });
        var obj_validator_list    = $("#pm_list").baigoValidator(opts_validator_list);
        var obj_submit_list       = $("#pm_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validator_list.verify()) {
                obj_submit_list.formSubmit();
            }
        });
        $("#pm_list").baigoCheckall();
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>