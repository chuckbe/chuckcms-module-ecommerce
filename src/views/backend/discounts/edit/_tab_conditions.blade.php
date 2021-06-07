<div class="form-group row required">
    <label for="discount_valid_from" class="col-sm-3 col-form-label">Geldig van *</label>
    <div class="col-sm-9">
        <input type="date" class="form-control" id="discount_valid_from" name="valid_from" value="{{ old('valid_from', date('Y-m-d', strtotime($discount->valid_from))) }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_valid_until" class="col-sm-3 col-form-label">Geldig tot *</label>
    <div class="col-sm-9">
        <input type="date" class="form-control" id="discount_valid_until" name="valid_until" value="{{ old('valid_until', date('Y-m-d', strtotime($discount->valid_until))) }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_minimum" class="col-sm-3 col-form-label">Minimum *</label>
    <div class="col-sm-9">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="discount_minimum-addon1">€</span>
            </div>
            <input type="text" class="form-control" id="discount_minimum" name="minimum" value="{{ old('minimum', $discount->minimum) }}" aria-label="Minimum" aria-describedby="discount_minimum-addon1" required>
        </div>
    </div>
</div>
<div class="form-group row required">
  <div class="col-sm-4 offset-sm-3">
    <label class="sr-only" for="">Minimum: BTW Inbegrepen?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="minimum_vat_included">
    <label for="discount_minimum_vat_included">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_minimum_vat_included" value="1" name="minimum_vat_included" @if($discount->minimum_vat_included) checked @endif/> Minimum: BTW Inbegrepen?
    </label>
  </div>
  <div class="col-sm-5">
    <label class="sr-only" for="">Minimum: inclusief verzending?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="minimum_shipping_included">
    <label for="discount_minimum_shipping_included">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_minimum_shipping_included" value="1" name="minimum_shipping_included" @if($discount->minimum_shipping_included) checked @endif/> Minimum: inclusief verzending?
    </label>
  </div>
</div>
<div class="form-group row required">
    <label for="discount_available_total" class="col-sm-3 col-form-label">Totaal beschikbaar *</label>
    <div class="col-sm-9">
        <input type="number" min="0" step="1" class="form-control" id="discount_available_total" name="available_total" value="{{ old('available_total', $discount->available_total) }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_available_customer" class="col-sm-3 col-form-label">Totaal beschikbaar per klant *</label>
    <div class="col-sm-9">
        <input type="number" min="1" step="1" class="form-control" id="discount_available_customer" name="available_customer" value="{{ old('available_customer', $discount->available_customer) }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_customer_groups" class="col-sm-3 col-form-label">Klantengroepen *</label>
    <div class="col-sm-9">
        <select class="custom-select" name="customer_groups[]" id="discount_customer_groups" data-placeholder="Selecteer beschikbare klantengroepen" multiple="multiple" required>
            <option disabled>Selecteer beschikbare klantengroepen</option>
            @foreach(ChuckEcommerce::allGroups() as $groupKey => $group)
              <option value="{{ $groupKey }}" @if(in_array($groupKey, $discount->customer_groups)) selected @endif>{{ $group['name'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row bg-white shadow-sm rounded p-3 mb-3 mx-1">
    <div class="col-sm-12">
        <h6>Voorwaarden</h6>
        <hr>
    </div>
    <div class="col-sm-12 conditions_wrapper _input_container">
        @if(is_array($discount->conditions) && count($discount->conditions) > 0 )
        @foreach($discount->conditions as $condition)
        <div class="form-group row conditions_input_line _input_line">
            <div class="col-6 col-sm-3">
                <label class="sr-only">Voorwaarde type *</label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
                    </div>
                    <select class="custom-select condition_type_input" name="condition_type[]" required>
                        <option value="collection" data-type="collection" @if($condition['type'] == 'collection') selected @else disabled @endif>Product heeft Categorie</option>
                        <option value="brand" data-type="brand" @if($condition['type'] == 'brand') selected @else disabled @endif>Product heeft Merk</option>
                        <option value="product" data-type="product" @if($condition['type'] == 'product') selected @else disabled @endif>Winkelwagen heeft product</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <label class="sr-only">Waarde *</label>
                <select class="custom-select condition_value_input" name="condition_value[]" required>
                    @foreach(ChuckRepeater::for('collections') as $collection)
                    <option value="{{ $collection->id }}" data-type="collection" class="{{ $condition['type'] !== 'collection' ? 'd-none' : '' }}" @if($condition['type'] !== 'collection') disabled @endif @if($condition['value'] == $collection->id) selected @endif>[collection] {{ $collection->name }}</option>
                    @endforeach

                    @foreach(ChuckRepeater::for('brands') as $brand)
                    <option value="{{ $brand->id }}" data-type="brand" class="{{ $condition['type'] !== 'brand' ? 'd-none' : '' }}" @if($condition['type'] !== 'brand') disabled @endif @if($condition['value'] == $collection->id) selected @endif>[brand] {{ $brand->name }}</option>
                    @endforeach

                    @foreach(ChuckProduct::all() as $product)
                    <option value="{{ $product->id }}" data-type="product" class="{{ $condition['type'] !== 'product' ? 'd-none' : '' }}" @if($condition['type'] !== 'product') disabled @endif @if($condition['value'] == $collection->id) selected @endif>[product] {{ ChuckProduct::title($product) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <hr class="mb-0">
            </div>
        </div>
        @endforeach
        @else
        <div class="form-group row conditions_input_line _input_line d-none">
            <div class="col-6 col-sm-3">
                <label class="sr-only">Voorwaarde type *</label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
                    </div>
                    <select class="custom-select condition_type_input" name="condition_type[]" disabled required>
                        <option value="collection" data-type="collection">Product heeft Categorie</option>
                        <option value="brand" data-type="brand">Product heeft Merk</option>
                        <option value="product" data-type="product">Winkelwagen heeft product</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <label class="sr-only">Waarde *</label>
                <select class="custom-select condition_value_input" name="condition_value[]" disabled required>
                    @foreach(ChuckRepeater::for('collections') as $collection)
                    <option value="{{ $collection->id }}" data-type="collection">[collection] {{ $collection->name }}</option>
                    @endforeach

                    @foreach(ChuckRepeater::for('brands') as $brand)
                    <option value="{{ $brand->id }}" data-type="brand" class="d-none" disabled>[brand] {{ $brand->name }}</option>
                    @endforeach

                    @foreach(ChuckProduct::all() as $product)
                    <option value="{{ $product->id }}" data-type="product" class="d-none" disabled>[product] {{ ChuckProduct::title($product) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <hr class="mb-0">
            </div>
        </div>
        @endif
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <label for="new_conditions_type">Voorwaarde type:</label>
                <select id="new_conditions_type" class="conditions_type_selector custom-select" data-element-selector="#new_conditions_value" class="custom-select">
                    <option value="collection" data-type="collection" selected>Product heeft Collectie</option>
                    <option value="brand" data-type="brand">Product heeft Merk</option>
                    <option value="product" data-type="product">Winkelwagen heeft product</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label for="new_conditions_value">Waarde:</label>
                <select id="new_conditions_value" class="custom-select">
                    @foreach(ChuckRepeater::for('collections') as $collection)
                    <option value="{{ $collection->id }}" data-type="collection" @if($loop->first) selected @endif>[collection] {{ $collection->name }}</option>
                    @endforeach

                    @foreach(ChuckRepeater::for('brands') as $brand)
                    <option value="{{ $brand->id }}" data-type="brand" class="d-none" disabled>[brand] {{ $brand->name }}</option>
                    @endforeach

                    @foreach(ChuckProduct::all() as $product)
                    <option value="{{ $product->id }}" data-type="product" class="d-none" disabled>[product] {{ ChuckProduct::title($product) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 pt-4">
                <button type="button" class="btn btn-success" id="new_condition_button">+ Toevoegen</button>
                <div class="w-100 d-block"></div>
                <small class="d-none text-danger" id="new_condition_error">Vul alle velden in</small>
            </div>
        </div>
    </div>
</div>