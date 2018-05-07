<?php $cfg = array(
    'title'          => $this->lang['consoleMod']['user']['main']['title'] . ' &raquo; ' . $this->lang['mod']['page']['show'],
    'menu_active'    => 'user',
    'sub_active'     => 'list',
    'pathInclude'    => BG_PATH_TPLSYS . 'console' . DS . 'default' . DS . 'include' . DS,
    'str_url'        => BG_URL_CONSOLE . 'index.php?m=user',
);

include($cfg['pathInclude'] . 'function.php');
include($cfg['pathInclude'] . 'console_head.php'); ?>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=list" class="nav-link">
                <span class="oi oi-chevron-left"></span>
                <?php echo $this->lang['common']['href']['back']; ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo BG_URL_HELP; ?>index.php?m=console&a=user" target="_blank" class="nav-link">
                <span class="badge badge-pill badge-primary">
                    <span class="oi oi-question-mark"></span>
                </span>
                <?php echo $this->lang['mod']['href']['help']; ?>
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['username']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['userRow']['user_name']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['mail']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['userRow']['user_mail']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['nick']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['userRow']['user_nick']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['note']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['userRow']['user_note']; ?></div>
                    </div>

                    <div class="form-group">
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=form&user_id=<?php echo $this->tplData['userRow']['user_id']; ?>">
                            <span class="oi oi-pencil"></span>
                            <?php echo $this->lang['mod']['href']['edit']; ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
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
                                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=show&app_id=<?php echo $value['app_id']; ?>"><?php echo $value['app_name']; ?></a>
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
            <div class="card bg-light">
                <div class="card-body">
                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['id']; ?></label>
                        <div class="form-text"><?php echo $this->tplData['userRow']['user_id']; ?></div>
                    </div>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['status']; ?></label>
                        <div class="form-text">
                            <?php user_status_process($this->tplData['userRow']['user_status'], $this->lang['mod']['status']); ?>
                        </div>
                    </div>

                    <?php if (is_array($this->tplData['userRow']['user_contact']) && !fn_isEmpty($this->tplData['userRow']['user_contact'])) {
                        foreach ($this->tplData['userRow']['user_contact'] as $key=>$value) { ?>
                            <div class="form-group">
                                <label>
                                    <?php if (isset($value['key'])) { echo $value['key']; } ?>
                                </label>
                                <div class="form-text"><?php if (isset($value['value'])) { echo $value['value']; } ?></div>
                            </div>
                        <?php }
                    }

                    if (is_array($this->tplData['userRow']['user_extend']) &&!fn_isEmpty($this->tplData['userRow']['user_extend'])) {
                        foreach ($this->tplData['userRow']['user_extend'] as $key=>$value) { ?>
                            <div class="form-group">
                                <label>
                                    <?php if (isset($value['key'])) { echo $value['key']; } ?>
                                </label>
                                <div class="form-text"><?php if (isset($value['value'])) { echo $value['value']; } ?></div>
                            </div>
                        <?php }
                    } ?>

                    <div class="form-group">
                        <label><?php echo $this->lang['mod']['label']['fromApp']; ?></label>
                        <div class="form-text">
                            <?php if (isset($this->tplData['appRow']['app_name'])) { ?>
                                <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=app&a=show&app_id=<?php echo $this->tplData['userRow']['user_app_id']; ?>">
                                    <?php echo $this->tplData['appRow']['app_name']; ?>
                                </a>
                            <?php } else {
                                echo $this->lang['mod']['label']['unknown'];
                            } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="<?php echo BG_URL_CONSOLE; ?>index.php?m=user&a=form&user_id=<?php echo $this->tplData['userRow']['user_id']; ?>">
                            <span class="oi oi-pencil"></span>
                            <?php echo $this->lang['mod']['href']['edit']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot.php');
include($cfg['pathInclude'] . 'html_foot.php');
