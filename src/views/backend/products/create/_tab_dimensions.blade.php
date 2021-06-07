<div class="form-group row required">
    <label for="product_width" class="col-sm-2 col-form-label">Breedte (cm)</label>
    <div class="col-sm-10">
        <input type="number" step="0.01" min="0.00" max="999.99" class="form-control product_width_input" placeholder="0.00" id="product_width" name="width" value="{{ !is_null(old('width')) ? old('width') : '0.00' }}">
    </div>
</div>
<div class="form-group row">
    <label for="product_height" class="col-sm-2 col-form-label">Hoogte (cm)</label>
    <div class="col-sm-10">
        <input type="number" step="0.01" min="0.00" max="999.99" class="form-control product_height_input" placeholder="0.00" id="product_height" name="height" value="{{ !is_null(old('height')) ? old('height') : '0.00' }}">
    </div>
</div>
<div class="form-group row">
    <label for="product_depth" class="col-sm-2 col-form-label">Diepte (cm)</label>
    <div class="col-sm-10">
        <input type="number" step="0.01" min="0.00" max="999.99" class="form-control product_depth_input" placeholder="0.00" id="product_depth" name="depth" value="{{ !is_null(old('depth')) ? old('depth') : '0.00' }}">
    </div>
</div>
<div class="form-group row">
    <label for="product_weight" class="col-sm-2 col-form-label">Gewicht (kg)</label>
    <div class="col-sm-10">
        <input type="number" step="0.001" min="0.000" max="999.999" class="form-control product_weight_input" placeholder="0.00" id="product_weight" name="weight" value="{{ !is_null(old('weight')) ? old('weight') : '0.000' }}">
    </div>
</div>