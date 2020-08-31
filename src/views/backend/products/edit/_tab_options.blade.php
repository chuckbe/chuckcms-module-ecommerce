<div class="form-group row">
    <div class="col-6 col-sm-3 order-1">
        <small>Key *</small>
    </div>
    <div class="col-12 col-sm-6 order-3 order-sm-2">
        <small>Waarde *</small>
    </div>
    <div class="col-sm-12 order-4">
        <hr class="mt-1 mb-0">
    </div>
</div>
<div class="options_input_container _input_container" id="options_input_container">
    @if(array_key_exists('options', $product->json) && count($product->json['options']) > 0)
    @foreach($product->json['options'] as $optionKey => $optionValue)
    <div class="form-group row required option_input_line _input_line">
        <div class="col-6 col-sm-3 order-1">
            <label class="sr-only" for="option_key_{{ $optionKey }}">Key *</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
                </div>
                <input type="text" class="form-control form-control-sm option_key_input" id="option_key_{{ $optionKey }}" name="option_key[]" value="{{ $optionKey }}" required>
            </div>
        </div>
        <div class="col-12 col-sm-6 order-3 order-sm-2">
            <label class="sr-only" for="option_value_{{ $optionKey }}">Waarde *</label>
            <input type="text" class="form-control form-control-sm option_value_input" id="option_value_{{ $optionKey }}" name="option_value[]" value="{{ $optionValue['value'] }}" required>
        </div>

        <div class="col-sm-12 order-4">
            <hr class="mb-0">
        </div>
    </div>
    @endforeach
    @else
    <div class="form-group row required option_input_line _input_line d-none">
        <div class="col-6 col-sm-3 order-1">
            <label class="sr-only" for="option_key_">Key *</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
                </div>
                <input type="text" class="form-control form-control-sm option_key_input" id="option_key_" name="option_key[]" value="" disabled required>
            </div>
        </div>
        <div class="col-12 col-sm-6 order-3 order-sm-2">
            <label class="sr-only" for="option_value_">Waarde *</label>
            <input type="text" class="form-control form-control-sm option_value_input" id="option_value_" name="option_value[]" value="" disabled required>
        </div>

        <div class="col-sm-12 order-4">
            <hr class="mb-0">
        </div>
    </div>
    @endif
</div>
<div class="form-group row new_option_input_form py-3">
    <div class="col-sm-3">
        <label for="new_option_key">Key *</label>
        <input type="text" class="form-control form-control-sm" id="new_option_key">
    </div>
    <div class="col-sm-6">
        <label for="new_option_value">Waarde *</label>
        <input type="text" class="form-control form-control-sm" id="new_option_value">
    </div>
    <div class="col-sm-3">
        <button class="btn btn-outline-success mt-4 mt-md-2" type="button" id="new_option_button">Toevoegen</button>
        <div class="w-100 d-block"></div>
        <small class="d-none text-danger" id="new_option_error">Vul alle velden in</small>
    </div>
</div>