<?php if (isset($this->lang['common']['profile']['qa']['title'])) {
    $title_sub = $this->lang['common']['profile']['qa']['title'];
} else {
    $title_sub = $this->profile['qa']['title'];
}

$cfg = array(
    'title'          => $this->lang['mod']['page']['profile'] . ' &raquo; ' . $title_sub,
    'menu_active'    => "profile",
    'sub_active'     => "qa",
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=profile&a=qa',
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php');

    include($cfg['pathInclude'] . 'profile_menu.php'); ?>

    <form name="profile_form" id="profile_form" autocomplete="off">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="qa">

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['username']; ?></label>
                            <input type="text" value="<?php echo $this->tplData['adminLogged']['admin_name']; ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['password']; ?> <span class="text-danger">*</span></label>
                            <input type="password" name="admin_pass" id="admin_pass" data-validate class="form-control">
                            <small class="form-text" id="msg_admin_pass"></small>
                        </div>

                        <?php for ($_iii = 1; $_iii <= 3; $_iii++) { ?>
                            <hr class="bg-card-hr">
                            <div class="form-group">
                                <label>
                                    <?php echo $this->lang['mod']['label']['secQues'], ' ', $_iii; ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-row">
                                    <div class="col-sm-7 col-xs-6">
                                        <input type="text" name="admin_sec_ques_<?php echo $_iii; ?>" id="admin_sec_ques_<?php echo $_iii; ?>" data-validate value="<?php echo $this->tplData['adminLogged']['userRow']['user_sec_ques_' . $_iii]; ?>" class="form-control">
                                    </div>
                                    <div class="col-sm-5 col-xs-6">
                                        <select class="form-control" id="admin_ques_often_<?php echo $_iii; ?>">
                                            <option value=""><?php echo $this->lang['mod']['option']['pleaseQuesOften']; ?></option>
                                            <?php foreach ($this->lang['mod']['quesOften'] as $_key=>$_value) { ?>
                                                <option value="<?php echo $_value; ?>"><?php echo $_value; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="form-text" id="msg_admin_sec_ques_<?php echo $_iii; ?>"></small>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?php echo $this->lang['mod']['label']['secAnsw'], ' ', $_iii; ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-row">
                                    <div class="col-sm-7 col-xs-6">
                                        <input type="text" name="admin_sec_answ_<?php echo $_iii; ?>" id="admin_sec_answ_<?php echo $_iii; ?>" data-validate class="form-control">
                                    </div>
                                </div>
                                <small class="form-text" id="msg_admin_sec_answ_<?php echo $_iii; ?>"></small>
                            </div>
                        <?php } ?>

                        <div class="bg-submit-box"></div>
                        <div class="bg-validator-box"></div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary bg-submit">
                            <?php echo $this->lang['mod']['btn']['save']; ?>
                        </button>
                    </div>
                </div>
            </div>

            <?php include($cfg['pathInclude'] . 'profile_side.php'); ?>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        <?php for ($_iii = 1; $_iii <= 3; $_iii++) { ?>
            admin_sec_ques_<?php echo $_iii; ?>: {
                len: { min: 1, max: 0 },
                validate: { type: "str", format: "text", group: "#group_admin_sec_ques_<?php echo $_iii; ?>" },
                msg: { selector: "#msg_admin_sec_ques_<?php echo $_iii; ?>", too_short: "<?php echo $this->lang['rcode']['x010238'], ' ', $_iii; ?>", too_long: "<?php echo $this->lang['rcode']['x010236']; ?>" }
            },
            admin_sec_answ_<?php echo $_iii; ?>: {
                len: { min: 1, max: 0 },
                validate: { type: "str", format: "text", group: "#group_admin_sec_answ_<?php echo $_iii; ?>" },
                msg: { selector: "#msg_admin_sec_answ_<?php echo $_iii; ?>", too_short: "<?php echo $this->lang['rcode']['x010237'], ' ', $_iii; ?>" }
            },
        <?php } ?>
        admin_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010243']; ?>" }
        }
    };

    var options_validator_form = {
        msg_global:{
            msg: "<?php echo $this->lang['common']['label']['errInput']; ?>"
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=profile&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#profile_form").baigoValidator(opts_validator_form), options_validator_form;
        var obj_submit_form       = $("#profile_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        <?php for ($_iii = 1; $_iii <= 3; $_iii++) { ?>
            $("#admin_ques_often_<?php echo $_iii; ?>").change(function(){
                var _qo_<?php echo $_iii; ?> = $(this).val();
                $("#admin_sec_ques_<?php echo $_iii; ?>").val(_qo_<?php echo $_iii; ?>);
            });
        <?php } ?>
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
