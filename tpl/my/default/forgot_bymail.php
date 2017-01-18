<?php $cfg = array(
    "title"  => $this->lang["page"]["forgot"] . " &raquo; " . $this->lang["page"]["verify"],
); ?>

<?php include(BG_PATH_TPL . "my/default/include/my_head.php"); ?>

    <form name="forgot_form" id="forgot_form">
        <input type="hidden" name="act" value="bymail">
        <input type="hidden" name="verify_id" value="<?php echo $this->tplData["verifyRow"]["verify_id"]; ?>">
        <input type="hidden" name="verify_token" value="<?php echo $this->tplData["verifyRow"]["verify_token"]; ?>">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">

        <div class="form-group">
            <label class="control-label"><?php echo $this->lang["label"]["username"]; ?></label>
            <input type="text" name="user_name" id="user_name" value="<?php echo $this->tplData["userRow"]["user_name"]; ?>" readonly class="form-control input-lg">
        </div>

        <div class="form-group">
            <div id="group_user_pass_new">
                <label class="control-label"><?php echo $this->lang["label"]["passNew"]; ?><span id="msg_user_pass_new">*</span></label>
                <input type="password" name="user_pass_new" id="user_pass_new" placeholder="<?php echo $this->rcode["x010222"]; ?>" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_user_pass_confirm">
                <label class="control-label"><?php echo $this->lang["label"]["passConfirm"]; ?><span id="msg_user_pass_confirm">*</span></label>
                <input type="password" name="user_pass_confirm" id="user_pass_confirm" placeholder="<?php echo $this->rcode["x010224"]; ?>" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_seccode">
                <label class="control-label"><?php echo $this->lang["label"]["seccode"]; ?><span id="msg_seccode">*</span></label>
                <div class="input-group">
                    <input type="text" name="seccode" id="seccode" placeholder="<?php echo $this->rcode["x030201"]; ?>" data-validate class="form-control input-lg">
                    <span class="input-group-addon">
                        <a href="javascript:reloadImg('seccodeImg','<?php echo BG_URL_MISC; ?>index.php?mod=seccode&act=make');" title="<?php echo $this->lang["alt"]["seccode"]; ?>">
                            <img src="<?php echo BG_URL_MISC; ?>index.php?mod=seccode&act=make" id="seccodeImg" alt="<?php echo $this->lang["alt"]["seccode"]; ?>" height="32">
                        </a>
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-submit-box"></div>

        <div class="form-group">
            <button type="button" class="btn btn-primary btn-block btn-lg bg-submit"><?php echo $this->lang["btn"]["submit"]; ?></button>
        </div>
    </form>

<?php include(BG_PATH_TPL . "my/default/include/my_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        user_pass_new: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_user_pass_new" },
            msg: { selector: "#msg_user_pass_new", too_short: "<?php echo $this->rcode["x010222"]; ?>" }
        },
        user_pass_confirm: {
            len: { min: 1, max: 0 },
            validate: { type: "confirm", target: "#user_pass_new", group: "#group_user_pass_confirm" },
            msg: { selector: "#msg_user_pass_confirm", too_short: "<?php echo $this->rcode["x010224"]; ?>", not_match: "<?php echo $this->rcode["x010225"]; ?>" }
        },
        seccode: {
            len: { min: 4, max: 4 },
            validate: { type: "ajax", format: "text", group: "#group_seccode" },
            msg: { selector: "#msg_seccode", too_short: "<?php echo $this->rcode["x030201"]; ?>", too_long: "<?php echo $this->rcode["x030201"]; ?>", ajaxIng: "<?php echo $this->rcode["x030401"]; ?>", ajax_err: "<?php echo $this->rcode["x030402"]; ?>" },
            ajax: { url: "<?php echo BG_URL_MISC; ?>request.php?mod=seccode&act=chk", key: "seccode", type: "str" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_MY; ?>request.php?mod=forgot",
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
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
