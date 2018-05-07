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
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=app'
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=list" class="nav-link">
                <span class="oi oi-chevron-left"></span>
                <?php echo $this->lang['common']['href']['back']; ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=app#form" target="_blank" class="nav-link">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <form name="app_form" id="app_form">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="a" value="submit">
        <input type="hidden" name="app_id" value="<?php echo $this->tplData['appRow']['app_id']; ?>">

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['appName']; ?> <span class="text-danger">*</span></label>
                            <input type="text" name="app_name" id="app_name" value="<?php echo $this->tplData['appRow']['app_name']; ?>" data-validate class="form-control">
                            <small class="form-text" id="msg_app_name"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['appUrlNotify']; ?> <span class="text-danger">*</span></label>
                            <input type="text" name="app_url_notify" id="app_url_notify" value="<?php echo $this->tplData['appRow']['app_url_notify']; ?>" data-validate class="form-control">
                            <small class="form-text" id="msg_app_url_notify"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['appUrlSync']; ?> <span class="text-danger">*</span></label>
                            <input type="text" name="app_url_sync" id="app_url_sync" value="<?php echo $this->tplData['appRow']['app_url_sync']; ?>" data-validate class="form-control">
                            <small class="form-text" id="msg_app_url_sync"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['allow']; ?> <span class="text-danger">*</span></label>
                            <dl>
                                <dd>
                                    <div class="form-check">
                                        <label for="chk_all" class="form-check-label">
                                            <input type="checkbox" id="chk_all" data-parent="first" class="form-check-input">
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
                                        <div class="form-check form-check-inline">
                                            <label for="allow_<?php echo $key_m; ?>" class="form-check-label">
                                                <input type="checkbox" id="allow_<?php echo $key_m; ?>" data-parent="chk_all" class="form-check-input">
                                                <?php echo $this->lang['mod']['label']['all']; ?>
                                            </label>
                                        </div>
                                        <?php foreach ($value_m['allow'] as $key_s=>$value_s) { ?>
                                            <div class="form-check form-check-inline">
                                                <label for="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" class="form-check-label">
                                                    <input type="checkbox" name="app_allow[<?php echo $key_m; ?>][<?php echo $key_s; ?>]" value="1" id="allow_<?php echo $key_m; ?>_<?php echo $key_s; ?>" data-parent="allow_<?php echo $key_m; ?>" <?php if (isset($this->tplData['appRow']['app_allow'][$key_m][$key_s])) { ?>checked<?php } ?> class="form-check-input">
                                                    <?php if (isset($this->lang['allow'][$key_m]['allow'][$key_s])) {
                                                        echo $this->lang['allow'][$key_m]['allow'][$key_s];
                                                    } else {
                                                        echo $value_s;
                                                    } ?>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </dd>
                                <?php } ?>
                            </dl>
                            <small class="form-text" id="msg_app_allow"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['ipAllow']; ?></label>
                            <textarea name="app_ip_allow" id="app_ip_allow" data-validate class="form-control bg-textarea-md"><?php echo $this->tplData['appRow']['app_ip_allow']; ?></textarea>
                            <small class="form-text"><?php echo $this->lang['mod']['label']['ipNote']; ?></small>
                            <small class="form-text" id="msg_app_ip_allow"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['ipBad']; ?></label>
                            <textarea name="app_ip_bad" id="app_ip_bad" data-validate class="form-control bg-textarea-md"><?php echo $this->tplData['appRow']['app_ip_bad']; ?></textarea>
                            <small class="form-text"><?php echo $this->lang['mod']['label']['ipNote']; ?></small>
                            <small class="form-text" id="msg_app_ip_bad"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['note']; ?></label>
                            <input type="text" name="app_note" id="app_note" value="<?php echo $this->tplData['appRow']['app_note']; ?>" data-validate class="form-control">
                            <small class="form-text" id="msg_app_note"></small>
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

            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <?php if ($this->tplData['appRow']['app_id'] > 0) { ?>
                            <div class="form-group">
                                <label><?php echo $this->lang['mod']['label']['id']; ?></label>
                                <div class="form-text"><?php echo $this->tplData['appRow']['app_id']; ?></div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['status']; ?> <span class="text-danger">*</span></label>
                            <?php foreach ($this->tplData['status'] as $key=>$value) { ?>
                                <div class="form-check">
                                    <label for="app_status_<?php echo $value; ?>" class="form-check-label">
                                        <input type="radio" name="app_status" id="app_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['appRow']['app_status'] == $value) { ?>checked<?php } ?> data-validate="app_status" class="form-check-input">
                                        <?php if (isset($this->lang['mod']['status'][$value])) {
                                            echo $this->lang['mod']['status'][$value];
                                        } else {
                                            echo $value;
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                            <small class="form-text" id="msg_app_status"></small>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang['mod']['label']['sync']; ?> <span class="text-danger">*</span></label>
                            <?php foreach ($this->tplData['sync'] as $key=>$value) { ?>
                                <div class="form-check">
                                    <label for="app_sync_<?php echo $value; ?>" class="form-check-label">
                                        <input type="radio" name="app_sync" id="app_sync_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($this->tplData['appRow']['app_sync'] == $value) { ?>checked<?php } ?> data-validate="app_sync" class="form-check-input">
                                        <?php if (isset($this->lang['mod']['sync'][$value])) {
                                            echo $this->lang['mod']['sync'][$value];
                                        } else {
                                            echo $value;
                                        } ?>
                                    </label>
                                </div>
                            <?php } ?>
                            <small class="form-text" id="msg_app_sync"></small>
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
            validate: { type: "str", format: "text" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x050201']; ?>", too_long: "<?php echo $this->lang['rcode']['x050202']; ?>" }
        },
        app_url_notify: {
            len: { min: 1, max: 3000 },
            validate: { type: "str", format: "url" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x050207']; ?>", too_long: "<?php echo $this->lang['rcode']['x050208']; ?>", format_err: "<?php echo $this->lang['rcode']['x050209']; ?>" }
        },
        app_url_sync: {
            len: { min: 1, max: 3000 },
            validate: { type: "str", format: "url" },
            msg: { too_short: "<?php echo $this->lang['rcode']['x050219']; ?>", too_long: "<?php echo $this->lang['rcode']['x050220']; ?>", format_err: "<?php echo $this->lang['rcode']['x050221']; ?>" }
        },
        app_ip_allow: {
            len: { min: 0, max: 3000 },
            validate: { type: "str", format: "text" },
            msg: { too_long: "<?php echo $this->lang['rcode']['x050210']; ?>" }
        },
        app_ip_bad: {
            len: { min: 0, max: 3000 },
            validate: { type: "str", format: "text" },
            msg: { too_long: "<?php echo $this->lang['rcode']['x050211']; ?>" }
        },
        app_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text" },
            msg: { too_long: "<?php echo $this->lang['rcode']['x050205']; ?>" }
        },
        app_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='app_status']", type: "radio" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x050206']; ?>" }
        },
        app_sync: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='app_sync']", type: "radio" },
            msg: { too_few: "<?php echo $this->lang['rcode']['x050218']; ?>" }
        }
    };

    var options_validator_form = {
        msg_global:{
            msg: "<?php echo $this->lang['common']['label']['errInput']; ?>"
        }
    };

    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=app&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#app_form").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form       = $("#app_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#app_form").baigoCheckall();
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
