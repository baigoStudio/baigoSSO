    <?php
    $_lang_pageFirst    = $lang->get('First page', 'console.common');
    $_lang_pagePrevList = $lang->get('Previous ten pages', 'console.common');
    $_lang_pagePrev     = $lang->get('Previous page', 'console.common');
    $_lang_pageNext     = $lang->get('Next page', 'console.common');
    $_lang_pageNextList = $lang->get('Next ten pages', 'console.common');
    $_lang_pageEnd      = $lang->get('End page', 'console.common');

    if (!isset($_str_pageParam)) {
        $_str_pageParam = 'page';
    }
    if (!isset($_arr_pageRow)) {
        $_arr_pageRow = $pageRow;
    } ?>

    <ul class="pagination">
        <?php if ($_arr_pageRow['first']) { ?>
            <li class="page-item">
                <a href="{:ROUTE_PAGE}<?php echo $_str_pageParam; ?>/<?php echo $_arr_pageRow['first']; ?>/" title="<?php echo $_lang_pageFirst; ?>" class="page-link"><?php echo $_lang_pageFirst; ?></a>
            </li>
        <?php }

        if ($_arr_pageRow['group_prev']) { ?>
            <li class="page-item d-none d-lg-block">
                <a href="{:ROUTE_PAGE}<?php echo $_str_pageParam; ?>/<?php echo $_arr_pageRow['group_prev']; ?>/" title="<?php echo $_lang_pagePrevList; ?>" class="page-link">...</a>
            </li>
        <?php } ?>

        <li class="page-item<?php if (!$_arr_pageRow['prev']) { ?> disabled<?php } ?>">
            <?php if ($_arr_pageRow['prev']) { ?>
                <a href="{:ROUTE_PAGE}<?php echo $_str_pageParam; ?>/<?php echo $_arr_pageRow['prev']; ?>/" title="<?php echo $_lang_pagePrev; ?>" class="page-link"><span class="fas fa-chevron-left"></span></a>
            <?php } else { ?>
                <span title="<?php echo $_lang_pagePrev; ?>" class="page-link"><span class="fas fa-chevron-left"></span></span>
            <?php } ?>
        </li>

        <?php for ($iii = $_arr_pageRow['group_begin']; $iii <= $_arr_pageRow['group_end']; ++$iii) { ?>
            <li class="page-item<?php if ($iii == $_arr_pageRow['page']) { ?> active<?php } ?> d-none d-lg-block">
                <?php if ($iii == $_arr_pageRow['page']) { ?>
                    <span class="page-link"><?php echo $iii; ?></span>
                <?php } else { ?>
                    <a href="{:ROUTE_PAGE}<?php echo $_str_pageParam; ?>/<?php echo $iii; ?>/" title="<?php echo $iii; ?>" class="page-link"><?php echo $iii; ?></a>
                <?php } ?>
            </li>
        <?php } ?>

        <li class="page-item<?php if (!$_arr_pageRow['next']) { ?> disabled<?php } ?>">
            <?php if ($_arr_pageRow['next']) { ?>
                <a href="{:ROUTE_PAGE}<?php echo $_str_pageParam; ?>/<?php echo $_arr_pageRow['next']; ?>/" title="<?php echo $_lang_pageNext; ?>" class="page-link"><span class="fas fa-chevron-right"></span></a>
            <?php } else { ?>
                <span title="<?php echo $_lang_pageNext; ?>" class="page-link"><span class="fas fa-chevron-right"></span></span>
            <?php } ?>
        </li>

        <?php if ($_arr_pageRow['group_next']) { ?>
            <li class="page-item d-none d-lg-block">
                <a href="{:ROUTE_PAGE}<?php echo $_str_pageParam; ?>/<?php echo $_arr_pageRow['group_next']; ?>/" title="<?php echo $_lang_pageNextList; ?>" class="page-link">...</a>
            </li>
        <?php }

        if ($_arr_pageRow['final']) { ?>
            <li class="page-item">
                <a href="{:ROUTE_PAGE}<?php echo $_str_pageParam; ?>/<?php echo $_arr_pageRow['final']; ?>/" title="<?php echo $_lang_pageEnd; ?>" class="page-link"><?php echo $_lang_pageEnd; ?></a>
            </li>
        <?php } ?>
    </ul>