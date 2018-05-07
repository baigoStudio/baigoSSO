        <?php foreach ($this->phplib as $key=>$value) { ?>
            <div class="form-group">
                <label>
                    <?php if (isset($this->lang['phplib'][$key])) {
                        echo $this->lang['phplib'][$key];
                    } else {
                        echo $value;
                    } ?>
                </label>
                <div class="form-text">
                    <?php if (in_array($key, $this->tplData['phplibRow'])) { ?>
                        <span class="badge badge-success"><?php echo $this->lang['mod']['phplib']['installed']; ?></span>
                    <?php } else { ?>
                        <span class="badge badge-danger"><?php echo $this->lang['mod']['phplib']['notinstalled']; ?></span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>