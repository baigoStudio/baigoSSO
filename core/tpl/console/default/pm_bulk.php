<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['pm']['main']['title'] . ' &raquo; ' . $this->lang['consoleMod']['pm']['sub']['bulk'],
    'menu_active'    => "pm",
    'sub_active'     => "bulk",
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'datetimepicker' => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=pm',
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&a=list" class="nav-link">
                <span class="oi oi-chevron-left"></span>
                <?php echo $this->lang['common']['href']['back']; ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=pm#form" target="_blank" class="nav-link">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <form name="pm_form" id="pm_form">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="bulk">

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['pmBulkType']; ?> <span class="text-danger">*</span></label>
                    <select name="pm_bulk_type" id="pm_bulk_type" data-validate class="form-control">
                        <option value="bulkUsers"><?php echo $this->lang['mod']['option']['bulkUsers']; ?></option>
                        <option value="bulkKeyName"><?php echo $this->lang['mod']['option']['bulkKeyName']; ?></option>
                        <option value="bulkKeyMail"><?php echo $this->lang['mod']['option']['bulkKeyMail']; ?></option>
                        <option value="bulkRangeId"><?php echo $this->lang['mod']['option']['bulkRangeId']; ?></option>
                        <option value="bulkRangeTime"><?php echo $this->lang['mod']['option']['bulkRangeTime']; ?></option>
                        <option value="bulkRangeLogin"><?php echo $this->lang['mod']['option']['bulkRangeLogin']; ?></option>
                    </select>
                    <small class="form-text" id="msg_pm_bulk_type"></small>
                </div>

                <div class="form-group">
                    <div id="bulkUsers" class="bulk_types">
                        <label><?php echo $this->lang['mod']['label']['pmTo']; ?> <span class="text-danger">*</span></label>
                        <input type="text" name="pm_to_users" id="pm_to_users" data-validate class="form-control">
                        <small class="form-text"><?php echo $this->lang['mod']['label']['toNote']; ?></small>
                        <small class="form-text" id="msg_pm_to_users"></small>
                    </div>

                    <div id="bulkKeyName" class="bulk_types">
                        <label><?php echo $this->lang['mod']['label']['key']; ?> <span class="text-danger">*</span></label>
                        <input type="text" name="pm_to_key_name" id="pm_to_key_name" data-validate class="form-control">
                        <small class="form-text"><?php echo $this->lang['mod']['label']['keyNameNote']; ?></small>
                        <small class="form-text" id="msg_pm_to_key_name"></small>
                    </div>

                    <div id="bulkKeyMail" class="bulk_types">
                        <label><?php echo $this->lang['mod']['label']['key']; ?> <span class="text-danger">*</span></label>
                        <input type="text" name="pm_to_key_mail" id="pm_to_key_mail" data-validate class="form-control">
                        <small class="form-text"><?php echo $this->lang['mod']['label']['keyMailNote']; ?></small>
                        <small class="form-text" id="msg_pm_to_key_mail"></small>
                    </div>

                    <div id="bulkRangeId" class="bulk_types">
                        <label><?php echo $this->lang['mod']['label']['id']; ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="pm_to_min_id" id="pm_to_min_id" data-validate class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text border-right-0"><?php echo $this->lang['mod']['label']['to']; ?></span>
                            </div>
                            <input type="text" name="pm_to_max_id" id="pm_to_max_id" data-validate class="form-control">
                        </div>
                        <small class="form-text"><?php echo $this->lang['mod']['label']['rangeIdNote']; ?></small>
                        <small class="form-text" id="msg_pm_to_range_id"></small>
                    </div>

                    <div id="bulkRangeTime" class="bulk_types">
                        <label><?php echo $this->lang['mod']['label']['timeReg']; ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="pm_to_begin_time" id="pm_to_begin_time" data-validate class="form-control input_date" value="<?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIME, $this->tplData['begin_time']); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text border-right-0"><?php echo $this->lang['mod']['label']['to']; ?></span>
                            </div>
                            <input type="text" name="pm_to_end_time" id="pm_to_end_time" data-validate class="form-control input_date" value="<?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIME, $this->tplData['end_time']); ?>">
                        </div>
                        <small class="form-text"><?php echo $this->lang['mod']['label']['rangeTimeNote']; ?></small>
                        <small class="form-text" id="msg_pm_to_range_time"></small>
                    </div>

                    <div id="bulkRangeLogin" class="bulk_types">
                        <label><?php echo $this->lang['mod']['label']['timeLogin']; ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="pm_to_begin_login" id="pm_to_begin_login" data-validate class="form-control input_date" value="<?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIME, $this->tplData['begin_time']); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text border-right-0"><?php echo $this->lang['mod']['label']['to']; ?></span>
                            </div>
                            <input type="text" name="pm_to_end_login" id="pm_to_end_login" data-validate class="form-control input_date" value="<?php echo date(BG_SITE_DATE . ' ' . BG_SITE_TIME, $this->tplData['end_time']); ?>">
                        </div>
                        <small class="form-text"><?php echo $this->lang['mod']['label']['rangeLoginNote']; ?></small>
                        <small class="form-text" id="msg_pm_to_range_login"></small>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['title']; ?></label>
                    <input type="text" name="pm_title" id="pm_title" data-validate class="form-control">
                    <small class="form-text" id="msg_pm_title"></small>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['content']; ?> <span class="text-danger">*</span></label>
                    <textarea name="pm_content" id="pm_content" data-validate class="form-control bg-textarea-md"></textarea>
                    <small class="form-text" id="msg_pm_content"></small>
                </div>

                <div class="bg-submit-box"></div>
                <div class="bg-validator-box"></div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary bg-submit">
                    <?php echo $this->lang['mod']['btn']['send']; ?>
                </button>
            </div>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        pm_title: {
            len: { min: 0, max: 90 },
            validate: { type: "str", format: "text" },
            msg: { too_long: "<?php echo $this->lang['rcode']['x110202']; ?>" }
        },
        pm_content: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110201']; ?>", too_long: "<?php echo $this->lang['rcode']['x110203']; ?>" }
        },
        pm_bulk_type: {
            len: { min: 1, max: 0 },
            validate: { type: "select" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110204']; ?>" }
        },
        pm_to_users: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110205']; ?>" }
        },
        pm_to_key_name: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110206']; ?>" }
        },
        pm_to_key_mail: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110207']; ?>" }
        },
        pm_to_min_id: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "int", group: "#pm_to_min_id" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110208']; ?>", format_err: "<?php echo $this->lang['rcode']['x110209']; ?>" }
        },
        pm_to_max_id: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "int", group: "#pm_to_max_id" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110210']; ?>", format_err: "<?php echo $this->lang['rcode']['x110209']; ?>" }
        },
        pm_to_begin_time: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_begin_time" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110212']; ?>", format_err: "<?php echo $this->lang['rcode']['x110213']; ?>" }
        },
        pm_to_end_time: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_end_time" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110214']; ?>", format_err: "<?php echo $this->lang['rcode']['x110213']; ?>" }
        },
        pm_to_begin_login: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_begin_login" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110215']; ?>", format_err: "<?php echo $this->lang['rcode']['x110216']; ?>" }
        },
        pm_to_end_login: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_end_login" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110217']; ?>", format_err: "<?php echo $this->lang['rcode']['x110216']; ?>" }
        }
    };

    var options_validator_form = {
        msg_global:{
            msg: "<?php echo $this->lang['common']['label']['errInput']; ?>"
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=pm&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    function bulk_type(_type_id) {
        $(".bulk_types").hide();
        $("#" + _type_id).show();
    }

    $(document).ready(function(){
        bulk_type('bulkUsers');
        var obj_validator_form    = $("#pm_form").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form       = $("#pm_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#pm_bulk_type").change(function(){
            var _type_id = $(this).val();
            bulk_type(_type_id);
        });
        $(".input_date").datetimepicker(opts_datetimepicker);
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
