                <div class="form-group">
                    <label><?php echo $lang->get('Name'); ?></label>
                    <div class="form-text">
                        <?php if (isset($pluginRow['plugin_config']['plugin_url']) && !empty($pluginRow['plugin_config']['plugin_url'])) { ?>
                            <a href="<?php echo $pluginRow['plugin_config']['plugin_url']; ?>" target="_blank">
                                <?php echo $pluginRow['plugin_config']['name']; ?>
                            </a>
                        <?php } else {
                            echo $pluginRow['plugin_config']['name'];
                        } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Class'); ?></label>
                    <div class="form-text">
                        <?php echo $pluginRow['plugin_config']['class']; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Version'); ?></label>
                    <div class="form-text">
                        <?php if (isset($pluginRow['plugin_config']['version'])) {
                            echo $pluginRow['plugin_config']['version'];
                        } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Author'); ?></label>
                    <div class="form-text">
                        <?php if (isset($pluginRow['plugin_config']['author'])) { ?>
                            <?php if (isset($pluginRow['plugin_config']['author_url']) && !empty($pluginRow['plugin_config']['author_url'])) { ?>
                                <a href="<?php echo $pluginRow['plugin_config']['author_url']; ?>" target="_blank">
                                    <?php echo $pluginRow['plugin_config']['author']; ?>
                                </a>
                            <?php } else {
                                echo $pluginRow['plugin_config']['author'];
                            }
                        } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label><?php echo $lang->get('Detail'); ?></label>
                    <p class="bg-content">
                        <?php if (isset($pluginRow['plugin_config']['detail'])) {
                            echo $pluginRow['plugin_config']['detail'];
                        } ?>
                    </p>
                </div>
