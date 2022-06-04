  <div class="modal fade" id="modal_nm">
    <div class="modal-dialog">
      <div class="modal-content">

      </div>
    </div>
  </div>

  <script type="text/javascript">
  $(document).ready(function(){
    $('#modal_nm').on('shown.bs.modal',function(event){
      var _obj_button = $(event.relatedTarget);
      var _href       = _obj_button.data('href');
      $('#modal_nm .modal-content').load(_href);
    }).on('hidden.bs.modal', function(){
      $('#modal_nm .modal-content').empty();
    });
  });
  </script>
