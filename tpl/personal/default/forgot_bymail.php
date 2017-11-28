<?php $cfg = array(
    'title'         => $this->lang['mod']['page']['forgot'] . ' &raquo; ' . $this->lang['mod']['page']['verify'],
    'pathInclude'   => BG_PATH_TPL . 'personal' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'personal_head.php'); ?>

    <form name="forgot_form" id="forgot_form">
        <input type="hidden" name="act" value="bymail">
        <input type="hidden" name="verify_id" value="<?php echo $this->tplData['verifyRow']['verify_id']; ?>">
        <input type="hidden" name="verify_token" value="<?php echo $this->tplData['verifyRow']['verify_token']; ?>">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">

        <div class="form-group">
            <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?></label>
            <input type="text" name="user_name" id="user_name" value="<?php echo $this->tplData['userRow']['user_name']; ?>" readonly class="form-control input-lg">
        </div>

        <div class="form-group">
            <div id="group_user_pass_new">
                <label class="control-label"><?php echo $this->lang['mod']['label']['passNew']; ?><span id="msg_user_pass_new">*</span></label>
                <input type="password" name="user_pass_new" id="user_pass_new" placeholder="<?php echo $this->lang['rcode']['x010222']; ?>" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_user_pass_confirm">
                <label class="control-label"><?php echo $this->lang['mod']['label']['passConfirm']; ?><span id="msg_user_pass_confirm">*</span></label>
                <input type="password" name="user_pass_confirm" id="user_pass_confirm" placeholder="<?php echo $this->lang['rcode']['x010224']; ?>" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_seccode">
                <label class="control-label"><?php echo $this->lang['mod']['label']['seccode']; ?><span id="msg_seccode">*</span></label>
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
                <input type="text" name="seccode" id="seccode" placeholder="<?php echo $this->lang['rcode']['x030201']; ?>" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="bg-submit-box"></div>

        <div class="form-group">
            <button type="button" class="btn btn-primary btn-block btn-lg bg-submit"><?php echo $this->lang['mod']['btn']['submit']; ?></button>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'personal_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        user_pass_new: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_user_pass_new" },
            msg: { selector: "#msg_user_pass_new", too_short: "<?php echo $this->lang['rcode']['x010222']; ?>" }
        },
        user_pass_confirm: {
            len: { min: 1, max: 0 },
            validate: { type: "confirm", target: "#user_pass_new", group: "#group_user_pass_confirm" },
            msg: { selector: "#msg_user_pass_confirm", too_short: "<?php echo $this->lang['rcode']['x010224']; ?>", not_match: "<?php echo $this->lang['rcode']['x010225']; ?>" }
        },
        seccode: {
            len: { min: 4, max: 4 },
            validate: { type: "ajax", format: "text", group: "#group_seccode" },
            msg: { selector: "#msg_seccode", too_short: "<?php echo $this->lang['rcode']['x030201']; ?>", too_long: "<?php echo $this->lang['rcode']['x030201']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_MISC; ?>request.php?mod=seccode&act=chk", key: "seccode", type: "str" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_PERSONAL; ?>request.php?mod=forgot",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#forgot_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#forgot_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

</body>
</html>
