<?php $cfg = array(
    'title'          => $this->lang['common']['page']['opt'] . ' &raquo; ' . $this->lang['common']['page']['dbconfig'],
    'menu_active'    => 'opt',
    'sub_active'     => 'dbconfig',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'baigoClear'     => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=opt&a=dbconfig',
);

include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=opt#dbconfig" target="_blank" class="nav-link">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-3">
            <div class="alert alert-warning">
                <?php echo $this->lang['mod']['text']['clear']; ?>
            </div>

            <div class="card bg-light mb-3">
                <div class="card-body">
                    <form name="form_app" id="form_app">
                        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                        <input type="hidden" name="a" id="act_app" value="app_clear">
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" id="btn_app">
                                <span class="oi oi-trash"></span>
                                <?php echo $this->lang['mod']['btn']['dataApp']; ?>
                            </button>
                        </div>
                        <div class="form-group">
                            <div class="progress" id="progress_app">
                                <div class="progress-bar progress-bar-info progress-bar-striped active"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="msg_app">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <form name="opt_dbconfig" id="opt_dbconfig">
                <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
                <input type="hidden" name="a" value="dbconfig">

                <div class="card">
                    <div class="card-body">
                        <?php include($cfg['pathInclude'] . 'dbconfig.php'); ?>

                        <div class="bg-submit-box"></div>
                        <div class="bg-validator-box mt-3"></div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary bg-submit">
                            <?php echo $this->lang['mod']['btn']['save']; ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=opt&c=request",
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    var options_validator_form = {
        msg_global:{
            msg: "<?php echo $this->lang['common']['label']['errInput']; ?>"
        }
    };

    var opts_app = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>index.php?m=opt&c=request",
        confirm: {
            selector: "#act_app",
            val: "app_clear",
            msg: "<?php echo $this->lang['mod']['confirm']['clear']; ?>"
        },
        selector: {
            progress: "#progress_app",
            msg: "#msg_app"
        },
        msg: {
            loading: "<?php echo $this->lang['rcode']['x070407']; ?>",
            complete: "<?php echo $this->lang['rcode']['y070407']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#opt_dbconfig").baigoValidator(opts_validator_form, options_validator_form);
        var obj_submit_form       = $("#opt_dbconfig").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });

        var obj_app  = $("#form_app").baigoClear(opts_app);
        $("#btn_app").click(function(){
            obj_app.clearSubmit();
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php');
