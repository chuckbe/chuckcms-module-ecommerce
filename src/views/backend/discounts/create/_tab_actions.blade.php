<div class="form-group row required">
    <label for="discount_name" class="col-sm-2 col-form-label">Naam *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="discount_name" name="name" value="{{ old('name') }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_description" class="col-sm-2 col-form-label">Beschrijving</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="discount_description" name="description" rows="2">{{ old('description') }}</textarea>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_code" class="col-sm-2 col-form-label">Code *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="discount_code" name="code" value="{{ old('code') }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_priority" class="col-sm-2 col-form-label">Prioriteit *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="discount_priority" name="priority" value="{{ old('priority', 1) }}" required>
    </div>
</div>
<div class="form-group row required">
  <div class="col-sm-6">
    <label class="sr-only" for="">Tonen in winkelmand?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="discount_highlight">
    <label for="discount_highlight">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_highlight" value="1" name="discount_highlight" checked/> Tonen in winkelmand?
    </label>
  </div>
  <div class="col-sm-6">
    <label class="sr-only" for="">Actief?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="discount_active">
    <label for="discount_active">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_active" value="1" name="discount_active"/> Actief?
    </label>
  </div>
</div>