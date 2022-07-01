<div class="form-group row required">
    <label for="discount_name" class="col-sm-2 col-form-label">Naam *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="discount_name" name="name" value="{{ old('name', $discount->name) }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_description" class="col-sm-2 col-form-label">Beschrijving</label>
    <div class="col-sm-10">
        <textarea class="form-control" id="discount_description" name="description" rows="2">{{ old('description', $discount->description) }}</textarea>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_code" class="col-sm-2 col-form-label">Code *</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" id="discount_code" name="code" value="{{ old('code', $discount->code) }}" aria-label="Code" aria-describedby="discount_code_refresh_button" required>
            <div class="input-group-append">
                <span class="input-group-text" id="discount_code_refresh_button"><i class="fas fa-refresh" style="font-family: 'FontAwesome';font-style: normal;"></i></span>
            </div>
        </div>
    </div>
</div>
<div class="form-group row required">
    <label for="discount_priority" class="col-sm-2 col-form-label">Prioriteit *</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="discount_priority" name="priority" value="{{ old('priority', $discount->priority) }}" required>
    </div>
</div>
<div class="form-group row required">
  <div class="col-sm-6">
    <label class="sr-only" for="">Tonen in winkelmand?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="highlight">
    <label for="discount_highlight">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_highlight" value="1" name="highlight" @if($discount->highlight) checked @endif/> Tonen in winkelmand?
    </label>
  </div>
  <div class="col-sm-6">
    <label class="sr-only" for="">Actief?</label>
    <div class="w-100 d-block mb-lg-1"></div>
    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="active">
    <label for="discount_active">
        <input type="checkbox" class="boolean_checkbox_input" id="discount_active" value="1" name="active" @if($discount->active) checked @endif/> Actief?
    </label>
  </div>
</div>
