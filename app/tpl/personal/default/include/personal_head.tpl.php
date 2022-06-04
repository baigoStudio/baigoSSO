<?php include($tpl_include . 'html_head' . GK_EXT_TPL); ?>

  <div class="container">
    <div class="bg-card-xs my-lg-5 my-3">
      <div class="my-3 text-center">
        <div class="mb-3">
          <img class="d-block mx-auto bg-logo-sm" src="<?php echo $ui_ctrl['logo_personal']; ?>">
        </div>

        <div class="lead">
          <?php if (isset($cfg['title'])) {
            echo $cfg['title'];
          } ?>
        </div>
      </div>
