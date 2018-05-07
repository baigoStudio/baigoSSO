
<?php $cfg = array(
    'title'          => $this->lang['mod']['page']['login'],
    'active'         => 'login',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'reloadImg'      => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS
);

include($cfg['pathInclude'] . 'login_head.php'); ?>

    <form name="login_form" id="login_form">
        <input type="hidden" name="a" value="login">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">

        <div class="form-group">
            <label><?php echo $this->lang['mod']['label']['username']; ?> <span class="text-danger">*</span></label>
            <input type="text" name="admin_name" id="admin_name" placeholder="<?php echo $this->lang['rcode']['x010201']; ?>" data-validate class="form-control form-control-lg">
            <small class="form-text" id="msg_admin_name"></small>
        </div>

        <div class="form-group">
            <label><?php echo $this->lang['mod']['label']['password']; ?> <span class="text-danger">*</span></label>
            <input type="password" name="admin_pass" id="admin_pass" placeholder="<?php echo $this->lang['rcode']['x010212']; ?>" data-validate class="form-control form-control-lg">
            <small class="form-text" id="msg_admin_pass"></small>
        </div>

        <div class="form-group">
            <label><?php echo $this->lang['mod']['label']['captcha']; ?> <span class="text-danger">*</span></label>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="javascript:void(0);" class="captchaBtn">
                        <img src="<?php echo BG_URL_MISC; ?>index.php?m=captcha&a=make" class="captchaImg" alt="<?php echo $this->lang['mod']['btn']['captcha']; ?>">
                    </a>
                </li>
                <li class="list-inline-item">
                    <a href="javascript:void(0);" class="captchaBtn">
                        <span class="oi oi-reload"></span>
                        <?php echo $this->lang['mod']['btn']['captcha']; ?>
                    </a>
                </li>
            </ul>
            <input type="text" name="captcha" id="captcha" placeholder="<?php echo $this->lang['rcode']['x030201']; ?>" data-validate class="form-control form-control-lg">
            <small class="form-text" id="msg_captcha"></small>
        </div>

        <div class="bg-submit-box"></div>
        <div class="bg-validator-box"></div>

        <div class="form-group">
            <button type="button" class="btn btn-primary btn-block btn-lg bg-submit"><?php echo $this->lang['mod']['btn']['login']; ?></button>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'login_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        admin_name: {
            len: { min: 1, max: 30 },
            validate: { type: "str", format: "strDigit" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010201']; ?>", too_long: "<?php echo $this->lang['rcode']['x010202']; ?>", format_err: "<?php echo $this->lang['rcode']['x010203']; ?>" }
        },
        admin_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010212']; ?>" }
        },
        captcha: {
            len: { min: 4, max: 4 },
            validate: { type: "ajax", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x030201']; ?>", too_long: "<?php echo $this->lang['rcode']['x030201']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_MISC; ?>index.php?m=captcha&c=request&a=chk", key: "captcha", type: "str" }
        }
    };

    var options_validator_form = {
        msg_global:{
            msg: "<?php echo $this->lang['common']['label']['errInput']; ?>"
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=login&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        },
        jump: {
            url: "<?php echo $this->tplData['forward']; ?>",
            text: "<?php echo $this->lang['mod']['href']['jumping']; ?>"
        }
    };

    $(document).ready(function(){
        $("#admin_name").focus();
        var obj_validator_form  = $("#login_form").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form     = $("#login_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("body").keydown(function(e){
            if(e.keyCode == 13){
                if (obj_validator_form.verify()) {
                    obj_submit_form.formSubmit();
                }
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');