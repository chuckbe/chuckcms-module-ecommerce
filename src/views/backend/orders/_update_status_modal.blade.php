<!-- Modal -->
<div class="modal fade stick-up disable-scroll" id="updateStatusModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog ">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Update de <span class="semi-bold">status</span> van de bestelling</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <p class="p-b-10">Selecteer een status.</p>
            @if($errors->any())
              @foreach ($errors->all() as $error)
                <p class="text-danger">{{ $error }}</p>
              @endforeach
            @endif
          </div>
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.ecommerce.orders.status.update') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default form-group-default-select2">
                  <select class="form-control" name="order_status" placeholder="Selecteer status" required>
                    @foreach(ChuckEcommerce::getSetting('order.statuses') as $statusKey => $status)
                      <option value="{{ $statusKey }}">{{ $status['display_name'][app()->getLocale()] }} {{ $status['send_email'] == true ? '(email verzenden)' : '' }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-md-12 m-t-10 sm-m-t-10">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
            <button type="submit" class="btn btn-primary m-t-5 pull-right">Status Updaten</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
</div>
<style>
  .select2-dropdown {z-index:9999;}
</style>
<!-- /.modal-dialog -->