<?php $cfg = array(
  'title'             => $lang->get('Sync combine', 'console.common'),
  'menu_active'       => 'app',
  'sub_active'        => 'combine',
  'baigoValidate'     => 'true',
  'baigoSubmit'       => 'true',
  'baigoCheckall'     => 'true',
  'baigoQuery'        => 'true',
  'baigoDialog'       => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <div class="d-flex justify-content-between">
    <nav class="nav mb-3">
      <a href="#modal_nm" data-toggle="modal" data-href="<?php echo $hrefRow['add']; ?>" class="nav-link">
        <span class="bg-icon"><?php include($tpl_icon . 'plus' . BG_EXT_SVG); ?></span>
        <?php echo $lang->get('Add'); ?>
      </a>
    </nav>
    <form name="combine_search" id="combine_search" class="d-none d-lg-inline-block" action="<?php echo $hrefRow['index']; ?>">
      <div class="input-group mb-3">
        <input type="text" name="key" value="<?php echo $search['key']; ?>" placeholder="<?php echo $lang->get('Keyword'); ?>" class="form-control">
        <span class="input-group-append">
          <button class="btn btn-outline-secondary" type="submit">
            <span class="bg-icon"><?php include($tpl_icon . 'search' . BG_EXT_SVG); ?></span>
          </button>
        </span>
      </div>
    </form>
  </div>

  <?php if (!empty($search['key'])) { ?>
    <div class="mb-3 text-right">
      <?php if (!empty($search['key'])) { ?>
        <span class="badge badge-info">
          <?php echo $lang->get('Keyword'); ?>:
          <?php echo $search['key']; ?>
        </span>
      <?php } ?>

      <a href="<?php echo $hrefRow['index']; ?>" class="badge badge-danger badge-pill">
        <span class="bg-icon"><?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?></span>
        <?php echo $lang->get('Reset'); ?>
      </a>
    </div>
  <?php } ?>

  <form name="combine_list" id="combine_list" action="<?php echo $hrefRow['delete']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="table-responsive">
      <table class="table table-striped border bg-white">
        <thead>
          <tr>
            <th class="text-nowrap bg-td-xs">
              <div class="form-check">
                <input type="checkbox" name="chk_all" id="chk_all" data-parent="first" class="form-check-input">
                <label for="chk_all" class="form-check-label">
                  <small><?php echo $lang->get('ID'); ?></small>
                </label>
              </div>
            </th>
            <th>
              <?php echo $lang->get('Sync combine'); ?>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($combineRows as $key=>$value) { ?>
            <tr class="bg-manage-tr">
              <td class="text-nowrap bg-td-xs">
                <div class="form-check">
                  <input type="checkbox" name="combine_ids[]" value="<?php echo $value['combine_id']; ?>" id="combine_id_<?php echo $value['combine_id']; ?>" data-parent="chk_all" data-validate="combine_ids" class="form-check-input combine_id">
                  <label for="combine_id_<?php echo $value['combine_id']; ?>" class="form-check-label">
                    <small><?php echo $value['combine_id']; ?></small>
                  </label>
                </div>
              </td>
              <td>
                <div class="mb-2 text-wrap text-break">
                  <a href="#modal_nm" data-toggle="modal" data-href="<?php echo $hrefRow['edit'], $value['combine_id']; ?>">
                    <?php echo $value['combine_name']; ?>
                  </a>
                </div>
                <div class="bg-manage-menu">
                  <div class="d-flex flex-wrap">
                    <a href="#modal_nm" data-toggle="modal" data-href="<?php echo $hrefRow['edit'], $value['combine_id']; ?>" class="mr-2">
                      <span class="bg-icon"><?php include($tpl_icon . 'edit' . BG_EXT_SVG); ?></span>
                      <?php echo $lang->get('Edit'); ?>
                    </a>
                    <a href="<?php echo $hrefRow['combine_belong'], $value['combine_id']; ?>" class="mr-2">
                      <span class="bg-icon"><?php include($tpl_icon . 'check-circle' . BG_EXT_SVG); ?></span>
                      <?php echo $lang->get('Choose Apps'); ?>
                    </a>
                    <a href="javascript:void(0);" data-id="<?php echo $value['combine_id']; ?>" class="combine_delete text-danger">
                      <span class="bg-icon"><?php include($tpl_icon . 'trash-alt' . BG_EXT_SVG); ?></span>
                      <?php echo $lang->get('Delete'); ?>
                    </a>
                  </div>
                </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div class="mb-3">
      <small class="form-text" id="msg_combine_ids"></small>
    </div>

    <div class="clearfix">
      <div class="float-left">
        <button type="submit" class="btn btn-primary">
          <?php echo $lang->get('Delete'); ?>
        </button>
      </div>
      <div class="float-right">
        <?php include($tpl_include . 'pagination' . GK_EXT_TPL); ?>
      </div>
    </div>
  </form>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL);

  include($tpl_include . 'modal_nm' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_list = {
    rules: {
      combine_ids: {
        checkbox: '1'
      },
      act: {
        require: true
      }
    },
    attr_names: {
      combine_ids: '<?php echo $lang->get('Sync combine'); ?>',
      act: '<?php echo $lang->get('Action'); ?>'
    },
    type_msg: {
      require: '<?php echo $lang->get('Choose at least one {:attr}'); ?>',
      checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
    },
    selector_types: {
      combine_ids: 'validate'
    }
  };

  $(document).ready(function(){
    var obj_dialog          = $.baigoDialog(opts_dialog);
    var obj_validate_list   = $('#combine_list').baigoValidate(opts_validate_list);
    var obj_submit_list     = $('#combine_list').baigoSubmit(opts_submit);

    //console.log(obj_submit_list);

    $('#combine_list').submit(function(){
      if (obj_validate_list.verify()) {
        obj_dialog.confirm('<?php echo $lang->get('Are you sure to delete?'); ?>', function(result){
          if (result) {
            obj_submit_list.formSubmit();
          }
        });
      }
    });

    $('.combine_delete').click(function(){
      var _combine_id = $(this).data('id');
      $('.combine_id').prop('checked', false);
      $('#combine_id_' + _combine_id).prop('checked', true);
      $('#act').val('delete');
      $('#combine_list').submit();
    });

    $('#combine_list').baigoCheckall();

    var obj_query = $('#combine_search').baigoQuery();

    $('#combine_search').submit(function(){
      obj_query.formSubmit();
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
