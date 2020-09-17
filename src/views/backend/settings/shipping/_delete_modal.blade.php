<!-- Modal -->
<div class="modal fade stick-up" id="deleteCarrierModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
        </button>
        <h5>Ben je zeker dat je de volgende <span class="semi-bold">verzendmethode</span> wil verwijderen?</h5>
        <p class="p-b-10"><span id="delete_carrier_name"></span></p>
      </div>
      <div class="modal-body">
        <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.settings.shipping.carrier.delete') }}">
          <div class="row">
            <div class="col-md-4 m-t-10 sm-m-t-10 pull-right">
              <input type="hidden" id="delete_carrier_key" name="key" value="">
              <input type="hidden" name="_token" value="{{ Session::token() }}">
              <button type="submit" class="btn btn-danger btn-block m-t-5">Verwijderen</button>
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