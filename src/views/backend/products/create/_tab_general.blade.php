<div class="form-group row required">
    <label for="slug" class="col-sm-2 col-form-label">Slug *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control product_slug_input" placeholder="slug" id="product_slug" name="slug" value="{{ old('product_slug') }}" required>
    </div>
</div>
<div class="form-group row">
    <label for="upc" class="col-sm-2 col-form-label">UPC</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="UPC Code" name="code[upc]" value="{{ old('code.upc') }}">
    </div>
</div>
<div class="form-group row">
    <label for="ean" class="col-sm-2 col-form-label">EAN</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="EAN Code" name="code[ean]" value="{{ old('code.ean') }}">
    </div>
</div>
<div class="form-group row required">
  <div class="col-sm-4">
    <label class="sr-only" for="">Wordt weergegeven?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_displayed" disabled>
    <label for="is_displayed">
        <input type="checkbox" class="boolean_checkbox_input" id="is_displayed" value="1" name="is_displayed" checked /> Wordt weergegeven?
    </label>
  </div>
  <div class="col-sm-4">
    <label class="sr-only" for="">Mag verkocht worden?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_buyable" disabled>
    <label for="is_buyable">
        <input type="checkbox" class="boolean_checkbox_input" id="is_buyable" value="1" name="is_buyable" checked /> Mag verkocht worden?
    </label>
  </div>
  <div class="col-sm-4">
    <label class="sr-only" for="">Is virtueel product?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_download">
    <label for="is_download">
        <input type="checkbox" class="boolean_checkbox_input" id="is_download" value="0" name="is_download" /> Is virtueel product?
    </label>
  </div>
  <div class="col-sm-12 mt-2">
    <label class="sr-only" for="">In aanbieding?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_featured" disabled>
    <label for="is_featured">
        <input type="checkbox" class="boolean_checkbox_input" id="is_featured" value="1" name="is_featured" checked /> In aanbieding?
    </label>
  </div>
</div>