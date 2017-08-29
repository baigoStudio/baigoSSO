<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['app']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['belong'],
    'menu_active'    => 'app',
    'sub_active'     => 'list',
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?mod=app&act=belong&' . $this->tplData['query'],
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang['mod']['href']['back']; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=app" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang['mod']['href']['help']; ?>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);"><?php echo $this->lang['mod']['label']['appName']; ?> <?php echo $this->tplData['appRow']['app_name']; ?></a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="well">
                <label class="control-label"><?php echo $this->lang['mod']['label']['belongUser']; ?></label>
                <form name="belong_search" id="belong_search" class="form-inline" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get">
                    <input type="hidden" name="mod" value="app">
                    <input type="hidden" name="act" value="belong">
                    <input type="hidden" name="app_id" value="<?php echo $this->tplData['appRow']['app_id']; ?>">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="key_belong" class="form-control input-sm" value="<?php echo $this->tplData['search']['key_belong']; ?>" placeholder="<?php echo $this->lang['mod']['label']['key']; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

            <form name="belong_list" id="belong_list">
                <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                <input type="hidden" name="app_id" value="<?php echo $this->tplData['appRow']['app_id']; ?>">

                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-nowrap bg-td-xs">
                                        <label for="belong_all" class="checkbox-inline">
                                            <input type="checkbox" name="belong_all" id="belong_all" data-parent="first">
                                            <?php echo $this->lang['mod']['label']['all']; ?>
                                        </label>
                                    </th>
                                    <th class="text-nowrap bg-td-xs"><?php echo $this->lang['mod']['label']['id']; ?></th>
                                    <th><?php echo $this->lang['mod']['label']['user']; ?></th>
                                    <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['status']; ?> / <?php echo $this->lang['mod']['label']['note']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->tplData["userViews"] as $key=>$value) {
                                    switch ($value['user_status']) {
                                        case 'enable':
                                            $css_status = 'success';
                                        break;

                                        case "wait":
                                            $css_status = 'warning';
                                        break;

                                        default:
                                            $css_status = 'default';
                                        break;
                                    } ?>
                                    <tr>
                                        <td class="text-nowrap bg-td-xs"><input type="checkbox" name="user_ids[]" value="<?php echo $value['user_id']; ?>" id="user_belong_<?php echo $value['user_id']; ?>" data-validate="user_belong_id" data-parent="belong_all"></td>
                                        <td class="text-nowrap bg-td-xs"><?php echo $value['user_id']; ?></td>
                                        <td>
                                            <?php echo $value['user_name'];
                                            if (!fn_isEmpty($value['user_nick'])) {
                                                echo ' [ ', $value['user_nick'], ' ]';
                                            } ?>
                                        </td>
                                        <td class="text-nowrap bg-td-md">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->lang['mod']['user'][$value['user_status']]; ?></span>
                                                </li>
                                                <li><?php echo $value['user_note']; ?></li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><span id="msg_user_belong"></span></td>
                                    <td colspan="2">
                                        <div class="bg-submit-box bg-submit-box-belong"></div>
                                        <input type="hidden" name="act" id="act" value="deauth">
                                        <button type="button" class="btn btn-primary btn-sm bg-submit-belong">
                                            <?php echo $this->lang['mod']['btn']['deauth']; ?>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-md-6">
            <div class="well">
                <label class="control-label"><?php echo $this->lang['mod']['label']["selectUser"]; ?></label>
                <form name="user_search" id="user_search" class="form-inline" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get">
                    <input type="hidden" name="mod" value="app">
                    <input type="hidden" name="act" value="belong">
                    <input type="hidden" name="app_id" value="<?php echo $this->tplData['appRow']['app_id']; ?>">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="key" class="form-control input-sm" value="<?php echo $this->tplData['search']['key']; ?>" placeholder="<?php echo $this->lang['mod']['label']['key']; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-sm" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

            <form name="user_list" id="user_list">
                <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                <input type="hidden" name="app_id" value="<?php echo $this->tplData['appRow']['app_id']; ?>">

                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-nowrap bg-td-xs">
                                        <label for="user_all" class="checkbox-inline">
                                            <input type="checkbox" name="user_all" id="user_all" data-parent="first">
                                            <?php echo $this->lang['mod']['label']['all']; ?>
                                        </label>
                                    </th>
                                    <th class="text-nowrap bg-td-xs"><?php echo $this->lang['mod']['label']['id']; ?></th>
                                    <th><?php echo $this->lang['mod']['label']['user']; ?></th>
                                    <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['status']; ?> / <?php echo $this->lang['mod']['label']['note']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->tplData['userRows'] as $key=>$value) {
                                    switch ($value['user_status']) {
                                        case 'enable':
                                            $css_status = 'success';
                                        break;

                                        case "wait":
                                            $css_status = 'warning';
                                        break;

                                        default:
                                            $css_status = 'default';
                                        break;
                                    } ?>
                                    <tr>
                                        <td class="text-nowrap bg-td-xs"><input type="checkbox" name="user_ids[]" value="<?php echo $value['user_id']; ?>" id="user_id_<?php echo $value['user_id']; ?>" data-validate="user_id" data-parent="user_all"></td>
                                        <td class="text-nowrap bg-td-xs"><?php echo $value['user_id']; ?></td>
                                        <td>
                                            <?php echo $value['user_name'];
                                            if (!fn_isEmpty($value['user_nick'])) {
                                                echo ' [ ', $value['user_nick'], ' ]';
                                            } ?>
                                        </td>
                                        <td class="text-nowrap bg-td-md">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->lang['mod']['user'][$value['user_status']]; ?></span>
                                                </li>
                                                <li><?php echo $value['user_note']; ?></li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><span id="msg_user_id"></span></td>
                                    <td colspan="2">
                                        <div class="bg-submit-box bg-submit-box-list"></div>
                                        <input type="hidden" name="act" value="auth">
                                        <button type="button" class="btn btn-primary btn-sm bg-submit">
                                            <?php echo $this->lang['mod']['btn']['auth']; ?>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </form>

            <div class="text-right">
                <?php include($cfg['pathInclude'] . 'page.php'); ?>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_belong = {
        user_belong_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='user_belong_id']", type: "checkbox" },
            msg: { selector: "#msg_user_belong", too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        }
    };

    var opts_submit_belong = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=app",
        confirm: {
            selector: "#act",
            val: "deauth",
            msg: "<?php echo $this->lang['mod']['confirm']['deauth']; ?>"
        },
        box: {
            selector: ".bg-submit-box-belong"
        },
        selector: {
            submit_btn: ".bg-submit-belong"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    var opts_validator_list = {
        user_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='user_id']", type: "checkbox" },
            msg: { selector: "#msg_user_id", too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        }
    };

    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=app",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        },
        box: {
            selector: ".bg-submit-box-list"
        }
    };

    $(document).ready(function(){
        var obj_validate_belong   = $("#belong_list").baigoValidator(opts_validator_belong);
        var obj_submit_belong     = $("#belong_list").baigoSubmit(opts_submit_belong);
        $(".bg-submit-belong").click(function(){
            if (obj_validate_belong.verify()) {
                obj_submit_belong.formSubmit();
            }
        });
        var obj_validate_list = $("#user_list").baigoValidator(opts_validator_list);
        var obj_submit_list   = $("#user_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validate_list.verify()) {
                obj_submit_list.formSubmit();
            }
        });
        $("#belong_list").baigoCheckall();
        $("#user_list").baigoCheckall();
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>
