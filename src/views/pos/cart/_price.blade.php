<div class="priceCalculatorArea row">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="subtotaal row">
                  <div class="col-6 text-left">Subtotal</div>
                  <div class="col-6 text-right cof_cartSubtotalPrice">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('pos')->final()) }}</div>
                </div>
                <div class="korting row">
                    <div class="col-6 text-left">Discount</div>
                    <div class="col-6 text-right cof_cartDiscountPrice">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('pos')->discount()) }}</div>
                </div>
                <div class="row">
                    <div class="col-12" id="cof_cartCouponWrapper">
                        @foreach(ChuckCart::instance('pos')->discounts() as $discount)
                        <span class="badge badge-primary badge-sm mt-1 mr-1 cof_cartCouponItem d-none" data-coupon="" style="font-size:0.7rem!important">
                            <button type="button" class="close ml-1 cof_cartCouponItemRemoveBtn" style="font-size:1.2rem!important;line-height: 0.65!important" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span class="cof_couponText">{{ $discount->toArray()['code'] }}</span>
                        </span>
                      @endforeach
                    </div>
                </div>
                <hr class="priceCalculatorDivider"/>
                <div class="totaal row">
                    <div class="col-6 text-left">Total</div>
                    <div class="col-6 text-right tot-value cof_cartTotalPrice">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('pos')->final()) }}</div>
                </div>
                <div class="btw row">
                    <div class="col-6 text-left">VAT</div>
                    <div class="col-6 text-right cof_cartTotalVatPrice">{{ ChuckEcommerce::formatPrice(ChuckCart::instance('pos')->tax()) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
