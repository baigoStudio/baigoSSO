<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['admin']['main']['title'] . ' &raquo; ' . $this->lang['consoleMod']['admin']['sub']['auth'],
    'menu_active'    => 'admin',
    'sub_active'     => "auth",
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?mod=admin',
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=admin&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang['common']['href']['back']; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=admin#form" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang['mod']['href']['help']; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="admin_form" id="admin_form" autocomplete="off">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="act" value="auth">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <div id="group_admin_name">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?><span id="msg_admin_name">*</span></label>
                                <input type="text" name="admin_name" id="admin_name" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_admin_nick">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['nick']; ?><span id="msg_admin_nick"></span></label>
                                <input type="text" name="admin_nick" id="admin_nick" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['allow']; ?><span id="msg_admin_allow">*</span></label>
                            <?php allow_list($this->consoleMod, $this->lang['consoleMod'], $this->opt, $this->lang['opt'], $this->lang['mod']['label'], $this->lang['common']['page'], $this->tplData['adminRow']['admin_allow']); ?>
                        </div>

                        <div class="form-group">
                            <div id="group_admin_note">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['note']; ?><span id="msg_admin_note"></span></label>
                                <input type="text" name="admin_note" id="admin_note" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="bg-submit-box"></div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-primary bg-submit">
                            <?php echo $this->lang['mod']['btn']['save']; ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="well">
                    <div class="form-group">
                        <div id="group_admin_type">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['type']; ?><span id="msg_admin_type">*</span></label>
                            <?php foreach ($this->tplData['type'] as $key=>$value) { ?>
                                <div class="bg-radio">
                                    <label for="admin_type_<?php echo $value; ?>">
                                        <input type="radio" name="admin_type" id="admin_type_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['adminRow']['admin_type'] == $value) { ?>checked<?php } ?> data-validate="admin_type">
                                        <?php if (isset($this->lang['mod']['type'][$value])) {
                                            echo $this->lang['mod']['type'][$value];
                                        } else {
                                            echo $value;
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="group_admin_status">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?><span id="msg_admin_status">*</span></label>
                            <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                                <div class="bg-radio">
                                    <label for="admin_status_<?php echo $value; ?>">
                                        <input type="radio" name="admin_status" id="admin_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['adminRow']['admin_status'] == $value) { ?>checked<?php } ?> data-validate="admin_status">
                                        <?php if (isset($this->lang['mod']['status'][$value])) {
                                            echo $this->lang['mod']['status'][$value];
                                        } else {
                                            echo $value;
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['profileAllow']; ?></label>
                        <?php foreach ($this->profile as $key=>$value) { ?>
                            <div class="bg-checkbox">
                                <label for="admin_allow_profile_<?php echo $key; ?>">
                                    <input type="checkbox" name="admin_allow_profile[<?php echo $key; ?>]" id="admin_allow_profile_<?php echo $key; ?>" value="1">
                                    <?php echo $this->lang['mod']['label']['forbidModi'];
                                    if (isset($this->lang['common']['profile'][$key]['title'])) {
                                        echo $this->lang['common']['profile'][$key]['title'];
                                    } else {
                                        echo $value['title'];
                                    } ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        admin_name: {
            len: { min: 1, max: 30 },
            validate: { type: "ajax", format: "strDigit", group: "#group_admin_name" },
            msg: { selector: "#msg_admin_name", too_short: "<?php echo $this->lang['rcode']['x010201']; ?>", too_long: "<?php echo $this->lang['rcode']['x010202']; ?>", format_err: "<?php echo $this->lang['rcode']['x010203']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=admin&act=chkauth", key: "admin_name", type: "str", attach_selectors: ["#admin_id"], attach_keys: ["admin_id"] }
        },
        admin_nick: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_admin_nick" },
            msg: { selector: "#msg_admin_nick", too_long: "<?php echo $this->lang['rcode']['x020204']; ?>" }
        },
        admin_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_admin_note" },
            msg: { selector: "#msg_admin_note", too_long: "<?php echo $this->lang['rcode']['x020203']; ?>" }
        },
        admin_type: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='admin_type']", type: "radio", group: "#group_admin_type" },
            msg: { selector: "#msg_admin_type", too_few: "<?php echo $this->lang['rcode']['x020201']; ?>" }
        },
        admin_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='admin_status']", type: "radio", group: "#group_admin_status" },
            msg: { selector: "#msg_admin_status", too_few: "<?php echo $this->lang['rcode']['x020202']; ?>" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=admin",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#admin_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#admin_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#admin_form").baigoCheckall();
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>
