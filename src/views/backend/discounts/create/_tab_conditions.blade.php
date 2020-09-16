<div class="form-group row required">
    <label for="discount_valid_from" class="col-sm-2 col-form-label">Geldig van *</label>
    <div class="col-sm-10">
        <input type="date" class="form-control" id="discount_valid_from" name="valid_from" value="{{ old('valid_from') }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_valid_until" class="col-sm-2 col-form-label">Geldig tot *</label>
    <div class="col-sm-10">
        <input type="date" class="form-control" id="discount_valid_until" name="valid_until" value="{{ old('valid_until') }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_minimum" class="col-sm-2 col-form-label">Minimum *</label>
    <div class="col-sm-10">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="discount_minimum-addon1">€</span>
            </div>
            <input type="text" class="form-control" id="discount_minimum" name="minimum" value="{{ old('minimum') }}" aria-label="Minimum" aria-describedby="discount_minimum-addon1" required>
        </div>
    </div>
</div>
<div class="form-group row required">
  <div class="col-sm-5 offset-sm-2">
    <label class="sr-only" for="">Minimum: BTW Inbegrepen?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="discount_minimum_vat_included">
    <label for="discount_minimum_vat_included">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_minimum_vat_included" value="1" name="discount_minimum_vat_included"/> Minimum: BTW Inbegrepen?
    </label>
  </div>
  <div class="col-sm-5">
    <label class="sr-only" for="">Minimum: inclusief verzending?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="discount_minimum_shipping_included">
    <label for="discount_minimum_shipping_included">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_minimum_shipping_included" value="1" name="discount_minimum_shipping_included" checked/> Minimum: inclusief verzending?
    </label>
  </div>
</div>
<div class="form-group row required">
    <label for="discount_available_total" class="col-sm-2 col-form-label">Totaal beschikbaar *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="discount_available_total" name="priority" value="{{ old('available_total', 100) }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_available_customer" class="col-sm-2 col-form-label">Totaal beschikbaar per klant *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="discount_available_customer" name="priority" value="{{ old('available_customer', 1) }}" required>
    </div>
</div>
<div class="row">
  <div class="col-sm-5 offset-sm-2">
    <div class="form-group form-group-default form-group-default-select2 required">
      <label>Klantengroepen</label>
      <select class="custom-select" name="collection[]" data-placeholder="Selecteer beschikbare klantengroepen" multiple="multiple" required>
        <option disabled>Selecteer beschikbare klantengroepen</option>
        @foreach(ChuckEcommerce::allGroups() as $groupKey => $group)
          <option value="{{ $groupKey }}" selected>{{ $group['name'] }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>