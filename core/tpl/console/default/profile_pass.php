<?php if (isset($this->lang['common']['profile']['pass']['title'])) {
    $title_sub = $this->lang['common']['profile']['pass']['title'];
} else {
    $title_sub = $this->profile['pass']['title'];
}

$cfg = array(
    'title'          => $this->lang['mod']['page']['profile'] . ' &raquo; ' . $title_sub,
    'menu_active'    => "profile",
    'sub_active'     => "pass",
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=profile&a=info',
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php');

    include($cfg['pathInclude'] . 'profile_menu.php'); ?>

    <form name="profile_form" id="profile_form" autocomplete="off">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="pass">

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['username']; ?></label>
                            <input type="text" value="<?php echo $this->tplData['adminLogged']['admin_name']; ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['passOld']; ?> <span class="text-danger">*</span></label>
                            <input type="password" name="admin_pass" id="admin_pass" data-validate class="form-control">
                            <small class="form-text" id="msg_admin_pass"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['passNew']; ?> <span class="text-danger">*</span></label>
                            <input type="password" name="admin_pass_new" id="admin_pass_new" data-validate class="form-control">
                            <small class="form-text" id="msg_admin_pass_new"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['passConfirm']; ?> <span class="text-danger">*</span></label>
                            <input type="password" name="admin_pass_confirm" id="admin_pass_confirm" data-validate class="form-control">
                            <small class="form-text" id="msg_admin_pass_confirm"></small>
                        </div>

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
        admin_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010243']; ?>" }
        },
        admin_pass_new: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010222']; ?>" }
        },
        admin_pass_confirm: {
            len: { min: 1, max: 0 },
            validate: { type: "confirm", target: "#admin_pass_new" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x010224']; ?>", not_match: "<?php echo $this->lang['rcode']['x010225']; ?>" }
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
        var obj_validator_form    = $("#profile_form").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form       = $("#profile_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
