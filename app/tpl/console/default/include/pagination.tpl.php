  <?php
  $_lang_pageFirst    = $lang->get('First page', 'console.common');
  $_lang_pagePrevList = $lang->get('Previous ten pages', 'console.common');
  $_lang_pagePrev     = $lang->get('Previous page', 'console.common');
  $_lang_pageNext     = $lang->get('Next page', 'console.common');
  $_lang_pageNextList = $lang->get('Next ten pages', 'console.common');
  $_lang_pageEnd      = $lang->get('End page', 'console.common');

  if (!isset($pageParam)) {
    $pageParam = 'page';
  }

  if (!isset($pageRow)) {
    $pageRow = array(
      'page'          => 1,
      'first'         => 0,
      'final'         => 0,
      'prev'          => 0,
      'next'          => 0,
      'group_begin'   => 1,
      'group_end'     => 1,
      'group_prev'    => 0,
      'group_next'    => 0,
    );
  } ?>

  <ul class="pagination">
    <?php if ($pageRow['first']) { ?>
      <li class="page-item">
        <a href="{:ROUTE_PAGE}<?php echo $pageParam; ?>/<?php echo $pageRow['first']; ?>/" title="<?php echo $_lang_pageFirst; ?>" class="page-link"><?php echo $_lang_pageFirst; ?></a>
      </li>
    <?php }

    if ($pageRow['group_prev']) { ?>
      <li class="page-item d-none d-lg-block">
        <a href="{:ROUTE_PAGE}<?php echo $pageParam; ?>/<?php echo $pageRow['group_prev']; ?>/" title="<?php echo $_lang_pagePrevList; ?>" class="page-link">...</a>
      </li>
    <?php } ?>

    <li class="page-item<?php if (!$pageRow['prev']) { ?> disabled<?php } ?>">
      <?php if ($pageRow['prev']) { ?>
        <a href="{:ROUTE_PAGE}<?php echo $pageParam; ?>/<?php echo $pageRow['prev']; ?>/" title="<?php echo $_lang_pagePrev; ?>" class="page-link"><span class="bg-icon"><?php include($tpl_icon . 'chevron-left' . BG_EXT_SVG); ?></span></a>
      <?php } else { ?>
        <span title="<?php echo $_lang_pagePrev; ?>" class="page-link"><span class="bg-icon"><?php include($tpl_icon . 'chevron-left' . BG_EXT_SVG); ?></span></span>
      <?php } ?>
    </li>

    <?php for ($iii = $pageRow['group_begin']; $iii <= $pageRow['group_end']; ++$iii) { ?>
      <li class="page-item<?php if ($iii == $pageRow['page']) { ?> active<?php } ?> d-none d-lg-block">
        <?php if ($iii == $pageRow['page']) { ?>
          <span class="page-link"><?php echo $iii; ?></span>
        <?php } else { ?>
          <a href="{:ROUTE_PAGE}<?php echo $pageParam; ?>/<?php echo $iii; ?>/" title="<?php echo $iii; ?>" class="page-link"><?php echo $iii; ?></a>
        <?php } ?>
      </li>
    <?php } ?>

    <li class="page-item<?php if (!$pageRow['next']) { ?> disabled<?php } ?>">
      <?php if ($pageRow['next']) { ?>
        <a href="{:ROUTE_PAGE}<?php echo $pageParam; ?>/<?php echo $pageRow['next']; ?>/" title="<?php echo $_lang_pageNext; ?>" class="page-link"><span class="bg-icon"><?php include($tpl_icon . 'chevron-right' . BG_EXT_SVG); ?></span></a>
      <?php } else { ?>
        <span title="<?php echo $_lang_pageNext; ?>" class="page-link"><span class="bg-icon"><?php include($tpl_icon . 'chevron-right' . BG_EXT_SVG); ?></span></span>
      <?php } ?>
    </li>

    <?php if ($pageRow['group_next']) { ?>
      <li class="page-item d-none d-lg-block">
        <a href="{:ROUTE_PAGE}<?php echo $pageParam; ?>/<?php echo $pageRow['group_next']; ?>/" title="<?php echo $_lang_pageNextList; ?>" class="page-link">...</a>
      </li>
    <?php }

    if ($pageRow['final']) { ?>
      <li class="page-item">
        <a href="{:ROUTE_PAGE}<?php echo $pageParam; ?>/<?php echo $pageRow['final']; ?>/" title="<?php echo $_lang_pageEnd; ?>" class="page-link"><?php echo $_lang_pageEnd; ?></a>
      </li>
    <?php } ?>
  </ul>
