  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link<?php if ($route['act'] == 'admin') { ?> active<?php } ?>" href="<?php echo $hrefRow['admin']; ?>"><?php echo $lang->get('Add administrator'); ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link<?php if ($route['act'] == 'auth') { ?> active<?php } ?>" href="<?php echo $hrefRow['auth']; ?>"><?php echo $lang->get('Authorize as administrator'); ?></a>
    </li>
  </ul>
