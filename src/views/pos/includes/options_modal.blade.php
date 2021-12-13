
<div class="modal fade" id="attributeModal" tabindex="-1" role="dialog" aria-labelledby="attributeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attributeModalLabel">Selecteer attribute</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="couponsModal" tabindex="-1" role="dialog" aria-labelledby="couponsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponsModalLabel">Selecteer coupon</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label>Selecteer een coupon uit de lijst</label>
                        <div class="btn-group-horizontal btn-group-toggle" data-toggle="buttons">
                            @foreach(ChuckRepeater::for(config('chuckcms-module-ecommerce.discounts.slug')) as $discount)
                                @if($discount->json['active'] && \Carbon\Carbon::parse($discount->json['valid_from']) < \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))) && \Carbon\Carbon::parse($discount->json['valid_until']) > \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))))
                                    <label class="btn btn-secondary mr-2 my-3" for="coupon_selector_{{ $discount->id }}">
                                        <input
                                            type="radio" 
                                            id="coupon_selector_{{ $discount->id }}"
                                            name="coupon_selector" 
                                            value="{{ $discount->id }}"
                                            data-name="{{ $discount->json['name'] }}" 
                                            data-active="{{ $discount->json['active'] }}" 
                                            data-valid-from="{{ \Carbon\Carbon::parse($discount->json['valid_from'])->timestamp }}" 
                                            data-valid-until="{{ \Carbon\Carbon::parse($discount->json['valid_until'])->timestamp }}" 
                                            data-customers="{{ is_array($discount->json['customer_groups']) ? implode(',', $discount->json['customer_groups']) : '' }}" 
                                            data-minimum="{{ (int)$discount->json['minimum'] }}" 
                                            data-available-total="{{ (int)$discount->json['available_total'] }}" 
                                            data-available-customer="{{ (int)$discount->json['available_customer'] }}" 
                                            data-conditions="{{ json_encode($discount->json['conditions']) }}" 
                                            data-discount-type="{{ $discount->json['type'] }}" 
                                            data-discount-value="{{ $discount->json['value'] }}"> 
                                            <span style="pointer-events: none;">
                                                {{ $discount->json['name'] }}
                                            </span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <small>* Incompatibele coupons zullen automatisch verwijderd worden</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                        <small class="d-block text-danger text-end mb-2 d-none" id="cof_couponErrorText"></small>
                        <div class="w-100 d-block"></div>
                        <button class="btn btn-secondary" id="cof_cancelSelectCouponBtn">Annuleren</button>
                        <button class="btn btn-success float-end" id="cof_addSelectedCouponToCartBtn">Coupon Toevoegen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div role="alert" aria-live="assertive" aria-atomic="true"  class="toast text-success" id="customerChangedToast" style="position: absolute; bottom: 25px; left: 25px;">
    <div class="toast-header">
      <strong class="mr-auto"><b>SCANNER</b></strong>
      <small>nu</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Klant werd succesvol gewijzigd!
    </div>
</div>