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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" id="addProductFromModalToCartButton">Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}























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
                                @if($discount->active && \Carbon\Carbon::parse($discount->valid_from) < \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))) && \Carbon\Carbon::parse($discount->valid_until) > \Carbon\Carbon::parse(date('Y-m-d', strtotime(now()))))
                                    <label class="btn btn-secondary mr-2 my-3">
                                        <input 
                                            type="radio" 
                                            name="coupon_selector" 
                                            value="{{ $discount->id }}"
                                            data-name="{{ $discount->name }}" 
                                            data-active="{{ $discount->active }}" 
                                            data-valid-from="{{ \Carbon\Carbon::parse($discount->valid_from)->timestamp }}" 
                                            data-valid-until="{{ \Carbon\Carbon::parse($discount->valid_until)->timestamp }}" 
                                            data-customers="{{ is_array($discount->customers_groups) ? implode(',', $discount->customers_groups) : '' }}" 
                                            data-minimum="{{ (int)$discount->minimum }}" 
                                            data-available-total="{{ (int)$discount->available_total }}" 
                                            data-available-customer="{{ (int)$discount->available_customer }}" 
                                            data-conditions="{{ json_encode($discount->conditions) }}" 
                                            data-discount-type="{{ $discount->type }}" 
                                            data-discount-value="{{ $discount->value }}"> 
                                            <span>
                                                {{ $discount->name.($discount->remove_incompatible ? '*' : '') }}
                                            </span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <small>* Incompatibele coupons zullen automatisch verwijderd worden</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>