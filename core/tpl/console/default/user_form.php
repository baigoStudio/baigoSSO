<?php if( BG_REG_NEEDMAIL == 'on') {
    $str_mailNeed = '*';
    $num_mailMin  = 1;
} else {
    $str_mailNeed = '';
    $num_mailMin  = 0;
}

if ($this->tplData['userRow']['user_id'] < 1) {
    $title_sub    = $this->lang['mod']['page']['add'];
    $str_sub      = 'form';
} else {
    $title_sub    = $this->lang['mod']['page']['edit'];
    $str_sub      = 'list';
}

$cfg = array(
    'title'          => $this->lang['consoleMod']['user']['main']['title'] . ' &raquo; ' . $title_sub,
    'menu_active'    => 'user',
    'sub_active'     => $str_sub,
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?mod=user',
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=user&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang['common']['href']['back']; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=user" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang['mod']['href']['help']; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="user_form" id="user_form">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="act" value="submit">
        <input type="hidden" name="user_id" value="<?php echo $this->tplData['userRow']['user_id']; ?>">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php if ($this->tplData['userRow']['user_id'] > 0) { ?>
                            <div class="form-group">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?><span id="msg_user_name">*</span></label>
                                <input type="text" name="user_name" id="user_name" readonly value="<?php echo $this->tplData['userRow']['user_name']; ?>" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['password']; ?></label>
                                <input type="text" name="user_pass" id="user_pass" class="form-control" placeholder="<?php echo $this->lang['mod']['label']['onlyModi']; ?>">
                            </div>
                        <?php } else { ?>
                            <div class="form-group">
                                <div id="group_user_name">
                                    <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?><span id="msg_user_name">*</span></label>
                                    <input type="text" name="user_name" id="user_name" data-validate class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div id="group_user_pass">
                                    <label class="control-label"><?php echo $this->lang['mod']['label']['password']; ?><span id="msg_user_pass">*</span></label>
                                    <input type="text" name="user_pass" id="user_pass" data-validate class="form-control">
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <div id="group_user_mail">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['mail']; ?><span id="msg_user_mail"><?php echo $str_mailNeed; ?></span></label>
                                <input type="text" name="user_mail" id="user_mail" value="<?php echo $this->tplData['userRow']['user_mail']; ?>" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_user_nick">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['nick']; ?><span id="msg_user_nick"></span></label>
                                <input type="text" name="user_nick" id="user_nick" value="<?php echo $this->tplData['userRow']['user_nick']; ?>" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_user_note">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['note']; ?><span id="msg_user_note"></span></label>
                                <input type="text" name="user_note" id="user_note" value="<?php echo $this->tplData['userRow']['user_note']; ?>" data-validate class="form-control">
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
                    <?php if ($this->tplData['userRow']['user_id'] > 0) { ?>
                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['id']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['userRow']['user_id']; ?></div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div id="group_user_status">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?><span id="msg_user_status">*</span></label>
                            <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                                <div class="bg-radio">
                                    <label for="user_status_<?php echo $value; ?>">
                                        <input type="radio" name="user_status" id="user_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['userRow']['user_status'] == $value) { ?>checked<?php } ?> data-validate="user_status">
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

                    <?php if (is_array($this->tplData['userRow']['user_contact']) && !fn_isEmpty($this->tplData['userRow']['user_contact'])) {
                        foreach ($this->tplData['userRow']['user_contact'] as $key=>$value) { ?>
                            <div class="form-group">
                                <div id="group_user_contact_<?php echo $key; ?>">
                                    <label class="control-label">
                                        <?php if (isset($value['key'])) { echo $value['key']; } ?>
                                    </label>
                                    <input type="hidden" name="user_contact[<?php echo $key; ?>][key]" value="<?php if (isset($value['key'])) { echo $value['key']; } ?>">
                                    <input type="text" name="user_contact[<?php echo $key; ?>][value]" value="<?php if (isset($value['value'])) { echo $value['value']; } ?>" class="form-control">
                                </div>
                            </div>
                        <?php }
                    }

                    if (is_array($this->tplData['userRow']['user_extend']) &&!fn_isEmpty($this->tplData['userRow']['user_extend'])) {
                        foreach ($this->tplData['userRow']['user_extend'] as $key=>$value) { ?>
                            <div class="form-group">
                                <div id="group_user_extend_<?php echo $key; ?>">
                                    <label class="control-label">
                                        <?php if (isset($value['key'])) { echo $value['key']; } ?>
                                    </label>
                                    <input type="hidden" name="user_extend[<?php echo $key; ?>][key]" value="<?php if (isset($value['key'])) { echo $value['key']; } ?>">
                                    <input type="text" name="user_extend[<?php echo $key; ?>][value]" value="<?php if (isset($value['value'])) { echo $value['value']; } ?>" class="form-control">
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        user_name: {
            len: { min: 1, max: 30 },
            validate: { type: "ajax", format: "strDigit", group: "#group_user_name" },
            msg: { selector: "#msg_user_name", too_short: "<?php echo $this->lang['rcode']['x010201']; ?>", too_long: "<?php echo $this->lang['rcode']['x010202']; ?>", format_err: "<?php echo $this->lang['rcode']['x010203']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=user&act=chkname", key: "user_name", type: "str", attach_selectors: ["#user_id"], attach_keys: ["user_id"] }
        },
        user_mail: {
            len: { min: <?php echo $num_mailMin; ?>, max: 300 },
            validate: { type: "ajax", format: "email", group: "#group_user_mail" },
            msg: { selector: "#msg_user_mail", too_short: "<?php echo $this->lang['rcode']['x010206']; ?>", too_long: "<?php echo $this->lang['rcode']['x010207']; ?>", format_err: "<?php echo $this->lang['rcode']['x010208']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=user&act=chkmail", key: "user_mail", type: "str", attach_selectors: ["#user_id"], attach_keys: ["user_id"] }
        },
        user_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_user_pass" },
            msg: { selector: "#msg_user_pass", too_short: "<?php echo $this->lang['rcode']['x010212']; ?>" }
        },
        user_nick: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_user_nick" },
            msg: { selector: "#msg_user_nick", too_long: "<?php echo $this->lang['rcode']['x010214']; ?>" }
        },
        user_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_user_note" },
            msg: { selector: "#msg_user_note", too_long: "<?php echo $this->lang['rcode']['x010215']; ?>" }
        },
        user_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='user_status']", type: "radio", group: "#group_user_status" },
            msg: { selector: "#msg_user_status", too_few: "<?php echo $this->lang['rcode']['x020203']; ?>" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=user",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validate_form = $("#user_form").baigoValidator(opts_validator_form);
        var obj_submit_form   = $("#user_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>
