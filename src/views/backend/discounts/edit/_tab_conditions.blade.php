<div class="form-group row required">
    <label for="discount_valid_from" class="col-sm-3 col-form-label">Selecteer klant(en)</label>
    <div class="col-sm-9">
        <select class="custom-select" name="customers[]" id="discount_customers" multiple>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" @if(is_array($discount->customers) && in_array($customer->id, $discount->customers)) selected @endif>{{ $customer->surname.' '.$customer->name. ' ('.$customer->email.')' }}</option>
            @endforeach
        </select>
        <small>Indien er klanten geselecteerd zijn, is deze korting alleen toegankelijk voor geregistreerde klanten</small>
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

<div class="row bg-white shadow-sm rounded p-3 mb-3 mx-1">
    <div class="col-sm-12">
        <h6>Voorwaarden <button class="btn btn-sm btn-outline-secondary float-right" role="button" id="addNewConditionGroupBtn"><small>+ groep</small></button></h6>
    </div>
    <div class="col-sm-12 conditions_group_container">
        @if(is_array($discount->conditions) && count($discount->conditions) > 0 )
        @foreach($discount->conditions as $condition)
        <div class="conditions_wrapper _input_container" data-group="{{ $loop->iteration }}">
            <hr>

            <small class="d-inline-block mb-2 w-75">De winkelwagen bevat minstens <input type="number" style="width:50px" min="1" step="1" name="condition_min_quantity[]" value="{{ $condition['min_quantity'] }}" required> product(en) met één van de volgende regels:</small>

            <small class="d-inline-block float-right"><button class="btn btn-sm btn-outline-danger remove_condition_group_btn{{ $loop->iteration == 1 ? ' d-none' : '' }}" role="button"><small>- groep</small></button></small>

            <small class="d-inline-block float-right mr-2"><button class="btn btn-sm btn-outline-primary add_rule_btn" role="button"><small>+ regel</small></button></small>
            
            @if(is_array($condition['rules']) && count($condition['rules']) > 0 )
            @foreach($condition['rules'] as $rule)
            <div class="form-group row conditions_input_line _input_line">
                <div class="col-6 col-sm-3">
                    <label class="sr-only">Voorwaarde type *</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
                        </div>
                        <select class="custom-select condition_type_input" name="condition_type[{{ $loop->iteration }}][]" required>
                            <option value="collection" data-type="collection" @if($rule['type'] == 'collection') selected @else disabled @endif>Product met Categorie</option>
                            <option value="brand" data-type="brand" @if($rule['type'] == 'brand') selected @else disabled @endif>Product met Merk</option>
                            <option value="product" data-type="product" @if($rule['type'] == 'product') selected @else disabled @endif>Specifiek product</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="sr-only">Waarde *</label>
                    <select class="custom-select custom-select-sm condition_value_input" name="condition_value[{{ $loop->iteration }}][]" required>
                        @foreach(ChuckRepeater::for('collections') as $collection)
                        <option value="{{ $collection->id }}" data-type="collection" class="{{ $rule['type'] !== 'collection' ? 'd-none' : '' }}" @if($rule['type'] !== 'collection') disabled @endif @if($rule['value'] == $collection->id) selected @endif>[category] {{ $collection->name }}</option>
                        @endforeach

                        @foreach(ChuckRepeater::for('brands') as $brand)
                        <option value="{{ $brand->id }}" data-type="brand" class="{{ $rule['type'] !== 'brand' ? 'd-none' : '' }}" @if($rule['type'] !== 'brand') disabled @endif @if($rule['value'] == $brand->id) selected @endif>[brand] {{ $brand->name }}</option>
                        @endforeach

                        @foreach(ChuckProduct::all() as $product)
                        <option value="{{ $product->id }}" data-type="product" class="{{ $rule['type'] !== 'product' ? 'd-none' : '' }}" @if($rule['type'] !== 'product') disabled @endif @if($rule['value'] == $product->id) selected @endif>[product] {{ ChuckProduct::title($product) }}</option>
                        @endforeach
                    </select>
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
                        <select class="custom-select condition_type_input" name="condition_type[{{ $loop->iteration }}][]" disabled required>
                            <option value="collection" data-type="collection">Product met Categorie</option>
                            <option value="brand" data-type="brand">Product met Merk</option>
                            <option value="product" data-type="product">Specifiek product</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="sr-only">Waarde *</label>
                    <select class="custom-select custom-select-sm condition_value_input" name="condition_value[{{ $loop->iteration }}][]" disabled required>
                        @foreach(ChuckRepeater::for('collections') as $collection)
                        <option value="{{ $collection->id }}" data-type="collection">[category] {{ $collection->name }}</option>
                        @endforeach

                        @foreach(ChuckRepeater::for('brands') as $brand)
                        <option value="{{ $brand->id }}" data-type="brand">[brand] {{ $brand->name }}</option>
                        @endforeach

                        @foreach(ChuckProduct::all() as $product)
                        <option value="{{ $product->id }}" data-type="product" class="d-none" disabled>[product] {{ ChuckProduct::title($product) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
        </div>
        @endforeach
        @else
        <div class="conditions_wrapper _input_container" data-group="1">
            <hr>

            <small class="d-inline-block mb-2">De winkelwagen bevat minstens <input type="number" style="width:50px" min="1" step="1" name="condition_min_quantity[]" value="1" required> product(en) met één van de volgende regels:</small>

            <small class="d-inline-block float-right"><button class="btn btn-sm btn-outline-danger remove_condition_group_btn d-none" role="button"><small>Verwijder groep</small></button></small>

            <small class="d-inline-block float-right mr-2"><button class="btn btn-sm btn-outline-primary add_rule_btn" role="button"><small>+ regel</small></button></small>

            <div class="form-group row conditions_input_line _input_line d-none">
                <div class="col-6 col-sm-3">
                    <label class="sr-only">Voorwaarde type *</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
                        </div>
                        <select class="custom-select condition_type_input" name="condition_type[1][]" disabled required>
                            <option value="collection" data-type="collection">Product met Categorie</option>
                            <option value="brand" data-type="brand">Product met Merk</option>
                            <option value="product" data-type="product">Specifiek product</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="sr-only">Waarde *</label>
                    <select class="custom-select custom-select-sm condition_value_input" name="condition_value[1][]" disabled required>
                        @foreach(ChuckRepeater::for('collections') as $collection)
                        <option value="{{ $collection->id }}" data-type="collection">[category] {{ $collection->name }}</option>
                        @endforeach

                        @foreach(ChuckRepeater::for('brands') as $brand)
                        <option value="{{ $brand->id }}" data-type="brand">[brand] {{ $brand->name }}</option>
                        @endforeach

                        @foreach(ChuckProduct::all() as $product)
                        <option value="{{ $product->id }}" data-type="product" class="d-none" disabled>[product] {{ ChuckProduct::title($product) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <hr class="mb-2">
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="row d-none" id="addNewRuleWrapper">
            <div class="col-sm-6">
                <label for="new_rule_type">Soort regel:</label>
                <select id="new_rule_type" class="rule_type_selector custom-select custom-select-sm" data-element-selector="#new_rule_value" class="custom-select">
                    <option value="collection" data-type="collection" selected>Product met Categorie</option>
                    <option value="brand" data-type="brand" selected>Product met Merk</option>
                    <option value="product" data-type="product">Specifiek product</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label for="new_rule_value">Waarde:</label>
                <select id="new_rule_value" class="custom-select custom-select-sm">
                    @foreach(ChuckRepeater::for('collections') as $collection)
                    <option value="{{ $collection->id }}" data-type="collection" @if($loop->first) selected @endif>[category] {{ $collection->name }}</option>
                    @endforeach

                    @foreach(ChuckRepeater::for('brands') as $brand)
                    <option value="{{ $brand->id }}" data-type="brand" @if($loop->first) selected @endif>[brand] {{ $brand->name }}</option>
                    @endforeach

                    @foreach(ChuckProduct::all() as $product)
                    <option value="{{ $product->id }}" data-type="product" class="d-none" disabled>[product] {{ ChuckProduct::title($product) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3 pt-4">
                <button type="button" class="btn btn-sm btn-outline-success" id="new_rule_button" data-group="1"><small>+ regel toevoegen</small></button>
                <div class="w-100 d-block"></div>
                <small class="d-none text-danger" id="new_rule_error">Vul alle velden in</small>
            </div>
        </div>
    </div>
</div>