<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="deleteCollectionModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Ben je zeker dat je de volgende <span class="semi-bold">collectie</span> wil verwijderen?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
        <p class="p-b-10">Collectie: '<span id="delete_collection_name"></span>' verwijderen?</p>
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.collections.delete') }}">
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" id="delete_collection_id" name="id" value="">
              <input type="hidden" name="_token" value="{{ Session::token() }}">
              <button type="submit" class="btn btn-danger float-right">Verwijderen</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
</div>
<!-- /.modal-dialog -->