<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['app']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['show'],
    'menu_active'    => 'app',
    'sub_active'     => 'list',
    'baigoValidator' => 'true',
    'baigoSubmit'    => 'true',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang['common']['href']['back']; ?>
                </a>
            </li>
        </ul>
    </div>

    <form name="app_form" id="app_form">
        <input type="hidden" name="<?php echo $this->common['tokenRow']['name_session']; ?>" value="<?php echo $this->common['tokenRow']['token']; ?>">
        <input type="hidden" name="act" id="act" value="reset">
        <input type="hidden" name="app_id" value="<?php echo $this->tplData['appRow']['app_id']; ?>">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['appName']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['appRow']['app_name']; ?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['apiUrl']; ?></label>
                            <div class="form-control-static"><?php echo BG_SITE_URL . BG_URL_API; ?>api.php</div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['appId']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['appRow']['app_id']; ?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['appKey']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['appRow']['app_key']; ?></div>
                        </div>

                        <div class="bg-submit-box"></div>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary bg-submit">
                                <?php echo $this->lang['mod']['btn']['resetKey']; ?>
                            </button>
                            <span class="help-block"><?php echo $this->lang['mod']['label']['appKeyNote']; ?></span>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['appUrlNotify']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['appRow']['app_url_notify']; ?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['appUrlSync']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['appRow']['app_url_sync']; ?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['allow']; ?></label>
                            <dl class="bg-dl">
                                <?php foreach ($this->tplData['allow'] as $key_m=>$value_m) { ?>
                                    <dt>
                                        <?php if (isset($this->lang['allow'][$key_m]['title'])) {
                                            echo $this->lang['allow'][$key_m]['title'];
                                        } else {
                                            echo $value_m['title'];
                                        } ?>
                                    </dt>
                                    <dd>
                                        <ul class="list-inline">
                                            <?php foreach ($value_m['allow'] as $key_s=>$value_s) { ?>
                                                <li>
                                                    <span class="glyphicon glyphicon-<?php if (isset($this->tplData['appRow']['app_allow'][$key_m][$key_s])) { ?>ok-sign text-success<?php } else { ?>remove-sign text-danger<?php } ?>"></span>
                                                    <?php if (isset($this->lang['allow'][$key_m]['allow'][$key_s])) {
                                                        echo $this->lang['allow'][$key_m]['allow'][$key_s];
                                                    } else {
                                                        echo $value_s;
                                                    } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </dd>
                                <?php } ?>
                            </dl>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['ipAllow']; ?></label>
                            <div class="form-control-static">
                                <pre><?php echo $this->tplData['appRow']['app_ip_allow']; ?></pre>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['ipBad']; ?></label>
                            <div class="form-control-static">
                                <pre><?php echo $this->tplData['appRow']['app_ip_bad']; ?></pre>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo $this->lang['mod']['label']['note']; ?></label>
                            <div class="form-control-static"><?php echo $this->tplData['appRow']['app_note']; ?></div>
                        </div>

                        <div class="form-group">
                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=form&app_id=<?php echo $this->tplData['appRow']['app_id']; ?>">
                                <span class="glyphicon glyphicon-edit"></span>
                                <?php echo $this->lang['mod']['href']['edit']; ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo $this->lang['mod']['label']['belongUser']; ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-nowrap bg-td-xs"><?php echo $this->lang['mod']['label']['id']; ?></th>
                                    <th><?php echo $this->lang['mod']['label']['user']; ?></th>
                                    <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['status']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->tplData['userViews'] as $key=>$value) { ?>
                                    <tr>
                                        <td class="text-nowrap bg-td-xs"><?php echo $value['user_id']; ?></td>
                                        <td>
                                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=user&act=show&user_id=<?php echo $value['user_id']; ?>"><?php echo $value['user_name']; ?></a>
                                        </td>
                                        <td class="text-nowrap bg-td-md">
                                            <?php user_status_process($value['user_status'], $this->lang['mod']['user']); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="well">
                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['id']; ?></label>
                        <div class="form-control-static"><?php echo $this->tplData['appRow']['app_id']; ?></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?></label>
                        <div class="form-control-static">
                            <?php app_status_process($this->tplData['appRow']['app_status'], $this->lang['mod']['status']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['sync']; ?></label>
                        <div class="form-control-static">
                            <?php app_sync_process($this->tplData['appRow']['app_sync'], $this->lang['mod']['sync']); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=form&app_id=<?php echo $this->tplData['appRow']['app_id']; ?>">
                            <span class="glyphicon glyphicon-edit"></span>
                            <?php echo $this->lang['mod']['href']['edit']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form>

<?php include($cfg['pathInclude'] . 'console_foot.php'); ?>

    <script type="text/javascript">
    var opts_submit_form = {
        ajax_url: "<?php echo BG_URL_CONSOLE; ?>request.php?mod=app",
        confirm: {
            selector: "#act",
            val: "reset",
            msg: "<?php echo $this->lang['mod']['confirm']['resetKey']; ?>"
        },
        msg_text: {
            submitting: "<?php echo $this->lang['common']['label']['submitting']; ?>"
        }
    };

    $(document).ready(function(){
        var obj_submit_form = $("#app_form").baigoSubmit(opts_submit_form);
        $(".bg-submit").click(function(){
            obj_submit_form.formSubmit();
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot.php'); ?>
