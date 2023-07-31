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
            <input type="text" class="form-control numpad pos_checkoutCashInput" placeholder="0.00">
            <div class="input-group-append">
                <button class="input-group-text pos_checkoutCashPaymentReset">⌫</button>
                <button class="input-group-text pos_checkoutCashAddPayment" data-amount="5">+€5</button>
                <button class="input-group-text pos_checkoutCashAddPayment" data-amount="10">+€10</button>
                <button class="input-group-text pos_checkoutCashAddPayment" data-amount="20">+€20</button>
                <button class="input-group-text pos_checkoutCashAddPayment" data-amount="50">+€50</button>
                <button class="input-group-text pos_checkoutCashFitPayment" data-amount="{{ ChuckCart::instance('pos')->final() }}">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('pos')->final()) }}</button>
                <button class="input-group-text pos_checkoutCashToPayments">Betaal</button>
            </div>
        </div>
    </div>

    <div class="col-12">
        <label>Kaart</label>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text">€</div>
            </div>
            <input type="text" class="form-control numpad pos_checkoutCardInput" placeholder="0.00">
            <div class="input-group-append">
                <button class="input-group-text pos_checkoutCardPaymentReset">⌫</button>
                <button class="input-group-text pos_checkoutCardFitPayment" data-amount="{{ ChuckCart::instance('pos')->final() }}">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('pos')->final()) }}</button>
                <button class="input-group-text pos_checkoutCardToPayments">Betaal</button>
            </div>
        </div>
    </div>

    <div class="col-12 d-none" id="pos_paymentListWrapper">
        <h5 class="mt-3 mb-2">Betalingen</h5>
        <ul id="pos_paymentList" class="list-group">
            @foreach($order->payments as $payment)
            @if($payment->type == 'cash')
            <li class="pos_paymentListItem list-group-item d-flex justify-content-between align-items-center" data-type="cash" data-id="{{ $payment->id }}" data-amount="{{ $payment->amount }}" data-status="success"><span>Cash: {{ ChuckEcommerce::formatPrice($payment->amount) }}</span><span class="badge badge-info py-1 pos_removeCashPayment">Verwijderen</span><span class="badge badge-success py-1 paymentStatus">Betaald</span></li>
            @elseif($payment->type == 'pointofsale')

            @endif
            @endforeach
        </ul>
    </div>

    <div class="col-12 text-center">
        <h5 class="mt-3 mb-2">Resterend bedrag</h5>
        <p class="pos_checkoutPendingAmount" data-amount="{{ ChuckCart::instance('pos')->final() }}">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('pos')->final()) }}</p>
    </div>

    <div class="col-12">
        <hr>
        <button class="btn btn-secondary" id="pos_cancelOrderBtn" data-order="{{ $order->id }}">Annuleren</button>
        <button class="btn btn-success float-right" id="pos_finalizeOrderBtn" data-order="{{ $order->id }}" disabled>Bestelling voltooien</button>
    </div>
</div>
