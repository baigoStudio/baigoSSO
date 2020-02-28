<div class="modal-header">
    <?php echo $lang->get('Private message', 'console.common'), ' &raquo; ', $lang->get('Show'); ?>
    <button type="button" class="close" data-dismiss="modal">
        <span>&times;</span>
    </button>
</div>
<div class="modal-body">
    <ul class="list-inline">
        <li class="list-inline-item"><?php echo $lang->get('Sender'); ?>:</li>
        <li class="list-inline-item">
            <a href="<?php echo $route_console; ?>user/show/id/<?php echo $pmRow['pm_from']; ?>/">
                <?php if ($pmRow['pm_from'] == -1) {
                    echo $lang->get('System');
                } else if (isset($pmRow['fromUser']['user_name'])) {
                    echo $pmRow['fromUser']['user_name'];
                } else {
                    echo $lang->get('Unknown');
                } ?>
            </a>
        </li>
        <li class="list-inline-item"><?php echo $lang->get('Recipient'); ?>:</li>
        <li class="list-inline-item">
            <a href="<?php echo $route_console; ?>user/show/id/<?php echo $pmRow['pm_to']; ?>/">
                <?php if (isset($pmRow['toUser']['user_name'])) {
                    echo $pmRow['toUser']['user_name'];
                } else {
                    echo $lang->get('Unknown');
                } ?>
            </a>
        </li>
        <li class="list-inline-item">
            <small><?php echo $pmRow['pm_time_format']['date_time']; ?></small>
        </li>
    </ul>

    <h5><?php echo $pmRow['pm_title']; ?></h5>

    <p class="bg-wrap-enforce"><?php echo $pmRow['pm_content']; ?></p>

    <ul class="list-inline">
        <li class="list-inline-item">
            <?php $str_status = $pmRow['pm_status'];
            include($path_tpl . 'include' . DS . 'status_process' . GK_EXT_TPL); ?>
        </li>
        <li class="list-inline-item">
            <small><?php echo $lang->get($pmRow['pm_type']); ?></small>
        </li>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
        <?php echo $lang->get('Close'); ?>
    </button>
</div>
