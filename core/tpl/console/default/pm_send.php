<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['pm']['main']['title'] . ' &raquo; ' . $this->lang['consoleMod']['pm']['sub']['send'],
    'menu_active'    => "pm",
    'sub_active'     => "send",
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    //'datetimepicker' => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=pm',
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills">
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
    </div>

    <form name="pm_send" id="pm_send">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="send">

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label><?php echo $this->lang['mod']['label']['pmTo']; ?> <span class="text-danger">*</span></label>
                    <input type="text" name="pm_to" id="pm_to" data-validate class="form-control">
                    <small class="form-text" id="msg_pm_to"></small>
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
                <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang['mod']['btn']['send']; ?></button>
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
        pm_to: {
            len: { min: 1, max: 0 },
            validate: { type: "ajax", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x110205']; ?>", ajaxIng: "<?php echo $this->lang['rcode']['x030401']; ?>", ajax_err: "<?php echo $this->lang['rcode']['x030402']; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=user&c=request&a=readname", key: "user_name", type: "str" }
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

    $(document).ready(function(){
        var obj_validator_form  = $("#pm_send").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form     = $("#pm_send").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
