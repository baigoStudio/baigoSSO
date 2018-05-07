<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['app']['main']['title'],
    'menu_active'    => 'app',
    'sub_active'     => 'list',
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=app&a=list&' . $this->tplData['query'],
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="mb-3 clearfix">
        <div class="float-left">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=form" class="nav-link">
                        <span class="oi oi-plus"></span>
                        <?php echo $this->lang['mod']['href']['add']; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=app" target="_blank" class="nav-link">
                        <span class="badge badge-pill badge-primary">
                            <span class="oi oi-question-mark"></span>
                        </span>
                        <?php echo $this->lang['mod']['href']['help']; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="float-right">
            <form name="app_search" id="app_search" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get">
                <input type="hidden" name="m" value="app">
                <input type="hidden" name="a" value="list">
                <div class="input-group">
                    <select name="status" class="custom-select">
                        <option value=""><?php echo $this->lang['mod']['option']['allStatus']; ?></option>
                        <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                            <option <?php if ($this->tplData['search']['status'] == $value) { ?>selected<?php } ?> value="<?php echo $value; ?>">
                                <?php if (isset($this->lang['mod']['status'][$value])) {
                                    echo $this->lang['mod']['status'][$value];
                                } else {
                                    echo $value;
                                } ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="text" name="key" value="<?php echo $this->tplData['search']['key']; ?>" placeholder="<?php echo $this->lang['mod']['label']['key']; ?>" class="form-control">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-secondary">
                            <span class="oi oi-magnifying-glass"></span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <form name="app_list" id="app_list">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">

        <div class="table-responsive">
            <table class="table table-striped table-hover border">
                <thead>
                    <tr>
                        <th class="text-nowrap bg-td-xs">
                            <div class="form-check">
                                <label for="chk_all" class="form-check-label">
                                    <input type="checkbox" name="chk_all" id="chk_all" data-parent="first" class="form-check-input">
                                    <?php echo $this->lang['mod']['label']['all']; ?>
                                </label>
                            </div>
                        </th>
                        <th class="text-nowrap bg-td-xs"><?php echo $this->lang['mod']['label']['id']; ?></th>
                        <th><?php echo $this->lang['mod']['label']['appName']; ?></th>
                        <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['status']; ?> / <?php echo $this->lang['mod']['label']['note']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->tplData['appRows'] as $key=>$value) { ?>
                        <tr>
                            <td class="text-nowrap bg-td-xs"><input type="checkbox" name="app_ids[]" value="<?php echo $value['app_id']; ?>" id="app_id_<?php echo $value['app_id']; ?>" data-validate="app_id" data-parent="chk_all"></td>
                            <td class="text-nowrap bg-td-xs"><?php echo $value['app_id']; ?></td>
                            <td>
                                <ul class="list-unstyled">
                                    <li><?php echo $value['app_name']; ?></li>
                                    <li>
                                        <ul class="bg-nav-line">
                                            <li>
                                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=show&app_id=<?php echo $value['app_id']; ?>"><?php echo $this->lang['mod']['href']['show']; ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=form&app_id=<?php echo $value['app_id']; ?>"><?php echo $this->lang['mod']['href']['edit']; ?></a>
                                            </li>
                                            <li class="dropdown">
                                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                                    <?php echo $this->lang['mod']['btn']['more']; ?>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=show&app_id=<?php echo $value['app_id']; ?>"><?php echo $this->lang['mod']['href']['secret']; ?></a>
                                                    <a class="dropdown-item"href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=belong&app_id=<?php echo $value['app_id']; ?>"><?php echo $this->lang['mod']['href']['belong']; ?></a>
                                                    <a class="dropdown-item" href="#app_modal" data-toggle="modal" data-id="<?php echo $value['app_id']; ?>"><?php echo $this->lang['mod']['href']['notifyTest']; ?></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-nowrap bg-td-md">
                                <ul class="list-unstyled">
                                    <li>
                                        <?php app_status_process($value['app_status'], $this->lang['mod']['status']); ?>
                                    </li>
                                    <li><?php echo $value['app_note']; ?></li>
                                </ul>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <span class="form-text" id="msg_app_id"></span>
            <div class="bg-submit-box bg-submit-box-list"></div>
        </div>

        <div class="mt-3 clearfix">
            <div class="float-left">
                <div class="input-group">
                    <select name="a" id="a" data-validate class="custom-select">
                        <option value=""><?php echo $this->lang['mod']['option']['batch']; ?></option>
                        <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                            <option value="<?php echo $value; ?>">
                                <?php if (isset($this->lang['mod']['status'][$value])) {
                                    echo $this->lang['mod']['status'][$value];
                                } else {
                                    echo $value;
                                } ?>
                            </option>
                        <?php } ?>
                        <option value="del"><?php echo $this->lang['mod']['option']['del']; ?></option>
                    </select>
                    <span class="input-group-append">
                        <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['submit']; ?></button>
                    </div>
                </div>
                <span class="form-text" id="msg_a"></span>
            </div>
            <div class="float-right">
                <?php include($cfg['pathInclude'] . 'page.php'); ?>
            </div>
        </div>
    </form>


    <div class="modal fade" id="app_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <?php echo $this->lang['mod']['label']['notifyTest']; ?>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="app_notify" name="app_notify">
                        <input type="hidden" name="a" value="notify">
                        <input type="hidden" name="app_id_notify" id="app_id_notify">
                        <div class="bg-submit-box bg-submit-box-modal"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <?php echo $this->lang['common']['btn']['close']; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        app_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='app_id']", type: "checkbox" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        },
        a: {
            len: { min: 1, max: 0 },
            validate: { type: "select" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030203']; ?>" }
        }
    };

    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=app&c=request",
        confirm: {
            selector: "#a",
            val: "del",
            msg: "<?php echo $this->lang['mod']['confirm']['del']; ?>"
        },
        box: {
            selector: ".bg-submit-box-list"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    var opts_submit_notify = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=app&c=request",
        box: {
            selector: ".bg-submit-box-modal",
            delay: 50000
        },
        selector: {
            submit_btn: ".bg-submit-modal"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_notify  = $("#app_notify").baigoSubmit(opts_submit_notify);

        $("#app_modal").on("shown.bs.modal",function(event){
            var _obj_button = $(event.relatedTarget);
            var _id         = _obj_button.data("id");
            $("#app_id_notify").val(_id);
            obj_notify.formSubmit();
        });

        var obj_validator_list    = $("#app_list").baigoValidator(opts_validator_list);
        var obj_submit_list       = $("#app_list").baigoSubmit(opts_submit_list);
        $(".bg-submit").click(function(){
            if (obj_validator_list.verify()) {
                obj_submit_list.formSubmit();
            }
        });
        $("#app_list").baigoCheckall();
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');