        <?php foreach ($this->phplib as $key=>$value) { ?>
            <div class="form-group">
                <label class="control-label">
                    <?php if (isset($this->lang['phplib'][$key])) {
                        echo $this->lang['phplib'][$key];
                    } else {
                        echo $value;
                    } ?>
                </label>
                <div class="form-control-static">
                    <?php if (in_array($key, $this->tplData['phplibRow'])) { ?>
                        <span class="label label-success"><?php echo $this->lang['mod']['phplib']['installed']; ?></span>
                    <?php } else { ?>
                        <span class="label label-danger"><?php echo $this->lang['mod']['phplib']['notinstalled']; ?></span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>