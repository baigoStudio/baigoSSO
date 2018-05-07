<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['pm']['main']['title'],
    'menu_active'    => "pm",
    'sub_active'     => 'list',
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    "tooltip"        => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=pm&a=list&' . $this->tplData['query'],
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="mb-3 clearfix">
        <div class="float-left">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&a=send" class="nav-link">
                        <span class="oi oi-pencil"></span>
                        <?php echo $this->lang['mod']['href']['pmSend']; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&a=bulk" class="nav-link">
                        <span class="oi oi-bullhorn"></span>
                        <?php echo $this->lang['mod']['href']['pmBulk']; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=pm" target="_blank" class="nav-link">
                        <span class="badge badge-pill badge-primary">
                            <span class="oi oi-question-mark"></span>
                        </span>
                        <?php echo $this->lang['mod']['href']['help']; ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="float-right">
            <form name="pm_search" id="pm_search" action="<?php echo BG_URL_CONSOLE; ?>index.php" method="get">
                <input type="hidden" name="m" value="pm">
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
                    <select name="type" class="custom-select d-none d-md-block">
                        <option value=""><?php echo $this->lang['mod']['option']['allType']; ?></option>
                        <?php foreach ($this->tplData['type'] as $key=>$value) { ?>
                            <option <?php if ($this->tplData['search']['type'] == $value) { ?>selected<?php } ?> value="<?php echo $value; ?>">
                                <?php if (isset($this->lang['mod']['type'][$value])) {
                                    echo $this->lang['mod']['type'][$value];
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

    <form name="pm_list" id="pm_list">
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
                        <th><?php echo $this->lang['mod']['label']['pm']; ?></th>
                        <th><?php echo $this->lang['mod']['label']['pmFrom']; ?> / <?php echo $this->lang['mod']['label']['pmTo']; ?></th>
                        <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['time']; ?></th>
                        <th class="text-nowrap bg-td-sm"><?php echo $this->lang['mod']['label']['status']; ?> / <?php echo $this->lang['mod']['label']['type']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->tplData['pmRows'] as $key=>$value) { ?>
                        <tr>
                            <td class="text-nowrap bg-td-xs"><input type="checkbox" name="pm_ids[]" value="<?php echo $value['pm_id']; ?>" id="pm_id_<?php echo $value['pm_id']; ?>" data-validate="pm_id" data-parent="chk_all"></td>
                            <td class="text-nowrap bg-td-xs"><?php echo $value['pm_id']; ?></td>
                            <td>
                                <ul class="list-unstyled">
                                    <li><?php echo fn_htmlcode($value['pm_title'], 'decode', 'json'); ?></li>
                                    <li>
                                        <ul class="bg-nav-line">
                                            <li>
                                                <a href="#pm_modal" data-toggle="modal" data-id="<?php echo $value['pm_id']; ?>"><?php echo $this->lang['mod']['href']['show']; ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled">
                                    <li>
                                        <?php if ($value['pm_from'] == -1) {
                                            echo $this->lang['mod']['label']['pmSys'];
                                        } else if (isset($value['fromUser']['user_name'])) { ?>
                                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&a=list&pm_from=<?php echo $value['pm_from']; ?>"><?php echo $value['fromUser']['user_name']; ?></a>
                                        <?php } else {
                                            echo $this->lang['mod']['label']['unknow'];
                                        } ?>
                                    </li>
                                    <li>
                                        <?php if (isset($value['toUser']['user_name'])) { ?>
                                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&a=list&pm_to=<?php echo $value['pm_to']; ?>"><?php echo $value['toUser']['user_name']; ?></a>
                                        <?php } else {
                                            echo $this->lang['mod']['label']['unknow'];
                                        } ?>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-nowrap bg-td-md">
                                <abbr title="<?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIMESHORT, $value['pm_time']); ?>" data-toggle="tooltip" data-placement="bottom">
                                    <?php echo date(BG_SITE_DATESHORT . ' ' . BG_SITE_TIMESHORT, $value['pm_time']); ?>
                                </abbr>
                            </td>
                            <td class="text-nowrap bg-td-sm">
                                <ul class="list-unstyled">
                                    <li>
                                        <?php pm_status_process($value['pm_status'], $this->lang['mod']['status']); ?>
                                    </li>
                                    <li>
                                        <?php echo $this->lang['mod']['type'][$value['pm_type']]; ?>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <span class="form-text" id="msg_pm_id"></span>
            <div class="bg-submit-box"></div>
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
                    </span>
                </div>
                <span class="form-text" id="msg_a"></span>
            </div>
            <div class="float-right">
                <?php include($cfg['pathInclude'] . 'page.php'); ?>
            </div>
        </div>
    </form>

    <div class="modal fade" id="pm_modal">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        pm_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='pm_id']", type: "checkbox" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        },
        a: {
            len: { min: 1, max: 0 },
            validate: { type: "select" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030203']; ?>" }
        }
    };

    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&c=request",
        confirm: {
            selector: "#a",
            val: "del",
            msg: "<?php echo $this->lang['mod']['confirm']['del']; ?>"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        $("#pm_modal").on("shown.bs.modal",function(event){
            var _obj_button = $(event.relatedTarget);
            var _pm_id  = _obj_button.data("id");
            $("#pm_modal .modal-content").load("<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&a=show&pm_id=" + _pm_id + "&view=iframe");
    	}).on("hidden.bs.modal", function(){
        	$("#pm_modal .modal-content").empty();
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

<?php include($cfg['pathInclude'] . 'html_foot.php');