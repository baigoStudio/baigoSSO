<?php if ($this->tplData['appRow']['app_id'] < 1) {
    $title_sub  = $this->lang['mod']['page']['add'];
    $str_sub    = 'form';
} else {
    $title_sub  = $this->lang['mod']['page']['edit'];
    $str_sub    = 'list';
}

$cfg = array(
    'title'          => $this->lang['consoleMod']['app']['main']['title'] . ' &raquo; ' . $title_sub,
    'menu_active'    => 'app',
    'sub_active'     => $str_sub,
    'baigoCheckall'  => 'true',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?mod=app'
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang['common']['href']['back']; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=app#form" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang['mod']['href']['help']; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="app_form" id="app_form">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="act" value="submit">
        <input type="hidden" name="app_id" value="<?php echo $this->tplData['appRow']['app_id']; ?>">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <div id="group_app_name">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['appName']; ?><span id="msg_app_name">*</span></label>
                                <input type="text" name="app_name" id="app_name" value="<?php echo $this->tplData['appRow']['app_name']; ?>" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_app_url_notify">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['appUrlNotify']; ?><span id="msg_app_url_notify">*</span></label>
                                <input type="text" name="app_url_notify" id="app_url_notify" value="<?php echo $this->tplData['appRow']['app_url_notify']; ?>" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_app_url_sync">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['appUrlSync']; ?><span id="msg_app_url_sync">*</span></label>
                                <input type="text" name="app_url_sync" id="app_url_sync" value="<?php echo $this->tplData['appRow']['app_url_sync']; ?>" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['allow']; ?><span id="msg_app_allow">*</span></label>
                            <dl class="bg-dl">
                                <dd>
                                    <div class="bg-checkbox">
                                        <label for="chk_all">
                                            <input type="checkbox" id="chk_all" data-parent="first">
                                            <?php echo $this->lang['mod']['label']['all']; ?>
                                        </label>
                                    </div>
                                </dd>
                                <?php foreach ($this->tplData['allow'] as $key_m=>$value_m) { ?>
                                    <dt>
                                        <?php if (isset($this->lang['allow'][$key_m]['title'])) {
                                            echo $this->lang['allow'][$key_m]['title'];
                                        } else {
                                            echo $value_m['title'];
                                        } ?>
                                    </dt>
                                    <dd>
                                        <label for="allow_<?php echo $key_m; ?>" class="checkbox-inline">
                                            <input type="checkbox" id="allow_<?php echo $key_m; ?>" data-parent="chk_all">
                                            <?php echo $this->lang['mod']['label']['all']; ?>
                                        </label>
                                        <?php foreach ($value_m['allow'] as $key_s=>$value_s) { ?>
                                            <label for="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" class="checkbox-inline">
                                                <input type="checkbox" name="app_allow[<?php echo $key_m; ?>][<?php echo $key_s; ?>]" value="1" id="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" data-parent="allow_<?php echo $key_m; ?>" <?php if (isset($this->tplData['appRow']['app_allow'][$key_m][$key_s])) { ?>checked<?php } ?>>
                                                <?php if (isset($this->lang['allow'][$key_m]['allow'][$key_s])) {
                                                    echo $this->lang['allow'][$key_m]['allow'][$key_s];
                                                } else {
                                                    echo $value_s;
                                                } ?>
                                            </label>
                                        <?php } ?>
                                    </dd>
                                <?php } ?>
                            </dl>
                        </div>

                        <div class="form-group">
                            <div id="group_app_ip_allow">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['ipAllow']; ?><span id="msg_app_ip_allow"></span></label>
                                <textarea name="app_ip_allow" id="app_ip_allow" data-validate class="form-control bg-textarea-md"><?php echo $this->tplData['appRow']['app_ip_allow']; ?></textarea>
                            </div>
                            <span class="help-block"><?php echo $this->lang['mod']['label']['ipNote']; ?></span>
                        </div>

                        <div class="form-group">
                            <div id="group_app_ip_bad">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['ipBad']; ?><span id="msg_app_ip_bad"></span></label>
                                <textarea name="app_ip_bad" id="app_ip_bad" data-validate class="form-control bg-textarea-md"><?php echo $this->tplData['appRow']['app_ip_bad']; ?></textarea>
                            </div>
                            <span class="help-block"><?php echo $this->lang['mod']['label']['ipNote']; ?></span>
                        </div>

                        <div class="form-group">
                            <div id="group_app_note">
                                <label class="control-label"><?php echo $this->lang['mod']['label']['note']; ?><span id="msg_app_note"></span></label>
                                <input type="text" name="app_note" id="app_note" value="<?php echo $this->tplData['appRow']['app_note']; ?>" data-validate class="form-control">
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
                    <?php if ($this->tplData['appRow']['app_id'] > 0) { ?>
                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['id']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['appRow']['app_id']; ?></div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div id="group_app_status">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?><span id="msg_app_status">*</span></label>
                            <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                                <div class="bg-radio">
                                    <label for="app_status_<?php echo $value; ?>">
                                        <input type="radio" name="app_status" id="app_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['appRow']['app_status'] == $value) { ?>checked<?php } ?> data-validate="app_status">
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
                        <div id="group_app_sync">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['sync']; ?><span id="msg_app_sync">*</span></label>
                            <?php foreach ($this->tplData['sync'] as $key=>$value) { ?>
                                <div class="bg-radio">
                                    <label for="app_sync_<?php echo $value; ?>">
                                        <input type="radio" name="app_sync" id="app_sync_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['appRow']['app_sync'] == $value) { ?>checked<?php } ?> data-validate="app_sync">
                                        <?php if (isset($this->lang['mod']['sync'][$value])) {
                                            echo $this->lang['mod']['sync'][$value];
                                        } else {
                                            echo $value;
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_validator_form = {
        app_name: {
            len: { min: 1, max: 30 },
            validate: { type: "str", format: "text", group: "#group_app_name" },
            msg: { selector: "#msg_app_name", too_short: "<?php echo $this->lang['rcode']['x050201']; ?>", too_long: "<?php echo $this->lang['rcode']['x050202']; ?>" }
        },
        app_url_notify: {
            len: { min: 1, max: 3000 },
            validate: { type: "str", format: "url", group: "#group_app_url_notify" },
            msg: { selector: "#msg_app_url_notify", too_short: "<?php echo $this->lang['rcode']['x050207']; ?>", too_long: "<?php echo $this->lang['rcode']['x050208']; ?>", format_err: "<?php echo $this->lang['rcode']['x050209']; ?>" }
        },
        app_url_sync: {
            len: { min: 1, max: 3000 },
            validate: { type: "str", format: "url", group: "#group_app_url_sync" },
            msg: { selector: "#msg_app_url_sync", too_short: "<?php echo $this->lang['rcode']['x050219']; ?>", too_long: "<?php echo $this->lang['rcode']['x050220']; ?>", format_err: "<?php echo $this->lang['rcode']['x050221']; ?>" }
        },
        app_ip_allow: {
            len: { min: 0, max: 3000 },
            validate: { type: "str", format: "text", group: "#group_app_ip_allow" },
            msg: { selector: "#msg_app_ip_allow", too_long: "<?php echo $this->lang['rcode']['x050210']; ?>" }
        },
        app_ip_bad: {
            len: { min: 0, max: 3000 },
            validate: { type: "str", format: "text", group: "#group_app_ip_bad" },
            msg: { selector: "#msg_app_ip_bad", too_long: "<?php echo $this->lang['rcode']['x050211']; ?>" }
        },
        app_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_app_note" },
            msg: { selector: "#msg_app_note", too_long: "<?php echo $this->lang['rcode']['x050205']; ?>" }
        },
        app_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='app_status']", type: "radio", group: "#group_app_status" },
            msg: { selector: "#msg_app_status", too_few: "<?php echo $this->lang['rcode']['x050206']; ?>" }
        },
        app_sync: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='app_sync']", type: "radio", group: "#group_app_sync" },
            msg: { selector: "#msg_app_sync", too_few: "<?php echo $this->lang['rcode']['x050218']; ?>" }
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=app",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#app_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#app_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#app_form").baigoCheckall();
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>
