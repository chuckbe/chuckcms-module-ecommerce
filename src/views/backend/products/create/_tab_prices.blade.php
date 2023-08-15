<div class="row">
  <div class="col-sm-6">
    <div class="form-group form-group-default">
      <label>Inkoopprijs</label>
      <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[wholesale]" value="{{ old('price.wholesale') }}" placeholder="Inkoopprijs">
    </div>
  </div>
  <div class="col-sm-6">
    <div class="form-group form-group-default required">
      <label>Verkoopprijs *</label>
      <input type="text" id="sale_price_ex_input" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[sale]" value="{{ old('price.sale') }}" placeholder="Verkoopprijs" required>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <div class="form-group form-group-default form-group-default-select2 required">
      <label>Belasting (BTW) *</label>
      <select class="custom-select" id="tax-input" name="price[vat]" data-init-plugin="select2" data-minimum-results-for-search="Infinity" required>
        @foreach(config('chuckcms-module-ecommerce.vat') as $vatKey => $vatValue)
          <option value="{{ $vatKey }}" data-amount="{{ $vatValue['amount'] }}" @if(old('price.vat') !== null ? old('price.vat') == $vatValue['amount'] : config('chuckcms-module-ecommerce.vat.'.$vatKey.'.default')) selected @endif>{{ $vatValue['type'] }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="form-group form-group-default required">
      <label>Verkoopprijs met BTW *</label>
      <input type="text" id="sale_price_in_input" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[final]" value="{{ old('price.final') }}" placeholder="Verkoopprijs" required>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group form-group-default">
          <label>Eenheidsprijs</label>
          <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[unit][amount]" value="{{ old('price.unit.amount') }}">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group form-group-default">
          <label>Per</label>
          <input type="text" class="form-control" name="price[unit][type]" value="{{ old('price.unit.type') }}">
        </div>
      </div>
    </div>
    
  </div>
  <div class="col-sm-6">
    <div class="form-group form-group-default">
      <label>Kortingsprijs met BTW</label>
      <input type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="price[discount]" value="{{ old('price.discount') }}" placeholder="Kortingsprijs met BTW">
    </div>
  </div>
</div>
