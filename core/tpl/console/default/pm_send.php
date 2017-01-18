<?php $cfg = array(
    "title"          => $this->consoleMod["pm"]["main"]["title"] . " &raquo; " . $this->consoleMod["pm"]["sub"]["send"]["title"],
    "menu_active"    => "pm",
    "sub_active"     => "send",
    "baigoCheckall"  => "true",
    "baigoValidator" => "true",
    "baigoSubmit"    => "true",
    "datetimepicker" => "true",
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/",
    "str_url"        => BG_URL_CONSOLE . "index.php?mod=pm",
); ?>

<?php include($cfg["pathInclude"] . "console_head.php"); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=pm&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang["href"]["back"]; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=pm#form" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang["href"]["help"]; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="pm_send" id="pm_send">
        <input type="hidden" name="<?php echo $this->common["tokenRow"]["name_session"]; ?>" value="<?php echo $this->common["tokenRow"]["token"]; ?>">
        <input type="hidden" name="act" value="send">

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <div id="group_pm_to">
                        <label class="control-label"><?php echo $this->lang["label"]["pmTo"]; ?><span id="msg_pm_to">*</span></label>
                        <input type="text" name="pm_to" id="pm_to" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_pm_title">
                        <label class="control-label"><?php echo $this->lang["label"]["title"]; ?><span id="msg_pm_title"></span></label>
                        <input type="text" name="pm_title" id="pm_title" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_pm_content">
                        <label class="control-label"><?php echo $this->lang["label"]["content"]; ?><span id="msg_pm_content">*</span></label>
                        <textarea name="pm_content" id="pm_content" data-validate class="form-control bg-textarea-md"></textarea>
                    </div>
                </div>

                <div class="bg-submit-box"></div>
            </div>
            <div class="panel-footer">
                <button type="button" class="btn btn-primary bg-submit"><?php echo $this->lang["btn"]["send"]; ?></button>
            </div>
        </div>
    </form>

<?php include($cfg["pathInclude"] . "console_foot.php"); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        pm_title: {
            len: { min: 0, max: 90 },
            validate: { type: "str", format: "text", group: "#group_pm_title" },
            msg: { selector: "#msg_pm_title", too_long: "<?php echo $this->rcode["x110202"]; ?>" }
        },
        pm_content: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_pm_content" },
            msg: { selector: "#msg_pm_content", too_short: "<?php echo $this->rcode["x110201"]; ?>", too_long: "<?php echo $this->rcode["x110203"]; ?>" }
        },
        pm_to: {
            len: { min: 1, max: 0 },
            validate: { type: "ajax", format: "text", group: "#group_pm_to" },
            msg: { selector: "#msg_pm_to", too_short: "<?php echo $this->rcode["x110205"]; ?>", ajaxIng: "<?php echo $this->rcode["x030401"]; ?>", ajax_err: "<?php echo $this->rcode["x030402"]; ?>" },
            ajax: { url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=user&act=getname", key: "user_name", type: "str" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=pm",
        msg_text: {
            submitting: "<?php echo $this->lang["label"]["submitting"]; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form  = $("#pm_send").baigoValidator(opts_validator_form);
        var obj_submit_form     = $("#pm_send").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg["pathInclude"] . "html_foot.php"); ?>
