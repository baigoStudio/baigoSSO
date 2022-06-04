  <div class="modal fade" id="modal_lg">
    <div class="modal-dialog">
      <div class="modal-content">

      </div>
    </div>
  </div>

  <script type="text/javascript">
  $(document).ready(function(){
    $('#modal_lg').on('shown.bs.modal',function(event){
      var _obj_button = $(event.relatedTarget);
      var _href       = _obj_button.data('href');
      $('#modal_lg .modal-content').load(_href);
    }).on('hidden.bs.modal', function(){
      $('#modal_lg .modal-content').empty();
    });
  });
  </script>
