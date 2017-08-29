        <?php foreach ($this->ext as $key=>$value) { ?>
            <div class="form-group">
                <label class="control-label">
                    <?php if (isset($this->lang['ext'][$key])) {
                        echo $this->lang['ext'][$key];
                    } else {
                        echo $value;
                    } ?>
                </label>
                <div class="form-control-static">
                    <?php if (in_array($key, $this->tplData["extRow"])) { ?>
                        <span class="label label-success"><?php echo $this->lang['mod']['ext']['installed']; ?></span>
                    <?php } else { ?>
                        <span class="label label-danger"><?php echo $this->lang['mod']['ext']['notinstalled']; ?></span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>