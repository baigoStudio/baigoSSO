    <div class="col-md-3">
        <div class="well">
            <div class="form-group">
                <label class="control-label"><?php echo $this->lang['mod']['label']['id']; ?></label>
                <div class="form-control-static"><?php echo $this->tplData['adminLogged']['admin_id']; ?></div>
            </div>

            <?php if ($this->tplData['adminLogged']['admin_status'] == 'enable') {
                $css_status = 'success';
            } else {
                $css_status = 'default';
            } ?>

            <div class="form-group">
                <label class="control-label"><?php echo $this->lang['mod']['label']['status']; ?></label>
                <div class="form-control-static">
                    <span class="label label-<?php echo $css_status; ?> bg-label"><?php echo $this->lang['mod']['status'][$this->tplData['adminLogged']['admin_status']]; ?></span>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?php echo $this->lang['mod']['label']['type']; ?></label>
                <div class="form-control-static">
                    <?php echo $this->lang['mod']['type'][$this->tplData['adminLogged']['admin_type']]; ?>
                </div>
            </div>

            <div class="form-group">
                <?php foreach ($this->profile as $_key=>$_value) {
                    if (isset($this->tplData['adminLogged']['admin_allow_profile'][$_key]) && $this->tplData['adminLogged']['admin_allow_profile'][$_key] == 1) { ?>
                        <div>
                            <span class="label label-danger bg-label">
                                <?php echo $this->lang['mod']['label']['forbidModi'];
                                if (isset($this->lang['common']['profile'][$_key]['title'])) {
                                    echo $this->lang['common']['profile'][$_key]['title'];
                                } else {
                                    echo $_value['title'];
                                }; ?>
                            </span>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>