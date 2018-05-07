<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['verify']['main']['title'],
    'menu_active'    => 'verify',
    'sub_active'     => 'list',
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=verify&a=list',
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=log" target="_blank" class="nav-link">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <form name="verify_list" id="verify_list">
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
                        <th><?php echo $this->lang['mod']['label']['operator']; ?></th>
                        <th class="text-nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['status']; ?> / <?php echo $this->lang['mod']['label']['type']; ?></th>
                        <th class="text-nowrap bg-td-lg"><?php echo $this->lang['mod']['label']['timeInit']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->tplData['verifyRows'] as $key=>$value) { ?>
                        <tr>
                            <td class="text-nowrap bg-td-xs"><input type="checkbox" name="verify_ids[]" value="<?php echo $value['verify_id']; ?>" id="verify_id_<?php echo $value['verify_id']; ?>" data-validate="verify_id" data-parent="chk_all"></td>
                            <td class="text-nowrap bg-td-xs"><?php echo $value['verify_id']; ?></td>
                            <td>
                                <ul class="list-unstyled">
                                    <li>
                                        <?php if (isset($value['userRow']['user_name'])) {
                                            echo $value['userRow']['user_name'];
                                        } else {
                                            echo $this->lang['mod']['label']['unknow'];
                                        } ?>
                                    </li>
                                    <li>
                                        <ul class="bg-nav-line">
                                            <li>
                                                <a href="#verify_modal" data-toggle="modal" data-id="<?php echo $value['verify_id']; ?>"><?php echo $this->lang['mod']['href']['show']; ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-nowrap bg-td-lg">
                                <ul class="list-unstyled">
                                    <li>
                                        <?php verify_status_process($value['verify_status'], $this->lang['mod']['status']); ?>
                                    </li>
                                    <li><?php echo $this->lang['mod']['type'][$value['verify_type']]; ?></li>
                                </ul>
                            </td>
                            <td class="text-nowrap bg-td-lg">
                                <?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIMESHORT, $value['verify_time_refresh']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <span class="form-text" id="msg_verify_id"></span>
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

    <div class="modal fade" id="verify_modal">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_list = {
        verify_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='verify_id']", type: "checkbox" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030202']; ?>" }
        },
        a: {
            len: { min: 1, max: 0 },
            validate: { type: "select" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x030203']; ?>" }
        }
    };
    var opts_submit_list = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=verify&c=request",
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
        $("#verify_modal").on("shown.bs.modal",function(event){
            var _obj_button = $(event.relatedTarget);
            var _verify_id  = _obj_button.data("id");
            $("#verify_modal .modal-content").load("<?php echo BG_URL_CONSOLE; ?>index.php?m=verify&a=show&verify_id=" + _verify_id + "&view=iframe");
    	}).on("hidden.bs.modal", function(){
        	$("#verify_modal .modal-content").empty();
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

<?php include($cfg['pathInclude'] . 'html_foot.php');
