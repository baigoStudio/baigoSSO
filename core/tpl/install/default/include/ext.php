        <?php foreach ($this->type["ext"] as $key=>$value) { ?>
            <div class="form-group">
                <label class="control-label"><?php echo $value; ?></label>
                <div class="form-control-static">
                    <?php if (in_array($key, $this->tplData["extRow"])) { ?>
                        <span class="label label-success bg-label"><?php echo $this->status["ext"]["installed"]; ?></span>
                    <?php } else { ?>
                        <span class="label label-danger bg-label"><?php echo $this->status["ext"]["uninstall"]; ?></span>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>