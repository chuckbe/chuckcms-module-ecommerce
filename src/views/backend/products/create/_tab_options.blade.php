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
    @if(is_array(old('options')) && array_key_exists('options', old('options')) && count(old('options')) > 0)
    @foreach(old('options') as $optionKey => $optionValue)
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
<div class="form-group row">
    <div class="col-sm-12">
      <div class="card-title">Extras</div>
    </div>
    <div class="col-sm-12">
        <div class="extraInputContainer">
            <div class="row extra_input_row" style="align-items: center;">
                <div class="col-sm-2">
                    <button class="btn btn-danger btn-round removeExtraRowButton" style="display:none;">-</button>
                    <button class="btn btn-success btn-round addExtraRowButton">+</button>
                </div>
                <div class="col-sm-5">
                    <div class="form-group form-group-default">
                        <label>Naam</label>
                        <input type="text" class="form-control" placeholder="Naam" name="extra_name[]">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group form-group-default">
                        <label>Maximum</label>
                        <input type="number" min="1" max="99999" step="1" class="form-control" name="extra_maximum[]">
                    </div>
                </div>
                <div class="col-sm-3 offset-sm-2">
                    <div class="form-group form-group-default">
                        <label>Prijs</label>
                        <input type="text" data-auto-tax data-auto-tax-group="1" data-auto-tax-price data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="extra_price[]">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group form-group-default">
                        <label>BTW</label>
                        <select class="custom-select" name="extra_vat[]" data-auto-tax data-auto-tax-group="1" data-auto-tax-vat data-init-plugin="select2" data-minimum-results-for-search="Infinity" required>
                            @foreach(config('chuckcms-module-ecommerce.vat') as $vatKey => $vatValue)
                            <option value="{{ $vatKey }}" data-amount="{{ $vatValue['amount'] }}">{{ $vatValue['type'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group form-group-default">
                        <label>Prijs met BTW</label>
                        <input type="text" data-auto-tax data-auto-tax-group="1" data-auto-tax-final data-a-dec="." data-a-sep="" data-m-dec="6" data-a-pad=true class="autonumeric form-control" name="extra_price_vat[]">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>