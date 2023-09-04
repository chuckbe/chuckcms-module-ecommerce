@include('chuckcms-module-ecommerce::backend.products.edit._tab_texts')

<hr>

<div class="form-group row required">
    <label for="slug" class="col-sm-2 col-form-label">Slug *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control product_slug_input" placeholder="slug" id="product_slug" name="slug" value="{{ explode('/', $product->url)[1] }}" required>
    </div>
</div>
<div class="form-group row">
    <label for="upc" class="col-sm-2 col-form-label">UPC</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="UPC Code" name="code[upc]" value="{{ $product->json['code']['upc'] }}">
    </div>
</div>
<div class="form-group row">
    <label for="ean" class="col-sm-2 col-form-label">EAN</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="EAN Code" name="code[ean]" value="{{ $product->json['code']['ean'] }}">
    </div>
</div>
<div class="form-group row required">
  <div class="col-sm-4">
    <label class="sr-only" for="">Wordt weergegeven?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_displayed" @if($product->json['is_displayed'] == true) disabled @endif>
    <label for="is_displayed">
        <input type="checkbox" class="boolean_checkbox_input" id="is_displayed" value="{{ $product->json['is_displayed'] == 'true' ? 1 : 0 }}" name="is_displayed" @if($product->json['is_displayed'] == true) checked @endif /> Wordt weergegeven?
    </label>
  </div>
  <div class="col-sm-4">
    <label class="sr-only" for="">Mag verkocht worden?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_buyable" @if($product->json['is_buyable'] == true) disabled @endif>
    <label for="is_buyable">
        <input type="checkbox" class="boolean_checkbox_input" id="is_buyable" value="{{ $product->json['is_buyable'] == 'true' ? 1 : 0 }}" name="is_buyable" @if($product->json['is_buyable'] == true) checked @endif /> Mag verkocht worden?
    </label>
  </div>
  <div class="col-sm-4">
    <label class="sr-only" for="">Is virtueel product?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_download" @if($product->json['is_download'] == true) disabled @endif>
    <label for="is_download">
        <input type="checkbox" class="boolean_checkbox_input" id="is_download" value="{{ $product->json['is_download'] == 'true' ? 1 : 0 }}" name="is_download" @if($product->json['is_download'] == true) checked @endif /> Is virtueel product?
    </label>
  </div>
  <div class="col-sm-4 mt-2">
    <label class="sr-only" for="">In aanbieding?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_featured" @if($product->json['is_featured'] ?? false) disabled @endif>
    <label for="is_featured">
        <input type="checkbox" class="boolean_checkbox_input" id="is_featured" value="{{ ($product->json['is_featured'] ?? false) == 'true' ? 1 : 0 }}" name="is_featured" @if($product->json['is_featured'] ?? false) checked @endif /> In aanbieding?
    </label>
  </div>
  <div class="col-sm-4 mt-2">
    <label class="sr-only" for="">Is beschikbaar in POS?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_pos_available" @if($product->json['is_pos_available'] ?? false) disabled @endif>
    <label for="is_pos_availabled">
        <input type="checkbox" class="boolean_checkbox_input" id="is_pos_availabled" value="{{ ($product->json['is_pos_available'] ?? false) == 'true' ? 1 : 0 }}" name="is_pos_available" @if($product->json['is_pos_available'] ?? false) checked @endif /> Is beschikbaar in POS?
    </label>
  </div>
</div>
