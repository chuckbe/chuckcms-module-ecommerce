<div class="attributes-combinations-block" data-langs="{{ ChuckSite::getSetting('lang') }}">

  <div class="row">
    <div class="col-sm-12">
      <div class="form-group form-group-default form-group-default-select2">
        <label>Attributen</label>
        <select class="custom-select" id="attributes_multi_select" name="attributes[]" data-init-plugin="select2" data-minimum-results-for-search="5" data-placeholder="Selecteer attributen" multiple="multiple">
          <option></option>
          @foreach($attributes as $attribute)
            <option value="{{ $attribute->id }}" @if(is_array(old('attributes')) && array_key_exists(''.$attribute->id.'', old('attributes'))) selected @endif>{{ $attribute->json['name'] }} ({{ count($attribute->json['values']) }})</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  
  <hr>
  <label for="">Opties</label>
  @foreach($attributes as $attribute)
  <div class="row attribute-select-row" data-attribute="{{ $attribute->id }}" @if(!array_key_exists(''.$attribute->id.'', (is_null(old('attributes')) ? [] : old('attributes')))) style="display:none;" @endif>
    <div class="col-sm-12">
      <div class="form-group form-group-default form-group-default-select2 required">
        <label>Attribuut {{ $attribute->json['name'] }}</label>
        <select class="custom-select attribute-multi-select-input" name="attribute[{{ $attribute->id }}][]" data-attribute="{{ $attribute->id }}" data-init-plugin="select2" data-minimum-results-for-search="Infinity" data-placeholder="Selecteer attribuuts" data-allow-clear="true" multiple="multiple">
          <option></option>
          @foreach($attribute->json['values'] as $attributeKey => $attributeValue)
            <option value="{{ $attributeKey }}" data-type="{{ $attribute->json['name'] }}" data-name="{{ $attribute->json['name'] }} {{ $attributeValue['display_name'][config('app.locale')] }}"  data-langs="{{ ChuckSite::getSetting('lang') }}" @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue) data-name-{{ $langKey }}="{{ $attributeValue['display_name'][$langKey] }}" @endforeach @if(is_array(old('attributes')) && count(old('attributes')) > 0) @if(array_key_exists(''.$attribute->id.'', old('attributes')) && array_key_exists($attributeKey, old('attributes')[''.$attribute->id.'']['values'])) selected @endif @endif>{{ $attributeValue['display_name'][config('app.locale')] }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  @endforeach

  <hr>
  <div class="row">
    <div class="col-sm-12 mb-3">
      <label for="">Combinaties:</label>
    </div>
  </div>
  
  <div class="row combination-row" data-combination-key="" style="display:none;">
    <div class="col-sm-8">
      <div class="form-group form-group-default required">
        <label>Combinatie Naam </label>
        <input type="text" class="form-control combination_name_input" value="" disabled>
        @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
        <input type="hidden" class="combination_display_name_{{ $langKey }}" name="combinations[slug][display_name][langKey]" value="">
        @endforeach
        <input type="hidden" class="combination_slug" name="combination_slugs[]" value="">
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group form-group-default required">
        <label>Combinatie Aantal</label>
        <input type="text" data-v-min="0" data-v-max="999999" data-m-dec="0" data-a-pad=true class="autonumeric form-control combination_quantity_input" name="combinations[slug][quantity]" value="0">
      </div>
    </div>


    <div class="col-sm-4">
      <div class="form-group form-group-default required">
        <label>Combinatie Prijs</label>
        <input class="form-control sale_price_ex_input combination_price_sale_input" type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="combinations[slug][price][sale]" value="0.000000" data-combination-key="" placeholder="Verkoopprijs">
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group form-group-default required">
        <label>Combinatie Prijs met BTW</label>
        <input class="form-control sale_price_in_input combination_price_final_input" type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="combinations[slug][price][final]" value="0.000000" data-combination-key="" placeholder="Verkoopprijs met BTW">
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group form-group-default">
        <label>Combinatie Kortingsprijs met BTW</label>
        <input class="form-control combination_price_discount_input" type="text" data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="combinations[slug][price][discount]" placeholder="Kortingsprijs met BTW">
      </div>
    </div>


    <div class="col-sm-3">
      <div class="form-group">
        <label>Breedte (cm)</label>
        <input type="number" step="0.01" min="0.00" max="999.99" class="form-control combination_width_input" placeholder="0.00" name="combinations[slug][dimensions][width]" value="0.00">
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <label>Hoogte (cm)</label>
        <input type="number" step="0.01" min="0.00" max="999.99" class="form-control combination_height_input" placeholder="0.00" name="combinations[slug][dimensions][height]" value="0.00">
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <label>Diepte (cm)</label>
        <input type="number" step="0.01" min="0.00" max="999.99" class="form-control combination_depth_input" placeholder="0.00" name="combinations[slug][dimensions][depth]" value="0.00">
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <label>Gewicht (kg)</label>
        <input type="number" step="0.001" min="0.000" max="999.999" class="form-control combination_weight_input" placeholder="0.00" name="combinations[slug][dimensions][weight]" value="0.000">
      </div>
    </div>
    <hr>

    
  </div>
</div>


<div class="row quantity-row" >
  <div class="col-sm-12">
    <div class="form-group form-group-default required">
      <label>Globaal Aantal</label>
      <input type="text" data-v-min="0" data-v-max="999999" data-m-dec="0" data-a-pad=true class="autonumeric form-control quantity_input_global" name="quantity" value="{{ old('quantity') }}">
    </div>
  </div>
</div>