<?php $cfg = array(
    'title'          => $this->lang['common']['page']['console'] . ' &raquo; ' . $this->lang['mod']['page']['forgot'],
    'active'         => "forgot",
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'reloadImg'      => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS
);

include($cfg['pathInclude'] . 'login_head.php'); ?>

    <ul class="nav nav-tabs">
        <?php foreach ($this->tplData['forgot'] as $_key=>$_value) { ?>
            <li<?php if ($_key == 'mail') { ?> class="active"<?php } ?>>
                <a href="#<?php echo $_key; ?>" data-toggle="tab">
                    <?php if (isset($this->lang['forgot'][$_key])) {
                        echo $this->lang['forgot'][$_key];
                    } else {
                        echo $_value;
                    } ?>
                </a>
            </li>
        <?php } ?>
    </ul>

    <div>&nbsp;</div>

    <div class="tab-content">
        <div class="tab-pane active" id="mail">
            <form name="forgot_mail" id="forgot_mail">
                <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                <input type="hidden" name="act" value="bymail">
                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?></label>
                    <input type="text" name="admin_name" value="<?php echo $this->tplData['userRow']['user_name']; ?>" readonly class="form-control input-lg">
                </div>

                <?php if (fn_isEmpty($this->tplData['userRow']['user_mail'])) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="glyphicon glyphicon-remove-sign"></span>
                        <?php echo $this->lang['rcode']['x010241']; ?>
                    </div>
                <?php } else { ?>

                    <div class="alert alert-warning">
                        <span class="glyphicon glyphicon-warning-sign"></span>
                        <?php echo $this->lang['mod']['text']['forgotByMail']; ?>
                    </div>

                    <div class="form-group">
                        <div id="group_seccode_mail">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['seccode']; ?><span id="msg_seccode_mail">*</span></label>
                            <ul class="list-inline">
                                <li>
                                    <a href="javascript:void(0);" class="seccodeBtn">
                                        <img src="<?php echo BG_URL_MISC; ?>index.php?mod=seccode&act=make" class="seccodeImg" alt="<?php echo $this->lang['mod']['href']['seccode']; ?>">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="seccodeBtn">
                                        <span class="glyphicon glyphicon-repeat"></span>
                                        <?php echo $this->lang['mod']['btn']['seccode']; ?>
                                    </a>
                                </li>
                            </ul>
                            <input type="text" name="seccode" id="seccode_mail" placeholder="<?php echo $this->lang['rcode']['x030201']; ?>" data-validate class="form-control input-lg">
                        </div>
                    </div>

                    <div class="bg-submit-box bg-submit-box-mail"></div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['submit']; ?></button>
                    </div>
                <?php } ?>
            </form>
        </div>
        <div class="tab-pane" id="qa">
            <form name="forgot_qa" id="forgot_qa">
                <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                <input type="hidden" name="act" value="byqa">

                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?></label>
                    <input type="text" name="admin_name" value="<?php echo $this->tplData['userRow']['user_name']; ?>" readonly class="form-control input-lg">
                </div>

                <?php if (fn_isEmpty($this->tplData['userRow']['user_sec_ques_1'])) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span class="glyphicon glyphicon-remove-sign"></span>
                        <?php echo $this->lang['rcode']['x010242']; ?>
                    </div>
                <?php } else {
                    for ($_iii = 1; $_iii <= 3; $_iii++) { ?>
                        <div class="form-group">
                            <div id="group_admin_sec_answ_<?php echo $_iii; ?>">
                                <label class="control-label"><?php echo $this->tplData['userRow']['user_sec_ques_' . $_iii]; ?><span id="msg_admin_sec_answ_<?php echo $_iii; ?>">*</span></label>
                                <input type="text" name="admin_sec_answ_<?php echo $_iii; ?>" id="admin_sec_answ_<?php echo $_iii; ?>" data-validate placeholder="<?php echo $this->lang['mod']['label']['answer']; ?>" class="form-control input-lg">
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div id="group_admin_pass_new">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['passNew']; ?><span id="msg_admin_pass_new">*</span></label>
                            <input type="password" name="admin_pass_new" id="admin_pass_new" data-validate placeholder="<?php echo $this->lang['rcode']['x010222']; ?>" class="form-control input-lg">
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="group_admin_pass_confirm">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['passConfirm']; ?><span id="msg_admin_pass_confirm">*</span></label>
                            <input type="password" name="admin_pass_confirm" id="admin_pass_confirm" data-validate placeholder="<?php echo $this->lang['rcode']['x010224']; ?>" class="form-control input-lg">
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="group_seccode_qa">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['seccode']; ?><span id="msg_seccode_qa">*</span></label>
                            <ul class="list-inline">
                                <li>
                                    <a href="javascript:void(0);" class="seccodeBtn">
                                        <img src="<?php echo BG_URL_MISC; ?>index.php?mod=seccode&act=make" class="seccodeImg" alt="<?php echo $this->lang['mod']['btn']['seccode']; ?>">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="seccodeBtn">
                                        <span class="glyphicon glyphicon-repeat"></span>
                                        <?php echo $this->lang['mod']['btn']['seccode']; ?>
                                    </a>
                                </li>
                            </ul>
                            <input type="text" name="seccode" id="seccode_qa" placeholder="<?php echo $this->lang['rcode']['x030201']; ?>" data-validate class="form-control input-lg">
                        </div>
                    </div>

                    <div class="bg-submit-box bg-submit-box-qa"></div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary bg-submit-qa"><?php echo $this->lang['mod']['btn']['submit']; ?></button>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'login_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_mail = {
        seccode_mail: {
            len: { min: 4, max: 4 },
            validate: { type: "ajax", format: "text", group: "#group_seccode_mail" },
            msg: { selector: "#msg_seccode_mail", too_short: "<?php echo $this->lang['rcode']['x030201']; ?>", too_long: "<?php echo $this->lang['rcode']['x030201']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_MISC; ?>request.php?mod=seccode&act=chk", key: "seccode", type: "str" }
        }
    };

    var opts_validator_qa = {
        <?php for ($_iii = 1; $_iii <= 3; $_iii++) { ?>
            admin_sec_answ_<?php echo $_iii; ?>: {
                len: { min: 1, max: 0 },
                validate: { type: "str", format: "text", group: "#group_admin_sec_answ_<?php echo $_iii; ?>" },
                msg: { selector: "#msg_admin_sec_answ_<?php echo $_iii; ?>", too_short: "<?php echo $this->lang['rcode']['x010237'] . $_iii; ?>" }
            },
        <?php } ?>
        admin_pass_new: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_admin_pass_new" },
            msg: { selector: "#msg_admin_pass_new", too_short: "<?php echo $this->lang['rcode']['x010222']; ?>" }
        },
        admin_pass_confirm: {
            len: { min: 1, max: 0 },
            validate: { type: "confirm", target: "#admin_pass_new", group: "#group_admin_pass_confirm" },
            msg: { selector: "#msg_admin_pass_confirm", too_short: "<?php echo $this->lang['rcode']['x010224']; ?>", not_match: "<?php echo $this->lang['rcode']['x010225']; ?>" }
        },
        seccode_qa: {
            len: { min: 4, max: 4 },
            validate: { type: "ajax", format: "text", group: "#group_seccode_qa" },
            msg: { selector: "#msg_seccode_qa", too_short: "<?php echo $this->lang['rcode']['x030201']; ?>", too_long: "<?php echo $this->lang['rcode']['x030201']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_MISC; ?>request.php?mod=seccode&act=chk", key: "seccode", type: "str" }
        }
    };

    var opts_submit_mail = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=forgot",
        box: {
            selector: ".bg-submit-box-mail"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    var opts_submit_qa = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=forgot",
        box: {
            selector: ".bg-submit-box-qa"
        },
        selector: {
            submit_btn: ".bg-submit-qa"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_mail  = $("#forgot_mail").baigoValidator(opts_validator_mail);
        var obj_submit_mail     = $("#forgot_mail").baigoSubmit(opts_submit_mail);
        $(".bg-submit").click(function(){
            if (obj_validator_mail.verify()) {
                obj_submit_mail.formSubmit();
            }
        });

        var obj_validator_qa  = $("#forgot_qa").baigoValidator(opts_validator_qa);
        var obj_submit_qa     = $("#forgot_qa").baigoSubmit(opts_submit_qa);
        $(".bg-submit-qa").click(function(){
            if (obj_validator_qa.verify()) {
                obj_submit_qa.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>