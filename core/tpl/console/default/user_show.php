<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['user']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['show'],
    'menu_active'    => 'user',
    'sub_active'     => 'list',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?mod=user',
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <div class="form-group">
        <ul class="nav nav-pills bg-nav-pills">
            <li>
                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=user&act=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <?php echo $this->lang['common']['href']['back']; ?>
                </a>
            </li>
            <li>
                <a href="<?php echo BG_URL_HELP; ?>index.php?mod=console&act=user" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <?php echo $this->lang['mod']['href']['help']; ?>
                </a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['username']; ?><span id="msg_user_name">*</span></label>
                        <div class="form-control-static"><?php echo $this->tplData['userRow']['user_name']; ?></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['mail']; ?></label>
                        <div class="form-control-static"><?php echo $this->tplData['userRow']['user_mail']; ?></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['nick']; ?></label>
                        <div class="form-control-static"><?php echo $this->tplData['userRow']['user_nick']; ?></div>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['note']; ?></label>
                        <div class="form-control-static"><?php echo $this->tplData['userRow']['user_note']; ?></div>
                    </div>

                    <div class="form-group">
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=user&act=form&user_id=<?php echo $this->tplData['userRow']['user_id']; ?>">
                            <span class="glyphicon glyphicon-edit"></span>
                            <?php echo $this->lang['mod']['href']['edit']; ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $this->lang['mod']['label']['belongApp']; ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-nowrap bg-td-xs"><?php echo $this->lang['mod']['label']['id']; ?></th>
                                <th><?php echo $this->lang['mod']['label']['app']; ?></th>
                                <th class="text-nowrap bg-td-md"><?php echo $this->lang['mod']['label']['status']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->tplData['appRows'] as $key=>$value) { ?>
                                <tr>
                                    <td class="text-nowrap bg-td-xs"><?php echo $value['app_id']; ?></td>
                                    <td>
                                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=show&app_id=<?php echo $value['app_id']; ?>"><?php echo $value['app_name']; ?></a>
                                    </td>
                                    <td class="text-nowrap bg-td-md">
                                        <?php app_status_process($value['app_status'], $this->lang['mod']['appStatus']); ?>
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
                    <div class="form-control-static"><?php echo $this->tplData['userRow']['user_id']; ?></div>
                </div>

                <div class="form-group">
                    <div id="group_user_status">
                        <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?></label>
                        <div class="form-control-static">
                            <?php user_status_process($this->tplData['userRow']['user_status'], $this->lang['mod']['status']); ?>
                        </div>
                    </div>
                </div>

                <?php if (is_array($this->tplData['userRow']['user_contact']) && !fn_isEmpty($this->tplData['userRow']['user_contact'])) {
                    foreach ($this->tplData['userRow']['user_contact'] as $key=>$value) { ?>
                        <div class="form-group">
                            <div id="group_user_contact_<?php echo $key; ?>">
                                <label class="control-label">
                                    <?php if (isset($value['key'])) { echo $value['key']; } ?>
                                </label>
                                <div class="form-control-static"><?php if (isset($value['value'])) { echo $value['value']; } ?></div>
                            </div>
                        </div>
                    <?php }
                }

                if (is_array($this->tplData['userRow']['user_extend']) &&!fn_isEmpty($this->tplData['userRow']['user_extend'])) {
                    foreach ($this->tplData['userRow']['user_extend'] as $key=>$value) { ?>
                        <div class="form-group">
                            <div id="group_user_extend_<?php echo $key; ?>">
                                <label class="control-label">
                                    <?php if (isset($value['key'])) { echo $value['key']; } ?>
                                </label>
                                <div class="form-control-static"><?php if (isset($value['value'])) { echo $value['value']; } ?></div>
                            </div>
                        </div>
                    <?php }
                } ?>

                <div class="form-group">
                    <label class="control-label"><?php echo $this->lang['mod']['label']['fromApp']; ?></label>
                    <div class="form-control-static">
                        <?php if (isset($this->tplData['appRow']['app_name'])) { ?>
                            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=app&act=show&app_id=<?php echo $this->tplData['userRow']['user_app_id']; ?>">
                                <?php echo $this->tplData['appRow']['app_name']; ?>
                            </a>
                        <?php } else {
                            echo $this->lang['mod']['label']['unknown'];
                        } ?>
                    </div>
                </div>

                <div class="form-group">
                    <a href="<?php echo BG_URL_CONSOLE; ?>index.php?mod=user&act=form&user_id=<?php echo $this->tplData['userRow']['user_id']; ?>">
                        <span class="glyphicon glyphicon-edit"></span>
                        <?php echo $this->lang['mod']['href']['edit']; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php');
include($cfg['pathInclude'] . 'html_foot.php'); ?>
