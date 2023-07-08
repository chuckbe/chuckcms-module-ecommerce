{{-- <div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="optionsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="" id="options-form">
            <div class="modal-header">
                <h5 class="modal-title font-cera-bold" id="optionsModalLabel">Selecteer de opties voor: <span class="options_product_name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="attributesModalBody">
                    <div class="row attributes_modal_row">
                        <div class="col-sm-12 mb-3">
                            <label class="d-block">Kies attribuut</label>
                            <div class="btn-group-horizontal btn-group-toggle attributes_modal_item_button_group" data-toggle="buttons">
                                <label class="btn btn-secondary mr-2 mb-3 attributes_modal_item_button">
                                    <input type="radio" name="attributes" id="option1"> <span class="attributes_modal_item_button_text">Active</span>
                                </label>
                                <label class="btn btn-secondary attributes_modal_item_button">
                                    <input type="radio" name="attributes" id="option2"> Radio
                                </label>
                                <label class="btn btn-secondary attributes_modal_item_button">
                                    <input type="radio" name="attributes" id="option3"> Radio
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="optionsModalBody">
                    <div class="row options_modal_row">

                        <div class="col-sm-12 options_modal_item_radio">
                            <label for="" class="options_item_name">Radio</label>
                            <div class="form-group cof_options_radio_item_input_group mb-2">
                                <div class="form-check cof_options_radio_item_input">
                                    <label class="form-check-label" for="exampleRadios1">
                                    <input class="form-check-input" type="radio" name="cof_options_radio" id="exampleRadios1" value="option1">
                                    <span> Default radio</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 options_modal_item_select">
                            <div class="form-group">
                                <label for="cofOptionsSelect" class="options_item_name">Select</label>
                                <select name="cof_options_select" class="custom-select cof_options_select_item_input" required>
                                    <option value="default" class="cof_options_option_input">Default</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="extrasModalBody">
                    <div class="row extras_modal_row">
                        <div class="col-sm-12 extras_modal_item">
                            <div class="form-check cof_extras_checkbox_item_input">
                                <input class="form-check-input extras_item_checkbox" type="checkbox" value="" id="defaultCheck1">
                                <label class="form-check-label extras_item_name" for="defaultCheck1">
                                Default checkbox
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container d-none" id="subproductModalBody" style="max-height: 50vh; overflow-y: scroll;">
                    <div class="row subproduct_group_modal_row py-3">
                        <div class="col-12 d-flex">
                            <h6 class="subproduct_product_group_label">Group 1 Label</h6>
                            <div class="d-flex ml-auto">
                                <span>
                                    <span class="subproduct_product_group_selected">0</span>/<span class="subproduct_product_group_max">0</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row subproduct_group_product_row pt-3">
                                <div class="col col-xl-3 card subproduct_group_product py-3">
                                    <img src="https://donuttello.com/photos/shares/donuts/Donuttello-Selection-Kaneel.jpg">
                                    <div class="d-flex">
                                        <small class="subproduct_group_product_name">name here</small>
                                        <small class="text-muted d-none ml-auto product_extra_price"></small>
                                    </div>
                                    <div class="d-flex position-absolute top-0 w-100 px-3" style="right: -1px">
                                        <div class="d-flex subproduct_group_product_qty bg-light py-2 ml-auto" style="max-width: 100px">
                                            <div class="col d-flex flex-wrap justify-content-center">
                                                <div class="reducebtn" style="cursor:pointer;">
                                                    <i class="fas fa-minus"></i>
                                                </div>
                                            </div>
                                            <div class="col col-md-4 px-0">
                                                <input type="text" class="p-0 m-0 w-100 text-center border-0 product_qty" name="quantity" readonly="" value="0">
                                            </div>
                                            <div class="col d-flex flex-wrap justify-content-center">
                                                <div class="addbtn" style="cursor:pointer;">
                                                    <i class="fas fa-plus"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex w-100">
                    <div class="col d-none">
                        <span id="subproduct_group_total_price"></span>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-primary btn-block" id="addProductFromModalToCartButton">Toevoegen</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>
</div> --}}









<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerModalLabel">Selecteer klant</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <label>Selecteer een klant uit de lijst</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text" id="cof_customerSelectDefaultGuest">Guest</div>
                    </div>
                    <select id="cof_customerSelectInput" class="custom-select">
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" class="cof_customerSelectInputOption" data-is-guest="{{ $customer->guest ? 'true' : 'false' }}" data-customer-email="{{ $customer->email }}" data-ean="{{ $customer->ean }}">{{ $customer->surname.' '.$customer->name.' ('.$customer->email.')' }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="input-group-text"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <hr>
                <button class="btn btn-secondary" id="cof_cancelSelectClientBtn">Annuleren</button>
                <button class="btn btn-success float-right" id="cof_selectCustomerForCartBtn">Selecteer klant</button>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <label>Selecteer een coupon uit de lijst</label>
                <div class="btn-group-horizontal btn-group-toggle" data-toggle="buttons">
                    @foreach(ChuckRepeater::for(config('chuckcms-module-ecommerce.discounts.slug')) as $discount)
                    @if($discount->type !== 'gift' && $discount->active && \Carbon\Carbon::parse($discount->valid_from) < \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))) && \Carbon\Carbon::parse($discount->valid_until) > \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))))
                    <label class="btn btn-secondary mr-2 mb-3">
                        <input type="radio" name="coupon_selector" value="{{ $discount->id }}" data-name="{{ $discount->name }}" data-active="{{ $discount->active }}" data-valid-from="{{ \Carbon\Carbon::parse($discount->valid_from)->timestamp }}" data-valid-until="{{ \Carbon\Carbon::parse($discount->valid_until)->timestamp }}" data-customers="{{ is_array($discount->customers) ? implode(',', $discount->customers) : '' }}" data-minimum="{{ (int)$discount->minimum }}" data-available-total="{{ (int)$discount->available_total }}" data-available-customer="{{ (int)$discount->available_customer }}" data-conditions="{{ json_encode($discount->conditions) }}" data-discount-type="{{ $discount->type }}" data-discount-value="{{ $discount->value }}" data-apply-on="{{ $discount->apply_on }}" data-apply-product="{{ $discount->apply_product }}" data-uncompatible-discounts="{{ is_array($discount->uncompatible_discounts) ? implode(',', $discount->uncompatible_discounts) : '' }}" data-remove-incompatible="{{ $discount->remove_incompatible }}"> <span>{{ $discount->name.($discount->remove_incompatible ? '*' : '') }}</span>
                    </label>
                    @else
                    <label class="btn btn-secondary mr-2 mb-3 d-none">
                        <input type="radio" name="coupon_selector" value="{{ $discount->id }}" data-name="{{ $discount->name }}" data-active="{{ $discount->active }}" data-valid-from="{{ \Carbon\Carbon::parse($discount->valid_from)->timestamp }}" data-valid-until="{{ \Carbon\Carbon::parse($discount->valid_until)->timestamp }}" data-customers="{{ is_array($discount->customers) ? implode(',', $discount->customers) : '' }}" data-minimum="{{ (int)$discount->minimum }}" data-available-total="{{ (int)$discount->available_total }}" data-available-customer="{{ (int)$discount->available_customer }}" data-conditions="{{ json_encode($discount->conditions) }}" data-discount-type="{{ $discount->type }}" data-discount-value="{{ $discount->value }}" data-apply-on="{{ $discount->apply_on }}" data-apply-product="{{ $discount->apply_product }}" data-uncompatible-discounts="{{ is_array($discount->uncompatible_discounts) ? implode(',', $discount->uncompatible_discounts) : '' }}" data-remove-incompatible="{{ $discount->remove_incompatible }}" disabled> <span>{{ $discount->name.($discount->remove_incompatible ? '*' : '') }}</span>
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
                <small class="d-block text-danger text-right mb-2 d-none" id="cof_couponErrorText"></small>
                <div class="w-100 d-block"></div>
                <button class="btn btn-secondary" id="cof_cancelSelectCouponBtn">Annuleren</button>
                <button class="btn btn-success float-right" id="cof_addSelectedCouponToCartBtn">Coupon Toevoegen</button>
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

<div role="alert" aria-live="assertive" aria-atomic="true"  class="toast text-danger" id="couponAlreadyInCartToast" style="position: absolute; bottom: 25px; left: 25px;">
    <div class="toast-header">
      <strong class="mr-auto"><b>COUPON</b></strong>
      <small>nu</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Coupon is reeds in gebruik!
    </div>
</div>

<div role="alert" aria-live="assertive" aria-atomic="true"  class="toast text-success" id="couponAddedToCartToast" style="position: absolute; bottom: 25px; left: 25px;">
    <div class="toast-header">
      <strong class="mr-auto"><b>COUPON</b></strong>
      <small>nu</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      Coupon werd toegevoegd!
    </div>
</div>












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

<div class="modal fade" id="ecomCouponsModal" tabindex="-1" role="dialog" aria-labelledby="couponsModalLabel" aria-hidden="true">
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
