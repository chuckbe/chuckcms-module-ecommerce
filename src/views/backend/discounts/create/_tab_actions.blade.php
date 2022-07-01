<div class="form-group row required">
    <label for="action_type" class="col-sm-2 col-form-label">Type *</label>
    <div class="col-sm-10">
        <select class="custom-select action_type_input" id="action_type" name="action_type" required>
            <option value="percentage">Percentage</option>
            <option value="currency">Bedrag</option>
            {{-- <option value="gift">Cadeau</option> --}}
        </select>
    </div>
</div>
<div class="form-group row required">
    <label for="action_value" class="col-sm-2 col-form-label">Hoeveelheid *</label>
    <div class="col-sm-10">
        <input type="number" min="0" class="form-control" id="action_value" name="action_value" value="{{ old('action_value') }}" required>
    </div>
</div>
<div class="form-group row required">
    <label for="action_value" class="col-sm-2 col-form-label">Toepassen op *</label>
    <div class="col-sm-10">
        <label for="apply_on_cart" class="d-block">
            <input type="radio" name="apply_on" id="apply_on_cart" value="cart" checked> Winkelwagen
        </label>
        <label for="apply_on_product" class="d-block">
            <input type="radio" name="apply_on" id="apply_on_product" value="product"> Specifiek product (+ combinaties)
        </label>
        <label for="apply_on_conditions" class="d-block">
            <input type="radio" name="apply_on" id="apply_on_conditions" value="conditions"> Geselecteerde product(en)
        </label>
    </div>
</div>
<div class="form-group row required d-none" id="actions_apply_products_row">
    <label for="apply_product" class="col-sm-2 col-form label">Selecteer product</label>
    <div class="col-sm-10">
        <select class="custom-select apply_product_input" id="apply_product" name="apply_product" required disabled>
            @foreach(ChuckProduct::all() as $product)
            <option value="{{ $product->id }}" data-type="product" class="">[product] {{ ChuckProduct::title($product) }}</option>
            @endforeach
        </select>
    </div>
</div>