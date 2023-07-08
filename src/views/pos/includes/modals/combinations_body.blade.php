<div id="attributesModalBody">
    <div class="row attributes_modal_row">
        <div class="col-sm-12 mb-3">
            @isset($combinations)
            <label class="d-block mb-2">Kies combinatie</label>
            <div class="btn-group-horizontal btn-group-toggle attributes_modal_item_button_group" id="attributelist" data-toggle="buttons">
                @foreach ($combinations as $combination)
                <label class="btn btn-outline-dark mr-2 mb-3 attributes_modal_item_button" for="combination-value{{ $loop->index }}">
                    <input
                        type="radio"
                        name="combination"
                        value="{{ $combination['code']['sku'] }}"
                        id="combination-value{{ $loop->index }}"
                        class="combinationRadio"
                        @if($combination['quantity'] == 0) disabled @endif>
                        <span class="attributes_modal_item_button_text" @if($combination['quantity'] == 0) style="text-decoration: line-through;" @endif>{{ $combination['display_name'][\LaravelLocalization::getCurrentLocale()] }}</span>
                </label>
                @endforeach
            </div>
            @endisset

            @isset($options)
            @foreach($options as $optionKey => $option)
            <div class="sizes mb-3">
                <label class="d-block mb-0" for="{{ \Str::slug($optionKey, '_') }}"><small>{{ ucfirst($optionKey) }}: </small></label>
                <div class="input-group mb-2 d-inline-block">
                    <select class="custom-select w-75 ce_optionSelectInput" id="{{ \Str::slug($optionKey, '_') }}" data-option-key="{{ $optionKey }}">
                        @foreach( explode('|', $option['value']) as $value)
                        <option value="{{ $value }}" data-option-key="{{ $optionKey }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endforeach
            @endif

            @isset($extras)
            @foreach($extras as $extraKey => $extra)
            <div class="sizes">
                <div class="input-group w-75 mb-2">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="{{ \Str::slug($extraKey, '_') }}"><small>{{ $extraKey }}: </small></label>
                    </div>
                    <select class="custom-select ce_extraSelectInput" id="{{ \Str::slug($extraKey, '_') }}" data-extra-key="{{ $extraKey }}" data-extra-price="{{ $extra['price'] }}" data-extra-final="{{ $extra['final'] }}">
                        @for ($i = 0; $i <= (int)$extra['maximum']; $i++)
                        <option value="{{ $i }}" data-extra-key="{{ $extraKey }}">{{ $i }}{{ $extra['final'] && floatval($extra['final']) > 0 && $i > 0 ? ' (+'.ChuckEcommerce::formatPrice(($i * round(floatval($extra['final']), 2))).')' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            @endforeach
            @endif
            {{--  <label class="d-block mb-2">Kies attribuut</label>
            <div class="btn-group-horizontal btn-group-toggle attributes_modal_item_button_group" id="attributelist" data-toggle="buttons">

                <label class="btn btn-secondary mr-2 mb-3 attributes_modal_item_button">
                    <input type="radio" name="attributes" id="option1"> <span class="attributes_modal_item_button_text">Active</span>
                </label>
                <label class="btn btn-secondary attributes_modal_item_button">
                    <input type="radio" name="attributes" id="option2"> Radio
                </label>
                <label class="btn btn-secondary attributes_modal_item_button">
                    <input type="radio" name="attributes" id="option3"> Radio
                </label>
            </div>  --}}
        </div>
    </div>
</div>
