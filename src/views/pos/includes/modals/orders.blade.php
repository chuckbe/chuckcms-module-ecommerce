<div class="modal fade" id="ordersModal" tabindex="-1" role="dialog" aria-labelledby="ordersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ordersModalLabel">Overzicht</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Tijd</th>
                              <th scope="col">Bedrag</th>
                              <th scope="col">Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @php
                            $ordersCount = $orders->count();
                            @endphp
                            @foreach($orders as $order)
                            @include('chuckcms-module-ecommerce::pos.includes.order_table_line', ['order' => $order, 'ordersCount' => $ordersCount])
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
