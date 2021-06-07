<div class="form-group row required">
    <label for="action_type" class="col-sm-2 col-form-label">Type *</label>
    <div class="col-sm-10">
        <select class="custom-select action_type_input" id="action_type" name="action_type" required>
            <option value="percentage" @if($discount->type == 'percentage') selected @endif>Percentage</option>
            <option value="currency" @if($discount->type == 'currency') selected @endif disabled>Bedrag</option>
        </select>
    </div>
</div>
<div class="form-group row required">
    <label for="action_value" class="col-sm-2 col-form-label">Hoeveelheid *</label>
    <div class="col-sm-10">
        <input type="number" min="0" class="form-control" id="action_value" name="action_value" value="{{ old('action_value', $discount->value) }}" required>
    </div>
</div>