<?php $cfg = array(
    'title'         => $lang->get('Error', 'personal.common'),
    'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'personal_head' . GK_EXT_TPL); ?>

    <nav class="nav mb-3">
        <a href="javascript:history.go(-1);" class="nav-link">
            <span class="fas fa-chevron-left"></span>
            <?php echo $lang->get('Back', 'personal.common'); ?>
        </a>
    </nav>

    <div class="card">
        <div class="card-body">
            <h3 class="text-danger">
                <span class="fas fa-times-circle"></span>
                <?php if (isset($msg)) {
                    echo $lang->get($msg);
                } ?>
            </h3>

            <div class="text-danger lead">
                <?php if (isset($rcode)) {
                    echo $rcode;
                } ?>
            </div>
            <?php if (isset($rcode)) { ?>
                <hr>
                <div>
                    <?php echo $lang->get($rcode, '', '', false); ?>
                </div>
            <?php } ?>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'personal_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
