<?php function listDetail($detail, $key, $key_class = '', $itemt_class = '') { ?>
    <h5>
        <span class="<?php echo $key_class; ?>" role="no">
            <strong>No. : </strong>
            <?php echo $key; ?>
        </span>
    </h5>
    <?php if (is_array($detail) && !empty($detail)) {
        foreach ($detail as $_key=>$_value) {
            if ($_key == 'args') {
                if (!empty($_value)) { ?>
                    <div role="args_btn">
                        <a data-toggle="collapse" href="#trace-collapse-<?php echo $key; ?>">
                            <strong>args</strong>
                            +
                        </a>
                    </div>
                    <div id="trace-collapse-<?php echo $key; ?>" class="collapse" data-parent="#bg-trace-accordion" role="args_detail">
                        <div class="text-break">
                            <?php if (is_array($_value) && !empty($_value)) {
                                foreach($_value as $_key_sub=>$_value_sub) {
                                    listDetail($_value_sub, $_key_sub, 'badge badge-secondary', 'text-muted');
                                }
                            } ?>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <div class="<?php echo $itemt_class; ?>" role="item">
                    <strong><?php echo $_key; ?> : </strong>
                    <?php echo $_value; ?>
                </div>
            <?php }
        }
    } else { ?>
        <div class="<?php echo $itemt_class; ?>" role="args_item">
            <pre class="pre-scrollable"><code><?php echo htmlentities($detail); ?></code></pre>
        </div>
    <?php } ?>
    <div>&nbsp;</div>
<?php } ?>

<div class="container my-5">
    <div id="bg-trace-accordion" class="bg-trace-accordion">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="trace-tab" data-toggle="tab"  href="#base">
                            <?php echo 'Base'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="backtrace-tab" data-toggle="tab" href="#backtrace">
                            <?php echo 'Debug backtrace'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="files-tab" data-toggle="tab" href="#files">
                            <?php echo 'Include files'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="files-tab" data-toggle="tab" href="#sql">
                            <?php echo 'SQL'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="files-tab" data-toggle="tab" href="#error">
                            <?php echo 'Error'; ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="base">
                        <?php if (isset($trace['base']) && !empty($trace['base'])) {
                            foreach($trace['base'] as $key=>$value) { ?>
                                <div>
                                    <strong><?php echo $key; ?> : </strong>
                                    <?php echo $value; ?>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <div class="tab-pane" id="backtrace">
                        <?php if (isset($trace['backtrace']) && !empty($trace['backtrace'])) {
                            foreach($trace['backtrace'] as $key=>$value) {
                                listDetail($value, $key, 'badge badge-primary');
                            }
                        } ?>
                    </div>
                    <div class="tab-pane" id="files">
                        <?php if (isset($trace['files']) && !empty($trace['files'])) {
                            foreach($trace['files'] as $key=>$value) { ?>
                                <h5>
                                    <span class="badge badge-primary"><?php echo $key; ?></span>
                                    <span class="badge badge-secondary"><?php echo $value['size']; ?></span>
                                </h5>
                                <div class="text-break">
                                    <?php echo $value['path']; ?>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <div class="tab-pane" id="sql">
                        <?php if (isset($trace['sql']) && !empty($trace['sql'])) {
                            foreach($trace['sql'] as $key=>$value) { ?>
                                <h5>
                                    <div class="badge badge-primary"><?php echo $key; ?></span>
                                </h5>
                                <div class="text-break">
                                    <?php echo str_replace(PHP_EOL, '<br>' . PHP_EOL, $value); ?>
                                </div>
                            <?php }
                        } ?>
                    </div>
                    <div class="tab-pane" id="error">
                        <?php if (isset($trace['error']) && !empty($trace['error'])) {
                            foreach($trace['error'] as $key=>$value) {
                                if (isset($value['err_message'])) { ?>
                                    <div class="text-break">
                                        <strong><?php echo 'message'; ?> : </strong>
                                        <?php echo $value['err_message']; ?>
                                    </div>
                                <?php }

                                if (isset($value['err_file'])) { ?>
                                    <div class="text-break">
                                        <strong><?php echo 'file'; ?> : </strong>
                                        <?php echo $value['err_file']; ?>
                                    </div>
                                <?php }

                                if (isset($value['err_line'])) { ?>
                                    <div class="text-break">
                                        <strong><?php echo 'line'; ?> : </strong>
                                        <?php echo $value['err_line']; ?>
                                    </div>
                                <?php }

                                if (isset($value['err_type'])) { ?>
                                    <div class="text-break">
                                        <strong><?php echo 'type'; ?> : </strong>
                                        <?php echo $value['err_type']; ?>
                                    </div>
                                <?php } ?>

                                <div>&nbsp;</div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
