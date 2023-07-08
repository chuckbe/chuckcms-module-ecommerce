<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      {{-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Afrekenen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> --}}
      <div class="modal-body">
        <div class="row d-none">
            {{-- <div class="col-4 text-center">
                <div class="m-auto rounded-circle p-3" style="
                        width: 100px;
                        height: 100px;
                        background: #f7f7f7;">
                        <img src="{{asset('chuckbe/chuckcms-module-order-form/qr-scan.svg')}}" class="img-fluid w-100 p-2">
                </div>
                <button class="btn btn-primary my-3">QR code</button>
            </div> --}}
            <div class="col-6 text-center">
                <div class="m-auto rounded-circle p-3" style="
                    width: 100px;
                    height: 100px;
                    background: #f7f7f7;">
                    <img src="{{asset('chuckbe/chuckcms-module-ecommerce/wallet.svg')}}" class="img-fluid w-100 p-2">
                </div>
                <button class="btn btn-primary my-3">Cash</button>
            </div>
            <div class="col-6 text-center">
                <div class="m-auto rounded-circle p-3" style="
                    width: 100px;
                    height: 100px;
                    background: #f7f7f7;">
                    <img src="{{asset('chuckbe/chuckcms-module-ecommerce/credit-card.svg')}}" class="img-fluid w-100 p-2">
                </div>
                <button class="btn btn-primary my-3">Kaartbetaling</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <h5 class="mt-3 mb-5">Voer betaling uit...</h5>
            </div>
            <div class="col-12">
                <label>Cash</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                    <input type="text" class="form-control numpad cof_checkoutCashInput" placeholder="0.00">
                    <div class="input-group-append">
                        <button class="input-group-text cof_checkoutCashPaymentReset">⌫</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="5">+€5</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="10">+€10</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="20">+€20</button>
                        <button class="input-group-text cof_checkoutCashAddPayment" data-amount="50">+€50</button>
                        <button class="input-group-text cof_checkoutCashFitPayment">Gepast</button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label>Kaart</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">€</div>
                    </div>
                    <input type="text" class="form-control numpad cof_checkoutCardInput" placeholder="0.00">
                    <div class="input-group-append">
                        <button class="input-group-text cof_checkoutCardPaymentReset">⌫</button>
                        <button class="input-group-text cof_checkoutCardFitPayment">Gepast</button>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center">
                <h5 class="mt-3 mb-2">Resterend bedrag</h5>
                <p class="cof_checkoutPendingAmount">0,00</p>
            </div>

            <div class="col-12">
                <hr>
                <button class="btn btn-secondary" id="cof_cancelOrderBtn">Annuleren</button>
                <button class="btn btn-success float-right" id="cof_finalizeOrderBtn" disabled>Bestelling voltooien</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
